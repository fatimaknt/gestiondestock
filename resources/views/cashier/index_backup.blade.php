@extends('layouts.app')

@section('title', 'Caisse - Gestion de Stock')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Colonne gauche - Produits et recherche -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-search me-2"></i>Recherche de Produits
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Barre de recherche -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Rechercher par nom, SKU ou description...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">Toutes les catégories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="supplierFilter" class="form-select">
                                    <option value="">Tous les fournisseurs</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Scanner de code-barres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" id="barcodeInput" class="form-control"
                                        placeholder="Scanner le code-barres...">
                                    <button class="btn btn-outline-secondary" type="button" onclick="scanBarcode()">
                                        <i class="bi bi-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des produits -->
                        <div class="row" id="productsList">
                            @foreach ($products as $product)
                                <div class="col-md-6 col-lg-4 mb-3 product-item" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-sku="{{ $product->sku }}"
                                    data-price="{{ $product->selling_price }}" data-stock="{{ $product->quantity }}"
                                    data-category="{{ $product->category_id }}"
                                    data-supplier="{{ $product->supplier_id }}">
                                    <div class="card h-100 product-card" onclick="addToCart({{ $product->id }})">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                                            <p class="card-text small text-muted mb-1">{{ $product->sku }}</p>
                                            <p class="card-text small text-muted mb-2">
                                                {{ $product->category->name ?? 'N/A' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">{{ number_format($product->quantity) }} en
                                                    stock</span>
                                                <span
                                                    class="fw-bold text-primary">{{ number_format($product->selling_price) }}
                                                    CFA</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Panier et finalisation -->
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-check me-2"></i>Panier de Vente
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Articles du panier -->
                        <div id="cartItems" class="mb-3">
                            <p class="text-muted text-center">Aucun article dans le panier</p>
                        </div>

                        <!-- Résumé -->
                        <div class="border-top pt-3">
                            <div class="row mb-2">
                                <div class="col-6">Sous-total:</div>
                                <div class="col-6 text-end" id="subtotal">0 CFA</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">TVA (18%):</div>
                                <div class="col-6 text-end" id="taxAmount">0 CFA</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Remise:</div>
                                <div class="col-6 text-end">
                                    <input type="number" id="discountInput" class="form-control form-control-sm text-end"
                                        value="0" min="0" step="100" onchange="calculateTotal()">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6 fw-bold">Total:</div>
                                <div class="col-6 text-end fw-bold" id="total">0 CFA</div>
                            </div>

                            <!-- Informations client -->
                            <div class="mb-3">
                                <h6 class="mb-2">Informations Client</h6>
                                <input type="text" id="customerName" class="form-control mb-2"
                                    placeholder="Nom du client">
                                <input type="tel" id="customerPhone" class="form-control mb-2"
                                    placeholder="Téléphone">
                                <input type="email" id="customerEmail" class="form-control mb-2" placeholder="Email">
                            </div>

                            <!-- Méthode de paiement -->
                            <div class="mb-3">
                                <h6 class="mb-2">Méthode de Paiement</h6>
                                <select id="paymentMethod" class="form-select">
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="bank_transfer">Virement bancaire</option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <textarea id="notes" class="form-control" rows="2" placeholder="Notes de vente..."></textarea>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-grid gap-2">
                                <button class="btn btn-success btn-lg" onclick="processSale()">
                                    <i class="bi bi-check-circle me-2"></i>Finaliser la Vente
                                </button>
                                <button class="btn btn-outline-secondary" onclick="clearCart()">
                                    <i class="bi bi-trash me-2"></i>Vider le Panier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="saleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vente Finalisée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>La vente a été enregistrée avec succès !</p>
                    <p><strong>Numéro de vente:</strong> <span id="saleNumber"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" id="printInvoice" class="btn btn-primary">
                        <i class="bi bi-printer me-2"></i>Imprimer la Facture
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let cart = [];
        let products = [];

        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;

            const existingItem = cart.find(item => item.product_id === productId);
            if (existingItem) {
                existingItem.quantity++;
                existingItem.total_price = existingItem.quantity * existingItem.unit_price;
            } else {
                cart.push({
                    product_id: productId,
                    name: product.name,
                    sku: product.sku,
                    quantity: 1,
                    unit_price: parseFloat(product.selling_price),
                    total_price: parseFloat(product.selling_price)
                });
            }

            updateCartDisplay();
            calculateTotal();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
            calculateTotal();
        }

        function updateQuantity(index, change) {
            const item = cart[index];
            const newQuantity = item.quantity + change;

            if (newQuantity > 0) {
                item.quantity = newQuantity;
                item.total_price = item.quantity * item.unit_price;
                updateCartDisplay();
                calculateTotal();
            }
        }

        function updateCartDisplay() {
            const cartContainer = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-muted text-center">Aucun article dans le panier</p>';
                return;
            }

            let html = '';
            cart.forEach((item, index) => {
                html += `
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                <div class="flex-grow-1">
                    <h6 class="mb-0 small">${item.name}</h6>
                    <small class="text-muted">${item.sku}</small>
                </div>
                <div class="text-end">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, -1)">-</button>
                        <span class="mx-2">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                    <div class="small text-muted">${item.unit_price} CFA</div>
                    <div class="fw-bold">${item.total_price} CFA</div>
                </div>
                <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
            });

            cartContainer.innerHTML = html;
        }

        function calculateTotal() {
            const subtotal = cart.reduce((sum, item) => sum + item.total_price, 0);
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const taxRate = 0.18; // 18% TVA
            const taxAmount = (subtotal - discount) * taxRate;
            const total = subtotal - discount + taxAmount;

            document.getElementById('subtotal').textContent = subtotal.toFixed(0) + ' CFA';
            document.getElementById('taxAmount').textContent = taxAmount.toFixed(0) + ' CFA';
            document.getElementById('total').textContent = total.toFixed(0) + ' CFA';
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
            calculateTotal();
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerEmail').value = '';
            document.getElementById('notes').value = '';
            document.getElementById('discountInput').value = '0';
        }

        function processSale() {
            if (cart.length === 0) {
                alert('Le panier est vide !');
                return;
            }

            const saleData = {
                customer_name: document.getElementById('customerName').value,
                customer_phone: document.getElementById('customerPhone').value,
                customer_email: document.getElementById('customerEmail').value,
                payment_method: document.getElementById('paymentMethod').value,
                notes: document.getElementById('notes').value,
                items: cart,
                subtotal: parseFloat(document.getElementById('subtotal').textContent.replace(' CFA', '').replace(/,/g,
                    '')),
                tax_amount: parseFloat(document.getElementById('taxAmount').textContent.replace(' CFA', '').replace(
                    /,/g, '')),
                discount_amount: parseFloat(document.getElementById('discountInput').value) || 0,
                total: parseFloat(document.getElementById('total').textContent.replace(' CFA', '').replace(/,/g, ''))
            };

            fetch('/cashier/sale', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(saleData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('saleNumber').textContent = data.sale_id;
                        document.getElementById('printInvoice').href = `/cashier/invoice/${data.sale_id}`;

                        const saleModal = new bootstrap.Modal(document.getElementById('saleModal'));
                        saleModal.show();

                        clearCart();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la vente');
                });
        }

        function searchProducts() {
            const query = document.getElementById('searchInput').value;
            const categoryId = document.getElementById('categoryFilter').value;
            const supplierId = document.getElementById('supplierFilter').value;

            fetch('/cashier/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        query,
                        category_id: categoryId,
                        supplier_id: supplierId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    products = data;
                    updateProductsDisplay();
                });
        }

        function updateProductsDisplay() {
            const container = document.getElementById('productsList');
            let html = '';

            products.forEach(product => {
                html += `
            <div class="col-md-6 col-lg-4 mb-3 product-item"
                 data-id="${product.id}"
                 data-name="${product.name}"
                 data-sku="${product.sku}"
                 data-price="${product.selling_price}"
                 data-stock="${product.quantity}"
                 data-category="${product.category_id}"
                 data-supplier="${product.supplier_id}">
                <div class="card h-100 product-card" onclick="addToCart(${product.id})">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h6 class="card-title text-truncate">${product.name}</h6>
                        <p class="card-text small text-muted mb-1">${product.sku}</p>
                        <p class="card-text small text-muted mb-2">${product.category ? product.category.name : 'N/A'}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">${product.quantity} en stock</span>
                            <span class="fw-bold text-primary">${product.selling_price} CFA</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
            });

            container.innerHTML = html;
        }

        function scanBarcode() {
            const barcode = document.getElementById('barcodeInput').value;
            if (!barcode) return;

            fetch('/cashier/barcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        barcode: barcode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Produit non trouvé');
                    } else {
                        addToCart(data.id);
                        document.getElementById('barcodeInput').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la recherche');
                });
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les produits depuis le DOM
            const productElements = document.querySelectorAll('.product-item');
            products = Array.from(productElements).map(el => ({
                id: parseInt(el.dataset.id),
                name: el.dataset.name,
                sku: el.dataset.sku,
                selling_price: parseFloat(el.dataset.price),
                quantity: parseInt(el.dataset.stock),
                category_id: parseInt(el.dataset.category),
                supplier_id: parseInt(el.dataset.supplier)
            }));

            // Ajouter les événements
            document.getElementById('searchInput').addEventListener('input', searchProducts);
            document.getElementById('categoryFilter').addEventListener('change', searchProducts);
            document.getElementById('supplierFilter').addEventListener('change', searchProducts);
            document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    scanBarcode();
                }
            });

            // Initialisation
            calculateTotal();
        });
    </script>
@endpush
