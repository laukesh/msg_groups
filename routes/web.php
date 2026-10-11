<?php

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public / auth / profile routes live in routes/modules/auth.php.
| Every admin module lives in routes/modules/admin/<module>.php and is
| loaded inside one shared  auth + /admin + admin.  group.
*/

Route::get('/admin/test-home', [HomeController::class, 'index'])->name('admin.test.home');

require __DIR__ . '/modules/auth.php';

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        $modules = [
            'core',                 // home, dashboard, users, roles, activity logs
            'assets',               // malls, buildings, units, assets, areas, KPIs ...
            'leasing',
            'tenants',
            'revenue',
            'fitout',
            'land',                 // land, due diligence, feasibility
            'projects',             // project core, budget, funding, governance ...
            'procurement',
            'construction',         // core + materials, equipment, manpower ...
            'construction_hse',
            'construction_claims',  // claims, delays, risks, correspondence
            'contract_management',
            'design_management',
            'commissioning',
            'handover',
            'portfolio_development',// portfolio-investment, development, development brief
        ];

        // Order matters: it matches the order of the original file.
        foreach ($modules as $module) {
            require __DIR__ . "/modules/admin/{$module}.php";
        }
    });
