<?php

namespace Modules\Sales\Policies;

use App\Models\User;
use Modules\Sales\Models\Order;
use Modules\Sales\States\Pending;

class OrderPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo('view order');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create order');
    }

    public function approve(User $user, Order $order)
    {
        return $user->hasPermissionTo('approve order')
            && $order->state->equals(Pending::class);
    }
}
