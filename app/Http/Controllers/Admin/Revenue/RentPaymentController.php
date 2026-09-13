<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use App\Models\PaymentAllocation;
use App\Models\Invoice;
use App\Models\RentSchedule;
use App\Models\InvoiceItemAllocation;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\RevenueAuditService;

class RentPaymentController extends Controller
{
	/**
     * Payment List
     */
    public function index(Request $request)
	{
	    $query = RentPayment::with([
	        'tenant',
	        'invoice',
	    ]);

	    // Payment No.
	    if ($request->filled('payment_no')) {
	        $query->where(
	            'payment_no',
	            'like',
	            '%' . $request->payment_no . '%'
	        );
	    }

	    // Tenant
	    if ($request->filled('tenant_id')) {
	        $query->where(
	            'tenant_id',
	            $request->tenant_id
	        );
	    }

	    // Payment Status
	    if ($request->filled('payment_status')) {
	        $query->where(
	            'payment_status',
	            $request->payment_status
	        );
	    }

	    // Reconciliation Status
	    if ($request->filled('reconciliation_status')) {
	        $query->where(
	            'reconciliation_status',
	            $request->reconciliation_status
	        );
	    }

	    // Payment Mode
	    if ($request->filled('payment_mode')) {
	        $query->where(
	            'payment_mode',
	            $request->payment_mode
	        );
	    }

	    // Date From
	    if ($request->filled('date_from')) {
	        $query->whereDate(
	            'payment_date',
	            '>=',
	            $request->date_from
	        );
	    }

	    // Date To
	    if ($request->filled('date_to')) {
	        $query->whereDate(
	            'payment_date',
	            '<=',
	            $request->date_to
	        );
	    }

	    if ($request->filled('invoice_id')) {

	        $query->where(
	            'invoice_id',
	            $request->invoice_id
	        );

	    }

	    $payments = $query
	        ->orderByDesc('id')
	        ->paginate(20)
	        ->withQueryString();

	    $tenants = Tenant::orderBy('company_name')->get();

	    return view(
	        'admin.revenue.payments.index',
	        compact(
	            'payments',
	            'tenants'
	        )
	    );
	}

    public function create($id)
    {
        $invoice = Invoice::with([
            'tenant',
            'leaseAgreement',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Payment validation
        |--------------------------------------------------------------------------
        */

        if (in_array($invoice->invoice_status, [
            'Draft',
            'Cancelled',
        ])) {

            return back()->with(
                'error',
                'Payment cannot be received for this invoice.'
            );
        }


        if ($invoice->balance_amount <= 0) {

            return back()->with(
                'error',
                'This invoice has no outstanding balance.'
            );
        }


        return view(
            'admin.revenue.payments.create',
            compact('invoice')
        );
    }

    public function store(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);


        $validated = $request->validate([

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $invoice->balance_amount,
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

            'cheque_no' => [
                'nullable',
                'string',
                'max:50',
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
        | Generate Payment Number
        |--------------------------------------------------------------------------
        */

        $year = now()->format('Y');

        $lastPayment = RentPayment::where(
            'payment_no',
            'like',
            'RP-' . $year . '-%'
        )
        ->orderByDesc('id')
        ->first();


        $lastNumber = 0;

        if ($lastPayment) {

            $lastNumber = (int) substr(
                $lastPayment->payment_no,
                -5
            );
        }


        $paymentNo = 'RP-' .
            $year .
            '-' .
            str_pad(
                $lastNumber + 1,
                5,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create Payment
        |--------------------------------------------------------------------------
        */

        $payment = RentPayment::create([

            'uuid' => Str::uuid(),

            'payment_no' => $paymentNo,

            'tenant_id' => $invoice->tenant_id,

            'invoice_id' => $invoice->id,

            'payment_date' => $validated['payment_date'],

            'payment_amount' => $validated['payment_amount'],

            'payment_mode' => $validated['payment_mode'],

            'bank_name' => $validated['bank_name'] ?? null,

            'cheque_no' => $validated['cheque_no'] ?? null,

            'transaction_reference' => $validated['transaction_reference'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Important
            |--------------------------------------------------------------------------
            | Payment is initially Pending.
            | Invoice is NOT updated here.
            |--------------------------------------------------------------------------
            */

            'payment_status' => 'Pending',

            'reconciliation_status' => 'Pending',

            'received_by' => Auth::id(),

            'remarks' => $validated['remarks'] ?? null,

            'created_by' => Auth::id(),

            'updated_by' => Auth::id(),

        ]);

        /*RevenueAuditService::log(
		    'Payments',
		    'Allocated',
		    'RentPayment',
		    $payment->id,
		    'Payment allocated against invoice.',
		    null,
		    [
		        'invoice_id' => $invoice->id,
		        'allocated_amount' => $payment->payment_amount,
		    ]
		);*/


        return redirect()
            ->route(
                'admin.revenue.invoices.show',
                $invoice->id
            )
            ->with(
                'success',
                'Payment created successfully and is pending confirmation.'
            );
    }

	/**
     * Payment Details
     */
    /*public function show($id)
    {
        $payment = RentPayment::with([
            'tenant',
            'invoice',
            'allocations',
        ])->findOrFail($id);


        return view(
            'admin.revenue.payments.show',
            compact('payment')
        );
    }*/
    public function show($id)
	{
	    $payment = RentPayment::with([
	        'invoice',
	        'tenant',
	        'allocations.invoice',
	    ])->findOrFail($id);

	    return view(
	        'admin.revenue.payments.show',
	        compact('payment')
	    );
	}

	/*public function confirm($id)
	{
	    DB::transaction(function () use ($id) {

	        $payment = RentPayment::where('id', $id)
	            ->lockForUpdate()
	            ->firstOrFail();

	        if ($payment->payment_status !== 'Pending') {
	            throw ValidationException::withMessages([
	                'payment' =>
	                    'Only pending payments can be confirmed.'
	            ]);
	        }

	        $payment->update([
	            'payment_status' => 'Confirmed',
	            'updated_by' => Auth::id(),
	        ]);
	    });

	    return back()->with(
	        'success',
	        'Payment confirmed successfully.'
	    );
	}*/

	public function confirm($id)
	{
	    DB::transaction(function () use ($id) {

	        // Lock payment so it cannot be confirmed twice
	        $payment = RentPayment::where('id', $id)
	            ->lockForUpdate()
	            ->firstOrFail();

	        // Only Pending payments can be confirmed
	        if ($payment->payment_status !== 'Pending') {
	            throw ValidationException::withMessages([
	                'payment' => 'Only pending payments can be confirmed.'
	            ]);
	        }

	        // Payment must have an invoice
	        if (!$payment->invoice_id) {
	            throw ValidationException::withMessages([
	                'payment' => 'This payment is not linked to an invoice.'
	            ]);
	        }

	        // Lock invoice
	        $invoice = Invoice::where('id', $payment->invoice_id)
	            ->lockForUpdate()
	            ->firstOrFail();

	        // Make sure payment does not exceed invoice balance
	        if ($payment->payment_amount > $invoice->balance_amount) {
	            throw ValidationException::withMessages([
	                'payment' => 'Payment amount cannot exceed invoice balance.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | 1. Confirm Payment
	        |--------------------------------------------------------------------------
	        */

	        $payment->update([
	            'payment_status' => 'Confirmed',
	            'updated_by' => Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | 2. Create Payment Allocation
	        |--------------------------------------------------------------------------
	        */

	       $paymentAllocation = PaymentAllocation::create([
	            'uuid' => (string) Str::uuid(),
	            'payment_id' => $payment->id,
	            'invoice_id' => $invoice->id,
	            'allocation_date' => now()->toDateString(),
	            'allocated_amount' => $payment->payment_amount,
	            'allocation_status' => 'Allocated',
	            'remarks' => 'Payment allocated to invoice.',
	            'created_by' => Auth::id(),
	            'updated_by' => Auth::id(),
	        ]);

	        $this->allocatePaymentToInvoiceItems(
			    $paymentAllocation
			);

			RevenueAuditService::log(
			    'Payments',
			    'Allocated',
			    'RentPayment',
			    $payment->id,
			    'Payment allocated against invoice.',
			    null,
			    [
			        'invoice_id' => $invoice->id,
			        'allocation_id' => $paymentAllocation->id,
			        'allocated_amount' => $paymentAllocation->allocated_amount,
			    ]
			);

	        /*
	        |--------------------------------------------------------------------------
	        | 3. Update Invoice
	        |--------------------------------------------------------------------------
	        */

	        $paidAmount = (float) $invoice->paid_amount + (float) $payment->payment_amount;

	        $balanceAmount = (float) $invoice->total_amount - $paidAmount;

	        // Avoid negative balance
	        $balanceAmount = max(0, $balanceAmount);

	        /*
	        |--------------------------------------------------------------------------
	        | 4. Determine Invoice Status
	        |--------------------------------------------------------------------------
	        */

	        if ($balanceAmount == 0) {

	            $invoiceStatus = 'Paid';

	        } elseif ($paidAmount > 0) {

	            $invoiceStatus = 'Partially Paid';

	        } else {

	            $invoiceStatus = 'Generated';
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | 5. Save Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice->update([
	            'paid_amount' => $paidAmount,
	            'balance_amount' => $balanceAmount,
	            'invoice_status' => $invoiceStatus,
	            'updated_by' => Auth::id(),
	        ]);

	        /*
			|--------------------------------------------------------------------------
			| Update Rent Schedule Payment Status
			|--------------------------------------------------------------------------
			*/

			$rentSchedule = RentSchedule::where(
			    'invoice_id',
			    $invoice->id
			)
			->lockForUpdate()
			->first();

			if ($rentSchedule) {

			    if ($invoiceStatus === 'Paid') {

			        $schedulePaymentStatus = 'Paid';

			    } elseif ($paidAmount > 0) {

			        $schedulePaymentStatus = 'Partial';

			    } else {

			        $schedulePaymentStatus = 'Pending';
			    }

			    $rentSchedule->update([
			        'payment_status' => $schedulePaymentStatus,
			        'updated_by' => Auth::id(),
			    ]);
			}



	    });

	    return redirect()
	        ->back()
	        ->with(
	            'success',
	            'Payment confirmed and invoice balance updated successfully.'
	        );
	}

	public function reverse($id)
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
	        | 2. Only Confirmed Payments Can Be Reversed
	        |--------------------------------------------------------------------------
	        */

	        if ($payment->payment_status !== 'Confirmed') {

	            throw ValidationException::withMessages([
	                'payment' => 'Only confirmed payments can be reversed.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | 3. Payment Must Have Invoice
	        |--------------------------------------------------------------------------
	        */

	        if (!$payment->invoice_id) {

	            throw ValidationException::withMessages([
	                'payment' => 'This payment is not linked to an invoice.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | 4. Lock Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice = Invoice::where('id', $payment->invoice_id)
	            ->lockForUpdate()
	            ->firstOrFail();


	        /*
	        |--------------------------------------------------------------------------
	        | 5. Find Active Payment Allocation
	        |--------------------------------------------------------------------------
	        */

	        $allocation = PaymentAllocation::where(
	            'payment_id',
	            $payment->id
	        )
	        ->where(
	            'allocation_status',
	            'Allocated'
	        )
	        ->lockForUpdate()
	        ->first();


	        if (!$allocation) {

	            throw ValidationException::withMessages([
	                'payment' => 'No active payment allocation found.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | 6. Store Old Values For Audit
	        |--------------------------------------------------------------------------
	        */

	        $oldPaymentStatus = $payment->payment_status;

	        $oldAllocationStatus = $allocation->allocation_status;

	        $oldPaidAmount = (float) $invoice->paid_amount;

	        $oldBalanceAmount = (float) $invoice->balance_amount;

	        $oldInvoiceStatus = $invoice->invoice_status;


	        /*
	        |--------------------------------------------------------------------------
	        | 7. Reverse Payment Allocation
	        |--------------------------------------------------------------------------
	        |
	        | This will:
	        | - Reverse invoice item allocations
	        | - Reverse payment allocation
	        | - Recalculate invoice
	        |
	        */

	        $this->reversePaymentAllocation($allocation);


	        /*
	        |--------------------------------------------------------------------------
	        | 8. Refresh Invoice
	        |--------------------------------------------------------------------------
	        |
	        | reversePaymentAllocation() has recalculated the invoice.
	        |
	        */

	        $invoice->refresh();


	        /*
	        |--------------------------------------------------------------------------
	        | 9. Mark Payment As Reversed
	        |--------------------------------------------------------------------------
	        */

	        $payment->update([
	            'payment_status' => 'Reversed',
	            'updated_by' => Auth::id(),
	            'updated_at' => now(),
	        ]);


	        /*
	        |--------------------------------------------------------------------------
	        | 10. Update Rent Schedule
	        |--------------------------------------------------------------------------
	        */

	        $rentSchedule = RentSchedule::where(
	            'invoice_id',
	            $invoice->id
	        )
	        ->lockForUpdate()
	        ->first();


	        if ($rentSchedule) {

	            if ($invoice->invoice_status === 'Paid') {

	                $schedulePaymentStatus = 'Paid';

	            } elseif ((float) $invoice->paid_amount > 0) {

	                $schedulePaymentStatus = 'Partial';

	            } else {

	                $schedulePaymentStatus = 'Pending';
	            }


	            $rentSchedule->update([
	                'payment_status' => $schedulePaymentStatus,
	                'updated_by' => Auth::id(),
	                'updated_at' => now(),
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | 11. Revenue Audit
	        |--------------------------------------------------------------------------
	        */

	        RevenueAuditService::log(
	            'Payments',
	            'Reversed',
	            'RentPayment',
	            $payment->id,
	            'Payment reversed and invoice balance restored.',
	            [
	                'payment_status' => $oldPaymentStatus,
	                'allocation_status' => $oldAllocationStatus,
	                'paid_amount' => $oldPaidAmount,
	                'balance_amount' => $oldBalanceAmount,
	                'invoice_status' => $oldInvoiceStatus,
	            ],
	            [
	                'payment_status' => 'Reversed',
	                'allocation_status' => 'Reversed',
	                'paid_amount' => $invoice->paid_amount,
	                'balance_amount' => $invoice->balance_amount,
	                'invoice_status' => $invoice->invoice_status,
	            ]
	        );
	    });


	    /*
	    |--------------------------------------------------------------------------
	    | 12. Redirect
	    |--------------------------------------------------------------------------
	    */

	    return back()->with(
	        'success',
	        'Payment reversed and invoice balance restored successfully.'
	    );
	}

	public function reconcile($id)
	{
	    DB::transaction(function () use ($id) {

	        $payment = RentPayment::where('id', $id)
	            ->lockForUpdate()
	            ->firstOrFail();

	        /*
	        |--------------------------------------------------------------------------
	        | Payment must be Confirmed
	        |--------------------------------------------------------------------------
	        */

	        if ($payment->payment_status !== 'Confirmed') {

	            throw ValidationException::withMessages([
	                'payment' =>
	                    'Only confirmed payments can be reconciled.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Payment must not already be reconciled
	        |--------------------------------------------------------------------------
	        */

	        if ($payment->reconciliation_status === 'Reconciled') {

	            throw ValidationException::withMessages([
	                'payment' =>
	                    'This payment is already reconciled.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Mark Payment as Reconciled
	        |--------------------------------------------------------------------------
	        */

	        $payment->update([
	            'reconciliation_status' => 'Reconciled',
	            'updated_by' => Auth::id(),
	        ]);

	        RevenueAuditService::log(
			    'Reconciliation',
			    'Reconciled',
			    'RentPayment',
			    $payment->id,
			    'Payment reconciled successfully.',
			    [
			        'reconciliation_status' => 'Pending',
			    ],
			    [
			        'reconciliation_status' => 'Reconciled',
			    ]
			);


	    });

	    return back()->with(
	        'success',
	        'Payment reconciled successfully.'
	    );
	}

	/*public function receipt($id)
	{
	    $payment = RentPayment::with([
	        'tenant',
	        'invoice',
	        'allocations',
	    ])->findOrFail($id);

	    // Receipt should only be available for confirmed payments
	    if ($payment->payment_status !== 'Confirmed') {
	        return back()->with(
	            'error',
	            'Receipt is available only for confirmed payments.'
	        );
	    }

	    return view(
	        'admin.revenue.payments.receipt',
	        compact('payment')
	    );
	}*/

	public function receipt($id)
	{
	    $payment = RentPayment::with([
	        'tenant',
	        'invoice',
	        'allocations',
	    ])->findOrFail($id);

	    if ($payment->payment_status !== 'Confirmed') {
	        return back()->with(
	            'error',
	            'Receipt is available only for confirmed payments.'
	        );
	    }

	    $amountInWords = $this->amountInWords(
	        $payment->payment_amount
	    );

	    return view(
	        'admin.revenue.payments.receipt',
	        compact(
	            'payment',
	            'amountInWords'
	        )
	    );
	}

	private function amountInWords($amount)
	{
	    $amount = number_format((float) $amount, 2, '.', '');

	    [$rupees, $paise] = explode('.', $amount);

	    $rupees = (int) $rupees;
	    $paise  = (int) $paise;

	    $words = $this->numberToWords($rupees);

	    $result = ucfirst($words) . ' Rupees';

	    if ($paise > 0) {
	        $result .= ' and ' .
	            ucfirst($this->numberToWords($paise)) .
	            ' Paise';
	    }

	    return $result . ' Only';
	}

	private function numberToWords($number)
	{
	    if ($number == 0) {
	        return 'Zero';
	    }

	    $ones = [
	        '',
	        'One',
	        'Two',
	        'Three',
	        'Four',
	        'Five',
	        'Six',
	        'Seven',
	        'Eight',
	        'Nine',
	        'Ten',
	        'Eleven',
	        'Twelve',
	        'Thirteen',
	        'Fourteen',
	        'Fifteen',
	        'Sixteen',
	        'Seventeen',
	        'Eighteen',
	        'Nineteen'
	    ];

	    $tens = [
	        '',
	        '',
	        'Twenty',
	        'Thirty',
	        'Forty',
	        'Fifty',
	        'Sixty',
	        'Seventy',
	        'Eighty',
	        'Ninety'
	    ];

	    if ($number < 20) {
	        return $ones[$number];
	    }

	    if ($number < 100) {
	        return $tens[intdiv($number, 10)] .
	            ($number % 10 ? ' ' . $ones[$number % 10] : '');
	    }

	    if ($number < 1000) {
	        return $ones[intdiv($number, 100)] .
	            ' Hundred' .
	            ($number % 100
	                ? ' ' . $this->numberToWords($number % 100)
	                : '');
	    }

	    if ($number < 100000) {
	        return $this->numberToWords(intdiv($number, 1000)) .
	            ' Thousand' .
	            ($number % 1000
	                ? ' ' . $this->numberToWords($number % 1000)
	                : '');
	    }

	    if ($number < 10000000) {
	        return $this->numberToWords(intdiv($number, 100000)) .
	            ' Lakh' .
	            ($number % 100000
	                ? ' ' . $this->numberToWords($number % 100000)
	                : '');
	    }

	    return $this->numberToWords(intdiv($number, 10000000)) .
	        ' Crore' .
	        ($number % 10000000
	            ? ' ' . $this->numberToWords($number % 10000000)
	            : '');
	}

	private function allocatePaymentToInvoiceItems(PaymentAllocation $paymentAllocation) {
	    $invoice = Invoice::with([
	        'items'
	    ])->findOrFail(
	        $paymentAllocation->invoice_id
	    );

	    $remainingAmount = (float)
	        $paymentAllocation->allocated_amount;

	    if ($remainingAmount <= 0) {
	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Get already allocated amount for each invoice item
	    |--------------------------------------------------------------------------
	    */

	    foreach ($invoice->items as $item) {

	        if ($remainingAmount <= 0) {
	            break;
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Total already allocated to this item
	        |--------------------------------------------------------------------------
	        */

	        $alreadyAllocated =
	            InvoiceItemAllocation::where(
	                'invoice_item_id',
	                $item->id
	            )
	            ->where(
	                'allocation_status',
	                'Allocated'
	            )
	            ->sum('allocated_amount');


	        /*
	        |--------------------------------------------------------------------------
	        | Remaining amount for this invoice item
	        |--------------------------------------------------------------------------
	        */

	        $itemTotal = (float) $item->total_amount;

	        $itemBalance =
	            $itemTotal - (float) $alreadyAllocated;

	        $itemBalance = max(
	            0,
	            $itemBalance
	        );


	        if ($itemBalance <= 0) {
	            continue;
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Amount to allocate
	        |--------------------------------------------------------------------------
	        */

	        $allocateAmount = min(
	            $remainingAmount,
	            $itemBalance
	        );


	        /*
	        |--------------------------------------------------------------------------
	        | Create invoice item allocation
	        |--------------------------------------------------------------------------
	        */

	        InvoiceItemAllocation::create([
	            'uuid' => (string) Str::uuid(),

	            'payment_allocation_id' =>
	                $paymentAllocation->id,

	            'invoice_item_id' =>
	                $item->id,

	            'allocation_date' =>
	                $paymentAllocation->allocation_date,

	            'allocated_amount' =>
	                round($allocateAmount, 2),

	            'allocation_status' =>
	                'Allocated',

	            'created_by' =>
	                Auth::id(),

	            'updated_by' =>
	                Auth::id(),
	        ]);


	        /*
	        |--------------------------------------------------------------------------
	        | Reduce remaining payment
	        |--------------------------------------------------------------------------
	        */

	        $remainingAmount -= $allocateAmount;

	        $remainingAmount = round(
	            $remainingAmount,
	            2
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Safety check
	    |--------------------------------------------------------------------------
	    */

	    if ($remainingAmount > 0.01) {

	        throw new \Exception(
	            'Payment amount could not be fully allocated to invoice items.'
	        );
	    }
	}

	private function reversePaymentAllocation(
	    PaymentAllocation $paymentAllocation
	) {

	    /*
	    |--------------------------------------------------------------------------
	    | 1. Reverse Invoice Item Allocations
	    |--------------------------------------------------------------------------
	    */

	    InvoiceItemAllocation::where(
	        'payment_allocation_id',
	        $paymentAllocation->id
	    )
	    ->where(
	        'allocation_status',
	        'Allocated'
	    )
	    ->update([

	        'allocation_status' => 'Reversed',

	        'remarks' => 'Payment allocation reversed.',

	        'updated_by' => Auth::id(),

	        'updated_at' => now(),

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | 2. Reverse Payment Allocation
	    |--------------------------------------------------------------------------
	    */

	    $paymentAllocation->update([

	        'allocation_status' => 'Reversed',

	        'remarks' => 'Payment allocation reversed.',

	        'updated_by' => Auth::id(),

	        'updated_at' => now(),

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | 3. Lock Invoice
	    |--------------------------------------------------------------------------
	    */

	    $invoice = Invoice::where(
	        'id',
	        $paymentAllocation->invoice_id
	    )
	    ->lockForUpdate()
	    ->firstOrFail();


	    /*
	    |--------------------------------------------------------------------------
	    | 4. Calculate Paid Amount
	    |--------------------------------------------------------------------------
	    |
	    | Only ACTIVE allocations are counted.
	    |
	    */

	    $paidAmount = PaymentAllocation::where(
	        'invoice_id',
	        $invoice->id
	    )
	    ->where(
	        'allocation_status',
	        'Allocated'
	    )
	    ->sum('allocated_amount');


	    $paidAmount = round(
	        (float) $paidAmount,
	        2
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | 5. Calculate Balance
	    |--------------------------------------------------------------------------
	    */

	    $balanceAmount = round(
	        (float) $invoice->total_amount - $paidAmount,
	        2
	    );


	    $balanceAmount = max(
	        0,
	        $balanceAmount
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | 6. Determine Invoice Status
	    |--------------------------------------------------------------------------
	    */

	    if ($paidAmount <= 0) {

	        $invoiceStatus = 'Generated';

	    } elseif ($balanceAmount <= 0) {

	        $invoiceStatus = 'Paid';

	    } else {

	        $invoiceStatus = 'Partially Paid';
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | 7. Update Invoice
	    |--------------------------------------------------------------------------
	    */

	    $invoice->update([

	        'paid_amount' => $paidAmount,

	        'balance_amount' => $balanceAmount,

	        'invoice_status' => $invoiceStatus,

	        'updated_by' => Auth::id(),

	        'updated_at' => now(),

	    ]);
	}

}
