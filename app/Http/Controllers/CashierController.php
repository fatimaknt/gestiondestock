<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\StockAlert;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->with(['category', 'supplier'])
            ->get();

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('cashier.index', compact('products', 'categories', 'suppliers'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        $categoryId = $request->get('category_id');
        $supplierId = $request->get('supplier_id');

        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->where('quantity', '>', 0);

        if ($query) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        if ($supplierId) {
            $products->where('supplier_id', $supplierId);
        }

        $products = $products->with(['category', 'supplier'])
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->get('barcode');

        $product = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->where('barcode', $barcode)
            ->with(['category', 'supplier'])
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        return response()->json($product);
    }

    public function processSale(Request $request)
    {
                // Log de debug
        Log::info('=== DÉBUT PROCESS SALE ===');
        Log::info('Données reçues:', $request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,card,transfer,check',
            'notes' => 'nullable|string'
        ]);

        // Calculer le total automatiquement si il est manquant
        if (!$request->total_price || $request->total_price <= 0) {
            $request->merge([
                'total_price' => $request->quantity * $request->unit_price
            ]);
            Log::info('Total calculé automatiquement:', ['total' => $request->total_price]);
        }

        Log::info('Validation réussie');

        try {
            Log::info('Début de la transaction');
            DB::beginTransaction();

            // Récupérer le produit
            $product = Product::find($request->product_id);
            Log::info('Produit trouvé:', ['id' => $product->id ?? 'null', 'name' => $product->name ?? 'null']);

            if (!$product) {
                throw new \Exception('Produit non trouvé');
            }

            // Vérifier le stock
            if ($product->quantity < $request->quantity) {
                throw new \Exception("Stock insuffisant pour {$product->name}. Stock disponible: {$product->quantity}");
            }

            // Créer la vente
            $sale = Sale::create([
                'shop_id' => Auth::user()->shop_id,
                'user_id' => Auth::user()->id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'subtotal' => $request->total_price,
                'tax_rate' => 0.00,
                'tax_amount' => 0.00,
                'discount' => 0.00,
                'total' => $request->total_price,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . time() . '-' . rand(100, 999)
            ]);

            // Créer l'article de vente
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $request->product_id,
                'shop_id' => Auth::user()->shop_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $request->total_price
            ]);

            // Mettre à jour le stock
            $quantityBefore = $product->quantity;
            $product->quantity -= $request->quantity;
            $product->save();

            // Créer le mouvement de stock
            StockMovement::create([
                'shop_id' => Auth::user()->shop_id,
                'product_id' => $request->product_id,
                'user_id' => Auth::user()->id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $product->quantity,
                'unit_cost' => $product->purchase_price,
                'reference' => $sale->id,
                'reference_type' => 'sale',
                'notes' => "Vente #{$sale->id}",
                'movement_date' => now()
            ]);

            // Vérifier si alerte de stock
            if ($product->quantity <= $product->min_quantity) {
                StockAlert::create([
                    'shop_id' => Auth::user()->shop_id,
                    'product_id' => $product->id,
                    'message' => "Stock faible pour {$product->name}. Quantité restante: {$product->quantity}",
                    'type' => 'low_stock',
                    'is_read' => false
                ]);
            }

            DB::commit();

            return redirect()->route('cashier.index')
                ->with('success', "Vente enregistrée avec succès ! ID: {$sale->id}");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cashier.index')
                ->with('error', 'Erreur lors de la vente: ' . $e->getMessage());
        }
    }

    public function getSalesHistory()
    {
        $sales = Sale::where('shop_id', Auth::user()->shop_id)
            ->with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cashier.history', compact('sales'));
    }

    public function getSaleDetails($id)
    {
        $sale = Sale::where('shop_id', Auth::user()->shop_id)
            ->with(['items.product', 'user'])
            ->findOrFail($id);

        return view('cashier.sale-details', compact('sale'));
    }

    public function generateInvoice($id)
    {
        $sale = Sale::where('shop_id', Auth::user()->shop_id)
            ->with(['items.product', 'user'])
            ->findOrFail($id);

        // Ici vous pouvez utiliser DomPDF pour générer la facture
        // Pour l'instant, retournons la vue
        return view('cashier.invoice', compact('sale'));
    }

    public function changeSaleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:completed,pending,cancelled,refunded'
        ]);

        $sale = Sale::where('shop_id', Auth::user()->shop_id)->findOrFail($id);
        $oldStatus = $sale->status;
        $sale->status = $request->status;
        $sale->save();

        // Log du changement de statut
        Log::info("Statut de vente #{$id} changé de {$oldStatus} à {$request->status}");

        return response()->json([
            'success' => true,
            'message' => "Statut de la vente changé avec succès de {$oldStatus} à {$request->status}",
            'new_status' => $request->status
        ]);
    }

    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $request->max_amount,
            'refund_reason' => 'required|string|max:500'
        ]);

        $sale = Sale::where('shop_id', Auth::user()->shop_id)->findOrFail($id);

        // Marquer la vente comme remboursée
        $sale->status = 'refunded';
        $sale->save();

        // Créer un mouvement de stock pour remettre les produits
        foreach ($sale->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();

            // Créer un mouvement de stock
            StockMovement::create([
                'shop_id' => Auth::user()->shop_id,
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
                'type' => 'in',
                'quantity' => $item->quantity,
                'quantity_before' => $product->quantity - $item->quantity,
                'quantity_after' => $product->quantity,
                'unit_cost' => $product->purchase_price,
                'reference' => $sale->id,
                'reference_type' => 'refund',
                'notes' => "Remboursement vente #{$sale->id} - {$request->refund_reason}",
                'movement_date' => now()
            ]);
        }

        Log::info("Remboursement traité pour la vente #{$id} - Montant: {$request->refund_amount} CFA");

        return response()->json([
            'success' => true,
            'message' => 'Remboursement traité avec succès'
        ]);
    }
}
