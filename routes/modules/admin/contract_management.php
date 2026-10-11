<?php

use App\Http\Controllers\Admin\ContractManagement\ContractClaimController;
use App\Http\Controllers\Admin\ContractManagement\ContractExtensionOfTimeController;
use App\Http\Controllers\Admin\ContractManagement\ContractInsuranceController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementAdvancePaymentController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementContractController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementCorrespondenceController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementDashboardController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementDocumentController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementRetentionController;
use App\Http\Controllers\Admin\ContractManagement\ContractPerformanceSecurityController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

$base = 'projects/{project}/contract-management/contracts';
$name = 'projects.contract-management.contracts.';

/* Contracts */
R::crud($base, $name, ContractManagementContractController::class, 'contract',
    only: ['index', 'show'],
    before: fn () => Route::post('/sync-procurement', [ContractManagementContractController::class, 'syncProcurementContracts'])
        ->name('sync-procurement'));

/* Contract children (everything except 'show') */
$children = [
    'claims'               => [ContractClaimController::class, 'claim'],
    'eot'                  => [ContractExtensionOfTimeController::class, 'eot'],
    'insurances'           => [ContractInsuranceController::class, 'insurance'],
    'performance-securities' => [ContractPerformanceSecurityController::class, 'security'],
    'retentions'           => [ContractManagementRetentionController::class, 'retention'],
    'advance-payments'     => [ContractManagementAdvancePaymentController::class, 'advancePayment'],
];
foreach ($children as $slug => [$controller, $param]) {
    R::crud("$base/{contract}/$slug", "$name$slug.", $controller, $param, only: R::NO_SHOW);
}

/* Documents & correspondence (with download) */
foreach ([
    'documents'      => [ContractManagementDocumentController::class, 'document'],
    'correspondence' => [ContractManagementCorrespondenceController::class, 'correspondence'],
] as $slug => [$controller, $param]) {
    R::crud("$base/{contract}/$slug", "$name$slug.", $controller, $param,
        only: R::NO_SHOW,
        before: fn () => Route::get('/{' . $param . '}/download', [$controller, 'download'])->name('download'));
}

Route::get('/contract-management', [ContractManagementDashboardController::class, 'index'])
    ->name('contract-management.index');
