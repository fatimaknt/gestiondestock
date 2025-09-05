@extends('layouts.app-with-nav')

@section('content')
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
                                <li class="breadcrumb-item active">Nouveau produit</li>
                            </ol>
                        </nav>
                        <h1 class="h2 fw-bold text-dark mb-1 mt-2">Nouveau produit</h1>
                        <p class="text-muted mb-0">Ajoutez un nouveau produit à votre inventaire</p>
                    </div>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de création -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="bi bi-plus-circle me-2 text-primary"></i>
                            Informations du produit
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ auth()->user()->shop_id }}">

                            <div class="row g-4">
                                <!-- Informations de base -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-box me-2 text-primary"></i>Nom du produit *
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Nom du produit" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="sku" class="form-label fw-semibold">
                                        <i class="bi bi-upc me-2 text-primary"></i>SKU
                                    </label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                        id="sku" name="sku" value="{{ old('sku') }}"
                                        placeholder="Code SKU unique">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Code-barres -->
                                <div class="col-md-6">
                                    <label for="barcode" class="form-label fw-semibold">
                                        <i class="bi bi-upc-scan me-2 text-primary"></i>Code-barres
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                            id="barcode" name="barcode" value="{{ old('barcode') }}"
                                            placeholder="Code-barres du produit">
                                        <button type="button" class="btn btn-outline-primary" id="scanBarcode"
                                            title="Scanner le code-barres">
                                            <i class="bi bi-camera"></i>
                                        </button>
                                    </div>
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Laissez vide pour génération automatique</small>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-text-paragraph me-2 text-primary"></i>Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" placeholder="Description détaillée du produit">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Catégorie et Fournisseur -->
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label fw-semibold">
                                        <i class="bi bi-tags me-2 text-primary"></i>Catégorie *
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach ($categories ?? [] as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                                        name="supplier_id">
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach ($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                            value="{{ old('purchase_price') }}" step="1" min="0"
                                            placeholder="0" required>
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
                                            id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
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
                                        id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0"
                                        placeholder="0" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="min_quantity" class="form-label fw-semibold">
                                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stock minimum
                                    </label>
                                    <input type="number" class="form-control @error('min_quantity') is-invalid @enderror"
                                        id="min_quantity" name="min_quantity" value="{{ old('min_quantity', 0) }}"
                                        min="0" placeholder="0">
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
                                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Pièce
                                        </option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogramme
                                        </option>
                                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gramme
                                        </option>
                                        <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Litre
                                        </option>
                                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Millilitre
                                        </option>
                                        <option value="m" {{ old('unit') == 'm' ? 'selected' : '' }}>Mètre
                                        </option>
                                        <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimètre
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
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                        id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image -->
                                <div class="col-md-6">
                                    <label for="image" class="form-label fw-semibold">
                                        <i class="bi bi-image me-2 text-primary"></i>Image du produit
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">
                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">
                                            <i class="bi bi-toggle-on me-2 text-success"></i>Produit actif
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Créer le produit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Validation Bootstrap
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Calcul automatique de la marge
        document.addEventListener('DOMContentLoaded', function() {
            const purchasePrice = document.getElementById('purchase_price');
            const sellingPrice = document.getElementById('selling_price');

            function calculateMargin() {
                if (purchasePrice.value && sellingPrice.value) {
                    const purchase = parseFloat(purchasePrice.value);
                    const selling = parseFloat(sellingPrice.value);
                    const margin = selling - purchase;
                    const marginPercent = purchase > 0 ? (margin / purchase) * 100 : 0;

                    // Ajouter un indicateur visuel de la marge
                    if (margin > 0) {
                        sellingPrice.classList.add('is-valid');
                        sellingPrice.classList.remove('is-invalid');
                    } else {
                        sellingPrice.classList.add('is-invalid');
                        sellingPrice.classList.remove('is-valid');
                    }
                }
            }

            if (purchasePrice) purchasePrice.addEventListener('input', calculateMargin);
            if (sellingPrice) sellingPrice.addEventListener('input', calculateMargin);
        });
    </script>

    <style>
        /* Styles personnalisés pour la page de création */
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

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .breadcrumb-item a {
            color: #667eea;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: #5a6fd8;
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

    <!-- JavaScript pour le scan de code-barres -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Génération automatique de SKU
            const nameInput = document.getElementById('name');
            const skuInput = document.getElementById('sku');

            nameInput.addEventListener('input', function() {
                if (!skuInput.value) {
                    const sku = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]/g, '')
                        .substring(0, 10)
                        .toUpperCase();
                    skuInput.value = sku + Math.random().toString(36).substr(2, 4).toUpperCase();
                }
            });

            // Génération automatique de code-barres si vide
            const barcodeInput = document.getElementById('barcode');
            const scanButton = document.getElementById('scanBarcode');

            // Génération automatique de code-barres
            if (!barcodeInput.value) {
                const timestamp = Date.now().toString();
                const random = Math.random().toString(36).substr(2, 5).toUpperCase();
                barcodeInput.value = timestamp + random;
            }

            // Simulation du scan (pour démonstration)
            scanButton.addEventListener('click', function() {
                // En production, ceci utiliserait une vraie API de scan
                const mockBarcodes = [
                    '1234567890123',
                    '9876543210987',
                    '5555666677778',
                    '1111222233334'
                ];

                const randomBarcode = mockBarcodes[Math.floor(Math.random() * mockBarcodes.length)];
                barcodeInput.value = randomBarcode;

                // Animation de succès
                this.innerHTML = '<i class="bi bi-check-circle text-success"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-camera"></i>';
                }, 2000);
            });

            // Validation en temps réel
            barcodeInput.addEventListener('input', function() {
                const value = this.value;
                if (value && value.length < 8) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endsection
