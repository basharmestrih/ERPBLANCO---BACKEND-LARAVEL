<?php

namespace Modules\Product\Policies;

use App\Models\User;

class ProductPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo('view product');
    }

    public function store (User $user)
    {
        return $user->hasPermissionTo('store product');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('update product');
    }

    public function destroy(User $user)
    {
       return $user->hasPermissionTo('destroy product');
    }
    
}
