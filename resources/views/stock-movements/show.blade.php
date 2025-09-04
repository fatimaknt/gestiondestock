@extends('layouts.app-with-nav')

@section('title', 'Détails du Mouvement - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-eye text-primary me-2"></i>Détails du Mouvement
            </h1>
            <div>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informations du Mouvement
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Produit :</strong></td>
                                        <td>{{ $stockMovement->product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>SKU :</strong></td>
                                        <td>{{ $stockMovement->product->sku }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type :</strong></td>
                                        <td>
                                            @if ($stockMovement->type === 'in')
                                                <span class="badge bg-success">Entrée</span>
                                            @elseif($stockMovement->type === 'out')
                                                <span class="badge bg-danger">Sortie</span>
                                            @else
                                                <span class="badge bg-warning">Ajustement</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Quantité :</strong></td>
                                        <td><strong>{{ $stockMovement->quantity }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Prix Unitaire :</strong></td>
                                        <td>{{ number_format($stockMovement->unit_cost) }} CFA</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Stock Avant :</strong></td>
                                        <td>{{ $stockMovement->quantity_before }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Après :</strong></td>
                                        <td><strong>{{ $stockMovement->quantity_after }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date :</strong></td>
                                        <td>{{ $stockMovement->movement_date ? $stockMovement->movement_date->format('d/m/Y') : $stockMovement->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Heure :</strong></td>
                                        <td>{{ $stockMovement->created_at->format('H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Utilisateur :</strong></td>
                                        <td>{{ $stockMovement->user->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($stockMovement->notes)
                            <div class="mt-4">
                                <h6><i class="bi bi-chat-text me-2"></i>Notes</h6>
                                <div class="alert alert-light">
                                    {{ $stockMovement->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calculator me-2"></i>Calculs
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted">Valeur Totale</h6>
                                    <h4 class="text-primary">
                                        {{ number_format($stockMovement->quantity * $stockMovement->unit_cost) }} CFA</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted">Impact Stock</h6>
                                    <h4 class="{{ $stockMovement->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $stockMovement->type === 'in' ? '+' : '-' }}{{ $stockMovement->quantity }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Historique
                        </h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>Créé le :</strong> {{ $stockMovement->created_at->format('d/m/Y à H:i') }}<br>
                            <strong>Modifié le :</strong> {{ $stockMovement->updated_at->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
