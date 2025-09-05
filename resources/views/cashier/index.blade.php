@extends('layouts.app-with-nav')

@section('title', 'Caisse - Gestion de Stock')

@section('content')
    <!-- Messages Flash -->
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

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                                <div class="input-group">
                                    <input type="text" id="barcodeInput" class="form-control"
                                        placeholder="Scanner code-barres...">
                                    <button type="button" class="btn btn-outline-primary" id="scanBarcodeBtn"
                                        title="Scanner le code-barres">
                                        <i class="bi bi-camera"></i>
                                    </button>
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

            <!-- Colonne droite - Formulaire de Vente Simple -->
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-check me-2"></i>Nouvelle Vente
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cashier.sale') }}" method="POST">
                            @csrf

                            <!-- Sélection du produit -->
                            <div class="mb-3">
                                <h6 class="mb-2">Produit à Vendre</h6>
                                <select name="product_id" id="product_id" class="form-select" required
                                    onchange="updateProductInfo()">
                                    <option value="">Sélectionner un produit</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}"
                                            data-stock="{{ $product->quantity }}">
                                            {{ $product->name }} - {{ $product->sku }} ({{ $product->quantity }} en
                                            stock)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantité -->
                            <div class="mb-3">
                                <label class="form-label">Quantité</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    value="1" min="1" required oninput="calculateTotal()">
                            </div>

                            <!-- Prix unitaire -->
                            <div class="mb-3">
                                <label class="form-label">Prix Unitaire (CFA)</label>
                                <input type="number" name="unit_price" id="unit_price" class="form-control"
                                    step="0.01" required oninput="calculateTotal()">
                            </div>

                            <!-- Total calculé -->
                            <div class="mb-3">
                                <label class="form-label">Total (CFA)</label>
                                <div class="input-group">
                                    <input type="number" name="total_price" id="total_price" class="form-control"
                                        step="0.00" min="0">
                                    <!--
                                                                         <button type="button" class="btn btn-outline-secondary" onclick="calculateTotal()">
                                                                                <i class="bi bi-calculator"></i> Calculer
                                                                            </button>
                                                                            -->
                                </div>
                                <small class="text-muted">Le total se calcule automatiquement, mais vous pouvez le modifier
                                    si nécessaire</small>
                            </div>

                            <!-- Informations client -->
                            <div class="mb-3">
                                <h6 class="mb-2">Informations Client</h6>
                                <input type="text" name="customer_name" class="form-control mb-2"
                                    placeholder="Nom du client" required>
                                <input type="tel" name="customer_phone" class="form-control mb-2"
                                    placeholder="Téléphone">
                                <input type="email" name="customer_email" class="form-control mb-2"
                                    placeholder="Email">
                            </div>

                            <!-- Méthode de paiement -->
                            <div class="mb-3">
                                <h6 class="mb-2">Méthode de Paiement</h6>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="transfer">Virement bancaire</option>
                                    <option value="check">Chèque</option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <textarea name="notes" class="form-control" rows="2" placeholder="Notes de vente..."></textarea>
                            </div>

                            <!-- Bouton de vente -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer la Vente
                                </button>
                            </div>
                        </form>
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

    <script>
        // === JAVASCRIPT DIRECT DANS LA PAGE ===

        // Test immédiat
        // JavaScript chargé

        // Fonction de calcul directe
        function calculateTotal() {
            // Calcul du total

            var quantity = document.getElementById('quantity').value;
            var unitPrice = document.getElementById('unit_price').value;
            var totalInput = document.getElementById('total_price');

            // Debug: Quantité et prix

            if (quantity && unitPrice) {
                var total = quantity * unitPrice;
                totalInput.value = total;
                // Total calculé
            }
        }

        // Fonction de mise à jour produit
        function updateProductInfo() {
            // Mise à jour du produit

            var select = document.getElementById('product_id');
            var selectedOption = select.options[select.selectedIndex];

            if (selectedOption && selectedOption.value) {
                var price = selectedOption.getAttribute('data-price');
                alert('Prix trouvé: ' + price);

                if (price) {
                    document.getElementById('unit_price').value = price;
                    calculateTotal();
                }
            }
        }

        // Test au chargement
        window.onload = function() {
            // Page chargée

            // Vérifier s'il y a un message de succès et vider le formulaire
            if (document.querySelector('.alert-success')) {
                clearForm();
            }
        };

        // Fonction pour vider le formulaire
        function clearForm() {
            document.getElementById('product_id').value = '';
            document.getElementById('quantity').value = '1';
            document.getElementById('unit_price').value = '';
            document.getElementById('total_price').value = '';
            document.querySelector('input[name="customer_name"]').value = '';
            document.querySelector('input[name="customer_phone"]').value = '';
            document.querySelector('input[name="customer_email"]').value = '';
            document.querySelector('select[name="payment_method"]').value = '';
            document.querySelector('textarea[name="notes"]').value = '';

            console.log('Formulaire vidé');
        }

        // Fonctionnalité de scan de code-barres
        document.addEventListener('DOMContentLoaded', function() {
            const barcodeInput = document.getElementById('barcodeInput');
            const scanBtn = document.getElementById('scanBarcodeBtn');

            // Scan avec caméra ou simulation
            scanBtn.addEventListener('click', function() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    // Scan avec caméra réelle
                    startCameraScan();
                } else {
                    // Simulation pour démonstration
                    simulateScan();
                }
            });

            // Fonction de simulation (pour démonstration)
            function simulateScan() {
                const mockBarcodes = [
                    '1234567890123',
                    '9876543210987',
                    '5555666677778',
                    '1111222233334'
                ];

                const randomBarcode = mockBarcodes[Math.floor(Math.random() * mockBarcodes.length)];
                barcodeInput.value = randomBarcode;

                // Animation de succès
                scanBtn.innerHTML = '<i class="bi bi-check-circle text-success"></i>';
                setTimeout(() => {
                    scanBtn.innerHTML = '<i class="bi bi-camera"></i>';
                }, 2000);

                // Recherche automatique du produit
                searchProductByBarcode(randomBarcode);
            }

            // Fonction de scan avec caméra
            function startCameraScan() {
                // Demander l'accès à la caméra
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment' // Caméra arrière sur mobile
                        }
                    })
                    .then(function(stream) {
                        // Créer une modal pour la caméra
                        createCameraModal(stream);
                    })
                    .catch(function(error) {
                        console.error('Erreur d\'accès à la caméra:', error);
                        showAlert('Impossible d\'accéder à la caméra. Utilisation du mode simulation.',
                            'warning');
                        simulateScan();
                    });
            }

            // Créer une modal pour afficher la caméra
            function createCameraModal(stream) {
                // Créer la modal
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.id = 'cameraModal';
                modal.innerHTML = `
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-camera me-2"></i>Scanner le Code-Barres
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <video id="cameraVideo" autoplay playsinline style="width: 100%; max-width: 500px;"></video>
                                <div class="mt-3">
                                    <p class="text-muted">Pointez la caméra vers le code-barres</p>
                                    <div class="scan-overlay">
                                        <div class="scan-frame"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-primary" id="captureBtn">
                                    <i class="bi bi-camera-fill me-2"></i>Capturer
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Ajouter la modal au DOM
                document.body.appendChild(modal);

                // Afficher la modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Configurer la vidéo
                const video = modal.querySelector('#cameraVideo');
                video.srcObject = stream;

                // Gérer la fermeture de la modal
                modal.addEventListener('hidden.bs.modal', function() {
                    // Arrêter la caméra
                    stream.getTracks().forEach(track => track.stop());
                    // Supprimer la modal
                    modal.remove();
                });

                // Gérer la capture
                const captureBtn = modal.querySelector('#captureBtn');
                captureBtn.addEventListener('click', function() {
                    // Pour l'instant, on simule la capture
                    // En production, on utiliserait une librairie comme QuaggaJS ou ZXing
                    const mockBarcode = '1234567890123';
                    barcodeInput.value = mockBarcode;
                    searchProductByBarcode(mockBarcode);
                    bsModal.hide();
                });
            }

            // Recherche automatique quand on tape un code-barres
            barcodeInput.addEventListener('input', function() {
                const barcode = this.value.trim();
                if (barcode.length >= 8) {
                    searchProductByBarcode(barcode);
                }
            });

            // Fonction pour rechercher un produit par code-barres
            function searchProductByBarcode(barcode) {
                fetch(`/cashier/search-barcode?barcode=${barcode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            showAlert('Produit non trouvé', 'warning');
                        } else {
                            // Remplir automatiquement le formulaire
                            document.getElementById('product_id').value = data.id;
                            document.getElementById('unit_price').value = data.selling_price;
                            document.getElementById('quantity').value = '1';
                            document.getElementById('total_price').value = data.selling_price;

                            showAlert(`Produit trouvé: ${data.name}`, 'success');

                            // Vider le champ code-barres
                            barcodeInput.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showAlert('Erreur lors de la recherche', 'danger');
                    });
            }

            // Fonction pour afficher des alertes
            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                const container = document.querySelector('.container-fluid');
                container.insertBefore(alertDiv, container.firstChild);

                // Supprimer l'alerte après 3 secondes
                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            }
        });
    </script>

    <!-- CSS pour l'interface de scan -->
    <style>
        .scan-overlay {
            position: relative;
            display: inline-block;
        }

        .scan-frame {
            width: 200px;
            height: 100px;
            border: 2px solid #007bff;
            border-radius: 8px;
            position: relative;
            animation: scanPulse 2s infinite;
        }

        .scan-frame::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid rgba(0, 123, 255, 0.3);
            border-radius: 8px;
            animation: scanGlow 2s infinite;
        }

        @keyframes scanPulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes scanGlow {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            }

            50% {
                box-shadow: 0 0 20px rgba(0, 123, 255, 0.8);
            }
        }

        #cameraVideo {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-lg {
            max-width: 600px;
        }
    </style>
@endsection
