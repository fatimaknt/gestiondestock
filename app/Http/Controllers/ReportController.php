<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stockReport()
    {
        $products = Product::with(['category', 'supplier'])
            ->where('shop_id', Auth::user()->shop_id)
            ->orderBy('quantity', 'asc')
            ->get();

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        // Statistiques du stock
        $totalProducts = $products->count();
        $totalValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });
        $lowStockProducts = $products->where('quantity', '<=', 'min_quantity')->count();
        $outOfStockProducts = $products->where('quantity', 0)->count();

        return view('reports.stock', compact(
            'products',
            'categories',
            'suppliers',
            'totalProducts',
            'totalValue',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function salesReport(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->where('shop_id', Auth::user()->shop_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('reports.sales', compact('products', 'categories', 'suppliers'));
    }

    public function exportStock()
    {
        $products = Product::with(['category', 'supplier'])
            ->where('shop_id', Auth::user()->shop_id)
            ->orderBy('name')
            ->get();

        // Statistiques du stock
        $totalProducts = $products->count();
        $totalValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });
        $lowStockProducts = $products->where('quantity', '<=', 'min_quantity')->count();
        $outOfStockProducts = $products->where('quantity', 0)->count();

        $shop = Auth::user()->shop;

        $pdf = Pdf::loadView('reports.export.stock-pdf', compact(
            'products',
            'totalProducts',
            'totalValue',
            'lowStockProducts',
            'outOfStockProducts',
            'shop'
        ));

        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download('rapport_stock_' . date('Y-m-d') . '.pdf');
    }

    public function exportSales(Request $request)
    {
        $query = Sale::with(['client', 'items.product'])
            ->where('shop_id', Auth::user()->shop_id);

        // Filtres optionnels
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        // Statistiques des ventes
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total');
        $totalQuantity = $sales->sum(function($sale) {
            return $sale->items->sum('quantity');
        });

        $shop = Auth::user()->shop;

        $pdf = Pdf::loadView('reports.export.sales-pdf', compact(
            'sales',
            'totalSales',
            'totalRevenue',
            'totalQuantity',
            'shop'
        ));

        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download('rapport_ventes_' . date('Y-m-d') . '.pdf');
    }
}
