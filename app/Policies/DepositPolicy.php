<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Deposit;

class DepositPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view deposits');
    }

    public function view(User $user, Deposit $deposit)
    {
        return $user->hasPermissionTo('view deposits');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create deposits');
    }

    public function update(User $user, Deposit $deposit)
    {
        return $user->hasPermissionTo('edit deposits');
    }

    public function delete(User $user, Deposit $deposit)
    {
        return $user->hasPermissionTo('delete deposits');
    }
}
