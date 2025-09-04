<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\StockAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $shopId = $user->shop_id;

        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_products' => Product::where('shop_id', $shopId)->count(),
            'low_stock_products' => Product::where('shop_id', $shopId)
                ->where('quantity', '<=', DB::raw('min_quantity'))
                ->count(),
            'out_of_stock_products' => Product::where('shop_id', $shopId)
                ->where('quantity', 0)
                ->count(),
            'total_sales_today' => Sale::where('shop_id', $shopId)
                ->whereDate('created_at', $today)
                ->sum('total'),
            'total_sales_week' => Sale::where('shop_id', $shopId)
                ->where('created_at', '>=', $thisWeek)
                ->sum('total'),
            'total_sales_month' => Sale::where('shop_id', $shopId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('total'),
            'total_purchases_month' => Purchase::where('shop_id', $shopId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('total'),
        ];

        $top_products = Product::where('shop_id', $shopId)
            ->with('category')
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        $recent_sales = Sale::where('shop_id', $shopId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $stock_alerts = StockAlert::where('shop_id', $shopId)
            ->where('is_read', false)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $sales_chart = Sale::where('shop_id', $shopId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Préparer les données pour la vue
        $stats = [
            'total_products' => $stats['total_products'],
            'low_stock' => $stats['low_stock_products'],
            'monthly_sales' => $stats['total_sales_month'],
            'revenue' => $stats['total_sales_month'],
        ];

        return view('dashboard', compact(
            'stats',
            'top_products',
            'recent_sales',
            'stock_alerts',
            'sales_chart'
        ));
    }
}
