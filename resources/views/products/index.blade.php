@extends('layouts.app-with-nav')

@section('content')
    <!-- Contenu principal -->
    <div class="container-fluid py-4">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête de la page -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-1">Gestion des Produits</h1>
                        <p class="text-muted mb-0">Gérez votre inventaire de produits</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Nouveau produit
                        </a>
                        <button class="btn btn-outline-secondary" onclick="exportProducts()">
                            <i class="bi bi-download me-2"></i>Exporter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="searchInput"
                                placeholder="Rechercher un produit...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="supplierFilter">
                            <option value="">Tous les fournisseurs</option>
                            @foreach ($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="low_stock">Stock faible</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des produits -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-list-ul me-2 text-primary"></i>
                        Liste des produits
                    </h5>
                    <span class="badge bg-primary">{{ $products->total() ?? 0 }} produits</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="productsTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-image me-2 text-muted"></i>
                                        Produit
                                    </div>
                                </th>
                                <th class="border-0 px-4 py-3">
                                    <i class="bi bi-upc me-2 text-muted"></i>
                                    SKU
                                </th>
                                <th class="border-0 px-4 py-3">
                                    <i class="bi bi-tags me-2 text-muted"></i>
                                    Catégorie
                                </th>
                                <th class="border-0 px-4 py-3">
                                    <i class="bi bi-box me-2 text-muted"></i>
                                    Stock
                                </th>
                                <th class="border-0 px-4 py-3">
                                    <i class="bi bi-cash me-2 text-muted"></i>
                                    Prix
                                </th>
                                <th class="border-0 px-4 py-3">
                                    <i class="bi bi-circle me-2 text-muted"></i>
                                    Statut
                                </th>
                                <th class="border-0 px-4 py-3 text-end">
                                    <i class="bi bi-gear me-2 text-muted"></i>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products ?? [] as $product)
                                <tr>
                                    <td class="px-4 py-3 product-name-cell">
                                        <div class="d-flex align-items-start">
                                            <img src="{{ $product->image ?? 'https://via.placeholder.com/50x50' }}"
                                                class="rounded me-3 flex-shrink-0" width="50" height="50"
                                                alt="{{ $product->name }}">
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="fw-semibold mb-1 product-name" title="{{ $product->name }}">
                                                    {{ $product->name }}</h6>
                                                <small class="text-muted d-block product-description"
                                                    title="{{ $product->description }}">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-light text-dark">{{ $product->sku ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @if ($product->quantity <= ($product->min_quantity ?? 0))
                                                    <span class="badge bg-danger">{{ $product->quantity }}</span>
                                                @elseif($product->quantity <= ($product->min_quantity ?? 0) * 2)
                                                    <span class="badge bg-warning">{{ $product->quantity }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $product->quantity }}</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">/ {{ $product->min_quantity ?? 0 }} min</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <div class="fw-bold text-success">
                                                {{ number_format($product->selling_price, 2, ',', ' ') }} CFA</div>
                                            <small class="text-muted">Achat:
                                                {{ number_format($product->purchase_price, 2, ',', ' ') }} CFA</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox h1 mb-3 d-block"></i>
                                            <h5>Aucun produit trouvé</h5>
                                            <p class="mb-3">Commencez par ajouter votre premier produit</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Ajouter un produit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($products) && $products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
    </div>

    <style>
        /* Styles de base pour le tableau */
        .table {
            width: 100%;
        }

        /* Amélioration de l'affichage du nom du produit */
        .product-name-cell h6 {
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.3;
        }

        .product-name-cell small {
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.2;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche en temps réel
            const searchInput = document.getElementById('searchInput');
            const productsTable = document.getElementById('productsTable');

            if (searchInput && productsTable) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const rows = productsTable.querySelectorAll('tbody tr');

                    rows.forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Filtres
            const categoryFilter = document.getElementById('categoryFilter');
            const supplierFilter = document.getElementById('supplierFilter');
            const statusFilter = document.getElementById('statusFilter');

            function applyFilters() {
                const category = categoryFilter.value;
                const supplier = supplierFilter.value;
                const status = statusFilter.value;

                // Ici vous pouvez implémenter la logique de filtrage
                console.log('Filtres appliqués:', {
                    category,
                    supplier,
                    status
                });
            }

            if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
            if (supplierFilter) supplierFilter.addEventListener('change', applyFilters);
            if (statusFilter) statusFilter.addEventListener('change', applyFilters);
        });

        // Fonction d'export des produits
        function exportProducts() {
            const table = document.getElementById('productsTable');
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            let csvContent = "data:text/csv;charset=utf-8,";

            // En-têtes en français
            csvContent += "Nom du produit,SKU,Catégorie,Stock,Prix de vente,Prix d'achat,Statut,Description\n";

            // Données des produits
            rows.forEach(function(row) {
                if (row.style.display !== 'none') { // Exporter seulement les produits visibles
                    const cells = row.querySelectorAll('td');
                    const rowData = [
                        cells[0]?.querySelector('h6')?.textContent?.trim() || '',
                        cells[1]?.textContent?.trim() || '',
                        cells[2]?.textContent?.trim() || '',
                        cells[3]?.querySelector('.badge')?.textContent?.trim() || '',
                        cells[4]?.querySelector('.text-success')?.textContent?.trim() || '',
                        cells[4]?.querySelector('.text-muted')?.textContent?.replace('Achat: ', '')?.trim() ||
                        '',
                        cells[5]?.textContent?.trim() || '',
                        cells[0]?.querySelector('small')?.textContent?.trim() || ''
                    ];
                    csvContent += rowData.join(',') + '\n';
                }
            });

            // Créer et télécharger le fichier CSV
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'produits_export_' + new Date().toISOString().split('T')[0] + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Message de confirmation
            alert('Export terminé ! Fichier téléchargé avec succès.');
        }
    </script>

    <style>
        /* Styles personnalisés pour la page produits */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .navbar-nav .nav-link {
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            transform: translateY(-1px);
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }

        /* Animation d'entrée */
        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand span {
                display: none;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-group .btn {
                border-radius: 0.375rem !important;
                margin: 0;
            }
        }
    </style>
@endsection
