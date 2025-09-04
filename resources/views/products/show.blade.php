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
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('products.index') }}">
                                <i class="bi bi-box me-2"></i>Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('suppliers.index') }}">
                                <i class="bi bi-truck me-2"></i>Fournisseurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-cart me-2"></i>Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people me-2"></i>Clients
                            </a>
                        </li>
                    </ul>

                    <div class="navbar-nav">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a>
                                </li>
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
            <!-- En-tête de la page -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                                            <i class="bi bi-box me-1"></i>Produits
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                                </ol>
                            </nav>
                            <h1 class="h2 fw-bold text-dark mb-1 mt-2">{{ $product->name }}</h1>
                            <p class="text-muted mb-0">Détails du produit</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du produit -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Informations du produit
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Nom du produit</label>
                                    <p class="fw-bold">{{ $product->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Code SKU</label>
                                    <p class="fw-bold">{{ $product->sku }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-muted">Description</label>
                                    <p>{{ $product->description ?: 'Aucune description' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Catégorie</label>
                                    <p>{{ $product->category->name ?? 'Non catégorisé' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Fournisseur</label>
                                    <p>{{ $product->supplier->name ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Prix d'achat</label>
                                    <p class="fw-bold text-success">
                                        {{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Prix de vente</label>
                                    <p class="fw-bold text-primary">
                                        {{ number_format($product->selling_price, 0, ',', ' ') }} CFA</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted">Quantité en stock</label>
                                    <p class="fw-bold">{{ $product->quantity }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted">Stock minimum</label>
                                    <p>{{ $product->min_quantity }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted">Unité</label>
                                    <p>{{ $product->unit }}</p>
                                </div>
                                @if ($product->expiry_date)
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted">Date d'expiration</label>
                                        <p>{{ $product->expiry_date->format('d/m/Y') }}</p>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Statut</label>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Image du produit -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-image me-2 text-primary"></i>
                                Image du produit
                            </h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded p-4">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucune image</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-graph-up me-2 text-primary"></i>
                                Statistiques
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Marge bénéficiaire</span>
                                <span class="fw-bold text-success">{{ number_format($product->profit_margin, 1) }}%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Bénéfice unitaire</span>
                                <span class="fw-bold text-success">{{ number_format($product->profit, 0, ',', ' ') }}
                                    CFA</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Statut du stock</span>
                                @if ($product->isOutOfStock())
                                    <span class="badge bg-danger">Rupture</span>
                                @elseif($product->isLowStock())
                                    <span class="badge bg-warning">Stock faible</span>
                                @else
                                    <span class="badge bg-success">Stock OK</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
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

            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem !important;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection
