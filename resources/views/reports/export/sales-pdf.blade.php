<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Ventes - {{ $shop->name ?? 'Boutique' }}</title>
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
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-completed {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc3545;
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

        .products-list {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rapport des Ventes</h1>
        <p><strong>{{ $shop->name ?? 'Boutique' }}</strong></p>
        <p>Généré le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $totalSales }}</div>
            <div class="stat-label">Ventes Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Chiffre d'Affaires</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalQuantity }}</div>
            <div class="stat-label">Produits Vendus</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', ' ') : 0 }}
                FCFA</div>
            <div class="stat-label">Panier Moyen</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Facture</th>
                <th>Client</th>
                <th>Date</th>
                <th>Produits</th>
                <th>Quantité</th>
                <th>Sous-Total</th>
                <th>TVA</th>
                <th>Remise</th>
                <th>Total</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                @php
                    $products = $sale->items
                        ->map(function ($item) {
                            return $item->product->name . ' (x' . $item->quantity . ')';
                        })
                        ->join(', ');
                    $totalQuantity = $sale->items->sum('quantity');
                    $statusClass = 'status-' . strtolower($sale->status);
                @endphp
                <tr>
                    <td>{{ $sale->invoice_number ?? 'N/A' }}</td>
                    <td>{{ $sale->client->name ?? 'Client Général' }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td class="products-list">{{ Str::limit($products, 50) }}</td>
                    <td>{{ $totalQuantity }}</td>
                    <td>{{ number_format($sale->subtotal, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($sale->tax_amount ?? 0, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($sale->discount ?? 0, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($sale->total, 0, ',', ' ') }} FCFA</td>
                    <td class="{{ $statusClass }}">{{ ucfirst($sale->status) }}</td>
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
