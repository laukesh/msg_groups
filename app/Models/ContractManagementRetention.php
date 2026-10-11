<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractManagementRetention extends Model
{
    protected $table = 'contract_management_retentions';

    protected $fillable = [

        'project_id',
        'contract_management_contract_id',

        'procurement_contract_invoice_id',
        'procurement_contract_payment_id',

        'retention_number',
        'retention_date',

        'invoice_number',
        'payment_reference',

        'certified_amount',
        'retention_percentage',
        'retention_amount',

        'released_amount',
        'balance_amount',

        'currency',

        'expected_release_date',
        'release_date',

        'status',

        'release_remarks',
        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'retention_date' => 'date',

        'expected_release_date' => 'date',

        'release_date' => 'date',

        'certified_amount' => 'decimal:2',

        'retention_percentage' => 'decimal:2',

        'retention_amount' => 'decimal:2',

        'released_amount' => 'decimal:2',

        'balance_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


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
    | Procurement Invoice
    |--------------------------------------------------------------------------
    */

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContractInvoice::class,
            'procurement_contract_invoice_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Payment
    |--------------------------------------------------------------------------
    */

    public function payment(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContractPayment::class,
            'procurement_contract_payment_id'
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
    | Release Helpers
    |--------------------------------------------------------------------------
    */

    public function isFullyReleased(): bool
    {
        return (float) $this->balance_amount <= 0;
    }


    public function isPartiallyReleased(): bool
    {
        return
            (float) $this->released_amount > 0 &&
            (float) $this->balance_amount > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Days Until Expected Release
    |--------------------------------------------------------------------------
    */

    public function daysUntilRelease(): ?int
    {
        if (!$this->expected_release_date) {
            return null;
        }

        return now()
            ->startOfDay()
            ->diffInDays(
                $this->expected_release_date,
                false
            );
    }
}