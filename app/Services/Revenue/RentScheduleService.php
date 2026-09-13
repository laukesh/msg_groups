<?php

namespace App\Services\Revenue;

use App\Models\RentSchedule;
use App\Models\LeaseAgreement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentScheduleService
{
    public function generateForAgreement(int $agreementId): RentSchedule
    {
        return DB::transaction(function () use ($agreementId) {

            /*
            |--------------------------------------------------------------------------
            | Lock Agreement
            |--------------------------------------------------------------------------
            */

            $agreement = LeaseAgreement::where('id', $agreementId)
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Agreement Validation
            |--------------------------------------------------------------------------
            */

            if ($agreement->agreement_status !== 'Active') {

                throw ValidationException::withMessages([
                    'agreement' =>
                        'Only active lease agreements can generate rent schedules.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Rent Start Date
            |--------------------------------------------------------------------------
            */

            $rentStartDate = Carbon::parse(
                $agreement->rent_start_date
            );


            /*
            |--------------------------------------------------------------------------
            | Billing Period
            |--------------------------------------------------------------------------
            */

            $periodStart = $rentStartDate->copy()->startOfMonth();

            if ($rentStartDate->greaterThan($periodStart)) {
                $periodStart = $rentStartDate->copy();
            }

            $periodEnd = $rentStartDate->copy()->endOfMonth();


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Schedule
            |--------------------------------------------------------------------------
            */

            $existingSchedule = RentSchedule::where(
                'lease_agreement_id',
                $agreement->id
            )
            ->whereDate('period_start', $periodStart)
            ->whereDate('period_end', $periodEnd)
            ->first();

            if ($existingSchedule) {

                throw ValidationException::withMessages([
                    'schedule' =>
                        'Rent schedule already exists for this billing period.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Charges
            |--------------------------------------------------------------------------
            */

            $charges = $this->calculateCharges(
                $agreement,
                $periodStart,
                $periodEnd
            );


            /*
            |--------------------------------------------------------------------------
            | Generate Schedule Number
            |--------------------------------------------------------------------------
            */

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

            $scheduleNo =
                'RS-' . $year . '-' .
                str_pad(
                    $lastNumber + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Create Rent Schedule
            |--------------------------------------------------------------------------
            */

            return RentSchedule::create([

                'uuid' => (string) \Illuminate\Support\Str::uuid(),

                'lease_agreement_id' =>$agreement->id,

                'invoice_id' => null,

                'schedule_no' =>$scheduleNo,

                'billing_period' => $periodStart->format('F Y'),

                'period_start' => $periodStart->format('Y-m-d'),

                'period_end' =>$periodEnd->format('Y-m-d'),

                'due_date' =>
                    $this->calculateDueDate(
                        $agreement,
                        $periodStart
                    ),

                'base_rent' => $charges['base_rent'],

                'escalation_amount' => $charges['escalation_amount'],

                'cam_amount' => $charges['cam_amount'],

                'utility_estimate' => $charges['utility_estimate'],

                'discount_amount' => $charges['discount_amount'],

                'taxable_amount' => $charges['taxable_amount'],

                'tax_amount' => $charges['tax_amount'],

                'total_amount' => $charges['total_amount'],

                'invoice_generated' => 'No',

                'payment_status' => 'Pending',

                'created_by' => Auth::id(),

                'updated_by' => Auth::id(),
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Charges
    |--------------------------------------------------------------------------
    */

    private function calculateCharges(
        LeaseAgreement $agreement,
        Carbon $periodStart,
        Carbon $periodEnd
    ): array {

        $daysInMonth =  $periodStart->daysInMonth;

        //$billingDays = $periodStart->diffInDays($periodEnd) + 1;

        $billingDays = $periodStart->copy()->startOfDay()
                            ->diffInDays(
                                $periodEnd->copy()->startOfDay()
                            ) + 1;


        $rentFreeDays = min(
            (int) $agreement->rent_free_days,
            $billingDays
        );


        $chargeableDays = max(
            0,
            $billingDays - $rentFreeDays
        );


        /*
        |--------------------------------------------------------------------------
        | Rent
        |--------------------------------------------------------------------------
        */

        $baseRent =
            (
                (float) $agreement->monthly_rent
                / $daysInMonth
            )
            * $chargeableDays;


        /*
        |--------------------------------------------------------------------------
        | CAM
        |--------------------------------------------------------------------------
        */

        $camAmount =
            (
                (float) $agreement->cam_amount
                / $daysInMonth
            )
            * $chargeableDays;


        /*
        |--------------------------------------------------------------------------
        | Other Charges
        |--------------------------------------------------------------------------
        */

        $utilityAmount = 0;

        $discountAmount = 0;


        /*
        |--------------------------------------------------------------------------
        | Taxable Amount
        |--------------------------------------------------------------------------
        */

        $taxableAmount =  $baseRent + $camAmount + $utilityAmount - $discountAmount;


        /*
        |--------------------------------------------------------------------------
        | GST
        |--------------------------------------------------------------------------
        */

        $taxRate = 18;

        $taxAmount = $taxableAmount* ($taxRate / 100);


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $totalAmount = $taxableAmount + $taxAmount;



/*        dd([
            'agreement_id' => $agreement->id,
            'monthly_rent' => $agreement->monthly_rent,
            'cam_amount' => $agreement->cam_amount,
            'rent_free_days' => $agreement->rent_free_days,
            'period_start' => $periodStart->format('Y-m-d'),
            'period_end' => $periodEnd->format('Y-m-d'),
            'days_in_month' => $daysInMonth,
            'billing_days' => $billingDays,
            'chargeable_days' => $chargeableDays,
        ]);*/


        return [

            'base_rent' =>
                round($baseRent, 2),

            'escalation_amount' =>
                0,

            'cam_amount' =>
                round($camAmount, 2),

            'utility_estimate' =>
                round($utilityAmount, 2),

            'discount_amount' =>
                round($discountAmount, 2),

            'taxable_amount' =>
                round($taxableAmount, 2),

            'tax_amount' =>
                round($taxAmount, 2),

            'total_amount' =>
                round($totalAmount, 2),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Due Date
    |--------------------------------------------------------------------------
    */

    private function calculateDueDate(
        LeaseAgreement $agreement,
        Carbon $periodStart
    ): string {

        $dueMonth =
            $periodStart->copy()->addMonth();

        $dueDay = min(
            (int) $agreement->payment_due_day,
            $dueMonth->daysInMonth
        );

        return $dueMonth
            ->day($dueDay)
            ->format('Y-m-d');
    }
}