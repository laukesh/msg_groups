<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractManagementAdvancePayment extends Model
{
    protected $table = 'contract_management_advance_payments';

    protected $fillable = [

        'project_id',
        'contract_management_contract_id',

        'procurement_contract_invoice_id',
        'procurement_contract_payment_id',

        'advance_number',
        'transaction_date',

        'transaction_type',
        'reference_number',

        'certified_amount',
        'advance_amount',
        'recovered_amount',
        'balance_amount',

        'currency',

        'expected_recovery_date',
        'recovery_date',

        'status',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'transaction_date' => 'date',

        'expected_recovery_date' => 'date',

        'recovery_date' => 'date',

        'certified_amount' => 'decimal:2',

        'advance_amount' => 'decimal:2',

        'recovered_amount' => 'decimal:2',

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
}