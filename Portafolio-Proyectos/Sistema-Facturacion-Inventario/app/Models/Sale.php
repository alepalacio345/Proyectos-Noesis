<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'total',
        'exchange_rate',
        'status',
        'notes',
    ];

    protected $casts = [
        'total'         => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** Total en Bolívares al momento de la venta */
    public function getTotalBsAttribute(): float
    {
        return round((float) $this->total * (float) $this->exchange_rate, 2);
    }
}