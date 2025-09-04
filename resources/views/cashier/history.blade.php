@extends('layouts.app-with-nav')

@section('title', 'Historique des Ventes - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-clock-history text-primary me-2"></i>Historique des Ventes
            </h2>
            <a href="{{ route('cashier.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle Vente
            </a>
        </div>

        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Date de début</label>
                        <input type="date" id="startDate" class="form-control"
                            value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date de fin</label>
                        <input type="date" id="endDate" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="completed">Terminé</option>
                            <option value="pending">En attente</option>
                            <option value="cancelled">Annulé</option>
                            <option value="refunded">Remboursé</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Méthode de paiement</label>
                        <select id="paymentFilter" class="form-select">
                            <option value="">Toutes les méthodes</option>
                            <option value="cash">Espèces</option>
                            <option value="card">Carte bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Virement bancaire</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button class="btn btn-primary" onclick="filterSales()">
                            <i class="bi bi-funnel me-2"></i>Filtrer
                        </button>
                        <button class="btn btn-outline-secondary ms-2" onclick="resetFilters()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Ventes</h6>
                                <h3 class="mb-0" id="totalSales">0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Chiffre d'Affaires</h6>
                                <h3 class="mb-0" id="totalRevenue">0 CFA</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cash" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Articles Vendus</h6>
                                <h3 class="mb-0" id="totalItems">0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Moyenne Panier</h6>
                                <h3 class="mb-0" id="averageCart">0 CFA</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des ventes -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Liste des Ventes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Vente</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Articles</th>
                                <th>Total</th>
                                <th>Paiement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>
                                        <strong>#{{ $sale->id }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $sale->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $sale->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $sale->customer_name ?: 'Client anonyme' }}</div>
                                        @if ($sale->customer_phone)
                                            <small class="text-muted">{{ $sale->customer_phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $sale->items->count() }} article(s)</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($sale->total) }} CFA</strong>
                                    </td>
                                    <td>
                                        @switch($sale->payment_method)
                                            @case('cash')
                                                <span class="badge bg-success">Espèces</span>
                                            @break

                                            @case('card')
                                                <span class="badge bg-info">Carte</span>
                                            @break

                                            @case('mobile_money')
                                                <span class="badge bg-warning">Mobile Money</span>
                                            @break

                                            @case('bank_transfer')
                                                <span class="badge bg-secondary">Virement</span>
                                            @break
                                        @endswitch
                                    </td>
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
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cashier.sale.details', $sale->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('cashier.invoice', $sale->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Imprimer la facture"
                                                target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de détails rapides -->
    <div class="modal fade" id="quickDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la Vente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" id="printFromModal" class="btn btn-primary" target="_blank">
                        <i class="bi bi-printer me-2"></i>Imprimer
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function filterSales() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const status = document.getElementById('statusFilter').value;
            const payment = document.getElementById('paymentFilter').value;

            // Ici vous pouvez implémenter la logique de filtrage
            // Pour l'instant, on recharge la page avec les paramètres
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (status) params.append('status', status);
            if (payment) params.append('payment_method', payment);

            window.location.search = params.toString();
        }

        function resetFilters() {
            document.getElementById('startDate').value = '{{ date('Y-m-d', strtotime('-30 days')) }}';
            document.getElementById('endDate').value = '{{ date('Y-m-d') }}';
            document.getElementById('statusFilter').value = '';
            document.getElementById('paymentFilter').value = '';

            window.location.search = '';
        }

        function showQuickDetails(saleId) {
            fetch(`/cashier/sale/${saleId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('quickDetailsContent').innerHTML = html;
                    document.getElementById('printFromModal').href = `/cashier/invoice/${saleId}`;

                    const modal = new bootstrap.Modal(document.getElementById('quickDetailsModal'));
                    modal.show();
                });
        }

        // Initialisation des statistiques
        document.addEventListener('DOMContentLoaded', function() {
            // Calculer les statistiques basées sur les données affichées
            const rows = document.querySelectorAll('#salesTableBody tr');
            let totalSales = rows.length;
            let totalRevenue = 0;
            let totalItems = 0;

            rows.forEach(row => {
                const totalCell = row.querySelector('td:nth-child(5) strong');
                const itemsCell = row.querySelector('td:nth-child(4) .badge');

                if (totalCell) {
                    const total = parseFloat(totalCell.textContent.replace(' CFA', '').replace(/,/g, ''));
                    totalRevenue += total;
                }

                if (itemsCell) {
                    const items = parseInt(itemsCell.textContent.match(/\d+/)[0]);
                    totalItems += items;
                }
            });

            document.getElementById('totalSales').textContent = totalSales;
            document.getElementById('totalRevenue').textContent = totalRevenue.toLocaleString() + ' CFA';
            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('averageCart').textContent = totalSales > 0 ?
                Math.round(totalRevenue / totalSales).toLocaleString() + ' CFA' : '0 CFA';
        });
    </script>
@endpush
