<?php

namespace Modules\Invoice\Models;

use Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Modules\Invoice\States\InvoiceState;
use Modules\Sales\Models\Order;
use Spatie\ModelStates\HasStates;

class Invoice extends Model
{
    use HasStates;

    protected $fillable = [
        'order_id',
        'customer_id',
        'invoice_number',
        'total_amount',
        'issued_at',
        'due_date',
        'issued_by',
        'payment_intent_id',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'state' => InvoiceState::class,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
