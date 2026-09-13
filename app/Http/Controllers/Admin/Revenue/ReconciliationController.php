<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\RevenueAuditService;

class ReconciliationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = RentPayment::query()
            ->with([
                'tenant',
                'invoice'
            ])
            ->where(
                'payment_status',
                'Confirmed'
            );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'payment_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'tenant',
                    function ($tenantQuery) use ($search) {

                        $tenantQuery
                            ->where(
                                'company_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'tenant_code',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Reconciliation Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'reconciliation_status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'payment_date',
                '>=',
                $request->from_date
            );
        }


        if ($request->filled('to_date')) {

            $query->whereDate(
                'payment_date',
                '<=',
                $request->to_date
            );
        }


        $payments = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $pendingQuery = RentPayment::where(
            'payment_status',
            'Confirmed'
        )
        ->where(
            'reconciliation_status',
            'Pending'
        );

        $reconciledQuery = RentPayment::where(
            'payment_status',
            'Confirmed'
        )
        ->where(
            'reconciliation_status',
            'Reconciled'
        );


        $pendingAmount = $pendingQuery->sum(
            'payment_amount'
        );

        $reconciledAmount = $reconciledQuery->sum(
            'payment_amount'
        );

        $pendingCount = $pendingQuery->count();

        $reconciledCount = $reconciledQuery->count();


        return view(
            'admin.revenue.reconciliation.index',
            compact(
                'payments',
                'pendingAmount',
                'reconciledAmount',
                'pendingCount',
                'reconciledCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECONCILE
    |--------------------------------------------------------------------------
    */

    public function reconcile($id)
    {
        DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | 1. Lock Payment
            |--------------------------------------------------------------------------
            */

            $payment = RentPayment::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | 2. Payment Must Be Confirmed
            |--------------------------------------------------------------------------
            */

            if ($payment->payment_status !== 'Confirmed') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' =>
                        'Only confirmed payments can be reconciled.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 3. Payment Must Not Already Be Reconciled
            |--------------------------------------------------------------------------
            */

            if ($payment->reconciliation_status === 'Reconciled') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' =>
                        'Payment is already reconciled.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 4. Payment Must Have Invoice
            |--------------------------------------------------------------------------
            */

            if (!$payment->invoice_id) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' =>
                        'This payment is not linked to an invoice.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 5. Verify Active Payment Allocation
            |--------------------------------------------------------------------------
            */

            $allocation = \App\Models\PaymentAllocation::where(
                'payment_id',
                $payment->id
            )
            ->where(
                'invoice_id',
                $payment->invoice_id
            )
            ->where(
                'allocation_status',
                'Allocated'
            )
            ->lockForUpdate()
            ->first();


            if (!$allocation) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' =>
                        'No active payment allocation found for this payment.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 6. Lock Invoice
            |--------------------------------------------------------------------------
            */

            $invoice = \App\Models\Invoice::where(
                'id',
                $payment->invoice_id
            )
            ->lockForUpdate()
            ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | 7. Validate Allocation Amount
            |--------------------------------------------------------------------------
            */

            if (
                (float) $allocation->allocated_amount
                <= 0
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment' =>
                        'Payment allocation amount must be greater than zero.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 8. Reconciliation
            |--------------------------------------------------------------------------
            */

            $oldStatus = $payment->reconciliation_status;


            $payment->update([

                'reconciliation_status' => 'Reconciled',

                'updated_by' => Auth::id(),

                'updated_at' => now(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | 9. Audit Log
            |--------------------------------------------------------------------------
            */

            RevenueAuditService::log(
                'Reconciliation',
                'Reconciled',
                'RentPayment',
                $payment->id,
                'Payment reconciled successfully.',
                [
                    'reconciliation_status' => $oldStatus,
                ],
                [
                    'reconciliation_status' => 'Reconciled',
                ]
            );
        });


        /*
        |--------------------------------------------------------------------------
        | 10. Redirect
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Payment reconciled successfully.'
        );
    }
}