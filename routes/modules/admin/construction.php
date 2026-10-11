<?php

use App\Http\Controllers\Admin\Construction\ConstructionConsultantController;
use App\Http\Controllers\Admin\Construction\ConstructionContractController;
use App\Http\Controllers\Admin\Construction\ConstructionContractorController;
use App\Http\Controllers\Admin\Construction\ConstructionCostControlController;
use App\Http\Controllers\Admin\Construction\ConstructionDashboardController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentDeploymentController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentMaintenanceController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentUsageLogController;
use App\Http\Controllers\Admin\Construction\ConstructionInspectionController;
use App\Http\Controllers\Admin\Construction\ConstructionManagementController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerAssignmentController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerEntryController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialDeliveryController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialReceiptController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialRequestController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialRequirementController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialStockController;
use App\Http\Controllers\Admin\Construction\ConstructionOtherCostController;
use App\Http\Controllers\Admin\Construction\ConstructionPaymentCertificateController;
use App\Http\Controllers\Admin\Construction\ConstructionProgressController;
use App\Http\Controllers\Admin\Construction\ConstructionQualityItpController;
use App\Http\Controllers\Admin\Construction\ConstructionQualityNcrController;
use App\Http\Controllers\Admin\Construction\ConstructionScheduleActivityController;
use App\Http\Controllers\Admin\Construction\ConstructionSiteIssueController;
use App\Http\Controllers\Admin\Construction\ConstructionSiteReportController;
use App\Http\Controllers\Admin\Construction\ConstructionSubmittalController;
use App\Http\Controllers\Admin\Construction\ConstructionVariationController;
use App\Http\Controllers\Admin\Construction\ConstructionWorkOrderController;
use App\Http\Controllers\Admin\Construction\SiteInstructionController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

$base = 'projects/{project}/construction';
$name = 'projects.construction.';

/* Dashboard / lists */
Route::get($base, [ConstructionDashboardController::class, 'index'])->name('projects.construction.dashboard');
Route::get("$base/contractors", [ConstructionContractorController::class, 'index'])->name('projects.construction.contractors.index');
Route::get("$base/contracts", [ConstructionContractController::class, 'index'])->name('projects.construction.contracts.index');

R::crud("$base/work-orders", "{$name}work-orders.", ConstructionWorkOrderController::class, 'workOrder',
    only: ['index', 'create', 'store']);

/* Standard CRUD (+ workflow) registers: slug => [controller, param, workflow actions] */
$standard = [
    'consultants'       => [ConstructionConsultantController::class, 'consultant', []],
    'progress'          => [ConstructionProgressController::class, 'progress', []],
    'site-issues'       => [ConstructionSiteIssueController::class, 'issue', []],
    'site-reports'      => [ConstructionSiteReportController::class, 'report', ['submit']],
    'schedule'          => [ConstructionScheduleActivityController::class, 'activity', []],
    'other-costs'       => [ConstructionOtherCostController::class, 'cost', []],
    'variations'        => [ConstructionVariationController::class, 'variation', ['submit', 'approve', 'reject']],
    'site-instructions' => [SiteInstructionController::class, 'siteInstruction',
        ['issue', 'acknowledge', 'start', 'comply', 'close', 'cancel']],
    'submittals'        => [ConstructionSubmittalController::class, 'submittal',
        ['submit', 'start-review', 'approve', 'approve-with-comments', 'revise', 'reject']],
    'inspections'       => [ConstructionInspectionController::class, 'inspection',
        ['schedule', 'conduct', 'pass', 'fail', 'corrective-action', 'reinspection', 'close']],
];
foreach ($standard as $slug => [$controller, $param, $actions]) {
    R::crud("$base/$slug", "{$name}$slug.", $controller, $param, actions: $actions);
}

/* Cost control */
Route::prefix("$base/cost-control")->name("{$name}cost-control.")->group(function () {
    $c = ConstructionCostControlController::class;
    Route::get('/', [$c, 'index'])->name('index');
    Route::get('/contracts', [$c, 'contracts'])->name('contracts');
    Route::get('/variations', [$c, 'variations'])->name('variations');
    Route::get('/invoices', [$c, 'invoices'])->name('invoices');
    Route::get('/payments', [$c, 'payments'])->name('payments');
    Route::get('/other-costs', [$c, 'otherCosts'])->name('other-costs');
    Route::get('/report', [$c, 'report'])->name('report');
});

/* Quality: ITP */
R::crud("$base/quality/itps", "{$name}quality.itps.", ConstructionQualityItpController::class, 'itp',
    actions: ['submit', 'start-review', 'approve', 'reject'],
    before: function () {
        $c = ConstructionQualityItpController::class;
        Route::post('/{itp}/items', [$c, 'addItem'])->name('items.store');
        Route::put('/{itp}/items/{item}', [$c, 'updateItem'])->name('items.update');
        Route::delete('/{itp}/items/{item}', [$c, 'deleteItem'])->name('items.destroy');
    });

/* Quality: NCR */
R::crud("$base/quality/ncrs", "{$name}quality.ncrs.", ConstructionQualityNcrController::class, 'ncr',
    actions: ['submit', 'start-review', 'require-corrective-action', 'start-verification', 'verify',
        'submit-corrective-action', 'return-for-correction']);

/* Materials */
Route::prefix("$base/materials")->name("{$name}materials.")->group(function () {
    $m = ConstructionMaterialController::class;
    Route::get('/', [$m, 'index'])->name('index');

    // Material master
    Route::get('/master', [$m, 'master'])->name('master.index');
    Route::get('/master/create', [$m, 'create'])->name('master.create');
    Route::post('/master', [$m, 'store'])->name('master.store');
    Route::get('/master/{material}', [$m, 'show'])->name('master.show');
    Route::get('/master/{material}/edit', [$m, 'edit'])->name('master.edit');
    Route::put('/master/{material}', [$m, 'update'])->name('master.update');
    Route::delete('/master/{material}', [$m, 'destroy'])->name('master.destroy');

    R::crud('requests', 'requests.', ConstructionMaterialRequestController::class, 'materialRequest',
        only: ['index', 'create', 'store', 'show', 'edit', 'update'],
        actions: ['submit', 'review', 'approve', 'request-changes', 'reject', 'cancel']);

    R::crud('deliveries', 'deliveries.', ConstructionMaterialDeliveryController::class, 'materialDelivery',
        only: ['index', 'store', 'show', 'edit', 'update'],
        actions: ['receive', 'cancel'],
        before: fn () => Route::get('/create/{materialRequest}', [ConstructionMaterialDeliveryController::class, 'create'])
            ->name('create'));

    R::crud('receipts', 'receipts.', ConstructionMaterialReceiptController::class, 'materialReceipt',
        only: ['index', 'store', 'show'],
        actions: ['inspect', 'cancel'],
        before: fn () => Route::get('/create/{materialDelivery}', [ConstructionMaterialReceiptController::class, 'create'])
            ->name('create'));

    R::crud('stock', 'stock.', ConstructionMaterialStockController::class, 'stock',
        only: ['index', 'show'],
        before: function () {
            $c = ConstructionMaterialStockController::class;
            Route::get('/transactions', [$c, 'transactions'])->name('transactions');
            Route::get('/transactions/{transaction}', [$c, 'transactionShow'])->name('transactions.show');
        });

    R::crud('requirements', 'requirements.', ConstructionMaterialRequirementController::class, 'requirement',
        only: ['index', 'create', 'store', 'show', 'edit', 'update'],
        actions: ['request', 'cancel']);
});

/* Equipment (numeric constraints keep '/deployments' etc. from matching '/{equipment}') */
R::crud("$base/equipment", "{$name}equipment.", ConstructionEquipmentController::class, 'equipment',
    where: R::num('equipment'));

R::crud("$base/equipment/deployments", "{$name}equipment.deployments.", ConstructionEquipmentDeploymentController::class, 'deployment',
    only: ['index', 'create', 'store', 'show'],
    actions: ['deploy', 'return' => 'returnEquipment', 'cancel']);

R::crud("$base/equipment/usage", "{$name}equipment.usage.", ConstructionEquipmentUsageLogController::class, 'usageLog',
    only: ['index', 'create', 'store', 'show', 'destroy'], where: R::num('usageLog'));

R::crud("$base/equipment/maintenance", "{$name}equipment.maintenance.", ConstructionEquipmentMaintenanceController::class, 'maintenance',
    only: ['index', 'create', 'store', 'show', 'destroy'],
    actions: ['start', 'complete', 'cancel'],
    where: R::num('maintenance'));

/* Manpower (assignments are registered BEFORE the '/{manpower}' routes) */
R::crud("$base/manpower", "{$name}manpower.", ConstructionManpowerController::class, 'manpower',
    where: R::num('manpower'),
    before: fn () => R::crud('assignments', 'assignments.', ConstructionManpowerAssignmentController::class, 'assignment',
        actions: ['activate', 'release', 'cancel'], where: R::num('assignment')));

R::crud("$base/manpower/entries", "{$name}manpower.entries.", ConstructionManpowerEntryController::class, 'entry',
    where: R::num('entry'));

/* Payment certificates */
R::crud("$base/payment-certificates", "{$name}payment-certificates.", ConstructionPaymentCertificateController::class, 'payment_certificate',
    actions: ['submit', 'review', 'approve', 'reject', 'paid' => 'markPaid'],
    where: R::num('payment_certificate'));

/* Construction overview (admin/construction) */
Route::get('construction', [ConstructionManagementController::class, 'index'])->name('construction.index');
