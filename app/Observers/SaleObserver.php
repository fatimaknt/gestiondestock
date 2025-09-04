<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        if ($sale->client_id) {
            $this->updateClientStats($sale->client_id);
        }
    }

    /**
     * Handle the Sale "updated" event.
     */
    public function updated(Sale $sale): void
    {
        if ($sale->client_id) {
            $this->updateClientStats($sale->client_id);
        }
    }

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        if ($sale->client_id) {
            $this->updateClientStats($sale->client_id);
        }
    }

    /**
     * Handle the Sale "restored" event.
     */
    public function restored(Sale $sale): void
    {
        if ($sale->client_id) {
            $this->updateClientStats($sale->client_id);
        }
    }

    /**
     * Handle the Sale "force deleted" event.
     */
    public function forceDeleted(Sale $sale): void
    {
        if ($sale->client_id) {
            $this->updateClientStats($sale->client_id);
        }
    }

    /**
     * Mettre à jour les statistiques du client
     */
    private function updateClientStats(int $clientId): void
    {
        $client = \App\Models\Client::find($clientId);
        if ($client) {
            $totalPurchases = $client->sales()->sum('total');
            $lastPurchaseDate = $client->sales()->orderBy('created_at', 'desc')->first()?->created_at;

            $client->update([
                'total_purchases' => $totalPurchases,
                'last_purchase_date' => $lastPurchaseDate
            ]);
        }
    }
}
