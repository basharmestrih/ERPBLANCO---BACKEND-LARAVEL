<?php

namespace Modules\Sales\Models;

use Modules\Customer\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\States\OrderState;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasStates;

    protected $fillable = [
        'customer_id',
        'created_by',
        'total_amount',
        'approved_by',
        'state',
    ];

    protected $casts = [
        'state' => OrderState::class,
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
