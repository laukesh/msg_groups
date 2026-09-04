<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementContractInvoice extends Model
{
    protected $table = 'procurement_contract_invoices';

    protected $fillable = [

        'procurement_contract_id',
        'procurement_contract_milestone_id',

        'invoice_number',
        'invoice_date',
        'invoice_type',

        'description',

        'amount',
        'tax_amount',
        'discount_amount',
        'net_amount',

        'currency',

        'status',

        'submitted_at',
        'approved_at',
        'rejected_at',
        'paid_at',

        'submitted_by',
        'approved_by',
        'rejected_by',

        'remarks',
        'rejection_remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'invoice_date' => 'date',

        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',

        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
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

    public function payments(): HasMany
    {
        return $this->hasMany(
            ProcurementContractPayment::class,
            'procurement_contract_invoice_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Processed Payments
    |--------------------------------------------------------------------------
    */

    public function processedPayments(): HasMany
    {
        return $this->hasMany(
            ProcurementContractPayment::class,
            'procurement_contract_invoice_id'
        )->where(
            'status',
            'Processed'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    public function getPaidAmountAttribute(): float
    {
        return (float) $this
            ->payments()
            ->where('status', 'Processed')
            ->sum('amount');
    }


    /*
    |--------------------------------------------------------------------------
    | Balance Amount
    |--------------------------------------------------------------------------
    */

    public function getBalanceAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->net_amount
            - $this->paid_amount
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Is Fully Paid
    |--------------------------------------------------------------------------
    */

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->balance_amount <= 0;
    }
}