<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPaymentAccount extends Model
{
    protected $fillable = [
        'method',
        'label',
        'bank',
        'phone',
        'id_number',
        'account_number',
        'account_type',
        'holder_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}