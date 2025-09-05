@extends('layouts.app-with-nav')

@section('content')
    <div class="container-fluid pt-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-box me-2"></i>Détails du Produit
            </h1>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations du produit -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations Générales</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>{{ $product->name }}</h5>
                                <p class="text-muted">{{ $product->description ?? 'Aucune description' }}</p>

                                <div class="mb-3">
                                    <strong>SKU/Code Barre:</strong>
                                    <span class="ms-2">{{ $product->sku ?? ($product->barcode ?? 'N/A') }}</span>
                                </div>

                                <div class="mb-3">
                                    <strong>Catégorie:</strong>
                                    <span class="ms-2">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>

                                <div class="mb-3">
                                    <strong>Fournisseur:</strong>
                                    <span class="ms-2">{{ $product->supplier->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Quantité en Stock:</strong>
                                    <span
                                        class="ms-2 badge bg-{{ $product->quantity <= $product->min_quantity ? 'warning' : 'success' }}">
                                        {{ $product->quantity }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <strong>Quantité Minimale:</strong>
                                    <span class="ms-2">{{ $product->min_quantity }}</span>
                                </div>

                                <div class="mb-3">
                                    <strong>Prix d'Achat:</strong>
                                    <span
                                        class="ms-2 text-success">{{ number_format($product->purchase_price, 0, ',', ' ') }}
                                        FCFA</span>
                                </div>

                                <div class="mb-3">
                                    <strong>Prix de Vente:</strong>
                                    <span
                                        class="ms-2 text-primary">{{ number_format($product->selling_price, 0, ',', ' ') }}
                                        FCFA</span>
                                </div>

                                <div class="mb-3">
                                    <strong>Marge:</strong>
                                    <span
                                        class="ms-2 text-info">{{ number_format($product->selling_price - $product->purchase_price, 0, ',', ' ') }}
                                        FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image et statut -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Image du Produit</h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="text-muted">
                                <i class="bi bi-image" style="font-size: 4rem;"></i>
                                <p>Aucune image</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statut du Stock</h6>
                    </div>
                    <div class="card-body">
                        @if ($product->quantity == 0)
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Rupture de Stock</strong>
                            </div>
                        @elseif ($product->quantity <= $product->min_quantity)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Stock Bas</strong>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Stock Normal</strong>
                            </div>
                        @endif

                        <div class="mt-3">
                            <strong>Valeur du Stock:</strong>
                            <div class="h5 text-primary">
                                {{ number_format($product->quantity * $product->purchase_price, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des mouvements -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Historique des Mouvements</h6>
                    </div>
                    <div class="card-body">
                        @if ($product->stockMovements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Quantité</th>
                                            <th>Stock Avant</th>
                                            <th>Stock Après</th>
                                            <th>Raison</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->stockMovements->sortByDesc('created_at') as $movement)
                                            <tr>
                                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $movement->type === 'in' ? 'success' : 'danger' }}">
                                                        {{ $movement->type === 'in' ? 'Entrée' : 'Sortie' }}
                                                    </span>
                                                </td>
                                                <td>{{ $movement->quantity }}</td>
                                                <td>{{ $movement->stock_before }}</td>
                                                <td>{{ $movement->stock_after }}</td>
                                                <td>{{ $movement->reason ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucun mouvement de stock enregistré.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
