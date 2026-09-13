<?php

namespace App\Services\Revenue;

use App\Models\Deposit;
use App\Models\LeaseAgreement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DepositService
{
    /**
     * Create required deposits for an active lease agreement.
     */
    public function createForAgreement(
        LeaseAgreement $agreement
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Security Deposit
        |--------------------------------------------------------------------------
        */

        if ((float) $agreement->security_deposit > 0) {

            $exists = Deposit::where(
                'lease_agreement_id',
                $agreement->id
            )
            ->where(
                'deposit_type',
                'Security Deposit'
            )
            ->exists();

            if (!$exists) {

                Deposit::create([

                    'uuid' => (string) Str::uuid(),

                    'lease_agreement_id' =>
                        $agreement->id,

                    'deposit_type' =>
                        'Security Deposit',

                    'deposit_amount' =>
                        $agreement->security_deposit,

                    'received_amount' =>
                        0,

                    'balance_amount' =>
                        $agreement->security_deposit,

                    'due_date' =>
                        $agreement->rent_start_date,

                    'payment_status' =>
                        'Pending',

                    'refundable_amount' =>
                        0,

                    'remarks' =>
                        'Security deposit generated from lease agreement.',

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Utility Deposit
        |--------------------------------------------------------------------------
        */

        if ((float) $agreement->utility_deposit > 0) {

            $exists = Deposit::where(
                'lease_agreement_id',
                $agreement->id
            )
            ->where(
                'deposit_type',
                'Utility Deposit'
            )
            ->exists();

            if (!$exists) {

                Deposit::create([

                    'uuid' => (string) Str::uuid(),

                    'lease_agreement_id' =>
                        $agreement->id,

                    'deposit_type' =>
                        'Utility Deposit',

                    'deposit_amount' =>
                        $agreement->utility_deposit,

                    'received_amount' =>
                        0,

                    'balance_amount' =>
                        $agreement->utility_deposit,

                    'due_date' =>
                        $agreement->rent_start_date,

                    'payment_status' =>
                        'Pending',

                    'refundable_amount' =>
                        0,

                    'remarks' =>
                        'Utility deposit generated from lease agreement.',

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),
                ]);
            }
        }
    }
}