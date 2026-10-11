<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\RentSchedule;
use App\Models\LeaseAgreement;
use App\Models\TaxConfiguration;
use App\Models\ChargeType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\Revenue\RentScheduleService;

class RentScheduleController extends Controller
{
    public function index()
    {
        $rentSchedules = RentSchedule::with([
            'leaseAgreement',
            'invoice'
        ])
        ->orderByDesc('id')
        ->paginate(20);

        return view(
            'admin.revenue.rent_schedules.index',
            compact('rentSchedules')
        );
    }

    public function show($id)
	{
	    $schedule = RentSchedule::with([
	        'leaseAgreement',
	        'invoice',
	    ])->findOrFail($id);

	    return view(
	        'admin.revenue.rent_schedules.show',
	        compact('schedule')
	    );
	}

    private function calculateCharges(
	    LeaseAgreement $agreement,
	    Carbon $periodStart,
	    Carbon $periodEnd
	): array {
	    $daysInMonth = $periodStart->daysInMonth;

	    $billingDays = $periodStart->diffInDays($periodEnd) + 1;

	    $rentFreeDays = min(
	        (int) $agreement->rent_free_days,
	        $billingDays
	    );

	    $chargeableDays = max(
	        0,
	        $billingDays - $rentFreeDays
	    );

	    $baseRent = (
	        (float) $agreement->monthly_rent
	        / $daysInMonth
	    ) * $chargeableDays;

	    $camAmount = (
	        (float) $agreement->cam_amount
	        / $daysInMonth
	    ) * $chargeableDays;

	    $utilityAmount = 0;

	    $discountAmount = 0;

	    $taxableAmount =
	        $baseRent
	        + $camAmount
	        + $utilityAmount
	        - $discountAmount;

	    $taxRate = 18;

	    $taxAmount = $taxableAmount * ($taxRate / 100);

	    $totalAmount =
	        $taxableAmount
	        + $taxAmount;

	    return [
	        'base_rent' => round($baseRent, 2),
	        'escalation_amount' => 0,
	        'cam_amount' => round($camAmount, 2),
	        'utility_estimate' => round($utilityAmount, 2),
	        'discount_amount' => round($discountAmount, 2),
	        'taxable_amount' => round($taxableAmount, 2),
	        'tax_amount' => round($taxAmount, 2),
	        'total_amount' => round($totalAmount, 2),
	    ];
	}

	public function generate($agreementId,RentScheduleService $rentScheduleService) {
	    $rentScheduleService->generateForAgreement(
	        (int) $agreementId
	    );

	    return back()->with(
	        'success',
	        'Rent schedule generated successfully.'
	    );
	}

	/*public function generate($agreementId)
	{
	    DB::transaction(function () use ($agreementId) {

	        $agreement = LeaseAgreement::where('id', $agreementId)
	            ->lockForUpdate()
	            ->firstOrFail();

	        // Only active agreements can generate rent schedules
	        if ($agreement->agreement_status !== 'Active') {
	            throw ValidationException::withMessages([
	                'agreement' => 'Only active lease agreements can generate rent schedules.'
	            ]);
	        }

	        $rentStartDate = Carbon::parse($agreement->rent_start_date);

	        // For demo: generate the month containing rent start date
	        $periodStart = $rentStartDate->copy()->startOfMonth();

	        // Don't start before rent start date
	        if ($rentStartDate->greaterThan($periodStart)) {
	            $periodStart = $rentStartDate->copy();
	        }

	        $periodEnd = $rentStartDate->copy()->endOfMonth();

	        $existingSchedule = RentSchedule::where(
	            'lease_agreement_id',
	            $agreement->id
	        )
	        ->whereDate('period_start', $periodStart)
	        ->whereDate('period_end', $periodEnd)
	        ->first();

	        if ($existingSchedule) {
	            throw ValidationException::withMessages([
	                'schedule' => 'Rent schedule already exists for this billing period.'
	            ]);
	        }


	        $daysInMonth = $periodStart->daysInMonth;

	        $billingDays =
	            $periodStart->diffInDays($periodEnd) + 1;

	        $rentFreeDays = min(
	            (int) $agreement->rent_free_days,
	            $billingDays
	        );

	        $chargeableDays = max(
	            0,
	            $billingDays - $rentFreeDays
	        );


	        $baseRent =
	            ((float) $agreement->monthly_rent / $daysInMonth)
	            * $chargeableDays;


	        $camAmount =
	            ((float) $agreement->cam_amount / $daysInMonth)
	            * $chargeableDays;

	        $utilityEstimate = 0;

	        $discountAmount = 0;


	        $taxableAmount =
	            $baseRent
	            + $camAmount
	            + $utilityEstimate
	            - $discountAmount;


	        $taxPercentage = 18;

	        $taxAmount =
	            $taxableAmount * ($taxPercentage / 100);


	        $totalAmount =
	            $taxableAmount + $taxAmount;


	        $year = now()->format('Y');

	        $lastSchedule = RentSchedule::where(
	            'schedule_no',
	            'like',
	            'RS-' . $year . '-%'
	        )
	        ->orderByDesc('id')
	        ->lockForUpdate()
	        ->first();

	        $lastNumber = 0;

	        if ($lastSchedule) {
	            $lastNumber = (int) substr(
	                $lastSchedule->schedule_no,
	                -5
	            );
	        }

	        $nextNumber = $lastNumber + 1;

	        $scheduleNo =
	            'RS-' . $year . '-' .
	            str_pad($nextNumber, 5, '0', STR_PAD_LEFT);


	        RentSchedule::create([
	            'uuid' => (string) \Illuminate\Support\Str::uuid(),

	            'lease_agreement_id' =>
	                $agreement->id,

	            'invoice_id' => null,

	            'schedule_no' =>
	                $scheduleNo,

	            'billing_period' =>
	                $periodStart->format('F Y'),

	            'period_start' =>
	                $periodStart->format('Y-m-d'),

	            'period_end' =>
	                $periodEnd->format('Y-m-d'),

	            'due_date' =>
	                $periodStart->copy()
	                    ->addMonth()
	                    ->day(
	                        min(
	                            (int) $agreement->payment_due_day,
	                            $periodStart->copy()
	                                ->addMonth()
	                                ->daysInMonth
	                        )
	                    )
	                    ->format('Y-m-d'),

	            'base_rent' =>
	                round($baseRent, 2),

	            'escalation_amount' => 0,

	            'cam_amount' =>
	                round($camAmount, 2),

	            'utility_estimate' =>
	                round($utilityEstimate, 2),

	            'discount_amount' =>
	                round($discountAmount, 2),

	            'taxable_amount' =>
	                round($taxableAmount, 2),

	            'tax_amount' =>
	                round($taxAmount, 2),

	            'total_amount' =>
	                round($totalAmount, 2),

	            'invoice_generated' => 'No',

	            'payment_status' => 'Pending',

	            'created_by' => Auth::id(),
	            'updated_by' => Auth::id(),
	        ]);
	    });

	    return back()->with(
	        'success',
	        'Rent schedule generated successfully.'
	    );
	}*/

	public function generateInvoice($id)
	{
	    DB::transaction(function () use ($id) {

	        $schedule = RentSchedule::with('leaseAgreement')
	            ->lockForUpdate()
	            ->findOrFail($id);

	        // Already generated?
	        if ($schedule->invoice_generated === 'Yes') {
	            throw ValidationException::withMessages([
	                'invoice' => 'Invoice has already been generated for this rent schedule.'
	            ]);
	        }

	        // Agreement must be active
	        if ($schedule->leaseAgreement->agreement_status !== 'Active') {
	            throw ValidationException::withMessages([
	                'agreement' => 'Invoice can only be generated for an active agreement.'
	            ]);
	        }

	        $agreement = $schedule->leaseAgreement;

	        /*
	        |--------------------------------------------------------------------------
	        | Tax Configuration
	        |--------------------------------------------------------------------------
	        */

	        $rentTax = TaxConfiguration::whereHas('chargeType', function ($query) {
	                $query->where('charge_code', 'RENT');
	            })
	            ->where('status', 'Active')
	            ->where('is_default', 'Yes')
	            ->whereDate('effective_from', '<=', $schedule->period_start)
	            ->where(function ($query) use ($schedule) {
	                $query->whereNull('effective_to')
	                      ->orWhereDate('effective_to', '>=', $schedule->period_start);
	            })
	            ->first();

	        $camTax = TaxConfiguration::whereHas('chargeType', function ($query) {
	                $query->where('charge_code', 'CAM');
	            })
	            ->where('status', 'Active')
	            ->where('is_default', 'Yes')
	            ->whereDate('effective_from', '<=', $schedule->period_start)
	            ->where(function ($query) use ($schedule) {
	                $query->whereNull('effective_to')
	                      ->orWhereDate('effective_to', '>=', $schedule->period_start);
	            })
	            ->first();

	        /*
	        |--------------------------------------------------------------------------
	        | Invoice Number
	        |--------------------------------------------------------------------------
	        */

	        $year = now()->year;

	        $lastInvoice = Invoice::where('invoice_no', 'like', "INV-$year-%")
	            ->orderByDesc('id')
	            ->lockForUpdate()
	            ->first();

	        $lastNumber = $lastInvoice
	            ? ((int) substr($lastInvoice->invoice_no, -5))
	            : 0;

	        $invoiceNo = 'INV-' . $year . '-' .
	            str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

	        /*
	        |--------------------------------------------------------------------------
	        | Create Invoice
	        |--------------------------------------------------------------------------
	        */

	        $invoice = Invoice::create([
	            'uuid' => (string) \Illuminate\Support\Str::uuid(),
	            'invoice_no' => $invoiceNo,
	            'lease_agreement_id' => $agreement->id,
	            'tenant_id' => $agreement->tenant_id,
	            'invoice_type' => 'Rent',
	            'invoice_date' => now()->toDateString(),
	            'billing_period_from' => $schedule->period_start,
	            'billing_period_to' => $schedule->period_end,
	            'due_date' => $schedule->due_date,

	            'subtotal' => $schedule->base_rent + $schedule->cam_amount,
	            'discount_amount' => $schedule->discount_amount,
	            'taxable_amount' => $schedule->taxable_amount,
	            'tax_amount' => $schedule->tax_amount,
	            'total_amount' => $schedule->total_amount,

	            'paid_amount' => 0,
	            'balance_amount' => $schedule->total_amount,

	            'invoice_status' => 'Generated',

	            'generated_by' => Auth::id(),
	            'created_by' => Auth::id(),
	            'updated_by' => Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | Rent Invoice Item
	        |--------------------------------------------------------------------------
	        */

	        $rentTaxPercentage = $rentTax
	            ? $rentTax->tax_percentage
	            : 0;

	        $rentTaxAmount = round(
	            $schedule->base_rent * $rentTaxPercentage / 100,
	            2
	        );

	        InvoiceItem::create([
	            'invoice_id' => $invoice->id,
	            'charge_type_id' => $rentTax->charge_type_id ?? null,
	            'item_description' =>
	                'Monthly Rent - ' . $schedule->billing_period,

	            'quantity' => 1,
	            'unit' => 'Month',

	            'rate' => $schedule->base_rent,
	            'taxable_amount' => $schedule->base_rent,
	            'tax_percentage' => $rentTaxPercentage,
	            'tax_amount' => $rentTaxAmount,
	            'discount_amount' => 0,

	            'total_amount' =>
	                $schedule->base_rent + $rentTaxAmount,

	            'created_by' => Auth::id(),
	            'updated_by' => Auth::id(),
	        ]);

	        /*
	        |--------------------------------------------------------------------------
	        | CAM Invoice Item
	        |--------------------------------------------------------------------------
	        */

	        if ($schedule->cam_amount > 0) {

	            $camTaxPercentage = $camTax
	                ? $camTax->tax_percentage
	                : 0;

	            $camTaxAmount = round(
	                $schedule->cam_amount * $camTaxPercentage / 100,
	                2
	            );

	            InvoiceItem::create([
	                'invoice_id' => $invoice->id,
	                'charge_type_id' => $camTax->charge_type_id ?? null,
	                'item_description' =>
	                    'CAM Charges - ' . $schedule->billing_period,

	                'quantity' => 1,
	                'unit' => 'Month',

	                'rate' => $schedule->cam_amount,
	                'taxable_amount' => $schedule->cam_amount,
	                'tax_percentage' => $camTaxPercentage,
	                'tax_amount' => $camTaxAmount,
	                'discount_amount' => 0,

	                'total_amount' =>
	                    $schedule->cam_amount + $camTaxAmount,

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
	            'invoice_id' => $invoice->id,
	            'invoice_generated' => 'Yes',
	            'payment_status' => 'Pending',
	            'updated_by' => Auth::id(),
	        ]);
	    });

	    return back()->with(
	        'success',
	        'Invoice generated successfully.'
	    );
	}
}