<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CamCharge;

class CamChargePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view cam charges');
    }

    public function view(User $user, CamCharge $camCharge)
    {
        return $user->hasPermissionTo('view cam charges');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create cam charges');
    }

    public function update(User $user, CamCharge $camCharge)
    {
        return $user->hasPermissionTo('edit cam charges');
    }

    public function delete(User $user, CamCharge $camCharge)
    {
        return $user->hasPermissionTo('delete cam charges');
    }
}
