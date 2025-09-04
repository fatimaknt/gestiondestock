@extends('layouts.app')

@section('content')
    <div class="min-vh-100 bg-light">
        <!-- Navigation principale -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-lg">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <i class="bi bi-box-seam h4 mb-0 me-2"></i>
                    <span class="fw-bold">Gestion de Stock</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cashier.index') }}">
                                <i class="bi bi-cart-check me-2"></i>Caisse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-box me-2"></i>Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('suppliers.index') }}">
                                <i class="bi bi-truck me-2"></i>Fournisseurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cashier.history') }}">
                                <i class="bi bi-clock-history me-2"></i>Historique Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('stock-movements.index') }}">
                                <i class="bi bi-arrow-left-right me-2"></i>Mouvements Stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.index') }}">
                                <i class="bi bi-people me-2"></i>Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shops.index') }}">
                                <i class="bi bi-shop me-2"></i>Ma Boutique
                            </a>
                        </li>
                        @if (auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-shield-lock me-2"></i>Administration
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="navbar-nav">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (auth()->user()->hasRole('admin'))
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i
                                                class="bi bi-shield-lock me-2"></i>Gestion Utilisateurs</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="container-fluid py-4">
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
                            <h1 class="h2 fw-bold text-dark mb-1">Tableau de bord</h1>
                            <p class="text-muted mb-0">Bienvenue, {{ Auth::user()->name }} ! Voici un aperçu de votre
                                activité</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i>Nouveau produit
                            </a>
                            <a href="{{ route('cashier.index') }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-2"></i>Nouvelle vente
                            </a>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <h3 class="fw-bold mb-0">{{ $stats['low_stock'] ?? 0 }}</h3>
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
                                    <h3 class="fw-bold mb-0">{{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }} CFA
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
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-lightning me-2 text-primary"></i>Actions Rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('reports.index') }}"
                                        class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-graph-up me-2" style="font-size: 1.5rem;"></i>
                                        <span>Rapports</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('reports.stock') }}"
                                        class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-box-seam me-2" style="font-size: 1.5rem;"></i>
                                        <span>Stock</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('reports.sales') }}"
                                        class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-cart-check me-2" style="font-size: 1.5rem;"></i>
                                        <span>Ventes</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('reports.export.stock') }}"
                                        class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-download me-2" style="font-size: 1.5rem;"></i>
                                        <span>Export</span>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('clients.index') }}"
                                        class="btn btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-people me-2" style="font-size: 1.5rem;"></i>
                                        <span>Clients</span>
                                    </a>
                                </div>
                                @if (auth()->user()->hasRole('admin'))
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ route('admin.users.index') }}"
                                            class="btn btn-outline-danger w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                            <i class="bi bi-shield-lock me-2" style="font-size: 1.100rem;"></i>
                                            <span>Administration</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et tableaux -->
            <div class="row g-4">
                <!-- Graphique des ventes -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-graph-up me-2 text-primary"></i>
                                Évolution des ventes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produits populaires -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-star me-2 text-warning"></i>
                                Produits populaires
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($topProducts ?? [] as $product)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->image ?? 'https://via.placeholder.com/40x40' }}"
                                                class="rounded me-3" width="40" height="40"
                                                alt="{{ $product->name }}">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">{{ $product->name }}</h6>
                                                <small
                                                    class="text-muted">{{ $product->category->name ?? 'Catégorie' }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">{{ $product->sales_count ?? 0 }}
                                                    ventes</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item border-0 py-4 text-center text-muted">
                                        <i class="bi bi-inbox h4 mb-2"></i>
                                        <p class="mb-0">Aucun produit vendu pour le moment</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableaux récents -->
            <div class="row g-4 mt-2">
                <!-- Ventes récentes -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-clock-history me-2 text-primary"></i>
                                    Ventes récentes
                                </h5>
                                <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Client</th>
                                            <th class="border-0">Montant</th>
                                            <th class="border-0">Statut</th>
                                            <th class="border-0">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSales ?? [] as $sale)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="bi bi-person text-primary"></i>
                                                        </div>
                                                        <span class="fw-semibold">{{ $sale->customer_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">{{ number_format($sale->total, 2, ',', ' ') }} CFA
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">Payé</span>
                                                </td>
                                                <td class="text-muted">{{ $sale->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="bi bi-inbox h4 mb-2 d-block"></i>
                                                    Aucune vente récente
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertes de stock -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                                    Alertes de stock
                                </h5>
                                <a href="{{ route('alerts.stock') }}" class="btn btn-sm btn-outline-warning">Gérer</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($stockAlerts ?? [] as $alert)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">{{ $alert->product->name ?? 'Produit' }}</h6>
                                                <small class="text-muted">{{ $alert->message }}</small>
                                            </div>
                                            <span class="badge bg-danger">{{ $alert->product->quantity ?? 0 }}
                                                restants</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item border-0 py-4 text-center text-muted">
                                        <i class="bi bi-check-circle h4 mb-2 text-success"></i>
                                        <p class="mb-0">Aucune alerte de stock</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-bell me-2 text-info"></i>
                                    Notifications
                                </h5>
                                <a href="{{ route('notifications.index') }}"
                                    class="btn btn-sm btn-outline-info">Gérer</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($notifications ?? [] as $notification)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-bell text-info"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">{{ $notification->title ?? 'Notification' }}
                                                    </h5>
                                                    <small
                                                        class="text-muted">{{ $notification->message ?? 'Aucun message' }}</small>
                                            </div>
                                            <small
                                                class="text-muted">{{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item border-0 py-4 text-center text-muted">
                                        <i class="bi bi-bell-slash h4 mb-2 text-muted"></i>
                                        <p class="mb-0">Aucune notification</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des ventes
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Ventes mensuelles',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        /* Styles personnalisés pour le dashboard */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .navbar-nav .nav-link {
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            transform: translateY(-1px);
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .list-group-item {
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* Animation d'entrée */
        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand span {
                display: none;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
            }
        }
    </style>
@endsection
