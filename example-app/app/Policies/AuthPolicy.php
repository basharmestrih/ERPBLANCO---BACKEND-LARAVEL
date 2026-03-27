<?php

namespace App\Policies;

use App\Models\User;

class AuthPolicy
{

    public function register (User $user)
    {
       return $user->hasPermissionTo('add user');
    }

    public function delete (User $user)
    {
       return $user->hasPermissionTo('delete user');
    }

}
