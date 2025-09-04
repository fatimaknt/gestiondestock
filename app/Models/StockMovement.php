<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'reference',
        'reference_type',
        'notes',
        'movement_date'
    ];

    protected $casts = [
        'movement_date' => 'date',
        'unit_cost' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'in' => 'Entrée',
            'out' => 'Sortie',
            'adjustment' => 'Ajustement',
            default => 'Inconnu'
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'in' => 'success',
            'out' => 'danger',
            'adjustment' => 'warning',
            default => 'secondary'
        };
    }
}
