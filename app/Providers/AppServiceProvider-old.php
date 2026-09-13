<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\BuildingRepositoryInterface;
use App\Repositories\EloquentBuildingRepository;
use App\Repositories\EloquentMallRepository;
use App\Repositories\MallRepositoryInterface;
use App\Repositories\FloorRepositoryInterface;
use App\Repositories\EloquentFloorRepository;
use App\Repositories\ZoneRepositoryInterface;
use App\Repositories\EloquentZoneRepository;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Mall Repository
        |--------------------------------------------------------------------------
        */
        $this->app->bind(
            MallRepositoryInterface::class,
            EloquentMallRepository::class
        );

        /*
        |--------------------------------------------------------------------------
        | Building Repository
        |--------------------------------------------------------------------------
        */
        $this->app->bind(
            BuildingRepositoryInterface::class,
            EloquentBuildingRepository::class
        );
         /*
        |--------------------------------------------------------------------------
        | Building floor Repository
        |--------------------------------------------------------------------------
        */
        $this->app->bind(
            FloorRepositoryInterface::class,
            EloquentFloorRepository::class
        );
           /*
        |--------------------------------------------------------------------------
        | Building zone Repository
        |--------------------------------------------------------------------------
        */
        $this->app->bind(
            ZoneRepositoryInterface::class,
            EloquentZoneRepository::class
        );
             /*
        |--------------------------------------------------------------------------
        | Building unit type Repository
        |--------------------------------------------------------------------------
        */
        $this->app->bind(
            \App\Repositories\UnitTypeRepositoryInterface::class,
            \App\Repositories\EloquentUnitTypeRepository::class
        );
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Force HTTPS in Production
        |--------------------------------------------------------------------------
        */
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /*
        |--------------------------------------------------------------------------
        | User Observer
        |--------------------------------------------------------------------------
        */
        User::observe(UserObserver::class);
    }
}