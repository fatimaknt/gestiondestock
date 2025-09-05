<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Stock - {{ $shop->name ?? 'Boutique' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-rupture {
            color: #dc3545;
            font-weight: bold;
        }

        .status-bas {
            color: #ffc107;
            font-weight: bold;
        }

        .status-normal {
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rapport de Stock</h1>
        <p><strong>{{ $shop->name ?? 'Boutique' }}</strong></p>
        <p>Généré le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-label">Produits Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($totalValue, 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Valeur Totale</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $lowStockProducts }}</div>
            <div class="stat-label">Stock Bas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $outOfStockProducts }}</div>
            <div class="stat-label">Rupture</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>SKU/Code</th>
                <th>Stock</th>
                <th>Min</th>
                <th>Prix Achat</th>
                <th>Prix Vente</th>
                <th>Valeur</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                @php
                    $stockValue = $product->quantity * $product->purchase_price;
                    $status =
                        $product->quantity == 0
                            ? 'Rupture'
                            : ($product->quantity <= $product->min_quantity
                                ? 'Stock Bas'
                                : 'Normal');
                    $statusClass =
                        $product->quantity == 0
                            ? 'status-rupture'
                            : ($product->quantity <= $product->min_quantity
                                ? 'status-bas'
                                : 'status-normal');
                @endphp
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $product->sku ?? ($product->barcode ?? 'N/A') }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->min_quantity }}</td>
                    <td>{{ number_format($product->purchase_price, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($product->selling_price, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($stockValue, 0, ',', ' ') }} FCFA</td>
                    <td class="{{ $statusClass }}">{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par le système de gestion de stock</p>
        <p>{{ $shop->name ?? 'Boutique' }} - {{ date('d/m/Y H:i') }}</p>
    </div>
</body>

</html>
