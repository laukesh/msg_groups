<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use App\Models\Invoice;
use App\Models\PaymentAllocation;
use App\Models\RentSchedule;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentAllocationController extends Controller
{
    public function allocate(Request $request)
	{
	    $validated = $request->validate([
	        'payment_id' => 'required|exists:rent_payments,id',
	        'invoice_id' => 'required|exists:invoices,id',
	        'allocated_amount' => 'required|numeric|min:0.01',
	        'allocation_date' => 'required|date',
	        'remarks' => 'nullable|string',
	    ]);

	    DB::transaction(function () use ($validated) {

	        /*
	        |--------------------------------------------------------------------------
	        | Lock Payment
	        |--------------------------------------------------------------------------
	        */

	        $payment = RentPayment::where(
	            'id',
	            $validated['payment_id']
	        )
	        ->lockForUpdate()
	        ->firstOrFail();

	        if ($payment->payment_status !== 'Confirmed') {
	            throw ValidationException::withMessages([
	                'payment' =>
	                    'Only confirmed payments can be allocated.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Lock Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice = Invoice::where(
	            'id',
	            $validated['invoice_id']
	        )
	        ->lockForUpdate()
	        ->firstOrFail();

	        if (in_array($invoice->invoice_status, [
	            'Cancelled',
	            'Draft'
	        ])) {
	            throw ValidationException::withMessages([
	                'invoice' =>
	                    'This invoice cannot receive payment.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Validate Tenant
	        |--------------------------------------------------------------------------
	        */

	        if ($payment->tenant_id != $invoice->tenant_id) {
	            throw ValidationException::withMessages([
	                'payment' =>
	                    'Payment and invoice belong to different tenants.'
	            ]);
	        }

	        $allocatedAmount =
	            round(
	                (float) $validated['allocated_amount'],
	                2
	            );

	        /*
	        |--------------------------------------------------------------------------
	        | Already Allocated Amount
	        |--------------------------------------------------------------------------
	        */

	        $alreadyAllocated = PaymentAllocation::where(
	            'payment_id',
	            $payment->id
	        )
	        ->where('allocation_status', 'Allocated')
	        ->sum('allocated_amount');

	        $remainingPayment =
	            round(
	                $payment->payment_amount
	                - $alreadyAllocated,
	                2
	            );

	        /*
	        |--------------------------------------------------------------------------
	        | Validate Payment Amount
	        |--------------------------------------------------------------------------
	        */

	        if ($allocatedAmount > $remainingPayment) {
	            throw ValidationException::withMessages([
	                'allocated_amount' =>
	                    'Allocated amount cannot exceed remaining payment amount.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Validate Invoice Balance
	        |--------------------------------------------------------------------------
	        */

	        $invoiceBalance =
	            round(
	                (float) $invoice->total_amount
	                - (float) $invoice->paid_amount,
	                2
	            );

	        if ($allocatedAmount > $invoiceBalance) {
	            throw ValidationException::withMessages([
	                'allocated_amount' =>
	                    'Allocated amount cannot exceed invoice balance.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Create Allocation
	        |--------------------------------------------------------------------------
	        */

	        PaymentAllocation::create([

	            'uuid' => (string) Str::uuid(),

	            'payment_id' =>
	                $payment->id,

	            'invoice_id' =>
	                $invoice->id,

	            'allocation_date' =>
	                $validated['allocation_date'],

	            'allocated_amount' =>
	                $allocatedAmount,

	            'allocation_status' =>
	                'Allocated',

	            'remarks' =>
	                $validated['remarks'] ?? null,

	            'created_by' =>
	                Auth::id(),

	            'updated_by' =>
	                Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | Update Invoice
	        |--------------------------------------------------------------------------
	        */

	        $newPaidAmount =
	            round(
	                (float) $invoice->paid_amount
	                + $allocatedAmount,
	                2
	            );

	        $newBalance =
	            round(
	                (float) $invoice->total_amount
	                - $newPaidAmount,
	                2
	            );

	        if ($newBalance <= 0) {

	            $newBalance = 0;

	            $invoiceStatus = 'Paid';

	        } else {

	            $invoiceStatus = 'Partially Paid';
	        }

	        $invoice->update([

	            'paid_amount' =>
	                $newPaidAmount,

	            'balance_amount' =>
	                $newBalance,

	            'invoice_status' =>
	                $invoiceStatus,

	            'updated_by' =>
	                Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | Update Rent Schedule
	        |--------------------------------------------------------------------------
	        */

	        $schedule = RentSchedule::where(
	            'invoice_id',
	            $invoice->id
	        )
	        ->lockForUpdate()
	        ->first();

	        if ($schedule) {

	            $scheduleStatus =
	                $newBalance <= 0
	                    ? 'Paid'
	                    : 'Partial';

	            $schedule->update([

	                'payment_status' =>
	                    $scheduleStatus,

	                'updated_by' =>
	                    Auth::id(),
	            ]);
	        }
	    });

	    return back()->with(
	        'success',
	        'Payment allocated successfully.'
	    );
	}
}
