<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer'])
            ->where('shop_id', Auth::user()->shop_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->with(['category'])
            ->get();

        $customers = Customer::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->get();

        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'shop_id' => Auth::user()->shop_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'total' => 0,
                'status' => 'completed',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Mettre à jour le stock
                $product->decrement('quantity', $item['quantity']);

                $total += $item['quantity'] * $item['unit_price'];
            }

            $sale->update(['total' => $total]);

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Vente enregistrée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Sale $sale)
    {
        if ($sale->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à cette vente.');
        }

        $sale->load(['items.product']);

        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        if ($sale->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à cette vente.');
        }

        try {
            DB::beginTransaction();

            // Restaurer le stock
            foreach ($sale->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $sale->items()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Vente supprimée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }

    public function getProductInfo($productId)
    {
        $product = Product::where('shop_id', Auth::user()->shop_id)
            ->where('id', $productId)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'selling_price' => $product->selling_price,
            'quantity' => $product->quantity,
            'category' => $product->category->name ?? 'N/A'
        ]);
    }
}
