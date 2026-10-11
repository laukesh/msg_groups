
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Tenant\TenantDashboardController;
use App\Http\Controllers\Admin\Tenant\TenantController;
use App\Http\Controllers\Admin\Tenant\TenantContactController;
use App\Http\Controllers\Admin\Tenant\TenantAddressController;
use App\Http\Controllers\Admin\Tenant\TenantBankAccountController;
use App\Http\Controllers\Admin\Tenant\TenantDocumentController;
use App\Http\Controllers\Admin\Tenant\TenantEmergencyContactController;
use App\Http\Controllers\Admin\Tenant\TenantNoteController;
use App\Http\Controllers\Admin\Tenant\TenantHistoryController;

use App\Http\Controllers\Admin\Procurement\ProcurementDashboardController;
use App\Http\Controllers\Admin\Procurement\ProcurementNegotiationController;

// Add these imports after creating the corresponding controllers.
use App\Http\Controllers\Admin\Tenant\TenantLoiController;
use App\Http\Controllers\Admin\Tenant\LeaseApprovalController;
use App\Http\Controllers\Admin\Leasing\LeaseAgreementController;
use App\Http\Controllers\Admin\Tenant\TenantDirectoryController;
use App\Http\Controllers\Admin\Tenant\TenantProfile360Controller as TenantProfileController;
use App\Http\Controllers\Admin\Tenant\TenantOpeningController;


/*
| TENANT DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('tenants/dashboard', [
    TenantDashboardController::class,
    'index',
])->name('tenants.dashboard');


/*
|--------------------------------------------------------------------------
| TENANT CRUD
|--------------------------------------------------------------------------
*/

Route::resource('tenants', TenantController::class);


/*
|--------------------------------------------------------------------------
| TENANT SUB-RESOURCES
|--------------------------------------------------------------------------
*/

$tenantChildren = [
    'contacts' => [
        'controller' => TenantContactController::class,
        'parameter' => 'contact',
    ],
    'addresses' => [
        'controller' => TenantAddressController::class,
        'parameter' => 'address',
    ],
    'bank-accounts' => [
        'controller' => TenantBankAccountController::class,
        'parameter' => 'account',
    ],
    'documents' => [
        'controller' => TenantDocumentController::class,
        'parameter' => 'document',
    ],
    'emergency-contacts' => [
        'controller' => TenantEmergencyContactController::class,
        'parameter' => 'contact',
    ],
    'notes' => [
        'controller' => TenantNoteController::class,
        'parameter' => 'note',
    ],
];

foreach ($tenantChildren as $slug => $config) {
    $controller = $config['controller'];
    $parameter = $config['parameter'];

    Route::get(
        "tenants/{tenant}/{$slug}",
        [$controller, 'index']
    )->name("tenants.{$slug}.index");

    Route::post(
        "tenants/{tenant}/{$slug}",
        [$controller, 'store']
    )->name("tenants.{$slug}.store");

    Route::get(
        "tenants/{tenant}/{$slug}/{{$parameter}}/edit",
        [$controller, 'edit']
    )->name("tenants.{$slug}.edit");

    Route::put(
        "tenants/{tenant}/{$slug}/{{$parameter}}",
        [$controller, 'update']
    )->name("tenants.{$slug}.update");

    Route::delete(
        "tenants/{tenant}/{$slug}/{{$parameter}}",
        [$controller, 'destroy']
    )->name("tenants.{$slug}.destroy");
}


/*
|--------------------------------------------------------------------------
| TENANT HISTORY
|--------------------------------------------------------------------------
*/

Route::get(
    'tenants/{tenant}/history',
    [TenantHistoryController::class, 'index']
)->name('tenants.history.index');


/*
|--------------------------------------------------------------------------
| PROCUREMENT DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    'tenders/dashboard',
    [ProcurementDashboardController::class, 'index']
)->name('tenders.dashboard');


/*
|--------------------------------------------------------------------------
| PROCUREMENT NEGOTIATIONS
|--------------------------------------------------------------------------
| URL: /admin/tenders/{procurementTender}/negotiations
|--------------------------------------------------------------------------
*/

Route::prefix('tenders/{procurementTender}/negotiations')
    ->name('tenders.negotiations.')
    ->group(function () {
        Route::get('/', [
            ProcurementNegotiationController::class, 'index',
        ])->name('index');

        Route::get('/create', [
            ProcurementNegotiationController::class, 'create',
        ])->name('create');

        Route::post('/', [
            ProcurementNegotiationController::class, 'store',
        ])->name('store');

        Route::get('/{negotiation}/edit', [
            ProcurementNegotiationController::class, 'edit',
        ])->name('edit');

        Route::get('/{negotiation}', [
            ProcurementNegotiationController::class, 'show',
        ])->name('show');

        Route::put('/{negotiation}', [
            ProcurementNegotiationController::class, 'update',
        ])->name('update');

        Route::get('/{negotiation}/rounds/create', [
            ProcurementNegotiationController::class, 'createRound',
        ])->name('rounds.create');

        Route::post('/{negotiation}/rounds', [
            ProcurementNegotiationController::class, 'storeRound',
        ])->name('rounds.store');

        Route::get('/{negotiation}/rounds/{item}/edit', [
            ProcurementNegotiationController::class, 'editRound',
        ])->name('rounds.edit');

        Route::put('/{negotiation}/rounds/{item}', [
            ProcurementNegotiationController::class, 'updateRound',
        ])->name('rounds.update');

        Route::post('/{negotiation}/finalize', [
            ProcurementNegotiationController::class, 'finalize',
        ])->name('finalize');

        Route::post('/{negotiation}/approve', [
            ProcurementNegotiationController::class, 'approve',
        ])->name('approve');
    });


/*
|--------------------------------------------------------------------------
| TENANT COMMERCIAL & LEASE MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('tenants')->name('tenants.')->group(function () {

    /*
    | Negotiations
    | Existing tender-based negotiation records remain under tenders.
    */

    Route::get('/negotiations', function () {
        return redirect()->route('admin.tenders.dashboard');
    })->middleware('permission:tenders.negotiations.view')
      ->name('negotiations.index');


    /*
    | Letter of Intent
    */

    Route::middleware('permission:lois.view')
        ->prefix('lois')
        ->name('lois.')
        ->controller(TenantLoiController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{loi}/edit', 'edit')
                ->whereNumber('loi')->name('edit');
            Route::get('/{loi}', 'show')
                ->whereNumber('loi')->name('show');
            Route::put('/{loi}', 'update')
                ->whereNumber('loi')->name('update');
            Route::delete('/{loi}', 'destroy')
                ->whereNumber('loi')->name('destroy');
        });


    /*
    | Lease Approval
    */

    Route::middleware('permission:lease-approvals.view')
        ->prefix('lease-approvals')
        ->name('lease-approvals.')
        ->controller(LeaseApprovalController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{approval}', 'show')
                ->whereNumber('approval')->name('show');
            Route::post('/{approval}/submit', 'submit')
                ->whereNumber('approval')->name('submit');
            Route::post('/{approval}/approve', 'approve')
                ->whereNumber('approval')->name('approve');
            Route::post('/{approval}/reject', 'reject')
                ->whereNumber('approval')->name('reject');
        });


    /*
    | Lease Agreements
    */

    Route::middleware('permission:lease-agreements.view')
        ->prefix('T')
        ->name('lease-agreements.')
        ->controller(LeaseAgreementController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{leaseAgreement}/edit', 'edit')
                ->whereNumber('leaseAgreement')->name('edit');
            Route::get('/{leaseAgreement}', 'show')
                ->whereNumber('leaseAgreement')->name('show');
            Route::put('/{leaseAgreement}', 'update')
                ->whereNumber('leaseAgreement')->name('update');
            Route::delete('/{leaseAgreement}', 'destroy')
                ->whereNumber('leaseAgreement')->name('destroy');
        });


    /*
    | Tenant Directory
    */

    Route::get('/directory', [
        TenantDirectoryController::class, 'index',
    ])->middleware('permission:tenant-directory.view')
      ->name('directory.index');

    Route::get('/directory/{tenant}', [
        TenantDirectoryController::class, 'show',
    ])->whereNumber('tenant')
      ->middleware('permission:tenant-directory.view')
      ->name('directory.show');


    /*
    | Tenant Profile 360
    */

    Route::get('/profiles', [
        TenantProfileController::class, 'index',
    ])->middleware('permission:tenant-profiles.view')
      ->name('profiles.index');

    Route::get('/profiles/{tenant}', [
        TenantProfileController::class, 'show',
    ])->whereNumber('tenant')
      ->middleware('permission:tenant-profiles.view')
      ->name('profiles.show');


    /*
    | Fit-Out Coordination
    */

    
    /*
    | Tenant Opening / Handover
    */

    Route::middleware('permission:tenant-openings.view')
        ->prefix('openings')
        ->name('openings.')
        ->controller(TenantOpeningController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{opening}/edit', 'edit')
                ->whereNumber('opening')->name('edit');
            Route::get('/{opening}', 'show')
                ->whereNumber('opening')->name('show');
            Route::put('/{opening}', 'update')
                ->whereNumber('opening')->name('update');
            Route::post('/{opening}/complete', 'complete')
                ->whereNumber('opening')->name('complete');
        });


   

});
