<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Building;

class BuildingPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Building $building)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Building $building)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Building $building)
    {
        return $user->hasRole('admin');
    }
}
