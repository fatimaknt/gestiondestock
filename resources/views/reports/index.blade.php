@extends('layouts.app-with-nav')

@section('title', 'Rapports - Gestion de Stock')

@section('content')
    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-graph-up text-primary me-2"></i>Rapports et Analyses
            </h1>
        </div>

        <!-- Statistiques générales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">0</h4>
                                <p class="mb-0">Total Produits</p>
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
                                <h4 class="mb-0">0</h4>
                                <p class="mb-0">Stock Faible</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">0</h4>
                                <p class="mb-0">Total Ventes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">0</h4>
                                <p class="mb-0">Total Achats</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cart-plus" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning me-2"></i>Actions Rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('reports.stock') }}"
                                    class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-box-seam me-2" style="font-size: 1.5rem;"></i>
                                    <span>Rapport Stock</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('reports.sales') }}"
                                    class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-cart-check me-2" style="font-size: 1.5rem;"></i>
                                    <span>Rapport Ventes</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('reports.export.stock') }}"
                                    class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-file-earmark-pdf me-2" style="font-size: 1.5rem;"></i>
                                    <span>Export Stock PDF</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('reports.export.sales') }}"
                                    class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-file-earmark-pdf me-2" style="font-size: 1.5rem;"></i>
                                    <span>Export Ventes PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Produits en rupture de stock -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>Produits en Rupture
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Aucun produit en rupture de stock</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits en alerte de stock -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>Alertes de Stock
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Aucune alerte de stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
