<?php

namespace App\Providers;

use App\Models\Mall;
use App\Models\Building;
use App\Models\User;

use App\Policies\MallPolicy;
//use App\Policies\BuildingPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Policy mappings.
     */
    protected $policies = [
        Mall::class => MallPolicy::class,
       // Building::class => BuildingPolicy::class,
    ];

    /**
     * Register authentication / authorization services.
     */
   public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if (
                $user->is_super_admin ||
                $user->hasRole('Super Admin')
            ) {
                return true;
            }

            return null;
        });
    }
}