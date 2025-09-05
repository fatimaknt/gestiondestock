@extends('layouts.app-with-nav')

@section('content')
    <div class="container-fluid pt-4">
        <!-- Messages de succès -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- En-tête du dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-1">
                            @if (Auth::user()->hasRole('admin'))
                                <i class="bi bi-shield-check me-2 text-primary"></i>Tableau de bord Administrateur
                            @else
                                <i class="bi bi-speedometer2 me-2 text-primary"></i>Tableau de bord
                            @endif
                        </h1>
                        <p class="text-muted mb-0">
                            @if (Auth::user()->hasRole('admin'))
                                Vue d'ensemble de toutes les boutiques et utilisateurs
                            @else
                                Bienvenue, {{ Auth::user()->name }} ! Voici un aperçu de votre activité
                            @endif
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        @if (Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-people me-2"></i>Gestion Utilisateurs
                            </a>
                            {{-- <a href="{{ route('shops.index') }}" class="btn btn-outline-success">
                                <i class="bi bi-shop me-2"></i>Gestion Boutiques
                            </a> --}}
                        @else
                            <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i>Nouveau produit
                            </a>
                            <a href="{{ route('cashier.index') }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-2"></i>Nouvelle vente
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->hasRole('admin'))
            <!-- Dashboard Admin -->
            <!-- Statistiques Globales -->
            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-shop text-primary h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Total Boutiques</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['total_shops'] ?? 0 }}</h3>
                                    <small class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ $stats['active_shops'] ?? 0 }} actives
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-people text-success h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Total Utilisateurs</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                                    <small class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ $stats['active_users'] ?? 0 }} actifs
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-currency-dollar text-info h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">CA Global (Mois)</h6>
                                    <h3 class="fw-bold mb-0">
                                        {{ number_format($stats['total_sales_month'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                                    <small class="{{ $growth >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i
                                            class="bi bi-arrow-{{ $growth >= 0 ? 'up' : 'down' }} me-1"></i>{{ number_format(abs($growth), 1) }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-exclamation-triangle text-warning h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Alertes Globales</h6>
                                    <h3 class="fw-bold mb-0">
                                        {{ ($stats['low_stock_products'] ?? 0) + ($stats['out_of_stock_products'] ?? 0) }}
                                    </h3>
                                    <small class="text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Surveillance requise
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides Admin -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-person-plus me-2" style="font-size: 1.5rem;"></i>
                                        <span>Nouvel Utilisateur</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('alerts.stock') }}"
                                        class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-exclamation-triangle me-2" style="font-size: 1.5rem;"></i>
                                        <span>Gérer Alertes</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('notifications.index') }}"
                                        class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-bell me-2" style="font-size: 1.5rem;"></i>
                                        <span>Notifications</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('reports.index') }}"
                                        class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-graph-up me-2" style="font-size: 1.5rem;"></i>
                                        <span>Rapports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et tableaux Admin -->
            <div class="row">
                <!-- Top boutiques -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Top 5 Boutiques (CA du Mois)</h6>
                        </div>
                        <div class="card-body">
                            @if (isset($topShops) && $topShops->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Boutique</th>
                                                <th>Ventes</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topShops as $shop)
                                                <tr>
                                                    <td>{{ $shop->name }}</td>
                                                    <td>{{ $shop->sales_count }}</td>
                                                    <td>{{ number_format($shop->sales_sum_total ?? 0, 0, ',', ' ') }} FCFA
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucune donnée disponible.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Utilisateurs récents -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Utilisateurs Récents</h6>
                        </div>
                        <div class="card-body">
                            @if (isset($recentUsers) && $recentUsers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Boutique</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentUsers as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->shop->name ?? 'N/A' }}</td>
                                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucun utilisateur récent.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes de stock globales -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Alertes de Stock Globales</h6>
                        </div>
                        <div class="card-body">
                            @if (isset($stockAlerts) && $stockAlerts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Boutique</th>
                                                <th>Stock Actuel</th>
                                                <th>Stock Minimum</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockAlerts as $alert)
                                                <tr>
                                                    <td>{{ $alert->product->name ?? 'N/A' }}</td>
                                                    <td>{{ $alert->product->shop->name ?? 'N/A' }}</td>
                                                    <td>{{ $alert->product->quantity ?? 0 }}</td>
                                                    <td>{{ $alert->product->min_quantity ?? 0 }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $alert->product->quantity == 0 ? 'danger' : 'warning' }}">
                                                            {{ $alert->product->quantity == 0 ? 'Rupture' : 'Stock Bas' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('alerts.stock') }}" class="btn btn-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Gérer toutes les alertes
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Aucune alerte de stock active
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Dashboard Vendeur (Original) -->
            <!-- Cartes de statistiques -->
            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-box-seam text-primary h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Total Produits</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up me-1"></i>+12% ce mois
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-exclamation-triangle text-warning h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Stock Faible</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['low_stock_products'] ?? 0 }}</h3>
                                    <small class="text-warning">
                                        <i class="bi bi-arrow-up me-1"></i>Attention
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-cart-check text-success h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Ventes du Mois</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['monthly_sales'] ?? 0 }}</h3>
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up me-1"></i>+8% ce mois
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-cash text-info h3 mb-0"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Chiffre d'Affaires</h6>
                                    <h3 class="fw-bold mb-0">
                                        {{ number_format($stats['total_sales_month'] ?? 0, 0, ',', ' ') }} CFA
                                    </h3>
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up me-1"></i>+15% ce mois
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('cashier.index') }}"
                                        class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-cart-check me-2" style="font-size: 1.5rem;"></i>
                                        <span>Nouvelle Vente</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('products.create') }}"
                                        class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-plus-circle me-2" style="font-size: 1.5rem;"></i>
                                        <span>Nouveau Produit</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('suppliers.create') }}"
                                        class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-truck me-2" style="font-size: 1.5rem;"></i>
                                        <span>Nouveau Fournisseur</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('clients.create') }}"
                                        class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-person-plus me-2" style="font-size: 1.5rem;"></i>
                                        <span>Nouveau Client</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et tableaux -->
            <div class="row">
                <!-- Top produits -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Top 5 Produits Vendus</h6>
                        </div>
                        <div class="card-body">
                            @if (isset($top_products) && $top_products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Quantité Vendue</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($top_products as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->total_quantity ?? 0 }}</td>
                                                    <td>{{ number_format($product->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucune vente enregistrée.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Alertes de stock -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Alertes de Stock</h6>
                        </div>
                        <div class="card-body">
                            @if (isset($stock_alerts) && $stock_alerts->count() > 0)
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $stock_alerts->count() }} produit(s) en stock bas
                                </div>
                                <a href="{{ route('products.index') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye me-2"></i>Voir les produits
                                </a>
                            @else
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Tous les produits sont en stock normal
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ventes récentes (Admin - Données Agregées) -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Activité Récente (Données Agregées)</h6>
                            <small class="text-muted">Vue d'ensemble sans détails sensibles</small>
                        </div>
                        <div class="card-body">
                            @if (isset($recentSales) && $recentSales->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Boutique</th>
                                                <th>Date</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentSales as $sale)
                                                <tr>
                                                    <td>{{ $sale->shop->name ?? 'N/A' }}</td>
                                                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ number_format($sale->total, 0, ',', ' ') }} FCFA</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $sale->status === 'completed' ? 'success' : ($sale->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($sale->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Note :</strong> Seules les données agrégées sont affichées pour respecter la
                                    confidentialité des boutiques.
                                </div>
                            @else
                                <p class="text-muted">Aucune activité récente.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
