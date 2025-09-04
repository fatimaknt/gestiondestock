<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

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
        return response('Export du stock - Fonctionnalité en cours de développement', 200);
    }

    public function exportSales(Request $request)
    {
        return response('Export des ventes - Fonctionnalité en cours de développement', 200);
    }
}
