<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .company-info {
            color: #6c757d;
            font-size: 14px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-info,
        .customer-info {
            flex: 1;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: bold;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .product-name {
            font-weight: bold;
            color: #333;
        }

        .product-sku {
            color: #6c757d;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            border-top: 2px solid #dee2e6;
            padding-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .total-row.final {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .payment-method {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .payment-cash {
            background-color: #d4edda;
            color: #155724;
        }

        .payment-card {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .payment-mobile {
            background-color: #fff3cd;
            color: #856404;
        }

        .payment-bank {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-refunded {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        @media print {
            body {
                background-color: white;
            }

            .invoice-container {
                box-shadow: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- En-tête -->
        <div class="header">
            <div class="company-name">{{ Auth::user()->shop->name ?? 'Mon Magasin' }}</div>
            <div class="company-info">
                Gestion de Stock - Système de Caisse<br>
                {{ Auth::user()->shop->address ?? 'Adresse du magasin' }}<br>
                Tél: {{ Auth::user()->shop->phone ?? 'Téléphone' }} | Email:
                {{ Auth::user()->shop->email ?? 'email@magasin.com' }}
            </div>
        </div>

        <!-- Détails de la facture -->
        <div class="invoice-details">
            <div class="invoice-info">
                <div class="invoice-title">FACTURE</div>
                <div class="info-row">
                    <span class="info-label">N° Facture:</span>
                    <span class="info-value">#{{ $sale->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $sale->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Heure:</span>
                    <span class="info-value">{{ $sale->created_at->format('H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vendeur:</span>
                    <span class="info-value">{{ $sale->user->name }}</span>
                </div>
            </div>
            <div class="customer-info">
                <div class="info-row">
                    <span class="info-label">Client:</span>
                    <span class="info-value">{{ $sale->customer_name ?: 'Client anonyme' }}</span>
                </div>
                @if ($sale->customer_phone)
                    <div class="info-row">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value">{{ $sale->customer_phone }}</span>
                    </div>
                @endif
                @if ($sale->customer_email)
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $sale->customer_email }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Méthode de paiement:</span>
                    <span class="info-value">
                        @switch($sale->payment_method)
                            @case('cash')
                                <span class="payment-method payment-cash">Espèces</span>
                            @break

                            @case('card')
                                <span class="payment-method payment-card">Carte bancaire</span>
                            @break

                            @case('mobile_money')
                                <span class="payment-method payment-mobile">Mobile Money</span>
                            @break

                            @case('bank_transfer')
                                <span class="payment-method payment-bank">Virement bancaire</span>
                            @break
                        @endswitch
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut:</span>
                    <span class="info-value">
                        @switch($sale->status)
                            @case('completed')
                                <span class="status-badge status-completed">Terminé</span>
                            @break

                            @case('pending')
                                <span class="status-badge status-pending">En attente</span>
                            @break

                            @case('cancelled')
                                <span class="status-badge status-cancelled">Annulé</span>
                            @break

                            @case('refunded')
                                <span class="status-badge status-refunded">Remboursé</span>
                            @break
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>SKU</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-right">Prix unitaire</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->name }}</div>
                            <div class="product-sku">{{ $item->product->category->name ?? 'N/A' }}</div>
                        </td>
                        <td><code>{{ $item->product->sku }}</code></td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price) }} CFA</td>
                        <td class="text-right">{{ number_format($item->total_price) }} CFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals-section">
            <div class="total-row">
                <span>Sous-total:</span>
                <span>{{ number_format($sale->subtotal) }} CFA</span>
            </div>
            <div class="total-row">
                <span>TVA (18%):</span>
                <span>{{ number_format($sale->tax_amount) }} CFA</span>
            </div>
            @if ($sale->discount_amount > 0)
                <div class="total-row">
                    <span>Remise:</span>
                    <span>-{{ number_format($sale->discount_amount) }} CFA</span>
                </div>
            @endif
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total) }} CFA</span>
            </div>
        </div>

        @if ($sale->notes)
            <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <strong>Notes:</strong><br>
                {{ $sale->notes }}
            </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <p>
                <strong>Merci pour votre confiance !</strong><br>
                Cette facture a été générée automatiquement par le système de gestion de stock.<br>
                Pour toute question, veuillez nous contacter.
            </p>
            <p>
                <small>
                    Généré le {{ now()->format('d/m/Y à H:i:s') }} |
                    Système de Gestion de Stock v1.0
                </small>
            </p>
        </div>
    </div>

    <!-- Boutons d'action (masqués à l'impression) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px;">
            🖨️ Imprimer la Facture
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px;">
            ❌ Fermer
        </button>
    </div>
</body>

</html>
