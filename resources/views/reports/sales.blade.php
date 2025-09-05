@extends('layouts.app-with-nav')

@section('title', 'Rapport Ventes - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-cart-check text-primary me-2"></i>Rapport des Ventes
            </h1>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('reports.export.sales') }}" class="btn btn-outline-info">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </a>
            </div>
        </div>

        <!-- Filtres de dates -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.sales') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from', date('Y-m-01')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="payment_method" class="form-label">Méthode de paiement</label>
                        <select name="payment_method" id="payment_method" class="form-select">
                            <option value="">Toutes les méthodes</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèces
                            </option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte
                            </option>
                            <option value="mobile" {{ request('payment_method') == 'mobile' ? 'selected' : '' }}>Mobile
                                Money</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="customer_filter" class="form-label">Client</label>
                        <input type="text" name="customer_filter" id="customer_filter" class="form-control"
                            placeholder="Nom du client" value="{{ request('customer_filter') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques des ventes -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">0</h4>
                        <p class="mb-0">Total Ventes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">0 CFA</h4>
                        <p class="mb-0">Chiffre d'Affaires</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">0</h4>
                        <p class="mb-0">Articles Vendus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">0 CFA</h4>
                        <p class="mb-0">Moyenne/Vente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des ventes (placeholder) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Évolution des Ventes
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Graphique des ventes</h5>
                    <p class="text-muted">Le graphique des ventes sera affiché ici une fois les données disponibles</p>
                </div>
            </div>
        </div>

        <!-- Liste des ventes -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Historique des Ventes
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-cart-check text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Aucune vente trouvée</h5>
                    <p class="text-muted">L'historique des ventes sera affiché ici une fois les données disponibles</p>
                    <a href="{{ route('cashier.index') }}" class="btn btn-primary">
                        <i class="bi bi-cart-plus me-2"></i>Nouvelle Vente
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
