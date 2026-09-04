<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractPerformanceSecurity extends Model
{
    protected $table = 'contract_performance_securities';

    protected $fillable = [

        'contract_management_contract_id',

        'security_number',
        'security_type',
        'instrument_number',

        'issuing_bank',
        'issuing_branch',

        'beneficiary',

        'security_amount',
        'currency',

        'issue_date',
        'expiry_date',
        'submission_date',
        'verification_date',
        'claim_expiry_date',
        'release_date',

        'status',
        'verification_status',

        'extension_required',
        'extended_expiry_date',

        'released_amount',

        'release_remarks',
        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'security_amount' => 'decimal:2',

        'released_amount' => 'decimal:2',

        'issue_date' => 'date',

        'expiry_date' => 'date',

        'submission_date' => 'date',

        'verification_date' => 'date',

        'claim_expiry_date' => 'date',

        'release_date' => 'date',

        'extended_expiry_date' => 'date',

        'extension_required' => 'boolean',
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
    | Effective Expiry Date
    |--------------------------------------------------------------------------
    */

    public function effectiveExpiryDate()
    {
        if (
            $this->extended_expiry_date
        ) {
            return $this->extended_expiry_date;
        }

        return $this->expiry_date;
    }


    /*
    |--------------------------------------------------------------------------
    | Expired
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        $expiryDate =
            $this->effectiveExpiryDate();


        if (!$expiryDate) {
            return false;
        }


        return $expiryDate->lt(
            now()->startOfDay()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Days Until Expiry
    |--------------------------------------------------------------------------
    */

    public function daysUntilExpiry(): ?int
    {
        $expiryDate =
            $this->effectiveExpiryDate();


        if (!$expiryDate) {
            return null;
        }


        return now()
            ->startOfDay()
            ->diffInDays(
                $expiryDate,
                false
            );
    }
}