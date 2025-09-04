@extends('layouts.app-with-nav')

@section('title', 'Mouvements de Stock - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-arrow-left-right text-primary me-2"></i>Mouvements de Stock
            </h1>
            <div>
                <a href="{{ route('stock-movements.export.pdf') }}" class="btn btn-outline-danger me-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </a>
                <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau Mouvement
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('stock-movements.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="product_filter" class="form-label">Produit</label>
                        <select name="product_filter" id="product_filter" class="form-select">
                            <option value="">Tous les produits</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_filter') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="type_filter" class="form-label">Type</label>
                        <select name="type_filter" id="type_filter" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="in" {{ request('type_filter') == 'in' ? 'selected' : '' }}>Entrée</option>
                            <option value="out" {{ request('type_filter') == 'out' ? 'selected' : '' }}>Sortie</option>
                            <option value="adjustment" {{ request('type_filter') == 'adjustment' ? 'selected' : '' }}>
                                Ajustement</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des mouvements -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Historique des Mouvements
                </h5>
            </div>
            <div class="card-body">
                @if ($movements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Produit</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Stock Avant</th>
                                    <th>Stock Après</th>
                                    <th>Prix Unitaire</th>
                                    <th>Utilisateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movements as $movement)
                                    <tr>
                                        <td>
                                            <strong>{{ $movement->movement_date ? $movement->movement_date->format('d/m/Y') : $movement->created_at->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $movement->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $movement->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $movement->product->sku }}</small>
                                        </td>
                                        <td>
                                            @if ($movement->type === 'in')
                                                <span class="badge bg-success">Entrée</span>
                                            @elseif($movement->type === 'out')
                                                <span class="badge bg-danger">Sortie</span>
                                            @else
                                                <span class="badge bg-warning">Ajustement</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $movement->quantity }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $movement->quantity_before }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $movement->quantity_after }}</strong>
                                        </td>
                                        <td>
                                            {{ number_format($movement->unit_cost) }} CFA
                                        </td>
                                        <td>
                                            {{ $movement->user->name }}
                                        </td>
                                        <td>
                                            <a href="{{ route('stock-movements.show', $movement) }}"
                                                class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $movements->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-arrow-left-right text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">Aucun mouvement de stock trouvé</h5>
                        <p class="text-muted">Commencez par créer votre premier mouvement de stock</p>
                        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer un Mouvement
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
