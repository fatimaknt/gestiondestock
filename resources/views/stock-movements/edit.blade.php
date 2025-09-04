@extends('layouts.app-with-nav')

@section('title', 'Modifier le Mouvement - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil text-primary me-2"></i>Modifier le Mouvement
            </h1>
            <a href="{{ route('stock-movements.show', $stockMovement) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Modifier les Détails
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stock-movements.update', $stockMovement) }}" method="POST"
                            id="editMovementForm">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="product_id" class="form-label">Produit *</label>
                                    <select name="product_id" id="product_id" class="form-select" required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}"
                                                data-price="{{ $product->purchase_price }}"
                                                {{ $product->id == $stockMovement->product_id ? 'selected' : '' }}>
                                                {{ $product->name }} (Stock: {{ $product->quantity }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type de Mouvement *</label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="in" {{ $stockMovement->type === 'in' ? 'selected' : '' }}>Entrée
                                            de Stock</option>
                                        <option value="out" {{ $stockMovement->type === 'out' ? 'selected' : '' }}>
                                            Sortie de Stock</option>
                                        <option value="adjustment"
                                            {{ $stockMovement->type === 'adjustment' ? 'selected' : '' }}>Ajustement de
                                            Stock</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantité *</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                                        value="{{ old('quantity', $stockMovement->quantity) }}" required>
                                    @error('quantity')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_cost" class="form-label">Prix Unitaire (CFA) *</label>
                                    <input type="number" name="unit_cost" id="unit_cost" class="form-control"
                                        min="0" step="100"
                                        value="{{ old('unit_cost', $stockMovement->unit_cost) }}" required>
                                    @error('unit_cost')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="movement_date" class="form-label">Date du Mouvement *</label>
                                    <input type="date" name="movement_date" id="movement_date" class="form-control"
                                        value="{{ old('movement_date', $stockMovement->movement_date ? $stockMovement->movement_date->format('Y-m-d') : date('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}" required>
                                    @error('movement_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    placeholder="Raison du mouvement, observations...">{{ old('notes', $stockMovement->notes) }}</textarea>
                                @error('notes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Résumé du mouvement -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i>Résumé de la Modification
                                </h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Stock Actuel:</strong>
                                        <div id="currentStock">-</div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Stock Après:</strong>
                                        <div id="newStock">-</div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Type:</strong>
                                        <div id="movementType">-</div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Impact:</strong>
                                        <div id="stockImpact">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('stock-movements.show', $stockMovement) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Mettre à Jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informations Actuelles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Produit</h6>
                            <p class="mb-1"><strong>{{ $stockMovement->product->name }}</strong></p>
                            <small class="text-muted">SKU: {{ $stockMovement->product->sku }}</small>
                        </div>
                        <div class="mb-3">
                            <h6>Type</h6>
                            @if ($stockMovement->type === 'in')
                                <span class="badge bg-success">Entrée</span>
                            @elseif($stockMovement->type === 'out')
                                <span class="badge bg-danger">Sortie</span>
                            @else
                                <span class="badge bg-warning">Ajustement</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <h6>Quantité</h6>
                            <p class="mb-0"><strong>{{ $stockMovement->quantity }}</strong></p>
                        </div>
                        <div class="mb-3">
                            <h6>Prix Unitaire</h6>
                            <p class="mb-0"><strong>{{ number_format($stockMovement->unit_cost) }} CFA</strong></p>
                        </div>
                        @if ($stockMovement->notes)
                            <div class="mb-3">
                                <h6>Notes</h6>
                                <p class="mb-0 text-muted">{{ $stockMovement->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>Attention
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            <small>
                                <strong>⚠️ Modification importante :</strong><br>
                                La modification d'un mouvement de stock peut affecter l'inventaire.
                                Vérifiez bien les quantités avant de valider.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const typeSelect = document.getElementById('type');
            const quantityInput = document.getElementById('quantity');
            const unitCostInput = document.getElementById('unit_cost');

            function updateSummary() {
                const selectedOption = productSelect.selectedOptions[0];
                const selectedType = typeSelect.value;
                const quantity = parseInt(quantityInput.value) || 0;
                const unitCost = parseFloat(unitCostInput.value) || 0;

                if (selectedOption && selectedType && quantity > 0) {
                    const currentStock = parseInt(selectedOption.dataset.stock);
                    let newStock = currentStock;
                    let impact = '';

                    if (selectedType === 'in') {
                        newStock = currentStock + quantity;
                        impact = `+${quantity} (Ajout)`;
                    } else if (selectedType === 'out') {
                        if (quantity > currentStock) {
                            impact = '⚠️ Quantité insuffisante';
                            newStock = 0;
                        } else {
                            newStock = currentStock - quantity;
                            impact = `-${quantity} (Retrait)`;
                        }
                    } else { // adjustment
                        newStock = quantity;
                        impact = `=${quantity} (Ajustement)`;
                    }

                    document.getElementById('currentStock').textContent = currentStock;
                    document.getElementById('newStock').textContent = newStock;
                    document.getElementById('movementType').textContent = selectedType === 'in' ? 'Entrée' :
                        selectedType === 'out' ? 'Sortie' : 'Ajustement';
                    document.getElementById('stockImpact').textContent = impact;
                } else {
                    document.getElementById('currentStock').textContent = '-';
                    document.getElementById('newStock').textContent = '-';
                    document.getElementById('movementType').textContent = '-';
                    document.getElementById('stockImpact').textContent = '-';
                }
            }

            // Event listeners
            productSelect.addEventListener('change', updateSummary);
            typeSelect.addEventListener('change', updateSummary);
            quantityInput.addEventListener('input', updateSummary);
            unitCostInput.addEventListener('input', updateSummary);

            // Initialiser le résumé
            updateSummary();
        });
    </script>
@endpush
