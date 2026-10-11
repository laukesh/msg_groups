<?php

use App\Http\Controllers\Admin\Fitout\ContractorController;
use App\Http\Controllers\Admin\Fitout\FitoutApprovalController;
use App\Http\Controllers\Admin\Fitout\FitoutDashboardController;
use App\Http\Controllers\Admin\Fitout\FitoutDocumentController;
use App\Http\Controllers\Admin\Fitout\FitoutInspectionController;
use App\Http\Controllers\Admin\Fitout\FitoutRequestController;
use App\Http\Controllers\Admin\Fitout\FitoutStageController;
use App\Http\Controllers\Admin\Fitout\HandoverController;
use App\Http\Controllers\Admin\Fitout\SnagListController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::prefix('fitout')->name('fitout.')->group(function () {

    Route::get('/dashboard', [FitoutDashboardController::class, 'index'])->name('dashboard');

    /* Requests */
    R::crud(
        'requests', 'requests.', FitoutRequestController::class, 'id',
        only: ['index', 'create', 'store', 'show'],
        actions: ['submit', 'approve', 'reject', 'start', 'complete', 'close', 'generate-approval'],
        before: function () {
            Route::post('/{id}/start-review', [FitoutRequestController::class, 'startReview'])->name('startReview');
        }
    );

    /* Contractors */
    R::crud('contractors', 'contractors.', ContractorController::class, 'id',
        only: ['index', 'create', 'store', 'show', 'edit', 'update'],
        actions: ['approve', 'reject', 'suspend']);

    /* Stages */
    Route::get('requests/{fitoutRequestId}/stages', [FitoutStageController::class, 'index'])->name('stages.index');
    R::crud('stages', 'stages.', FitoutStageController::class, 'id',
        only: ['show', 'edit', 'update'],
        actions: ['start', 'progress' => 'updateProgress', 'complete', 'hold', 'resume']);

    /* Documents */
    R::crud('documents', 'documents.', FitoutDocumentController::class, 'document', before: function () {
        Route::get('/{id}/review', [FitoutDocumentController::class, 'review'])->name('review');
        Route::post('/{id}/start-review', [FitoutDocumentController::class, 'startReview'])->name('start-review');
        Route::post('/{id}/approve', [FitoutDocumentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [FitoutDocumentController::class, 'reject'])->name('reject');
    });

    /* Approvals */
    R::crud('approvals', 'approvals.', FitoutApprovalController::class, 'id',
        only: ['index', 'show'],
        actions: ['approve', 'reject'],
        before: function () {
            Route::get('/pending', [FitoutApprovalController::class, 'pending'])->name('pending');
            Route::post('/generate/{fitoutRequestId}', [FitoutApprovalController::class, 'generate'])->name('generate');
        });

    /* Inspections */
    R::crud('inspections', 'inspections.', FitoutInspectionController::class, 'id',
        only: ['index', 'create', 'store', 'show', 'edit', 'update'],
        actions: ['start', 'complete', 'cancel', 'reschedule'],
        before: function () {
            Route::get('/{id}/reinspection', [FitoutInspectionController::class, 'createReinspection'])->name('reinspection.create');
            Route::post('/{id}/reinspection', [FitoutInspectionController::class, 'storeReinspection'])->name('reinspection.store');
        });

    /* Snags */
    R::crud('snags', 'snags.', SnagListController::class, 'snag', before: function () {
        Route::post('/{id}/resolve', [SnagListController::class, 'resolve'])->name('resolve');
        Route::post('/{id}/verify', [SnagListController::class, 'verify'])->name('verify');
        Route::post('/{id}/start-verification', [SnagListController::class, 'startVerification'])->name('start-verification');
    });

    /* Handover */
    R::crud('handovers', 'handovers.', HandoverController::class, 'handover',
        actions: ['schedule', 'start', 'tenant-accept', 'contractor-accept', 'approve', 'complete'],
        before: function () {
            Route::get('/{handover}/certificate', [HandoverController::class, 'certificate'])->name('certificate');
        });
});
