<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    public function boot()
    {
        // Load package-like routes for auth features
        if (file_exists(base_path('routes/auth.php'))) {
            $this->loadRoutesFrom(base_path('routes/auth.php'));
        }

        // Load views for the simple auth blades
        $this->loadViewsFrom(resource_path('views/auth'), 'auth');
    }
}
