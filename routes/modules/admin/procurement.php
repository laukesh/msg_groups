<?php

use App\Http\Controllers\Admin\Procurement\ProcurementAwardController;
use App\Http\Controllers\Admin\Procurement\ProcurementBidComparisonController;
use App\Http\Controllers\Admin\Procurement\ProcurementBidderController;
use App\Http\Controllers\Admin\Procurement\ProcurementCommercialEvaluationController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractInvoiceController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractMilestoneController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractPaymentController;
use App\Http\Controllers\Admin\Procurement\ProcurementDashboardController;
use App\Http\Controllers\Admin\Procurement\ProcurementDeliveryController;
use App\Http\Controllers\Admin\Procurement\ProcurementMaterialTrackingController;
use App\Http\Controllers\Admin\Procurement\ProcurementMilestoneDocumentController;
use App\Http\Controllers\Admin\Procurement\ProcurementMilestoneProgressController;
use App\Http\Controllers\Admin\Procurement\ProcurementNegotiationController;
use App\Http\Controllers\Admin\Procurement\ProcurementPackageController;
use App\Http\Controllers\Admin\Procurement\ProcurementPerformanceController;
use App\Http\Controllers\Admin\Procurement\ProcurementPlanController;
use App\Http\Controllers\Admin\Procurement\ProcurementPlanPerformanceController;
use App\Http\Controllers\Admin\Procurement\ProcurementPrequalificationController;
use App\Http\Controllers\Admin\Procurement\ProcurementPrequalificationCriterionController;
use App\Http\Controllers\Admin\Procurement\ProcurementPurchaseOrderController;
use App\Http\Controllers\Admin\Procurement\ProcurementTechnicalEvaluationController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderBidderController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderDocumentController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderSubmissionController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Everything that hangs off a tender is identical for the project-scoped
| routes (projects/{project}/procurement/...) and the global ones
| (procurement/...), so it is registered once here and mounted twice.
| Only the contract-payment routes differ between the two variants.
|--------------------------------------------------------------------------
*/
$tenderTree = function (bool $global) {
    $T  = 'tenders/{procurementTender}';
    $TC = "$T/contracts/{contract}";
    $MS = "$TC/milestones/{milestone}";

    R::crud("$T/awards", 'tenders.awards.', ProcurementAwardController::class, 'award',
        only: ['index', 'create', 'store', 'show', 'edit', 'update'],
        actions: ['submit', 'approve', 'issue-loa']);

    R::crud("$T/bid-comparisons", 'tenders.bid-comparisons.', ProcurementBidComparisonController::class, 'comparison');
    R::crud("$T/commercial-evaluations", 'tenders.commercial-evaluations.', ProcurementCommercialEvaluationController::class, 'evaluation');
    R::crud("$T/technical-evaluations", 'tenders.technical-evaluations.', ProcurementTechnicalEvaluationController::class, 'evaluation');
    R::crud("$T/submissions", 'tenders.submissions.', ProcurementTenderSubmissionController::class, 'submission');

    /* Contracts > milestones > documents / progress */
    R::crud("$T/contracts", 'tenders.contracts.', ProcurementContractController::class, 'contract',
        only: ['index', 'create', 'store', 'show'],
        actions: ['submit', 'approve', 'activate', 'close']);

    R::crud("$TC/milestones", 'tenders.contracts.milestones.', ProcurementContractMilestoneController::class, 'milestone',
        only: ['index', 'create', 'store', 'show'], actions: ['start', 'complete']);

    R::crud("$MS/documents", 'tenders.contracts.milestones.documents.', ProcurementMilestoneDocumentController::class, 'document',
        only: ['index', 'create', 'store', 'destroy'], actions: ['verify', 'reject']);

    R::crud("$MS/progress", 'tenders.contracts.milestones.progress.', ProcurementMilestoneProgressController::class, 'progress',
        only: ['index', 'create', 'store']);

    /* Contract invoices */
    R::crud("$TC/invoices", 'tenders.contracts.invoices.', ProcurementContractInvoiceController::class, 'invoice',
        only: ['index', 'show'], actions: ['submit', 'approve', 'reject']);
    Route::get("$MS/invoice/create", [ProcurementContractInvoiceController::class, 'create'])
        ->name('tenders.contracts.milestones.invoice.create');
    Route::post("$MS/invoice", [ProcurementContractInvoiceController::class, 'store'])
        ->name('tenders.contracts.milestones.invoice.store');

    /* Contract payments (differs between variants) */
    if ($global) {
        Route::get("$TC/invoices/{invoice}/payment/create", [ProcurementContractPaymentController::class, 'create'])
            ->name('tenders.contracts.invoices.payment.create');
        Route::post("$TC/invoices/{invoice}/payment", [ProcurementContractPaymentController::class, 'store'])
            ->name('tenders.contracts.invoices.payment.store');

        R::crud("$TC/payments", 'tenders.contracts.payments.', ProcurementContractPaymentController::class, 'payment',
            only: ['index', 'store', 'show'],
            actions: ['submit', 'approve', 'reject', 'process']);
    } else {
        R::crud("$TC/payments", 'tenders.contracts.payments.', ProcurementContractPaymentController::class, 'payment',
            only: ['index', 'create', 'store', 'show'],
            actions: ['submit', 'approve', 'reject', 'process'],
            before: fn () => Route::get('/{payment}/reject', [ProcurementContractPaymentController::class, 'rejectForm'])
                ->name('reject.form'));
    }

    /* Negotiations + rounds */
    R::crud("$T/negotiations", 'tenders.negotiations.', ProcurementNegotiationController::class, 'negotiation',
        only: ['index', 'create', 'store', 'edit', 'update', 'show'],
        actions: ['finalize', 'approve']);
    $N = "$T/negotiations/{negotiation}/rounds";
    Route::get("$N/create", [ProcurementNegotiationController::class, 'createRound'])->name('tenders.negotiations.rounds.create');
    Route::post($N, [ProcurementNegotiationController::class, 'storeRound'])->name('tenders.negotiations.rounds.store');
    Route::get("$N/{item}/edit", [ProcurementNegotiationController::class, 'editRound'])->name('tenders.negotiations.rounds.edit');
    Route::put("$N/{item}", [ProcurementNegotiationController::class, 'updateRound'])->name('tenders.negotiations.rounds.update');

    /* Tender bidders, prequalification, documents */
    R::crud("$T/bidders", 'tenders.bidders.', ProcurementTenderBidderController::class, 'tenderBidder',
        only: ['index', 'store', 'update', 'destroy']);

    R::crud("$T/prequalifications", 'tenders.prequalifications.', ProcurementPrequalificationController::class, 'prequalification');
    R::crud('prequalifications/{prequalification}/criteria', 'prequalifications.criteria.',
        ProcurementPrequalificationCriterionController::class, 'criterion', only: ['store', 'update', 'destroy']);

    R::crud("$T/documents", 'tenders.documents.', ProcurementTenderDocumentController::class, 'document');

    /* Purchase orders > deliveries / material tracking */
    R::crud("$T/purchase-orders", 'tenders.purchase-orders.', ProcurementPurchaseOrderController::class, 'purchaseOrder',
        only: ['index', 'create', 'store', 'show', 'destroy'],
        actions: ['submit', 'approve', 'issue']);

    R::crud("$T/purchase-orders/{purchaseOrder}/deliveries", 'tenders.purchase-orders.deliveries.',
        ProcurementDeliveryController::class, 'delivery', only: ['index', 'create', 'store', 'show']);

    R::crud("$T/purchase-orders/{purchaseOrder}/material-trackings", 'tenders.purchase-orders.material-trackings.',
        ProcurementMaterialTrackingController::class, 'materialTracking', only: ['index', 'create', 'store', 'show']);
};

/* ------------------------------------------------------------------ */
/* Project-scoped procurement                                          */
/* ------------------------------------------------------------------ */
Route::get('/projects/{project}/procurement', [ProcurementDashboardController::class, 'project'])
    ->name('projects.procurement');

Route::prefix('projects/{project}/procurement')->name('projects.procurement.')->group(function () use ($tenderTree) {

    R::crud('plans', 'plans.', ProcurementPlanController::class, 'procurementPlan');
    Route::get('/strategies', [ProcurementPlanController::class, 'strategies'])->name('strategies');

    R::crud('packages', 'packages.', ProcurementPackageController::class, 'procurementPackage',
        before: fn () => Route::get('/{procurementPackage}/budget-items', [ProcurementPackageController::class, 'getBudgetItems'])
            ->name('budget-items'));
    Route::get('plans/{procurementPlan}/budgets', [ProcurementPackageController::class, 'getBudgetsByPlan'])->name('plans.budgets');
    Route::get('/packages/budgets/{procurementPlan}', [ProcurementPackageController::class, 'getBudgetsByPlan'])->name('packages.budgets');

    R::crud('tenders', 'tenders.', ProcurementTenderController::class, 'procurementTender');
    $tenderTree(false);

    Route::get('/performance', [ProcurementPerformanceController::class, 'index'])->name('performance');
    Route::get('/performance/plans', [ProcurementPlanPerformanceController::class, 'index'])->name('performance.plans');
});

/* ------------------------------------------------------------------ */
/* Global procurement                                                  */
/* ------------------------------------------------------------------ */
Route::prefix('procurement')->name('procurement.')->group(function () use ($tenderTree) {

    Route::get('/', [ProcurementDashboardController::class, 'index'])->name('index');

    R::crud('plans', 'plans.', ProcurementPlanController::class, 'procurementPlan',
        before: fn () => Route::get('/{procurementPlan}/tenders', [ProcurementTenderController::class, 'planTenders'])
            ->name('tenders.index'));
    Route::get('/projects/{project}/strategies', [ProcurementPlanController::class, 'strategies'])->name('projects.strategies');

    R::crud('packages', 'packages.', ProcurementPackageController::class, 'procurementPackage',
        before: fn () => Route::get('/{procurementPackage}/tenders', [ProcurementTenderController::class, 'packageTenders'])
            ->name('tenders.index'));

    R::crud('tenders', 'tenders.', ProcurementTenderController::class, 'procurementTender');
    R::crud('bidders', 'bidders.', ProcurementBidderController::class, 'procurementBidder');
    $tenderTree(true);
});

/* Kept exactly as before (this name resolves to 'admin.admin.procurement.packages.budgets') */
Route::get('procurement/packages/budgets/{procurementPlan}', [ProcurementPackageController::class, 'getBudgetsByPlan'])
    ->name('admin.procurement.packages.budgets');
