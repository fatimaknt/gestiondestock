<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'birth_date',
        'gender',
        'notes',
        'is_active',
        'total_purchases',
        'last_purchase_date'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'last_purchase_date' => 'datetime',
        'total_purchases' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getSalesCountAttribute(): int
    {
        return $this->sales()->count();
    }

    public function getLastPurchaseDateAttribute()
    {
        $lastSale = $this->sales()->orderBy('created_at', 'desc')->first();
        return $lastSale ? $lastSale->created_at : null;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city, $this->postal_code, $this->country]);
        return implode(', ', $parts);
    }

    public function getTotalPurchasesFormattedAttribute(): string
    {
        return number_format($this->total_purchases, 0, ',', ' ') . ' CFA';
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        if (!$this->is_active) {
            return 'danger';
        }

        if ($this->total_purchases > 100000) {
            return 'success'; // Client VIP
        } elseif ($this->total_purchases > 50000) {
            return 'info'; // Client régulier
        } else {
            return 'warning'; // Nouveau client
        }
    }

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactif';
        }

        if ($this->total_purchases > 100000) {
            return 'VIP';
        } elseif ($this->total_purchases > 50000) {
            return 'Régulier';
        } else {
            return 'Nouveau';
        }
    }
}
