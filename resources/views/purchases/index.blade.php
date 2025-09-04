@extends('layouts.app')

@section('title', 'Gestion des Achats')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-cart-plus text-primary me-2"></i>Gestion des Achats
            </h1>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle Commande
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Liste des achats -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Commandes Fournisseurs
                </h5>
            </div>
            <div class="card-body">
                @if ($purchases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Fournisseur</th>
                                    <th>Date Commande</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td>
                                            <strong>#{{ $purchase->id }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $purchase->items->count() }} articles</small>
                                        </td>
                                        <td>
                                            <strong>{{ $purchase->supplier->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $purchase->order_date->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <strong>{{ number_format($purchase->total) }} CFA</strong>
                                        </td>
                                        <td>
                                            @if ($purchase->status === 'pending')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($purchase->status === 'received')
                                                <span class="badge bg-success">Reçue</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($purchase->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('purchases.show', $purchase) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if ($purchase->status === 'pending')
                                                    <form action="{{ route('purchases.receive', $purchase) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="Réceptionner">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $purchases->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">Aucune commande trouvée</h5>
                        <p class="text-muted">Commencez par créer votre première commande fournisseur</p>
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer une Commande
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
