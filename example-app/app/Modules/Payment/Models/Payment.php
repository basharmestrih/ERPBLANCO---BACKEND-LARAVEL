<?php

namespace Modules\Payment\Models;
use Illuminate\Database\Eloquent\Model;



class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'gateway',
        'gateway_transaction_id',
        'gateway_charge_id',
        'amount',
        'currency',
        'status',
        'paid_at',
        'gateway_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
    ];
}