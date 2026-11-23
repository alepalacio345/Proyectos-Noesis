<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'payment_method',
        'amount',
        'currency',
        'exchange_rate',
        'reference_number',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    /** Convierte el pago a USD para comparación */
    public function getAmountUsdAttribute(): float
    {
        if ($this->currency === 'USD') {
            return (float) $this->amount;
        }
        $rate = (float) $this->exchange_rate;
        return $rate > 0 ? round((float) $this->amount / $rate, 2) : 0;
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public static function methodLabel(string $method): string
    {
        return match ($method) {
            'cash_usd'   => 'Efectivo USD',
            'cash_bs'    => 'Efectivo Bs.',
            'pago_movil' => 'Pago Móvil',
            'zelle'      => 'Zelle',
            default      => $method,
        };
    }
}