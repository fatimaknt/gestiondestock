@extends('layouts.app')

@section('title', 'Nouvelle Commande')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-cart-plus text-primary me-2"></i>Nouvelle Commande Fournisseur
            </h1>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Articles de la Commande
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                            @csrf

                            <!-- Informations de base -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="supplier_id" class="form-label">Fournisseur *</label>
                                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="order_date" class="form-label">Date de Commande *</label>
                                    <input type="date" name="order_date" id="order_date" class="form-control"
                                        value="{{ old('order_date', date('Y-m-d')) }}" required>
                                    @error('order_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="expected_delivery" class="form-label">Livraison Prévue</label>
                                    <input type="date" name="expected_delivery" id="expected_delivery"
                                        class="form-control" value="{{ old('expected_delivery') }}">
                                    @error('expected_delivery')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Articles -->
                            <div id="purchaseItems">
                                <div class="purchase-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Produit *</label>
                                            <select name="items[0][product_id]" class="form-select product-select" required>
                                                <option value="">Sélectionner un produit</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-price="{{ $product->purchase_price }}">
                                                        {{ $product->name }} ({{ $product->sku }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantité *</label>
                                            <input type="number" name="items[0][quantity]"
                                                class="form-control quantity-input" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Prix Unitaire (CFA) *</label>
                                            <input type="number" name="items[0][unit_cost]"
                                                class="form-control price-input" min="0" step="100" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Total</label>
                                            <input type="text" class="form-control total-display" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton Ajouter Article -->
                            <div class="text-center mb-4">
                                <button type="button" class="btn btn-outline-primary" id="addItem">
                                    <i class="bi bi-plus-circle me-2"></i>Ajouter un Article
                                </button>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Notes sur la commande...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Créer la Commande
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Résumé -->
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calculator me-2"></i>Résumé de la Commande
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Total des Articles</h6>
                            <div class="h4 text-primary" id="totalItems">0 CFA</div>
                        </div>
                        <div class="mb-3">
                            <h6>Nombre d'Articles</h6>
                            <div class="h5" id="itemCount">0</div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6>Total Général</h6>
                            <div class="h3 text-success" id="grandTotal">0 CFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let itemCount = 1;

        function addPurchaseItem() {
            const template = `
        <div class="purchase-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Produit *</label>
                    <select name="items[${itemCount}][product_id]" class="form-select product-select" required>
                        <option value="">Sélectionner un produit</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                    data-price="{{ $product->purchase_price }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantité *</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity-input"
                           min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix Unitaire (CFA) *</label>
                    <input type="number" name="items[${itemCount}][unit_cost]" class="form-control price-input"
                           min="0" step="100" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Total</label>
                    <input type="text" class="form-control total-display" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

            document.getElementById('purchaseItems').insertAdjacentHTML('beforeend', template);
            itemCount++;
            updateTotals();
        }

        function removePurchaseItem(button) {
            button.closest('.purchase-item').remove();
            updateTotals();
        }

        function updateItemTotal(item) {
            const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(item.querySelector('.price-input').value) || 0;
            const total = quantity * price;

            item.querySelector('.total-display').value = total.toFixed(0) + ' CFA';
            updateTotals();
        }

        function updateTotals() {
            let totalItems = 0;
            let itemCount = 0;

            document.querySelectorAll('.purchase-item').forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(item.querySelector('.price-input').value) || 0;
                totalItems += quantity * price;
                if (quantity > 0 && price > 0) itemCount++;
            });

            document.getElementById('totalItems').textContent = totalItems.toFixed(0) + ' CFA';
            document.getElementById('itemCount').textContent = itemCount;
            document.getElementById('grandTotal').textContent = totalItems.toFixed(0) + ' CFA';
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter article
            document.getElementById('addItem').addEventListener('click', addPurchaseItem);

            // Supprimer article
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    removePurchaseItem(e.target);
                }
            });

            // Mettre à jour les totaux
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input') || e.target.classList.contains(
                        'price-input')) {
                    updateItemTotal(e.target.closest('.purchase-item'));
                }
            });

            // Sélection de produit
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    const option = e.target.selectedOptions[0];
                    const price = option.dataset.price;
                    if (price) {
                        e.target.closest('.purchase-item').querySelector('.price-input').value = price;
                        updateItemTotal(e.target.closest('.purchase-item'));
                    }
                }
            });

            // Initialisation
            updateTotals();
        });
    </script>
@endpush
