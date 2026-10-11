<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mall;

class MallPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Mall $mall)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Mall $mall)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Mall $mall)
    {
        return $user->hasRole('admin');
    }
}
