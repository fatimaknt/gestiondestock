<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Mouvements de Stock</title>
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
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
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

        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #333;
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
            background-color: #f2f2f2;
        }

        .type-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .type-in {
            background-color: #28a745;
            color: white;
        }

        .type-out {
            background-color: #dc3545;
            color: white;
        }

        .type-adjustment {
            background-color: #ffc107;
            color: #212529;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rapport des Mouvements de Stock</h1>
        <p><strong>{{ $shop->name ?? 'Boutique' }}</strong></p>
        <p>Généré le {{ $date }} par {{ $user->name }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Boutique :</span>
            <span class="info-value">{{ $shop->name ?? 'Non spécifiée' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Adresse :</span>
            <span class="info-value">{{ $shop->address ?? 'Non spécifiée' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Téléphone :</span>
            <span class="info-value">{{ $shop->phone ?? 'Non spécifié' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Période :</span>
            <span class="info-value">Tous les mouvements</span>
        </div>
    </div>

    @if ($movements->count() > 0)
        <div class="summary">
            <h3>Résumé des Mouvements</h3>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $movements->count() }}</div>
                    <div class="stat-label">Total Mouvements</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $movements->where('type', 'in')->count() }}</div>
                    <div class="stat-label">Entrées</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $movements->where('type', 'out')->count() }}</div>
                    <div class="stat-label">Sorties</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $movements->where('type', 'adjustment')->count() }}</div>
                    <div class="stat-label">Ajustements</div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Stock Avant</th>
                    <th>Stock Après</th>
                    <th>Prix Unitaire</th>
                    <th>Utilisateur</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr>
                        <td>{{ $movement->movement_date ? $movement->movement_date->format('d/m/Y') : $movement->created_at->format('d/m/Y') }}
                        </td>
                        <td><strong>{{ $movement->product->name }}</strong><br><small>{{ $movement->product->sku }}</small>
                        </td>
                        <td>
                            <span class="type-badge type-{{ $movement->type }}">
                                @if ($movement->type === 'in')
                                    Entrée
                                @elseif($movement->type === 'out')
                                    Sortie
                                @else
                                    Ajustement
                                @endif
                            </span>
                        </td>
                        <td><strong>{{ $movement->quantity }}</strong></td>
                        <td>{{ $movement->quantity_before }}</td>
                        <td><strong>{{ $movement->quantity_after }}</strong></td>
                        <td>{{ number_format($movement->unit_cost) }} CFA</td>
                        <td>{{ $movement->user->name }}</td>
                        <td>{{ $movement->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="info-section">
            <p style="text-align: center; color: #666; font-style: italic;">
                Aucun mouvement de stock trouvé pour cette période.
            </p>
        </div>
    @endif

    <div class="footer">
        <p>Ce rapport a été généré automatiquement par le système de gestion de stock.</p>
        <p>© {{ date('Y') }} - Tous droits réservés</p>
    </div>
</body>

</html>
