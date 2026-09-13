<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositRefundController extends Controller
{
    /**
     * Display refund records.
     */
    public function index()
    {
        $refunds = DepositRefund::with([
            'deposit.leaseAgreement'
        ])
            ->orderByDesc('id')
            ->get();

        $deposits = Deposit::with('leaseAgreement')
            ->where('payment_status', 'Paid')
            ->where('refundable_amount', '>', 0)
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.revenue.deposit_refunds.index',
            compact(
                'refunds',
                'deposits'
            )
        );
    }


    /**
     * Create refund.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'deposit_id' => [
                'required',
                'exists:deposits,id',
            ],

            'refund_date' => [
                'required',
                'date',
            ],

            'outstanding_rent' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cam_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'utility_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'damage_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'penalty_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_mode' => [
                'nullable',
                'in:Cash,Cheque,NEFT,RTGS,IMPS,UPI',
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use (
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Deposit
            |--------------------------------------------------------------------------
            */

            $deposit = Deposit::where(
                'id',
                $validated['deposit_id']
            )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Deposit Must Be Fully Paid
            |--------------------------------------------------------------------------
            */

            if (
                $deposit->payment_status !== 'Paid'
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'deposit_id' =>
                        'Refund can only be created after the deposit has been fully received.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Original Deposit
            |--------------------------------------------------------------------------
            */

            $originalDeposit =
                (float) $deposit->deposit_amount;


            /*
            |--------------------------------------------------------------------------
            | Deductions
            |--------------------------------------------------------------------------
            */

            $outstandingRent =
                (float) (
                    $validated['outstanding_rent']
                    ?? 0
                );

            $camDeduction =
                (float) (
                    $validated['cam_deduction']
                    ?? 0
                );

            $utilityDeduction =
                (float) (
                    $validated['utility_deduction']
                    ?? 0
                );

            $damageDeduction =
                (float) (
                    $validated['damage_deduction']
                    ?? 0
                );

            $penaltyDeduction =
                (float) (
                    $validated['penalty_deduction']
                    ?? 0
                );

            $otherDeduction =
                (float) (
                    $validated['other_deduction']
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | Total Deduction
            |--------------------------------------------------------------------------
            */

            $totalDeduction =
                $outstandingRent
                + $camDeduction
                + $utilityDeduction
                + $damageDeduction
                + $penaltyDeduction
                + $otherDeduction;


            /*
            |--------------------------------------------------------------------------
            | Deduction Cannot Exceed Deposit
            |--------------------------------------------------------------------------
            */

            if (
                $totalDeduction > $originalDeposit
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'outstanding_rent' =>
                        'Total deductions cannot exceed the original deposit amount of ₹'
                        . number_format(
                            $originalDeposit,
                            2
                        )
                        . '.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Refund Amount
            |--------------------------------------------------------------------------
            */

            $refundAmount =
                $originalDeposit
                - $totalDeduction;


            /*
            |--------------------------------------------------------------------------
            | Check Already Processed Refunds
            |--------------------------------------------------------------------------
            */

            $processedRefundAmount =
                DepositRefund::where(
                    'deposit_id',
                    $deposit->id
                )
                ->where(
                    'refund_status',
                    'Processed'
                )
                ->sum('refund_amount');


            /*
            |--------------------------------------------------------------------------
            | Remaining Refundable Amount
            |--------------------------------------------------------------------------
            */

            $remainingRefundable =
                max(
                    0,
                    (float) $deposit->refundable_amount
                    - (float) $processedRefundAmount
                );


            /*
            |--------------------------------------------------------------------------
            | Prevent Excess Refund
            |--------------------------------------------------------------------------
            */

            if (
                $refundAmount > $remainingRefundable
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'deposit_id' =>
                        'Refund amount of ₹'
                        . number_format(
                            $refundAmount,
                            2
                        )
                        . ' exceeds the remaining refundable amount of ₹'
                        . number_format(
                            $remainingRefundable,
                            2
                        )
                        . '.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Refund Number
            |--------------------------------------------------------------------------
            */

            $refundNo =
                $this->generateRefundNumber();


            /*
            |--------------------------------------------------------------------------
            | Create Refund
            |--------------------------------------------------------------------------
            */

            DepositRefund::create([

                'uuid' =>
                    (string) Str::uuid(),

                'deposit_id' =>
                    $deposit->id,

                'refund_no' =>
                    $refundNo,

                'refund_date' =>
                    $validated['refund_date'],

                'original_deposit' =>
                    $originalDeposit,

                'outstanding_rent' =>
                    $outstandingRent,

                'cam_deduction' =>
                    $camDeduction,

                'utility_deduction' =>
                    $utilityDeduction,

                'damage_deduction' =>
                    $damageDeduction,

                'penalty_deduction' =>
                    $penaltyDeduction,

                'other_deduction' =>
                    $otherDeduction,

                'total_deduction' =>
                    $totalDeduction,

                'refund_amount' =>
                    $refundAmount,

                'payment_mode' =>
                    $validated['payment_mode']
                    ?? null,

                'bank_name' =>
                    $validated['bank_name']
                    ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference']
                    ?? null,

                'refund_status' =>
                    'Pending',

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.revenue.deposit-refunds.index'
            )
            ->with(
                'success',
                'Deposit refund created successfully and is pending approval.'
            );
    }


    /**
     * Approve refund.
     */
    public function approve($id)
    {
        DB::transaction(function () use ($id) {

            $refund =
                DepositRefund::where(
                    'id',
                    $id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Only Pending Refund Can Be Approved
            |--------------------------------------------------------------------------
            */

            if (
                $refund->refund_status !== 'Pending'
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'refund' =>
                        'Only pending refunds can be approved.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Lock Deposit
            |--------------------------------------------------------------------------
            */

            $deposit =
                Deposit::where(
                    'id',
                    $refund->deposit_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Recheck Refundable Amount
            |--------------------------------------------------------------------------
            |
            | This is important because another refund could have
            | been processed after this refund was created.
            |
            */

            $processedRefundAmount =
                DepositRefund::where(
                    'deposit_id',
                    $deposit->id
                )
                ->where(
                    'refund_status',
                    'Processed'
                )
                ->sum('refund_amount');


            $remainingRefundable =
                max(
                    0,
                    (float) $deposit->refundable_amount
                    - (float) $processedRefundAmount
                );


            if (
                (float) $refund->refund_amount
                > $remainingRefundable
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'refund' =>
                        'This refund exceeds the currently available refundable amount.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Approve
            |--------------------------------------------------------------------------
            */

            $refund->update([

                'refund_status' =>
                    'Approved',

                'approved_by' =>
                    Auth::id(),

                'approved_at' =>
                    now(),

                'updated_by' =>
                    Auth::id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.revenue.deposit-refunds.index'
            )
            ->with(
                'success',
                'Refund approved successfully.'
            );
    }


    /**
     * Process refund payment.
     */
    public function process(Request $request, $id)
    {
        $validated = $request->validate([

            'payment_mode' => [
                'required',
                'in:Cash,Cheque,NEFT,RTGS,IMPS,UPI',
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $id
        ) {

            $refund =
                DepositRefund::where(
                    'id',
                    $id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Only Approved Refund Can Be Processed
            |--------------------------------------------------------------------------
            */

            if (
                $refund->refund_status !== 'Approved'
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'refund' =>
                        'Only approved refunds can be processed.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Lock Deposit
            |--------------------------------------------------------------------------
            */

            $deposit =
                Deposit::where(
                    'id',
                    $refund->deposit_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Recheck Refundable Amount
            |--------------------------------------------------------------------------
            */

            $processedRefundAmount =
                DepositRefund::where(
                    'deposit_id',
                    $deposit->id
                )
                ->where(
                    'refund_status',
                    'Processed'
                )
                ->sum('refund_amount');


            $remainingRefundable =
                max(
                    0,
                    (float) $deposit->refundable_amount
                    - (float) $processedRefundAmount
                );


            if (
                (float) $refund->refund_amount
                > $remainingRefundable
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'refund' =>
                        'Refund amount exceeds the remaining refundable amount.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Process Refund
            |--------------------------------------------------------------------------
            */

            /*$refund->update([

                'payment_mode' =>
                    $validated['payment_mode'],

                'bank_name' =>
                    $validated['bank_name']
                    ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference']
                    ?? null,

                'refund_status' =>
                    'Processed',

                'remarks' =>
                    $validated['remarks']
                    ?? $refund->remarks,

                'updated_by' =>
                    Auth::id(),
            ]);*/

            $newRefundableAmount = max( 0,  (float) $deposit->refundable_amount  - (float) $refund->refund_amount );

            $deposit->update([

                'refundable_amount' =>
                    round($newRefundableAmount, 2),

                'updated_by' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Process Refund
            |--------------------------------------------------------------------------
            */

            $refund->update([

                'payment_mode' =>
                    $validated['payment_mode'],

                'bank_name' =>
                    $validated['bank_name'] ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference'] ?? null,

                'refund_status' =>
                    'Processed',

                'remarks' =>
                    $validated['remarks']
                    ?? $refund->remarks,

                'updated_by' =>
                    Auth::id(),

            ]);


        });


        return redirect()
            ->route(
                'admin.revenue.deposit-refunds.index'
            )
            ->with(
                'success',
                'Refund processed successfully.'
            );
    }


    /**
     * Cancel refund.
     */
    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([

            'remarks' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        $refund =
            DepositRefund::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Pending / Approved Can Be Cancelled
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $refund->refund_status,
                [
                    'Pending',
                    'Approved'
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'Only pending or approved refunds can be cancelled.'
            );
        }


        $refund->update([

            'refund_status' =>
                'Cancelled',

            'remarks' =>
                trim(
                    ($refund->remarks
                        ? $refund->remarks . "\n"
                        : '')
                    . 'Cancellation: '
                    . $validated['remarks']
                ),

            'updated_by' =>
                Auth::id(),
        ]);


        return redirect()
            ->route(
                'admin.revenue.deposit-refunds.index'
            )
            ->with(
                'success',
                'Refund cancelled successfully.'
            );
    }


    /**
     * Generate refund number.
     */
    private function generateRefundNumber(): string
    {
        $year = now()->format('Y');

        $lastRefund =
            DepositRefund::where(
                'refund_no',
                'like',
                'RF-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if (!$lastRefund) {

            $number = 1;

        } else {

            $lastNumber =
                (int) substr(
                    $lastRefund->refund_no,
                    -5
                );

            $number =
                $lastNumber + 1;
        }


        return sprintf(
            'RF-%s-%05d',
            $year,
            $number
        );
    }

}
