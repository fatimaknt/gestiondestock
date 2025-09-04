<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::where('shop_id', Auth::user()->shop_id)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->get();

        return view('stock-movements.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->get();

        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'movement_date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::find($request->product_id);
            $quantityBefore = $product->quantity;

            // Calculer la nouvelle quantité
            if ($request->type === 'in') {
                $newQuantity = $quantityBefore + $request->quantity;
            } elseif ($request->type === 'out') {
                if ($request->quantity > $quantityBefore) {
                    return back()->with('error', 'Quantité insuffisante en stock');
                }
                $newQuantity = $quantityBefore - $request->quantity;
            } else { // adjustment
                $newQuantity = $request->quantity;
            }

            // Créer le mouvement de stock
            StockMovement::create([
                'shop_id' => Auth::user()->shop_id,
                'product_id' => $request->product_id,
                'user_id' => Auth::user()->id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $newQuantity,
                'unit_cost' => $request->unit_cost,
                'reference' => null,
                'reference_type' => 'manual',
                'notes' => $request->notes,
                'movement_date' => $request->movement_date
            ]);

            // Mettre à jour le stock du produit
            $product->quantity = $newQuantity;
            if ($request->type === 'in') {
                $product->purchase_price = $request->unit_cost;
            }
            $product->save();

            DB::commit();

            return redirect()->route('stock-movements.index')
                ->with('success', 'Mouvement de stock enregistré avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'user']);
        return view('stock-movements.show', compact('stockMovement'));
    }

    public function edit(StockMovement $stockMovement)
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)
            ->where('is_active', true)
            ->get();

        return view('stock-movements.edit', compact('stockMovement', 'products'));
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'movement_date' => 'required|date|before_or_equal:today'
        ]);

        try {
            DB::beginTransaction();

            // Récupérer l'ancien produit pour restaurer son stock
            $oldProduct = Product::find($stockMovement->product_id);
            if ($oldProduct) {
                $oldProduct->quantity = $stockMovement->quantity_before;
                $oldProduct->save();
            }

            $product = Product::find($request->product_id);
            $quantityBefore = $product->quantity;

            // Calculer la nouvelle quantité
            if ($request->type === 'in') {
                $newQuantity = $quantityBefore + $request->quantity;
            } elseif ($request->type === 'out') {
                if ($request->quantity > $quantityBefore) {
                    return back()->with('error', 'Quantité insuffisante en stock');
                }
                $newQuantity = $quantityBefore - $request->quantity;
            } else { // adjustment
                $newQuantity = $request->quantity;
            }

            // Mettre à jour le mouvement
            $stockMovement->update([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $newQuantity,
                'unit_cost' => $request->unit_cost,
                'notes' => $request->notes,
                'movement_date' => $request->movement_date
            ]);

            // Mettre à jour le stock du produit
            $product->quantity = $newQuantity;
            if ($request->type === 'in') {
                $product->purchase_price = $request->unit_cost;
            }
            $product->save();

            DB::commit();

            return redirect()->route('stock-movements.show', $stockMovement)
                ->with('success', 'Mouvement de stock modifié avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

            public function export()
    {
        try {
            $movements = StockMovement::where('shop_id', Auth::user()->shop_id)
                ->with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = 'mouvements_stock_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($movements) {
                $file = fopen('php://output', 'w');

                // En-têtes
                fputcsv($file, [
                    'Date', 'Produit', 'Type', 'Quantité', 'Stock Avant', 'Stock Après',
                    'Prix Unitaire (CFA)', 'Utilisateur', 'Notes'
                ]);

                // Données
                foreach ($movements as $movement) {
                    fputcsv($file, [
                        $movement->movement_date ? $movement->movement_date->format('d/m/Y') : $movement->created_at->format('d/m/Y'),
                        $movement->product->name,
                        $movement->type === 'in' ? 'Entrée' : ($movement->type === 'out' ? 'Sortie' : 'Ajustement'),
                        $movement->quantity,
                        $movement->quantity_before,
                        $movement->quantity_after,
                        number_format($movement->unit_cost),
                        $movement->user->name,
                        $movement->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

        public function exportPdf()
    {
        try {
            $movements = StockMovement::where('shop_id', Auth::user()->shop_id)
                ->with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $shop = Auth::user()->shop;
            $user = Auth::user();
            $date = now()->format('d/m/Y a H:i');

            $pdf = PDF::loadView('stock-movements.pdf', compact('movements', 'shop', 'user', 'date'));

            // Configuration PDF pour éviter les problèmes de caractères
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Arial',
                'chroot' => public_path(),
            ]);

            return $pdf->download('mouvements_stock_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export PDF : ' . $e->getMessage());
        }
    }
}
