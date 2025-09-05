@extends('layouts.app-with-nav')

@section('title', 'Alertes de Stock - Gestion de Stock')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-exclamation-triangle text-warning me-2"></i>Alertes de Stock
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>

        <!-- Statistiques des alertes -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Alertes</h6>
                                <h3 class="mb-0">{{ $alerts->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Non Lues</h6>
                                <h3 class="mb-0">{{ $alerts->where('is_read', false)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-bell" style="font-size: 2rem;"></i>
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
                                <h6 class="card-title">Lues</h6>
                                <h3 class="mb-0">{{ $alerts->where('is_read', true)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
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
                                <h6 class="card-title">Produits Concernés</h6>
                                <h3 class="mb-0">{{ $alerts->unique('product_id')->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des alertes -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Liste des Alertes</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Stock Restant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alerts as $alert)
                                <tr class="{{ $alert->is_read ? 'table-light' : 'table-warning' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-box-seam text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $alert->product->name ?? 'Produit supprimé' }}</h6>
                                                <small class="text-muted">{{ $alert->product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $alert->message }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($alert->type)
                                            @case('low_stock')
                                                <span class="badge bg-warning">Stock Faible</span>
                                            @break

                                            @case('out_of_stock')
                                                <span class="badge bg-danger">Rupture</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ $alert->type }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $alert->product && $alert->product->quantity <= 5 ? 'danger' : 'warning' }}">
                                            {{ $alert->product->quantity ?? 0 }} restants
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $alert->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $alert->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if ($alert->is_read)
                                            <span class="badge bg-success">Lue</span>
                                        @else
                                            <span class="badge bg-danger">Non lue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if (!$alert->is_read)
                                                <button class="btn btn-outline-success"
                                                    onclick="markAsRead({{ $alert->id }})">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-danger"
                                                onclick="deleteAlert({{ $alert->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-check-circle h1 text-success mb-3"></i>
                                                <h5>Aucune alerte de stock</h5>
                                                <p class="mb-0">Tous vos produits ont un stock suffisant !</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function markAsRead(alertId) {
                if (confirm('Marquer cette alerte comme lue ?')) {
                    fetch(`/alerts/${alertId}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Erreur lors de la mise à jour');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Erreur lors de la mise à jour');
                        });
                }
            }

            function deleteAlert(alertId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')) {
                    fetch(`/alerts/${alertId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Erreur lors de la suppression');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Erreur lors de la suppression');
                        });
                }
            }
        </script>
    @endsection
