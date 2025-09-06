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

        // Si c'est un admin, afficher le dashboard admin
        if ($user->hasRole('admin')) {
            return $this->adminDashboard($user);
        }

        // Sinon, dashboard vendeur normal
        return $this->sellerDashboard($user);
    }

    private function adminDashboard($user)
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Statistiques globales (anonymes pour l'admin)
        $stats = [
            'total_shops' => \App\Models\Shop::count(),
            'active_shops' => \App\Models\Shop::where('is_active', true)->count(),
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('is_active', true)->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('quantity <= min_quantity')->count(),
            'out_of_stock_products' => Product::where('quantity', 0)->count(),
            // Pas de chiffres d'affaires spécifiques pour l'admin
            'total_sales_today' => 0,
            'total_sales_week' => 0,
            'total_sales_month' => 0,
            'total_sales_last_month' => 0,
        ];

        // Calculer la croissance
        $growth = $stats['total_sales_last_month'] > 0
            ? (($stats['total_sales_month'] - $stats['total_sales_last_month']) / $stats['total_sales_last_month']) * 100
            : 0;

        // Pas de top boutiques pour l'admin (confidentialité)
        $topShops = collect();

        // Pas de top produits pour l'admin (confidentialité)
        $topProducts = collect();

        // Ventes récentes (toutes boutiques) - SANS détails sensibles
        $recentSales = Sale::select('id', 'total', 'status', 'created_at', 'shop_id')
            ->with(['shop:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Alertes de stock globales
        $stockAlerts = StockAlert::with(['product.shop'])
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Utilisateurs récents
        $recentUsers = \App\Models\User::with('shop')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Graphique des ventes par jour (30 derniers jours)
        $salesChart = Sale::where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Graphique des ventes par boutique (ce mois)
        $shopsChart = \App\Models\Shop::withSum(['sales' => function($query) use ($thisMonth) {
                $query->where('created_at', '>=', $thisMonth);
            }], 'total')
            ->orderBy('sales_sum_total', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats',
            'growth',
            'topShops',
            'topProducts',
            'recentSales',
            'stockAlerts',
            'recentUsers',
            'salesChart',
            'shopsChart'
        ));
    }

    private function sellerDashboard($user)
    {
        $shopId = $user->shop_id;
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_products' => Product::where('shop_id', $shopId)->count(),
            'low_stock_products' => Product::where('shop_id', $shopId)
                ->whereRaw('quantity <= min_quantity')
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

        return view('dashboard', compact(
            'stats',
            'top_products',
            'recent_sales',
            'stock_alerts',
            'sales_chart'
        ));
    }
}
