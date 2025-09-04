<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ShopController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Inscription publique désactivée pour la sécurité
    // Route::get('/register', function () {
    //     return view('auth.register');
    // })->name('register');

    // Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Routes de réinitialisation de mot de passe
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function () {
        return redirect()->back()->with('status', 'Fonctionnalité en cours de développement');
    })->name('password.email');

    Route::get('/reset-password/{token}', function () {
        return view('auth.reset-password');
    })->name('password.reset');

    Route::post('/reset-password', function () {
        return redirect()->back()->with('status', 'Fonctionnalité en cours de développement');
    })->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);

// Routes de la caisse
Route::prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::post('/search', [CashierController::class, 'searchProducts'])->name('search');
    Route::post('/barcode', [CashierController::class, 'getProductByBarcode'])->name('barcode');
    Route::post('/sale', [CashierController::class, 'processSale'])->name('sale');
    Route::get('/history', [CashierController::class, 'getSalesHistory'])->name('history');
    Route::get('/sale/{id}', [CashierController::class, 'getSaleDetails'])->name('sale.details');
            Route::get('/invoice/{id}', [CashierController::class, 'generateInvoice'])->name('invoice');
        Route::post('/sale/{id}/status', [CashierController::class, 'changeSaleStatus'])->name('sale.status');
        Route::post('/sale/{id}/refund', [CashierController::class, 'processRefund'])->name('sale.refund');
    });

    // Routes pour les alertes et notifications
    Route::prefix('alerts')->name('alerts.')->group(function () {
        Route::get('/', [App\Http\Controllers\AlertController::class, 'index'])->name('index');
        Route::get('/stock', [App\Http\Controllers\AlertController::class, 'stockAlerts'])->name('stock');
        Route::post('/{id}/mark-read', [App\Http\Controllers\AlertController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [App\Http\Controllers\AlertController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });
    // Routes des rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/export/stock', [ReportController::class, 'exportStock'])->name('export.stock');
        Route::get('/export/sales', [ReportController::class, 'exportSales'])->name('export.sales');
    });

    // Routes des mouvements de stock
    Route::get('/stock-movements/export', [StockMovementController::class, 'export'])->name('stock-movements.export');
    Route::get('/stock-movements/export-pdf', [StockMovementController::class, 'exportPdf'])->name('stock-movements.export.pdf');
    Route::resource('stock-movements', StockMovementController::class);

    // Routes des clients
    Route::resource('clients', ClientController::class);
    Route::patch('/clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');

    // Routes des boutiques
    Route::prefix('shops')->name('shops.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/edit', [ShopController::class, 'edit'])->name('edit');
        Route::put('/update', [ShopController::class, 'update'])->name('update');
        Route::get('/show', [ShopController::class, 'show'])->name('show');
    });

    // Routes d'administration des utilisateurs
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
