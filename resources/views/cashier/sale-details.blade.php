@extends('layouts.app-with-nav')

@section('title', 'Détails de la Vente #' . $sale->id)

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-receipt text-primary me-2"></i>Détails de la Vente #{{ $sale->id }}
            </h2>
            <div>
                <a href="{{ route('cashier.history') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('cashier.invoice', $sale->id) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer me-2"></i>Imprimer la Facture
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations de la vente -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informations de la Vente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Numéro de vente:</td>
                                        <td>#{{ $sale->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Date et heure:</td>
                                        <td>{{ $sale->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Vendeur:</td>
                                        <td>{{ $sale->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Statut:</td>
                                        <td>
                                            @switch($sale->status)
                                                @case('completed')
                                                    <span class="badge bg-success">Terminé</span>
                                                @break

                                                @case('pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge bg-danger">Annulé</span>
                                                @break

                                                @case('refunded')
                                                    <span class="badge bg-info">Remboursé</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Méthode de paiement:</td>
                                        <td>
                                            @switch($sale->payment_method)
                                                @case('cash')
                                                    <span class="badge bg-success">Espèces</span>
                                                @break

                                                @case('card')
                                                    <span class="badge bg-info">Carte bancaire</span>
                                                @break

                                                @case('mobile_money')
                                                    <span class="badge bg-warning">Mobile Money</span>
                                                @break

                                                @case('bank_transfer')
                                                    <span class="badge bg-secondary">Virement bancaire</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Sous-total:</td>
                                        <td>{{ number_format($sale->subtotal) }} CFA</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">TVA (18%):</td>
                                        <td>{{ number_format($sale->tax_amount) }} CFA</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Remise:</td>
                                        <td>{{ number_format($sale->discount_amount) }} CFA</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total:</td>
                                        <td class="h5 text-primary">{{ number_format($sale->total) }} CFA</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($sale->notes)
                            <div class="mt-3">
                                <h6>Notes:</h6>
                                <p class="text-muted">{{ $sale->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Articles vendus -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam me-2"></i>Articles Vendus
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>SKU</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-box-seam text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                                        <small
                                                            class="text-muted">{{ $item->product->category->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $item->product->sku }}</code>
                                            </td>
                                            <td>{{ number_format($item->unit_price) }} CFA</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="fw-bold">{{ number_format($item->total_price) }} CFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations client et actions -->
            <div class="col-md-4">
                <!-- Informations client -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person me-2"></i>Informations Client
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($sale->customer_name || $sale->customer_phone || $sale->customer_email)
                            <div class="mb-3">
                                @if ($sale->customer_name)
                                    <div class="mb-2">
                                        <strong>Nom:</strong><br>
                                        {{ $sale->customer_name }}
                                    </div>
                                @endif

                                @if ($sale->customer_phone)
                                    <div class="mb-2">
                                        <strong>Téléphone:</strong><br>
                                        <a href="tel:{{ $sale->customer_phone }}" class="text-decoration-none">
                                            {{ $sale->customer_phone }}
                                        </a>
                                    </div>
                                @endif

                                @if ($sale->customer_email)
                                    <div class="mb-2">
                                        <strong>Email:</strong><br>
                                        <a href="mailto:{{ $sale->customer_email }}" class="text-decoration-none">
                                            {{ $sale->customer_email }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Client anonyme</p>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($sale->status === 'completed')
                                <button class="btn btn-outline-warning" onclick="changeStatus('pending')">
                                    <i class="bi bi-pause-circle me-2"></i>Mettre en attente
                                </button>
                                <button class="btn btn-outline-danger" onclick="changeStatus('cancelled')">
                                    <i class="bi bi-x-circle me-2"></i>Annuler la vente
                                </button>
                            @elseif($sale->status === 'pending')
                                <button class="btn btn-outline-success" onclick="changeStatus('completed')">
                                    <i class="bi bi-check-circle me-2"></i>Marquer comme terminé
                                </button>
                                <button class="btn btn-outline-danger" onclick="changeStatus('cancelled')">
                                    <i class="bi bi-x-circle me-2"></i>Annuler la vente
                                </button>
                            @elseif($sale->status === 'cancelled')
                                <button class="btn btn-outline-success" onclick="changeStatus('completed')">
                                    <i class="bi bi-check-circle me-2"></i>Réactiver la vente
                                </button>
                            @endif

                            @if ($sale->status === 'completed')
                                <button class="btn btn-outline-info" onclick="processRefund()">
                                    <i class="bi bi-arrow-return-left me-2"></i>Traiter un remboursement
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistiques de la vente -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Statistiques
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $sale->items->count() }}</h4>
                                    <small class="text-muted">Articles</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">{{ number_format($sale->total) }}</h4>
                                <small class="text-muted">CFA</small>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $sale->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de changement de statut -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir changer le statut de cette vente ?</p>
                    <p><strong>Nouveau statut:</strong> <span id="newStatus"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="confirmStatusChange()">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    let pendingStatusChange = null;

    function changeStatus(newStatus) {
        pendingStatusChange = newStatus;

        const statusLabels = {
            'completed': 'Terminé',
            'pending': 'En attente',
            'cancelled': 'Annulé',
            'refunded': 'Remboursé'
        };

        document.getElementById('newStatus').textContent = statusLabels[newStatus];

        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    }

    function confirmStatusChange() {
        if (!pendingStatusChange) return;

        const saleId = {{ $sale->id }};

        fetch(`/cashier/sale/${saleId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: pendingStatusChange
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Recharger la page pour voir les changements
                } else {
                    alert('Erreur lors du changement de statut');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du changement de statut');
            });

        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        modal.hide();
    }

    function processRefund() {
        const refundAmount = prompt('Montant du remboursement (CFA):', '{{ $sale->total }}');
        if (!refundAmount) return;

        const refundReason = prompt('Raison du remboursement:');
        if (!refundReason) return;

        const saleId = {{ $sale->id }};

        fetch(`/cashier/sale/${saleId}/refund`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    refund_amount: refundAmount,
                    refund_reason: refundReason,
                    max_amount: {{ $sale->total }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Recharger la page pour voir les changements
                } else {
                    alert('Erreur lors du traitement du remboursement');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du traitement du remboursement');
            });
    }
</script>
