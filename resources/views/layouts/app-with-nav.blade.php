<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion de Stock') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-custom.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Personnalisation de la Boutique -->
    @if (isset($shopPersonalization))
        <style>
            :root {
                --bs-primary: {{ $shopPersonalization['primary_color'] }};
                --bs-secondary: {{ $shopPersonalization['secondary_color'] }};
            }

            .navbar-brand {
                color: {{ $shopPersonalization['primary_color'] }} !important;
            }

            .btn-primary {
                background-color: {{ $shopPersonalization['primary_color'] }};
                border-color: {{ $shopPersonalization['primary_color'] }};
            }

            .btn-primary:hover {
                background-color: {{ $shopPersonalization['primary_color'] }};
                border-color: {{ $shopPersonalization['primary_color'] }};
                opacity: 0.9;
            }

            .text-primary {
                color: {{ $shopPersonalization['primary_color'] }} !important;
            }

            .border-primary {
                border-color: {{ $shopPersonalization['primary_color'] }} !important;
            }

            .bg-primary {
                background-color: {{ $shopPersonalization['primary_color'] }} !important;
            }
        </style>
    @endif
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div id="app" class="h-full">
        <!-- Navigation principale -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-lg">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                    @if (isset($shopPersonalization) && $shopPersonalization['logo'])
                        <img src="{{ Storage::url($shopPersonalization['logo']) }}" alt="Logo" class="me-2"
                            style="height: 32px;">
                    @else
                        <i class="bi bi-box-seam h4 mb-0 me-2"></i>
                    @endif
                    <span class="fw-bold">
                        @if (isset($shopPersonalization))
                            {{ $shopPersonalization['name'] }}
                        @else
                            Gestion de Stock
                        @endif
                    </span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                                href="{{ route('products.index') }}">
                                <i class="bi bi-box me-2"></i>Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('cashier.*') ? 'active' : '' }}"
                                href="{{ route('cashier.index') }}">
                                <i class="bi bi-cash-coin me-2"></i>Caisse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('cashier.history') ? 'active' : '' }}"
                                href="{{ route('cashier.history') }}">
                                <i class="bi bi-cart me-2"></i>Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                                href="{{ route('suppliers.index') }}">
                                <i class="bi bi-truck me-2"></i>Fournisseurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                                href="{{ route('clients.index') }}">
                                <i class="bi bi-people me-2"></i>Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up me-2"></i>Rapports
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell me-2"></i>
                                @php
                                    $unreadAlerts = \App\Models\StockAlert::where('shop_id', Auth::user()->shop_id)
                                        ->where('is_read', false)
                                        ->count();
                                    $unreadNotifications = \DB::table('notifications')
                                        ->where('notifiable_id', Auth::id())
                                        ->where('notifiable_type', 'App\Models\User')
                                        ->whereNull('read_at')
                                        ->count();
                                    $totalUnread = $unreadAlerts + $unreadNotifications;
                                @endphp
                                @if ($totalUnread > 0)
                                    <span class="badge bg-danger">{{ $totalUnread }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="bi bi-bell me-2"></i>Notifications
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('alerts.stock') }}">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Alertes de stock
                                    </a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('shops.index') }}">
                                        <i class="bi bi-shop me-2"></i>Ma Boutique
                                    </a></li>
                                @if (Auth::user()->hasRole('admin'))
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="bi bi-people me-2"></i>Gestion des Utilisateurs
                                        </a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="min-vh-100 bg-light">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    @stack('scripts')

    <script>
        // Fonctions globales pour les notifications
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la mise à jour');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la mise à jour');
                });
        }

        function deleteNotification(notificationId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                fetch(`/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la suppression');
                    });
            }
        }

        function markAllAsRead() {
            if (confirm('Marquer toutes les notifications comme lues ?')) {
                // Implémenter la logique pour marquer toutes comme lues
                alert('Fonctionnalité à implémenter');
            }
        }

        function deleteAllRead() {
            if (confirm('Supprimer toutes les notifications lues ?')) {
                // Implémenter la logique pour supprimer toutes les lues
                alert('Fonctionnalité à implémenter');
            }
        }
    </script>
</body>

</html>
