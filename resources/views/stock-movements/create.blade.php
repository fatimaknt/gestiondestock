@extends('layouts.app-with-nav')

@section('title', 'Nouveau Mouvement de Stock - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-arrow-left-right text-primary me-2"></i>Nouveau Mouvement de Stock
            </h1>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>Détails du Mouvement
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stock-movements.store') }}" method="POST" id="movementForm">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="product_id" class="form-label">Produit *</label>
                                    <select name="product_id" id="product_id" class="form-select" required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}"
                                                data-price="{{ $product->purchase_price }}">
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
                                        <option value="in">Entrée de Stock</option>
                                        <option value="out">Sortie de Stock</option>
                                        <option value="adjustment">Ajustement de Stock</option>
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
                                        value="1" required>
                                    @error('quantity')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_cost" class="form-label">Prix Unitaire (CFA) *</label>
                                    <input type="number" name="unit_cost" id="unit_cost" class="form-control"
                                        min="0" step="100" required>
                                    @error('unit_cost')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="movement_date" class="form-label">Date du Mouvement *</label>
                                    <input type="date" name="movement_date" id="movement_date" class="form-control"
                                        value="{{ old('movement_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                        required>
                                    @error('movement_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Date maximale : aujourd'hui</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    placeholder="Raison du mouvement, observations...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Résumé du mouvement -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i>Résumé du Mouvement
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
                                <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer le Mouvement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Aide et informations -->
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-question-circle me-2"></i>Aide
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Types de Mouvements</h6>
                            <ul class="list-unstyled small">
                                <li><strong>Entrée:</strong> Ajoute du stock (achats, retours)</li>
                                <li><strong>Sortie:</strong> Retire du stock (ventes, pertes)</li>
                                <li><strong>Ajustement:</strong> Corrige le stock (inventaire)</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <h6>Bonnes Pratiques</h6>
                            <ul class="list-unstyled small">
                                <li>✓ Vérifiez toujours le stock avant</li>
                                <li>✓ Documentez la raison du mouvement</li>
                                <li>✓ Utilisez des prix cohérents</li>
                            </ul>
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

            // Auto-remplir le prix unitaire quand un produit est sélectionné
            productSelect.addEventListener('change', function() {
                const selectedOption = this.selectedOptions[0];
                if (selectedOption && selectedOption.dataset.price) {
                    unitCostInput.value = selectedOption.dataset.price;
                    updateSummary();
                }
            });
        });
    </script>
@endpush
