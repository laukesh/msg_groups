<?php

use App\Http\Controllers\Admin\Leasing\LeaseAgreementController;
use App\Http\Controllers\Admin\Leasing\LeaseDashboardController;
use App\Http\Controllers\Admin\Leasing\LeaseDocumentController;
use App\Http\Controllers\Admin\Leasing\LeaseEscalationController;
use App\Http\Controllers\Admin\Leasing\LeaseHistoryController;
use App\Http\Controllers\Admin\Leasing\LeaseProposalController;
use App\Http\Controllers\Admin\Leasing\LeaseRenewalController;
use App\Http\Controllers\Admin\Leasing\LeaseTermController;
use App\Http\Controllers\Admin\Leasing\LeaseTerminationController;
use App\Http\Controllers\Admin\Leasing\LeasingController;
use App\Http\Controllers\Admin\Leasing\LeasingPipelineController;
use App\Http\Controllers\Admin\Leasing\SpaceAvailabilityController;
use App\Http\Controllers\Admin\Leasing\LeasingProspectController;
use App\Http\Controllers\Admin\Leasing\LeadController;
use App\Http\Controllers\Admin\Leasing\SiteVisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('leasing')->name('leasing.')->group(function () {

    /* Proposals */
    Route::resource('proposals', LeaseProposalController::class);
    foreach (['submit', 'approve', 'reject'] as $a) {
        Route::post("proposals/{id}/$a", [LeaseProposalController::class, $a])->name("proposals.$a");
    }

    /* Agreements */
    Route::post('agreements/{id}/activate', [LeaseAgreementController::class, 'activate'])->name('agreements.activate');
    Route::resource('agreements', LeaseAgreementController::class);
    Route::resource('terms', LeaseTermController::class);

    /* Documents */
    Route::resource('documents', LeaseDocumentController::class);
    Route::post('documents/{document}/verify', [LeaseDocumentController::class, 'verify'])->name('documents.verify');
    Route::post('documents/{document}/reject', [LeaseDocumentController::class, 'reject'])->name('documents.reject');

    /* Renewals */
    Route::get('agreements/{agreement}/renew', [LeaseAgreementController::class, 'renew'])->name('agreements.renew');
    Route::post('agreements/{agreement}/renew', [LeaseAgreementController::class, 'processRenewal'])->name('agreements.process-renewal');

    Route::resource('renewals', LeaseRenewalController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    foreach (['submit', 'approve', 'reject', 'cancel'] as $a) {
        Route::post("renewals/{renewal}/$a", [LeaseRenewalController::class, $a])->name("renewals.$a");
    }
    Route::get('renewals/{renewal}/convert', [LeaseRenewalController::class, 'convert'])->name('renewals.convert');
    Route::post('renewals/{renewal}/convert', [LeaseRenewalController::class, 'convertStore'])->name('renewals.convert.store');

    /* Escalations */
    Route::resource('escalations', LeaseEscalationController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('escalations/{escalation}/approve', [LeaseEscalationController::class, 'approve'])->name('escalations.approve');
    Route::post('escalations/{escalation}/cancel', [LeaseEscalationController::class, 'cancel'])->name('escalations.cancel');

    /* History */
    Route::get('history', [LeaseHistoryController::class, 'index'])->name('history.index');

    /* Terminations */
    Route::resource('terminations', LeaseTerminationController::class);
    foreach (['submit', 'approve', 'cancel', 'complete'] as $a) {
        Route::post("terminations/{id}/$a", [LeaseTerminationController::class, $a])->name("terminations.$a");
    }
    Route::post('terminations/{id}/complete-inspection', [LeaseTerminationController::class, 'completeInspection'])->name('terminations.completeInspection');
    Route::post('terminations/{id}/complete-handover', [LeaseTerminationController::class, 'completeHandover'])->name('terminations.completeHandover');

    /* Space Availability (must be registered BEFORE the '/{agreement}' catch-all below,
       and 'export' must come before '{unit}') */
    Route::prefix('space-availability')->name('space-availability.')
        ->middleware('permission:leasing.space-availability.view')
        ->controller(SpaceAvailabilityController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/{unit}', 'show')->name('show');
        });

    /* Pipeline (registered BEFORE the '/{agreement}' catch-all) */
    Route::prefix('pipeline')->name('pipeline.')
        ->middleware('permission:pipeline.view')
        ->controller(LeasingPipelineController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->middleware('permission:pipeline.create')->name('create');
            Route::post('/', 'store')->middleware('permission:pipeline.create')->name('store');
           // Route::get('/{pipeline}/show', 'show')->name('show');
            Route::get('/{pipeline}', 'show')->middleware('permission:pipeline.view')->name('show');
            Route::get('/{pipeline}/edit', 'edit')->middleware('permission:pipeline.edit')->name('edit');
            Route::put('/{pipeline}', 'update')->middleware('permission:pipeline.edit')->name('update');
            Route::delete('/{pipeline}', 'destroy')->middleware('permission:pipeline.delete')->name('destroy');
        });
    /* Prospects */
    Route::patch(
    'prospects/{prospect}/convert',
    [LeasingProspectController::class, 'convert']
)
    ->middleware('permission:prospects.edit')
    ->name('prospects.convert');
    Route::prefix('prospects')->name('prospects.')
        ->middleware('permission:prospects.view')
        ->controller(LeasingProspectController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->middleware('permission:prospects.create')->name('create');
            Route::post('/', 'store')->middleware('permission:prospects.create')->name('store');
            Route::get('/{prospect}', 'show')->middleware('permission:prospects.view')->name('show');
            Route::get('/{prospect}/edit', 'edit')->middleware('permission:prospects.edit')->name('edit');
            Route::put('/{prospect}', 'update')->middleware('permission:prospects.edit')->name('update');
            Route::delete('/{prospect}', 'destroy')->middleware('permission:prospects.delete')->name('destroy');
        });
    /* Dashboard + overview (keep last: '/{agreement}' is a catch-all) */
    Route::get('dashboard', [LeaseDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [LeasingController::class, 'index'])->name('index');
    Route::get('/{agreement}', [LeasingController::class, 'show'])->whereNumber('agreement')->name('show');
     /*
        |--------------------------------------------------------------------------
        | Site Visits
        |--------------------------------------------------------------------------
        */



Route::get('site-visits', [SiteVisitController::class, 'index'])
    ->middleware('permission:site-visits.view')
    ->name('site-visits.index');

Route::get('site-visits/create', [SiteVisitController::class, 'create'])
    ->middleware('permission:site-visits.create')
    ->name('site-visits.create');

Route::post('site-visits', [SiteVisitController::class, 'store'])
    ->middleware('permission:site-visits.create')
    ->name('site-visits.store');

Route::get('site-visits/{siteVisit}', [SiteVisitController::class, 'show'])
    ->middleware('permission:site-visits.view')
    ->name('site-visits.show');

Route::get('site-visits/{siteVisit}/edit', [SiteVisitController::class, 'edit'])
    ->middleware('permission:site-visits.edit')
    ->name('site-visits.edit');

Route::put('site-visits/{siteVisit}', [SiteVisitController::class, 'update'])
    ->middleware('permission:site-visits.edit')
    ->name('site-visits.update');

Route::delete('site-visits/{siteVisit}', [SiteVisitController::class, 'destroy'])
    ->middleware('permission:site-visits.delete')
    ->name('site-visits.destroy');

Route::patch('site-visits/{siteVisit}/outcome', [SiteVisitController::class, 'outcome'])
    ->middleware('permission:site-visits.outcome')
    ->name('site-visits.outcome');
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class,'index' ])->middleware('permission:leads.view')->name('index');
        Route::get('/create', [ LeadController::class,'create'])->middleware('permission:leads.create')->name('create');
        Route::post('/', [LeadController::class,'store'])->middleware('permission:leads.create')->name('store');
        Route::get('/{lead}', [LeadController::class,'show'])->middleware('permission:leads.view')->name('show');
        Route::get('/{lead}/edit', [LeadController::class, 'edit'])->middleware('permission:leads.edit') ->name('edit');
        Route::put('/{lead}', [LeadController::class,'update'])->middleware('permission:leads.edit') ->name('update');
        Route::delete('/{lead}', [LeadController::class, 'destroy' ])->middleware('permission:leads.delete')->name('destroy');

    });
});