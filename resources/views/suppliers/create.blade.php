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
                                    <li class="breadcrumb-item active">Nouveau fournisseur</li>
                                </ol>
                            </nav>
                            <h1 class="h2 fw-bold text-dark mb-1 mt-2">Nouveau fournisseur</h1>
                            <p class="text-muted mb-0">Ajoutez un nouveau fournisseur à votre réseau</p>
                        </div>
                        <div>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-plus-circle me-2 text-primary"></i>
                                Informations du fournisseur
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('suppliers.store') }}" class="needs-validation"
                                novalidate>
                                @csrf

                                <div class="row g-4">
                                    <!-- Nom de l'entreprise -->
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-building me-2 text-primary"></i>Nom de l'entreprise *
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Ex: Entreprise ABC" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Personne de contact -->
                                    <div class="col-md-6">
                                        <label for="contact_person" class="form-label fw-semibold">
                                            <i class="bi bi-person me-2 text-primary"></i>Personne de contact *
                                        </label>
                                        <input type="text"
                                            class="form-control @error('contact_person') is-invalid @enderror"
                                            id="contact_person" name="contact_person"
                                            value="{{ old('contact_person') }}" placeholder="Ex: Jean Dupont" required>
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-2 text-primary"></i>Adresse email *
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="contact@entreprise.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="bi bi-telephone me-2 text-primary"></i>Numéro de téléphone *
                                        </label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}"
                                            placeholder="Ex: +221 77 123 45 67" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Adresse -->
                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt me-2 text-primary"></i>Adresse complète *
                                        </label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                            placeholder="Ex: 123 Rue de la Paix, Dakar, Sénégal" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12">
                                        <label for="notes" class="form-label fw-semibold">
                                            <i class="bi bi-sticky me-2 text-primary"></i>Notes (optionnel)
                                        </label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                            placeholder="Informations supplémentaires, conditions spéciales, etc.">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Boutons d'action -->
                                    <div class="col-12">
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-end gap-3">
                                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle me-2"></i>Annuler
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Enregistrer le fournisseur
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles personnalisés pour la page fournisseurs */
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

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

            .d-flex.justify-content-end {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection
