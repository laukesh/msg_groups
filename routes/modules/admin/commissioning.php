<?php

use App\Http\Controllers\Admin\Commissioning\CommissioningAuditController;
use App\Http\Controllers\Admin\Commissioning\CommissioningCertificateController;
use App\Http\Controllers\Admin\Commissioning\CommissioningDashboardController;
use App\Http\Controllers\Admin\Commissioning\CommissioningDocumentController;
use App\Http\Controllers\Admin\Commissioning\CommissioningManagementController;
use App\Http\Controllers\Admin\Commissioning\CommissioningScopeController;
use App\Http\Controllers\Admin\Commissioning\CommissioningTestController;
use App\Http\Controllers\Admin\Commissioning\CommissioningTestPlanController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::get('/commissioning', [CommissioningManagementController::class, 'index'])->name('commissioning.index');

$base = 'projects/{project}/commissioning';
$name = 'projects.commissioning.';

Route::get($base, [CommissioningDashboardController::class, 'index'])->name("{$name}index");

R::crud("$base/scopes", "{$name}scopes.", CommissioningScopeController::class, 'scope');

R::crud("$base/test-plans", "{$name}test-plans.", CommissioningTestPlanController::class, 'test_plan',
    actions: ['submit', 'approve', 'reject', 'hold', 'resume', 'start', 'complete']);

R::crud("$base/tests", "{$name}tests.", CommissioningTestController::class, 'test',
    before: fn () => Route::get('/{test}/retest', [CommissioningTestController::class, 'createRetest'])
        ->name('retest.create'));

R::crud("$base/certificates", "{$name}certificates.", CommissioningCertificateController::class, 'certificate',
    actions: ['submit', 'approve', 'reject', 'expire']);

R::crud("$base/documents", "{$name}documents.", CommissioningDocumentController::class, 'document',
    only: ['index', 'create', 'store', 'destroy'],
    before: fn () => Route::get('/{document}/download', [CommissioningDocumentController::class, 'download'])
        ->name('download'));

Route::get("$base/audit-trail", [CommissioningAuditController::class, 'index'])->name("{$name}audit.index");
