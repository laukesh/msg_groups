<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentSchedule;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\RevenueAuditService;
use Carbon\Carbon;
class InvoiceController extends Controller
{
	public function index()
    {
        $invoices = Invoice::with([
            'leaseAgreement',
            'tenant',
        ])
        ->orderByDesc('id')
        ->get();

        return view(
            'admin.revenue.invoices.index',
            compact('invoices')
        );
    }

    /**
     * Invoice Details
     */
    public function show($id)
    {
        $invoice = Invoice::with([
            'leaseAgreement',
            'tenant',
            'items.chargeType',
        ])->findOrFail($id);

        return view(
            'admin.revenue.invoices.show',
            compact('invoice')
        );
    }
    
    public function generateFromSchedule($scheduleId)
	{
	    DB::transaction(function () use ($scheduleId) {

	        $schedule = RentSchedule::with('leaseAgreement')
	            ->where('id', $scheduleId)
	            ->lockForUpdate()
	            ->firstOrFail();

	        /*
	        |--------------------------------------------------------------------------
	        | Prevent duplicate invoice
	        |--------------------------------------------------------------------------
	        */

	        if ($schedule->invoice_generated === 'Yes') {
	            throw ValidationException::withMessages([
	                'invoice' => 'Invoice has already been generated for this rent schedule.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Get agreement
	        |--------------------------------------------------------------------------
	        */

	        $agreement = $schedule->leaseAgreement;

	        if (!$agreement) {
	            throw ValidationException::withMessages([
	                'agreement' => 'Lease agreement not found.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Generate Invoice Number
	        |--------------------------------------------------------------------------
	        */

	        $year = now()->format('Y');

	        $lastInvoice = Invoice::where(
	            'invoice_no',
	            'like',
	            'INV-' . $year . '-%'
	        )
	        ->orderByDesc('id')
	        ->lockForUpdate()
	        ->first();

	        $lastNumber = 0;

	        if ($lastInvoice) {
	            $lastNumber = (int) substr(
	                $lastInvoice->invoice_no,
	                -5
	            );
	        }

	        $invoiceNo =
	            'INV-' . $year . '-' .
	            str_pad(
	                $lastNumber + 1,
	                5,
	                '0',
	                STR_PAD_LEFT
	            );

	        /*
	        |--------------------------------------------------------------------------
	        | Create Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice = Invoice::create([

	            'uuid' => (string) Str::uuid(),

	            'invoice_no' => $invoiceNo,

	            'lease_agreement_id' => $agreement->id,

	            'tenant_id' => $agreement->tenant_id,

	            'invoice_type' => 'Rent',
	            'invoice_date' => now()->format('Y-m-d'),

	            'billing_period_from' => $schedule->period_start,

	            'billing_period_to' => $schedule->period_end,

	            'due_date' => Carbon::parse($invoice->due_date),

	            'subtotal' => $schedule->base_rent + $schedule->cam_amount + $schedule->utility_estimate,

	            'discount_amount' => $schedule->discount_amount,
	            'taxable_amount' => $schedule->taxable_amount,
	            'tax_amount' => $schedule->tax_amount,
	            'total_amount' => $schedule->total_amount,
	            'paid_amount' => 0,
	            'balance_amount' =>$schedule->total_amount,
	            'invoice_status' => 'Generated',
	            'remarks' =>
	                'Generated from rent schedule ' .
	                $schedule->schedule_no,
	            'generated_by' => Auth::id(),
	            'created_by' => Auth::id(),
	            'updated_by' => Auth::id(),
	        ]);

	        /*
			|--------------------------------------------------------------------------
			| Audit Log - Invoice Created
			|--------------------------------------------------------------------------
			*/

			RevenueAuditService::log(
			    'Invoices',
			    'Created',
			    'Invoice',
			    $invoice->id,
			    'Invoice created successfully.',
			    null,
			    [
			        'invoice_no' => $invoice->invoice_no,
			        'tenant_id' => $invoice->tenant_id,
			        'total_amount' => $invoice->total_amount,
			        'invoice_status' => $invoice->invoice_status,
			    ]
			);

	        /*
	        |--------------------------------------------------------------------------
	        | Rent Invoice Item
	        |--------------------------------------------------------------------------
	        */

	        if ($schedule->base_rent > 0) {

	            InvoiceItem::create([

	                'invoice_id' => $invoice->id,
	                'charge_type_id' => 1, // RENT
	                'item_description' =>'Monthly Rent - ' . $schedule->billing_period,
	                'quantity' => 1,
	                'unit' => 'Month',
	                'rate' => $schedule->base_rent,
	                'taxable_amount' => $schedule->base_rent,
	                'tax_percentage' => 18,
	                'tax_amount' =>
	                    round(
	                        $schedule->base_rent * 0.18,
	                        2
	                    ),

	                'discount_amount' => 0,
	                'total_amount' =>
	                    round(
	                        $schedule->base_rent * 1.18,
	                        2
	                    ),
	                'created_by' => Auth::id(),
	                'updated_by' => Auth::id(),
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | CAM Invoice Item
	        |--------------------------------------------------------------------------
	        */

	        if ($schedule->cam_amount > 0) {

	            InvoiceItem::create([

	                'invoice_id' => $invoice->id,
	                'charge_type_id' => 2, // CAM
	                'item_description' =>'CAM Charges - ' .$schedule->billing_period,
	                'quantity' => 1,
	                'unit' => 'Month',
	                'rate' => $schedule->cam_amount,

	                'taxable_amount' =>  $schedule->cam_amount,
	                'tax_percentage' => 18,
	                'tax_amount' =>
	                    round(
	                        $schedule->cam_amount * 0.18,
	                        2
	                    ),

	                'discount_amount' => 0,
	                'total_amount' =>
	                    round(
	                        $schedule->cam_amount * 1.18,
	                        2
	                    ),
	                'created_by' => Auth::id(),
	                'updated_by' => Auth::id(),
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Update Rent Schedule
	        |--------------------------------------------------------------------------
	        */

	        $schedule->update([

	            'invoice_id' =>
	                $invoice->id,

	            'invoice_generated' => 'Yes',

	            'updated_by' => Auth::id(),
	        ]);
	    });

	    return back()->with(
	        'success',
	        'Invoice generated successfully.'
	    );
	}

	private function updateInvoiceStatus(Invoice $invoice)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Never change Cancelled invoices automatically
	    |--------------------------------------------------------------------------
	    */

	    if ($invoice->invoice_status === 'Cancelled') {
	        return;
	    }

	    $paid = round((float) $invoice->paid_amount, 2);

	    $total = round((float) $invoice->total_amount, 2);

	    $balance = round(
	        $total - $paid,
	        2
	    );

	    /*
	    |--------------------------------------------------------------------------
	    | Fully Paid
	    |--------------------------------------------------------------------------
	    */

	    if ($balance <= 0) {

	        $invoice->update([
	            'paid_amount' => $total,
	            'balance_amount' => 0,
	            'invoice_status' => 'Paid',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Overdue
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $invoice->due_date &&
	        Carbon::parse($invoice->due_date)->isPast()
	    ) {

	        $invoice->update([
	            'balance_amount' => $balance,
	            'invoice_status' => 'Overdue',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Partially Paid
	    |--------------------------------------------------------------------------
	    */

	    if ($paid > 0) {

	        $invoice->update([
	            'balance_amount' => $balance,
	            'invoice_status' => 'Partially Paid',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Generated
	    |--------------------------------------------------------------------------
	    */

	    $invoice->update([
	        'balance_amount' => $balance,
	        'invoice_status' => 'Generated',
	        'updated_by' => Auth::id(),
	    ]);
	}

	private function updateRentScheduleStatus(RentSchedule $schedule)
	{
	    $invoice = Invoice::find($schedule->invoice_id);

	    /*
	    |--------------------------------------------------------------------------
	    | Invoice not generated yet
	    |--------------------------------------------------------------------------
	    */

	    if (!$invoice) {

	        $schedule->update([
	            'payment_status' => 'Pending',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Fully Paid
	    |--------------------------------------------------------------------------
	    */

	    if ((float) $invoice->balance_amount <= 0) {

	        $schedule->update([
	            'payment_status' => 'Paid',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Overdue
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $schedule->due_date &&
	        Carbon::parse($schedule->due_date)->isPast()
	    ) {

	        $schedule->update([
	            'payment_status' => 'Overdue',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Partial Payment
	    |--------------------------------------------------------------------------
	    */

	    if ((float) $invoice->paid_amount > 0) {

	        $schedule->update([
	            'payment_status' => 'Partial',
	            'updated_by' => Auth::id(),
	        ]);

	        return;
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Pending
	    |--------------------------------------------------------------------------
	    */

	    $schedule->update([
	        'payment_status' => 'Pending',
	        'updated_by' => Auth::id(),
	    ]);
	}

	public function cancel($id)
	{
	    DB::transaction(function () use ($id) {

	        $invoice = Invoice::where('id', $id)
	            ->lockForUpdate()
	            ->firstOrFail();

	        /*
	        |--------------------------------------------------------------------------
	        | Already Cancelled
	        |--------------------------------------------------------------------------
	        */

	        if ($invoice->invoice_status === 'Cancelled') {
	            throw ValidationException::withMessages([
	                'invoice' => 'Invoice is already cancelled.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Paid Invoice
	        |--------------------------------------------------------------------------
	        */

	        if (
	            $invoice->invoice_status === 'Paid' ||
	            (float) $invoice->paid_amount > 0
	        ) {
	            throw ValidationException::withMessages([
	                'invoice' =>
	                    'Invoice cannot be cancelled because payment has already been allocated.'
	            ]);
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Cancel Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice->update([
	            'invoice_status' => 'Cancelled',
	            'updated_by' => Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | Update Related Rent Schedule
	        |--------------------------------------------------------------------------
	        */

	        $schedule = RentSchedule::where(
	            'invoice_id',
	            $invoice->id
	        )
	        ->lockForUpdate()
	        ->first();

	        if ($schedule) {

	            /*
	             * The rent schedule itself does not have
	             * a Cancelled status.
	             *
	             * We therefore remove its invoice link and
	             * mark it as not invoiced.
	             */

	            $schedule->update([
	                'invoice_id' => null,
	                'invoice_generated' => 'No',
	                'payment_status' => 'Pending',
	                'updated_by' => Auth::id(),
	            ]);
	        }
	    });

	    return back()->with(
	        'success',
	        'Invoice cancelled successfully.'
	    );
	}

	public function print($id)
	{
	    $invoice = Invoice::with([
	        'tenant',
	        'items.chargeType',
	    ])->findOrFail($id);

	    return view(
	        'admin.revenue.invoices.print',
	        compact('invoice')
	    );
	}

}
