<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositReceiptController extends Controller
{
    /**
     * Display receipts.
     */
    public function index($depositId = null)
    {
        $query = DepositReceipt::with('deposit.leaseAgreement')
            ->orderByDesc('id');

        if ($depositId) {
            $query->where('deposit_id', $depositId);
        }

        $receipts = $query->get();

        $deposits = Deposit::with('leaseAgreement')
            ->whereIn('payment_status', [
                'Pending',
                'Partial'
            ])
            ->where('balance_amount', '>', 0)
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.revenue.deposit_receipts.index',
            compact(
                'receipts',
                'deposits'
            )
        );
    }


    /**
     * Store receipt.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'deposit_id' => [
                'required',
                'exists:deposits,id',
            ],

            'receipt_date' => [
                'required',
                'date',
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_mode' => [
                'required',
                'in:Cash,Cheque,NEFT,RTGS,IMPS,UPI,Credit Card,Debit Card',
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


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Lock Deposit
            |--------------------------------------------------------------------------
            |
            | Prevent two users from receiving money against the
            | same remaining balance at the same time.
            |
            */

            $deposit = Deposit::where(
                'id',
                $validated['deposit_id']
            )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Receipt Amount Validation
            |--------------------------------------------------------------------------
            */

            $paymentAmount =
                (float) $validated['payment_amount'];

            $balanceAmount =
                (float) $deposit->balance_amount;


            /*
            |--------------------------------------------------------------------------
            | Prevent Overpayment
            |--------------------------------------------------------------------------
            */

            if (
                $paymentAmount > $balanceAmount
            ) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_amount' =>
                        'Payment amount cannot be greater than the remaining deposit balance of ₹'
                        . number_format(
                            $balanceAmount,
                            2
                        )
                        . '.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Receipt Number
            |--------------------------------------------------------------------------
            */

            $receiptNo =
                $this->generateReceiptNumber();


            /*
            |--------------------------------------------------------------------------
            | Create Receipt
            |--------------------------------------------------------------------------
            */

            $receipt = DepositReceipt::create([

                'uuid' =>
                    (string) Str::uuid(),

                'deposit_id' =>
                    $deposit->id,

                'receipt_no' =>
                    $receiptNo,

                'receipt_date' =>
                    $validated['receipt_date'],

                'payment_amount' =>
                    $paymentAmount,

                'payment_mode' =>
                    $validated['payment_mode'],

                'bank_name' =>
                    $validated['bank_name'] ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference']
                    ?? null,

                'payment_status' =>'Pending',

                'received_by' =>
                    Auth::id(),

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Deposit
            |--------------------------------------------------------------------------
            |
            | Only Confirmed receipts affect the deposit balance.
            |
            */
        });


        return redirect()
            ->route(
                'admin.revenue.deposit-receipts.index'
            )
            ->with(
                'success',
                'Deposit receipt created successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $receipt =
            DepositReceipt::with('deposit.leaseAgreement')
                ->findOrFail($id);

        return view(
            'admin.revenue.deposit_receipts.edit',
            compact('receipt')
        );
    }


    /**
     * Update receipt.
     */
    public function update(
        Request $request,
        $id
    ) {
        $receipt = DepositReceipt::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Only Pending Receipts Can Be Edited
        |--------------------------------------------------------------------------
        */

        if ($receipt->payment_status !== 'Pending') {

            return back()
                ->with(
                    'error',
                    'Only pending receipts can be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'receipt_date' => [
                'required',
                'date',
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_mode' => [
                'required',
                'in:Cash,Cheque,NEFT,RTGS,IMPS,UPI,Credit Card,Debit Card',
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


        /*
        |--------------------------------------------------------------------------
        | Update Receipt
        |--------------------------------------------------------------------------
        */

        $receipt->update([

            'receipt_date' =>
                $validated['receipt_date'],

            'payment_amount' =>
                $validated['payment_amount'],

            'payment_mode' =>
                $validated['payment_mode'],

            'bank_name' =>
                $validated['bank_name'] ?? null,

            'transaction_reference' =>
                $validated['transaction_reference'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Never allow edit form to change payment status.
            | Receipt remains Pending until Confirm action.
            |--------------------------------------------------------------------------
            */

            'payment_status' =>
                'Pending',

            'updated_by' =>
                Auth::id(),

            'remarks' =>
                $validated['remarks'] ?? null,
        ]);


        return redirect()
            ->route(
                'admin.revenue.deposit-receipts.index'
            )
            ->with(
                'success',
                'Deposit receipt updated successfully.'
            );
    }


    /**
     * Delete receipt.
     */
    public function destroy($id)
    {
        $receipt =
            DepositReceipt::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Confirmed Receipt Cannot Be Deleted
        |--------------------------------------------------------------------------
        */

        if (
            $receipt->payment_status === 'Confirmed'
        ) {

            return back()
                ->with(
                    'error',
                    'A confirmed receipt cannot be deleted. Use reversal workflow instead.'
                );
        }


        $receipt->updated_by = Auth::id();

        $receipt->save();

        $receipt->delete();


        return redirect()
            ->route(
                'admin.revenue.deposit-receipts.index'
            )
            ->with(
                'success',
                'Deposit receipt deleted successfully.'
            );
    }


    /**
     * Generate receipt number.
     */
    /*private function generateReceiptNumber(): string
    {
        $year = now()->format('Y');

        $lastReceipt =
            DepositReceipt::where(
                'receipt_no',
                'like',
                'DR-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if (!$lastReceipt) {

            $number = 1;

        } else {

            $lastNumber =
                (int) substr(
                    $lastReceipt->receipt_no,
                    -5
                );

            $number =
                $lastNumber + 1;
        }


        return sprintf(
            'DR-%s-%05d',
            $year,
            $number
        );
    }*/

    /*private function generateReceiptNumber(): string
{
    $year = now()->format('Y');

    $prefix = 'DR-' . $year . '-';

    $lastReceipt = DepositReceipt::where(
        'receipt_no',
        'like',
        $prefix . '%'
    )
    ->orderByRaw(
        "CAST(SUBSTRING_INDEX(receipt_no, '-', -1) AS UNSIGNED) DESC"
    )
    ->first();

    $lastNumber = 0;

    if ($lastReceipt) {
        $lastNumber = (int) substr(
            $lastReceipt->receipt_no,
            -5
        );
    }

    $nextNumber = $lastNumber + 1;

    $receiptNo = sprintf(
        '%s%05d',
        $prefix,
        $nextNumber
    );

    // Debug temporarily
    dd([
        'prefix' => $prefix,
        'last_receipt' => $lastReceipt?->receipt_no,
        'last_number' => $lastNumber,
        'next_number' => $nextNumber,
        'generated_receipt_no' => $receiptNo,
    ]);

    return $receiptNo;
}*/

private function generateReceiptNumber(): string
{
    $year = now()->format('Y');

    $prefix = 'DR-' . $year . '-';

    $lastReceipt = DepositReceipt::withTrashed()
        ->where('receipt_no', 'like', $prefix . '%')
        ->orderByRaw(
            "CAST(SUBSTRING_INDEX(receipt_no, '-', -1) AS UNSIGNED) DESC"
        )
        ->first();

    $lastNumber = 0;

    if ($lastReceipt) {
        $lastNumber = (int) substr(
            $lastReceipt->receipt_no,
            -5
        );
    }

    $nextNumber = $lastNumber + 1;

    return sprintf(
        '%s%05d',
        $prefix,
        $nextNumber
    );
}

    /**
	 * Reverse a confirmed receipt.
	*/
	public function reverse(Request $request, $id)
	{
	    $validated = $request->validate([
	        'reversal_remarks' => [
	            'required',
	            'string',
	            'max:1000',
	        ],
	    ]);


	    DB::transaction(function () use (
	        $validated,
	        $id
	    ) {

	        /*
	        |--------------------------------------------------------------------------
	        | Lock Receipt
	        |--------------------------------------------------------------------------
	        */

	        $receipt = DepositReceipt::where(
	            'id',
	            $id
	        )
	            ->lockForUpdate()
	            ->firstOrFail();


	        /*
	        |--------------------------------------------------------------------------
	        | Receipt Must Be Confirmed
	        |--------------------------------------------------------------------------
	        */

	        if (
	            $receipt->payment_status !== 'Confirmed'
	        ) {

	            throw \Illuminate\Validation\ValidationException::withMessages([
	                'receipt' =>
	                    'Only a confirmed receipt can be reversed.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Lock Deposit
	        |--------------------------------------------------------------------------
	        */

	        $deposit = Deposit::where(
	            'id',
	            $receipt->deposit_id
	        )
	            ->lockForUpdate()
	            ->firstOrFail();


	        /*
	        |--------------------------------------------------------------------------
	        | Calculate New Received Amount
	        |--------------------------------------------------------------------------
	        */

	        $newReceivedAmount =
	            (float) $deposit->received_amount
	            - (float) $receipt->payment_amount;


	        /*
	        |--------------------------------------------------------------------------
	        | Prevent Negative Value
	        |--------------------------------------------------------------------------
	        */

	        $newReceivedAmount =
	            max(
	                0,
	                $newReceivedAmount
	            );


	        /*
	        |--------------------------------------------------------------------------
	        | Calculate Balance
	        |--------------------------------------------------------------------------
	        */

	        $newBalanceAmount =
	            max(
	                0,
	                (float) $deposit->deposit_amount
	                - $newReceivedAmount
	            );


	        /*
	        |--------------------------------------------------------------------------
	        | Calculate Deposit Status
	        |--------------------------------------------------------------------------
	        */

	        if ($newReceivedAmount <= 0) {

	            $depositStatus = 'Pending';

	        } elseif ($newBalanceAmount <= 0) {

	            $depositStatus = 'Paid';

	        } else {

	            $depositStatus = 'Partial';
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Reverse Receipt
	        |--------------------------------------------------------------------------
	        */

	        $receipt->update([

	            'payment_status' =>
	                'Reversed',

	            'remarks' =>
	                trim(
	                    ($receipt->remarks
	                        ? $receipt->remarks . "\n"
	                        : '')
	                    . 'Reversal: '
	                    . $validated['reversal_remarks']
	                ),

	            'updated_by' =>
	                Auth::id(),
	        ]);


	        /*
	        |--------------------------------------------------------------------------
	        | Update Deposit
	        |--------------------------------------------------------------------------
	        */

	        $deposit->update([

	            'received_amount' =>
	                $newReceivedAmount,

	            'balance_amount' =>
	                $newBalanceAmount,

	            'payment_status' =>
	                $depositStatus,

	            'updated_by' =>
	                Auth::id(),
	        ]);
	    });


	    return redirect()
	        ->route(
	            'admin.revenue.deposit-receipts.index'
	        )
	        ->with(
	            'success',
	            'Deposit receipt reversed successfully.'
	        );
	}

    public function confirm($id)
    {
        DB::transaction(function () use ($id) {

            $receipt = DepositReceipt::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($receipt->payment_status !== 'Pending') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'receipt' => 'Only pending receipts can be confirmed.'
                ]);
            }

            $deposit = Deposit::where('id', $receipt->deposit_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Get already confirmed receipts
            $confirmedAmount = DepositReceipt::where(
                'deposit_id',
                $deposit->id
            )
            ->where('payment_status', 'Confirmed')
            ->sum('payment_amount');

            $remainingAmount =
                (float) $deposit->deposit_amount
                - (float) $confirmedAmount;

            // Prevent overpayment
            if ((float) $receipt->payment_amount > $remainingAmount) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'receipt' => sprintf(
                        'Cannot confirm this receipt. Remaining deposit balance is ₹%s, but this receipt is ₹%s.',
                        number_format($remainingAmount, 2),
                        number_format($receipt->payment_amount, 2)
                    )
                ]);
            }

            // Confirm receipt
            $receipt->update([
                'payment_status' => 'Confirmed',
                'updated_by' => Auth::id(),
            ]);

            // Recalculate deposit
            $this->syncDepositBalance($deposit);
        });

        return back()->with(
            'success',
            'Receipt confirmed and deposit balance updated successfully.'
        );
    }


    /*private function syncDepositBalance(Deposit $deposit): void
    {
        $confirmedAmount = DepositReceipt::where(
            'deposit_id',
            $deposit->id
        )
        ->where(
            'payment_status',
            'Confirmed'
        )
        ->sum('payment_amount');

        $confirmedAmount = (float) $confirmedAmount;

        $depositAmount = (float) $deposit->deposit_amount;

        $balanceAmount = max(
            0,
            $depositAmount - $confirmedAmount
        );

        if ($confirmedAmount <= 0) {

            $paymentStatus = 'Pending';

        } elseif ($balanceAmount > 0) {

            $paymentStatus = 'Partial';

        } else {

            $paymentStatus = 'Paid';
        }

        $deposit->update([
            'received_amount' => $confirmedAmount,
            'balance_amount' => $balanceAmount,
            'payment_status' => $paymentStatus,
            'updated_by' => Auth::id(),
        ]);
    }*/

    private function syncDepositBalance(Deposit $deposit): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Confirmed Receipts
        |--------------------------------------------------------------------------
        */

        $confirmedAmount = DepositReceipt::where(
            'deposit_id',
            $deposit->id
        )
        ->where(
            'payment_status',
            'Confirmed'
        )
        ->sum('payment_amount');

        $confirmedAmount = round(
            (float) $confirmedAmount,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Deposit Amount
        |--------------------------------------------------------------------------
        */

        $depositAmount = round(
            (float) $deposit->deposit_amount,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Balance
        |--------------------------------------------------------------------------
        */

        $balanceAmount = max(
            0,
            round(
                $depositAmount - $confirmedAmount,
                2
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Determine Payment Status
        |--------------------------------------------------------------------------
        */

        if ($confirmedAmount <= 0) {

            $paymentStatus = 'Pending';

        } elseif ($balanceAmount > 0) {

            $paymentStatus = 'Partial';

        } else {

            $paymentStatus = 'Paid';
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Refundable Amount
        |--------------------------------------------------------------------------
        |
        | Only a fully paid deposit becomes refundable.
        |
        */

        if ($paymentStatus === 'Paid') {

            $refundableAmount = $depositAmount;

        } else {

            $refundableAmount = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Deposit
        |--------------------------------------------------------------------------
        */

        $deposit->update([

            'received_amount' =>
                $confirmedAmount,

            'balance_amount' =>
                $balanceAmount,

            'payment_status' =>
                $paymentStatus,

            'refundable_amount' =>
                $refundableAmount,

            'updated_by' =>
                Auth::id(),

        ]);
    }

}

