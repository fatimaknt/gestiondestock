@extends('layouts.app-with-nav')

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
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-box me-2"></i>Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('suppliers.index') }}">
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
                                        <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                                            <i class="bi bi-truck me-1"></i>Fournisseurs
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                                </ol>
                            </nav>
                            <h1 class="h2 fw-bold text-dark mb-1 mt-2">{{ $supplier->name }}</h1>
                            <p class="text-muted mb-0">Détails du fournisseur</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du fournisseur -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Informations du fournisseur
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Nom de l'entreprise</label>
                                    <p class="fw-bold">{{ $supplier->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Personne de contact</label>
                                    <p class="fw-bold">{{ $supplier->contact_person }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Adresse email</label>
                                    <p>
                                        <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                            {{ $supplier->email }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Numéro de téléphone</label>
                                    <p>
                                        <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                            {{ $supplier->phone }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-muted">Adresse complète</label>
                                    <p>{{ $supplier->address }}</p>
                                </div>
                                @if ($supplier->notes)
                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-muted">Notes</label>
                                        <p>{{ $supplier->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Statistiques -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-graph-up me-2 text-primary"></i>
                                Statistiques
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Produits fournis</span>
                                <span class="badge bg-primary">{{ $supplier->products_count ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Date d'ajout</span>
                                <span class="fw-semibold">{{ $supplier->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Dernière mise à jour</span>
                                <span class="fw-semibold">{{ $supplier->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-lightning me-2 text-primary"></i>
                                Actions rapides
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $supplier->email }}" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-2"></i>Envoyer un email
                                </a>
                                <a href="tel:{{ $supplier->phone }}" class="btn btn-outline-success">
                                    <i class="bi bi-telephone me-2"></i>Appeler
                                </a>
                                <a href="{{ route('products.create') }}" class="btn btn-outline-info">
                                    <i class="bi bi-plus-circle me-2"></i>Ajouter un produit
                                </a>
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
