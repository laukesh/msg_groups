<?php

use App\Http\Controllers\Admin\Handover\HandoverAssetHandoverController;
use App\Http\Controllers\Admin\Handover\HandoverCertificateController;
use App\Http\Controllers\Admin\Handover\HandoverController as ProjectHandoverController;
use App\Http\Controllers\Admin\Handover\HandoverDefectController;
use App\Http\Controllers\Admin\Handover\HandoverDocumentController;
use App\Http\Controllers\Admin\Handover\HandoverFinalAccountController;
use App\Http\Controllers\Admin\Handover\HandoverFinalCompletionController;
use App\Http\Controllers\Admin\Handover\HandoverFinalPaymentController;
use App\Http\Controllers\Admin\Handover\HandoverManagementController;
use App\Http\Controllers\Admin\Handover\HandoverPracticalCompletionController;
use App\Http\Controllers\Admin\Handover\HandoverRequirementController;
use App\Http\Controllers\Admin\Handover\HandoverSnagController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

/* Handover overview */
Route::prefix('handover')->name('handover.')->group(function () {
    Route::get('/', [HandoverManagementController::class, 'index'])->name('index');
    Route::post('projects/{project}/start', [HandoverManagementController::class, 'start'])->name('projects.start');
});

$base = 'projects/{project}/handover';
$name = 'projects.handover.';

Route::get($base, [ProjectHandoverController::class, 'index'])->name("{$name}index");

$edit = ['index', 'store', 'update', 'destroy'];

R::crud("$base/requirements", "{$name}requirements.", HandoverRequirementController::class, 'requirement',
    only: $edit, actions: ['start', 'submit', 'review', 'complete', 'reject', 'waive']);

R::crud("$base/snags", "{$name}snags.", HandoverSnagController::class, 'snag',
    only: $edit, actions: ['assign', 'start', 'submit-rectification', 'verify', 'reject', 'close']);

R::crud("$base/documents", "{$name}documents.", HandoverDocumentController::class, 'document',
    only: $edit, actions: ['submit', 'review', 'approve', 'reject', 'archive']);

R::crud("$base/defects", "{$name}defects.", HandoverDefectController::class, 'defect',
    only: $edit, actions: ['assign', 'start', 'submit-rectification', 'verify', 'reject', 'close']);

foreach (['practical-completion' => HandoverPracticalCompletionController::class,
          'final-completion'     => HandoverFinalCompletionController::class] as $slug => $controller) {
    R::crud("$base/$slug", "$name$slug.", $controller, 'completion',
        only: ['index', 'update'], actions: ['submit', 'review', 'approve', 'reject']);
}

/* Final account (reject form + rejection URIs differ from the generic pattern) */
R::crud("$base/final-account", "{$name}final-account.", HandoverFinalAccountController::class, 'account',
    only: ['index', 'create', 'store', 'show', 'edit', 'update'],
    actions: ['submit', 'review', 'approve'],
    before: function () {
        Route::get('/{account}/payments', [HandoverFinalAccountController::class, 'payments'])->name('payments');
        Route::get('/{account}/reject', [HandoverFinalAccountController::class, 'rejectForm'])->name('reject.form');
        Route::post('/{account}/rejection', [HandoverFinalAccountController::class, 'reject'])->name('reject');
    });

/* Final payment */
Route::prefix("$base/final-payment")->name("{$name}final-payment.")->group(function () {
    $c = HandoverFinalPaymentController::class;
    Route::get('/', [$c, 'index'])->name('index');
    Route::post('/{finalAccount}/store', [$c, 'store'])->name('store');
    Route::get('/payment/{payment}', [$c, 'show'])->name('show');
    Route::put('/payment/{payment}', [$c, 'update'])->name('update');
    foreach (['submit', 'review', 'approve', 'paid', 'reject', 'cancel'] as $a) {
        Route::post("/payment/{payment}/$a", [$c, $a])->name($a);
    }
});

R::crud("$base/certificates", "{$name}certificates.", HandoverCertificateController::class, 'certificate',
    only: ['index', 'store', 'update'],
    actions: ['submit', 'review', 'approve', 'reject', 'cancel']);

/* Asset handover + items */
R::crud("$base/asset-handover", "{$name}asset-handover.", HandoverAssetHandoverController::class, 'assetHandover',
    only: ['index', 'store', 'update'],
    actions: ['submit', 'review', 'approve', 'reject'],
    before: function () {
        $c = HandoverAssetHandoverController::class;
        Route::post('/{assetHandover}/items', [$c, 'addItem'])->name('items.store');
        Route::put('/{assetHandover}/items/{item}', [$c, 'updateItem'])->name('items.update');
        Route::post('/{assetHandover}/items/{item}/verify', [$c, 'verifyItem'])->name('items.verify');
        Route::post('/{assetHandover}/items/{item}/accept', [$c, 'acceptItem'])->name('items.accept');
        Route::post('/{assetHandover}/items/{item}/reject', [$c, 'rejectItem'])->name('items.reject');
        Route::delete('/{assetHandover}/items/{item}', [$c, 'removeItem'])->name('items.destroy');
    });
