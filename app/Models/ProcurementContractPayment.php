<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementContractPayment extends Model
{
    protected $table = 'procurement_contract_payments';

    protected $fillable = [

        'procurement_contract_id',
        'procurement_contract_invoice_id',
        'procurement_contract_milestone_id',

        'payment_number',
        'payment_date',
        'payment_type',

        'amount',
        'currency',

        'payment_method',
        'transaction_reference',

        'bank_name',
        'account_reference',

        'description',
        'remarks',

        'status',

        'submitted_at',
        'submitted_by',

        'approved_at',
        'approved_by',

        'rejected_at',
        'rejected_by',
        'rejection_remarks',

        'processed_at',
        'processed_by',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'payment_date' => 'date',

        'amount' => 'decimal:2',

        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'processed_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invoice
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
    | Milestone
    |--------------------------------------------------------------------------
    */

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContractMilestone::class,
            'procurement_contract_milestone_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'rejected_by'
        );
    }


    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }


    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }


    public function isProcessed(): bool
    {
        return $this->status === 'Processed';
    }


    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }


    /*
    |--------------------------------------------------------------------------
    | Workflow
    |--------------------------------------------------------------------------
    */

    public function canBeSubmitted(): bool
    {
        return $this->status === 'Draft';
    }


    public function canBeApproved(): bool
    {
        return $this->status === 'Submitted';
    }


    public function canBeProcessed(): bool
    {
        return $this->status === 'Approved'
            && $this->invoice
            && $this->invoice->balance_amount >= (float) $this->amount;
    }


    public function canBeRejected(): bool
    {
        return in_array(
            $this->status,
            [
                'Draft',
                'Submitted',
                'Approved',
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invoice Balance
    |--------------------------------------------------------------------------
    */

    public function getInvoiceBalanceAttribute(): float
    {
        if (!$this->invoice) {
            return 0;
        }

        return (float) $this->invoice->balance_amount;
    }


    /*
    |--------------------------------------------------------------------------
    | Invoice Paid Amount
    |--------------------------------------------------------------------------
    */

    public function getInvoicePaidAmountAttribute(): float
    {
        if (!$this->invoice) {
            return 0;
        }

        return (float) $this->invoice->paid_amount;
    }


    /*
    |--------------------------------------------------------------------------
    | Remaining Invoice Balance
    |--------------------------------------------------------------------------
    */

    public function getRemainingInvoiceBalanceAttribute(): float
    {
        if (!$this->invoice) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | If already processed, invoice balance already includes this payment
        |--------------------------------------------------------------------------
        */

        if ($this->status === 'Processed') {
            return (float) $this->invoice->balance_amount;
        }

        return max(
            0,
            (float) $this->invoice->balance_amount
                - (float) $this->amount
        );
    }
}