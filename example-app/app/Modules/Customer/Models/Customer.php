<?php

namespace Modules\Customer\Models;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Models\Order;

class Customer extends Model
{

    protected $fillable = [
        'name',
        'phone',
        'password',
        'email',
        'total_spent',
        'total_orders',
        'last_purchase_at',
        'address',
        'company',
        'status',
        'notes'
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = ['last_purchase_at'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}


