<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DepositRefund;

class DepositRefundPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view deposit refunds');
    }

    public function view(User $user, DepositRefund $depositRefund)
    {
        return $user->hasPermissionTo('view deposit refunds');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create deposit refunds');
    }

    public function update(User $user, DepositRefund $depositRefund)
    {
        return $user->hasPermissionTo('edit deposit refunds');
    }

    public function delete(User $user, DepositRefund $depositRefund)
    {
        return $user->hasPermissionTo('delete deposit refunds');
    }
}
