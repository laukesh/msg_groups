<?php

use App\Http\Controllers\Admin\Revenue\ChargeTypeController;
use App\Http\Controllers\Admin\Revenue\DepositController;
use App\Http\Controllers\Admin\Revenue\DepositReceiptController;
use App\Http\Controllers\Admin\Revenue\DepositRefundController;
use App\Http\Controllers\Admin\Revenue\InvoiceController;
use App\Http\Controllers\Admin\Revenue\OutstandingController;
use App\Http\Controllers\Admin\Revenue\ReconciliationController;
use App\Http\Controllers\Admin\Revenue\RentPaymentController;
use App\Http\Controllers\Admin\Revenue\RentScheduleController;
use App\Http\Controllers\Admin\Revenue\RevenueAuditLogController;
use App\Http\Controllers\Admin\Revenue\RevenueDashboardController;
use App\Http\Controllers\Admin\Revenue\RevenueReportController;
use App\Http\Controllers\Admin\Revenue\TaxConfigurationController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::prefix('revenue')->name('revenue.')->group(function () {

    $noCreateShow = ['index', 'store', 'edit', 'update', 'destroy'];

    /* Tax configurations */
    R::crud('tax-configurations', 'tax-configurations.', TaxConfigurationController::class, 'id', only: $noCreateShow);

    /* Deposits */
    R::crud('deposits', 'deposits.', DepositController::class, 'id', only: $noCreateShow);

    /* Deposit receipts */
    Route::get('deposits/{deposit}/receipts', [DepositReceiptController::class, 'index'])->name('deposits.receipts');
    R::crud(
        'deposit-receipts', 'deposit-receipts.', DepositReceiptController::class, 'id',
        only: $noCreateShow,
        actions: ['reverse'],
        before: function () {
            // NOTE: name kept exactly as in the old file (resolves to admin.revenue.deposit-receipts.admin.revenue.deposit-receipts.confirm)
            Route::post('/{id}/confirm', [DepositReceiptController::class, 'confirm'])
                ->name('admin.revenue.deposit-receipts.confirm');
        }
    );

    /* Deposit refunds */
    R::crud('deposit-refunds', 'deposit-refunds.', DepositRefundController::class, 'id',
        only: ['index', 'store'], actions: ['approve', 'process', 'cancel']);

    /* Rent schedules */
    Route::get('/rent-schedules', [RentScheduleController::class, 'index'])->name('rent-schedules.index');
    Route::post('/rent-schedules/generate/{agreementId}', [RentScheduleController::class, 'generate'])->name('rent-schedules.generate');
    Route::post('/rent-schedules/{id}/generate-invoice', [RentScheduleController::class, 'generateInvoice'])->name('rent-schedules.generate-invoice');
    Route::get('/rent-schedules/{id}', [RentScheduleController::class, 'show'])->name('rent-schedules.show');

    /* Invoices */
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/payment/create', [RentPaymentController::class, 'create'])->name('payments.create');
    Route::post('/invoices/{id}/payment', [RentPaymentController::class, 'store'])->name('payments.store');
    Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    /* Payments */
    Route::get('/payments', [RentPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [RentPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/confirm', [RentPaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{id}/reverse', [RentPaymentController::class, 'reverse'])->name('payments.reverse');
    Route::post('/payments/{id}/reconcile', [RentPaymentController::class, 'reconcile'])->name('payments.reconcile');
    Route::get('/payments/{id}/receipt', [RentPaymentController::class, 'receipt'])->name('payments.receipt');

    /* Dashboard / outstanding */
    Route::get('/dashboard', [RevenueDashboardController::class, 'index'])->name('dashboard');
    Route::get('/outstanding', [OutstandingController::class, 'index'])->name('outstanding.index');
    Route::get('/outstanding/overdue', [OutstandingController::class, 'overdue'])->name('outstanding.overdue');
    Route::get('/outstanding/tenants', [OutstandingController::class, 'tenants'])->name('outstanding.tenants');

    /* Reports */
    Route::get('/reports/revenue', [RevenueReportController::class, 'index'])->name('reports.revenue');
    Route::get('/reports/collections', [RevenueReportController::class, 'collections'])->name('reports.collections');
    Route::get('/reports/charge-wise', [RevenueReportController::class, 'chargeWise'])->name('reports.charge-wise');
    Route::get('/reports/tenant-wise', [RevenueReportController::class, 'tenantWise'])->name('reports.tenant-wise');
    Route::get('/reports/aging', [RevenueReportController::class, 'aging'])->name('reports.aging');

    /* Settings: charge types */
    R::crud('settings/charge-types', 'settings.charge-types.', ChargeTypeController::class, 'id', only: R::NO_SHOW);

    /* Reconciliation + audit */
    Route::get('reconciliation', [ReconciliationController::class, 'index'])->name('reconciliation.index');
    Route::post('reconciliation/{id}/reconcile', [ReconciliationController::class, 'reconcile'])->name('reconciliation.reconcile');
    Route::get('/audit-log', [RevenueAuditLogController::class, 'index'])->name('audit.index');
});
