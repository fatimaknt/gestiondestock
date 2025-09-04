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
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">Modifier</li>
                                </ol>
                            </nav>
                            <h1 class="h2 fw-bold text-dark mb-1 mt-2">Modifier le produit</h1>
                            <p class="text-muted mb-0">Modifiez les informations de "{{ $product->name }}"</p>
                        </div>
                        <div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de modification -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-pencil-square me-2 text-primary"></i>
                                Modifier le produit
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('products.update', $product) }}"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <!-- Informations de base -->
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-box me-2 text-primary"></i>Nom du produit *
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $product->name) }}"
                                            placeholder="Ex: Parfum Chanel N°5" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sku" class="form-label fw-semibold">
                                            <i class="bi bi-upc-scan me-2 text-primary"></i>Code SKU *
                                        </label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                            id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                            placeholder="Ex: PARF-001" required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold">
                                            <i class="bi bi-text-paragraph me-2 text-primary"></i>Description
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3" placeholder="Description détaillée du produit...">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Catégorie et Fournisseur -->
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label fw-semibold">
                                            <i class="bi bi-tags me-2 text-primary"></i>Catégorie *
                                        </label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                            <option value="">Sélectionner une catégorie</option>
                                            @foreach ($categories ?? [] as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="supplier_id" class="form-label fw-semibold">
                                            <i class="bi bi-truck me-2 text-primary"></i>Fournisseur
                                        </label>
                                        <select class="form-select @error('supplier_id') is-invalid @enderror"
                                            id="supplier_id" name="supplier_id">
                                            <option value="">Sélectionner un fournisseur</option>
                                            @foreach ($suppliers ?? [] as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Prix -->
                                    <div class="col-md-6">
                                        <label for="purchase_price" class="form-label fw-semibold">
                                            <i class="bi bi-cash-coin me-2 text-primary"></i>Prix d'achat *
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('purchase_price') is-invalid @enderror"
                                                id="purchase_price" name="purchase_price"
                                                value="{{ old('purchase_price', $product->purchase_price) }}"
                                                step="1" min="0" placeholder="0" required>
                                            <span class="input-group-text">CFA</span>
                                        </div>
                                        @error('purchase_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="selling_price" class="form-label fw-semibold">
                                            <i class="bi bi-tag me-2 text-primary"></i>Prix de vente *
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('selling_price') is-invalid @enderror"
                                                id="selling_price" name="selling_price"
                                                value="{{ old('selling_price', $product->selling_price) }}"
                                                step="1" min="0" placeholder="0" required>
                                            <span class="input-group-text">CFA</span>
                                        </div>
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Stock -->
                                    <div class="col-md-4">
                                        <label for="quantity" class="form-label fw-semibold">
                                            <i class="bi bi-box me-2 text-primary"></i>Quantité en stock *
                                        </label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                            id="quantity" name="quantity"
                                            value="{{ old('quantity', $product->quantity) }}" min="0"
                                            placeholder="0" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="min_quantity" class="form-label fw-semibold">
                                            <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stock minimum
                                        </label>
                                        <input type="number"
                                            class="form-control @error('min_quantity') is-invalid @enderror"
                                            id="min_quantity" name="min_quantity"
                                            value="{{ old('min_quantity', $product->min_quantity) }}" min="0"
                                            placeholder="0">
                                        @error('min_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="unit" class="form-label fw-semibold">
                                            <i class="bi bi-rulers me-2 text-primary"></i>Unité
                                        </label>
                                        <select class="form-select @error('unit') is-invalid @enderror" id="unit"
                                            name="unit">
                                            <option value="">Sélectionner une unité</option>
                                            <option value="piece"
                                                {{ old('unit', $product->unit) == 'piece' ? 'selected' : '' }}>Pièce
                                            </option>
                                            <option value="kg"
                                                {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogramme
                                            </option>
                                            <option value="g"
                                                {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Gramme</option>
                                            <option value="l"
                                                {{ old('unit', $product->unit) == 'l' ? 'selected' : '' }}>Litre</option>
                                            <option value="ml"
                                                {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>Millilitre
                                            </option>
                                            <option value="m"
                                                {{ old('unit', $product->unit) == 'm' ? 'selected' : '' }}>Mètre</option>
                                            <option value="cm"
                                                {{ old('unit', $product->unit) == 'cm' ? 'selected' : '' }}>Centimètre
                                            </option>
                                        </select>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Date d'expiration -->
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="form-label fw-semibold">
                                            <i class="bi bi-calendar-event me-2 text-primary"></i>Date d'expiration
                                        </label>
                                        <input type="date"
                                            class="form-control @error('expiry_date') is-invalid @enderror"
                                            id="expiry_date" name="expiry_date"
                                            value="{{ old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '') }}">
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Statut -->
                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label fw-semibold">
                                            <i class="bi bi-toggle-on me-2 text-primary"></i>Statut
                                        </label>
                                        <select class="form-select @error('is_active') is-invalid @enderror"
                                            id="is_active" name="is_active">
                                            <option value="1"
                                                {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Actif
                                            </option>
                                            <option value="0"
                                                {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactif
                                            </option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12">
                                        <label for="image" class="form-label fw-semibold">
                                            <i class="bi bi-image me-2 text-primary"></i>Image du produit
                                        </label>
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <input type="file"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    id="image" name="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="Image actuelle" class="img-thumbnail"
                                                        style="max-height: 80px;">
                                                    <small class="text-muted d-block">Image actuelle</small>
                                                @else
                                                    <small class="text-muted">Aucune image</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Boutons d'action -->
                                    <div class="col-12">
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-end gap-3">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle me-2"></i>Annuler
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Mettre à jour
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

    <script>
        // Calcul automatique de la marge
        document.getElementById('purchase_price').addEventListener('input', calculateMargin);
        document.getElementById('selling_price').addEventListener('input', calculateMargin);

        function calculateMargin() {
            const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
            const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;

            if (purchasePrice > 0 && sellingPrice > 0) {
                const margin = ((sellingPrice - purchasePrice) / purchasePrice) * 100;
                console.log(`Marge: ${margin.toFixed(1)}%`);
            }
        }
    </script>
@endsection
