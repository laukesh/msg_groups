<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementContract extends Model
{
    protected $table = 'procurement_contracts';

    protected $fillable = [

        'procurement_tender_id',
        'procurement_award_id',
        'procurement_negotiation_id',
        'procurement_tender_submission_id',
        'procurement_bidder_id',

        'contract_number',
        'contract_title',
        'contract_type',

        'bidder_name',

        'contract_amount',
        'currency',

        'contract_start_date',
        'contract_end_date',
        'contract_duration_days',

        'signing_date',

        'status',

        'loa_number',
        'loa_date',

        'performance_security_required',
        'performance_security_amount',

        'retention_required',
        'retention_percentage',

        'responsible_user_id',

        'submitted_by',
        'submitted_at',

        'approved_by',
        'approval_date',
        'approval_remarks',

        'activated_by',
        'activated_at',

        'scope_of_work',
        'terms_and_conditions',
        'special_conditions',

        'remarks',

        'created_by',
        'updated_by',

        'completion_date',
        'closed_at',
        'closed_by',
        'closure_remarks',
    ];

    protected $casts = [

        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'signing_date' => 'date',
        'loa_date' => 'date',
        'approval_date' => 'date',

        'submitted_at' => 'datetime',
        'activated_at' => 'datetime',

        'contract_amount' => 'decimal:2',
        'performance_security_amount' => 'decimal:2',
        'retention_percentage' => 'decimal:2',

        'performance_security_required' => 'boolean',
        'retention_required' => 'boolean',

        'completion_date' => 'date',
        'closed_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Tender
    |--------------------------------------------------------------------------
    */

    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Award
    |--------------------------------------------------------------------------
    */

    public function award(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementAward::class,
            'procurement_award_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Negotiation
    |--------------------------------------------------------------------------
    */

    public function negotiation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementNegotiation::class,
            'procurement_negotiation_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tender Submission
    |--------------------------------------------------------------------------
    */

    public function submission(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderSubmission::class,
            'procurement_tender_submission_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contractor / Supplier
    |--------------------------------------------------------------------------
    */

    public function bidder(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidder::class,
            'procurement_bidder_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Milestones
    |--------------------------------------------------------------------------
    */

    public function milestones(): HasMany
    {
        return $this->hasMany(
            ProcurementContractMilestone::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invoices
    |--------------------------------------------------------------------------
    */

    public function invoices(): HasMany
    {
        return $this->hasMany(
            ProcurementContractInvoice::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(
            ProcurementContractPayment::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Purchase Orders
    |--------------------------------------------------------------------------
    */

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(
            ProcurementPurchaseOrder::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }


    public function canBeClosed(): bool
    {
        return $this->status === 'Completed';
    }

    /*
    |--------------------------------------------------------------------------
    | Procurement Package
    |--------------------------------------------------------------------------
    */

    public function package()
    {
        return $this->hasOneThrough(
            ProcurementPackage::class,
            ProcurementTender::class,
            'id',
            'id',
            'procurement_tender_id',
            'procurement_package_id'
        );
    }

    public function variations(): HasMany
    {
        return $this->hasMany(
            ConstructionVariation::class,
            'procurement_contract_id'
        );
    }
}