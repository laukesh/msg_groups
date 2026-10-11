<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractInsurance extends Model
{
    protected $table = 'contract_insurances';

    protected $fillable = [

        'contract_management_contract_id',

        'insurance_number',

        'insurance_type',

        'policy_number',

        'insurer_name',

        'insured_party',

        'beneficiary',

        'coverage_amount',

        'currency',

        'policy_start_date',

        'policy_expiry_date',

        'submission_date',

        'verification_date',

        'renewal_date',

        'status',

        'compliance_status',

        'days_before_expiry_alert',

        'premium_amount',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'coverage_amount' => 'decimal:2',

        'premium_amount' => 'decimal:2',

        'policy_start_date' => 'date',

        'policy_expiry_date' => 'date',

        'submission_date' => 'date',

        'verification_date' => 'date',

        'renewal_date' => 'date',

        'days_before_expiry_alert' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ContractManagementContract::class,
            'contract_management_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updater
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Expiry Helpers
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        if (!$this->policy_expiry_date) {
            return false;
        }

        return $this->policy_expiry_date
            ->isPast();
    }


    public function daysUntilExpiry(): ?int
    {
        if (!$this->policy_expiry_date) {
            return null;
        }

        return now()->startOfDay()
            ->diffInDays(
                $this->policy_expiry_date,
                false
            );
    }


    public function isExpiringSoon(): bool
    {
        $days = $this->daysUntilExpiry();

        if ($days === null) {
            return false;
        }

        return $days >= 0 &&
            $days <= $this->days_before_expiry_alert;
    }
}