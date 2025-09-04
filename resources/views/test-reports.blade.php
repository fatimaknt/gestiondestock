<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Rapports - Gestion de Stock</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .btn-action {
            min-height: 120px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-graph-up text-primary me-2"></i>Rapports et Analyses
            </h1>
            <a href="/dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour Dashboard
            </a>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning me-2"></i>Actions Rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-outline-primary w-100 btn-action d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-box-seam me-2" style="font-size: 1.5rem;"></i>
                                    <span>Rapport Stock</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-outline-success w-100 btn-action d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-cart-check me-2" style="font-size: 1.5rem;"></i>
                                    <span>Rapport Ventes</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-outline-info w-100 btn-action d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-download me-2" style="font-size: 1.5rem;"></i>
                                    <span>Export Stock</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-outline-warning w-100 btn-action d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-file-earmark-spreadsheet me-2" style="font-size: 1.5rem;"></i>
                                    <span>Export Ventes</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes de stock -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
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
            <div class="col-md-6">
                <div class="card">
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
