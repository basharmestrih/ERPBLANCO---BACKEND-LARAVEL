<?php

namespace Modules\StockMovement\Policies;

use App\Models\User;

class StockMovementPolicy
{

    public function store(User $user)
    {
         return $user->hasPermissionTo('add stock movement');
    }

     public function index(User $user)
    {
         return $user->hasPermissionTo('view stock movement');}
}
