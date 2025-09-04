@extends('layouts.app-with-nav')

@section('title', 'Rapport Stock - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-box-seam text-primary me-2"></i>Rapport de Stock
            </h1>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('reports.export.stock') }}" class="btn btn-outline-info">
                    <i class="bi bi-download me-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.stock') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="category_filter" class="form-label">Catégorie</label>
                        <select name="category_filter" id="category_filter" class="form-select">
                            <option value="">Toutes les catégories</option>
                            <option value="1" {{ request('category_filter') == '1' ? 'selected' : '' }}>Parfums
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="supplier_filter" class="form-label">Fournisseur</label>
                        <select name="supplier_filter" id="supplier_filter" class="form-select">
                            <option value="">Tous les fournisseurs</option>
                            <option value="1" {{ request('supplier_filter') == '1' ? 'selected' : '' }}>Fournisseur
                                Principal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stock_filter" class="form-label">Niveau de Stock</label>
                        <select name="stock_filter" id="stock_filter" class="form-select">
                            <option value="">Tous les niveaux</option>
                            <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Rupture</option>
                            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Faible</option>
                            <option value="normal" {{ request('stock_filter') == 'normal' ? 'selected' : '' }}>Normal
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">Statut</label>
                        <select name="status_filter" id="status_filter" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status_filter') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('status_filter') == '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résumé du stock -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $totalProducts ?? 0 }}</h4>
                        <p class="mb-0">Total Produits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $outOfStockProducts ?? 0 }}</h4>
                        <p class="mb-0">En Rupture</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $lowStockProducts ?? 0 }}</h4>
                        <p class="mb-0">Stock Faible</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ number_format($totalValue ?? 0, 0, ',', ' ') }} CFA</h4>
                        <p class="mb-0">Valeur Totale</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Inventaire du Stock
                </h5>
            </div>
            <div class="card-body">
                @if (isset($products) && $products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Catégorie</th>
                                    <th>Fournisseur</th>
                                    <th>Stock</th>
                                    <th>Prix d'achat</th>
                                    <th>Prix de vente</th>
                                    <th>Valeur</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/40x40' }}"
                                                    class="rounded me-2" width="40" height="40"
                                                    alt="{{ $product->name }}">
                                                <div>
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ $product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                {{ $product->supplier->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($product->quantity <= ($product->min_quantity ?? 0))
                                                <span class="badge bg-danger">{{ $product->quantity }}</span>
                                            @elseif($product->quantity <= ($product->min_quantity ?? 0) * 2)
                                                <span class="badge bg-warning">{{ $product->quantity }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $product->quantity }}</span>
                                            @endif
                                            <small class="text-muted">/ {{ $product->min_quantity ?? 0 }} min</small>
                                        </td>
                                        <td>{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</td>
                                        <td>{{ number_format($product->selling_price, 0, ',', ' ') }} CFA</td>
                                        <td class="fw-bold">
                                            {{ number_format($product->quantity * $product->purchase_price, 0, ',', ' ') }}
                                            CFA</td>
                                        <td>
                                            @if ($product->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">Aucun produit trouvé</h5>
                        <p class="text-muted">Commencez par ajouter des produits à votre inventaire</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter un Produit
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
