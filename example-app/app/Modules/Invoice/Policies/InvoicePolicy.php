<?php

namespace Modules\Invoice\Policies;

use App\Models\User;

class InvoicePolicy
{
    public function index(User $user): bool
    {
        return $user->hasPermissionTo('view invoice');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create invoice');
    }
}
