<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DepositReceipt;

class DepositReceiptPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view deposit receipts');
    }

    public function view(User $user, DepositReceipt $depositReceipt)
    {
        return $user->hasPermissionTo('view deposit receipts');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create deposit receipts');
    }

    public function update(User $user, DepositReceipt $depositReceipt)
    {
        return $user->hasPermissionTo('edit deposit receipts');
    }

    public function delete(User $user, DepositReceipt $depositReceipt)
    {
        return $user->hasPermissionTo('delete deposit receipts');
    }
}
