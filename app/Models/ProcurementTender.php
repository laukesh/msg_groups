<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementTender extends Model
{
    protected $table = 'procurement_tenders';

    protected $fillable = [
        'procurement_package_id',

        'tender_number',
        'tender_title',

        'tender_type',
        'procurement_method',

        'estimated_value',
        'currency',

        'tender_fee',
        'emd_amount',

        'issue_date',
        'submission_start_date',
        'submission_deadline',
        'opening_date',

        'technical_evaluation_date',
        'commercial_evaluation_date',
        'planned_award_date',

        'prequalification_required',

        'description',
        'scope_of_work',
        'terms_and_conditions',

        'status',

        'responsible_user_id',
        'responsible_name',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'tender_fee' => 'decimal:2',
        'emd_amount' => 'decimal:2',

        'prequalification_required' => 'boolean',

        'issue_date' => 'date',
        'submission_start_date' => 'date',
        'submission_deadline' => 'date',
        'opening_date' => 'date',

        'technical_evaluation_date' => 'date',
        'commercial_evaluation_date' => 'date',
        'planned_award_date' => 'date',
    ];


    /**
     * Tender belongs to Procurement Package.
     */
    public function procurementPackage(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPackage::class,
            'procurement_package_id'
        );
    }


    /**
     * Responsible user.
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /**
     * Created by.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /**
     * Updated by.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function tenderBidders(): HasMany
    {
        return $this->hasMany(
            ProcurementTenderBidder::class,
            'procurement_tender_id'
        );
    }

    public function prequalifications(): HasMany
    {
        return $this->hasMany(
            ProcurementPrequalification::class,
            'procurement_tender_id'
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(
            ProcurementTenderDocument::class,
            'procurement_tender_id'
        );
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(
            ProcurementTenderSubmission::class,
            'procurement_tender_id'
        );
    }

    public function technicalEvaluations(): HasMany
    {
        return $this->hasMany(
            ProcurementTechnicalEvaluation::class,
            'procurement_tender_id'
        );
    }

    public function commercialEvaluations(): HasMany
    {
        return $this->hasMany(
            ProcurementCommercialEvaluation::class,
            'procurement_tender_id'
        );
    }

    public function bidComparisons(): HasMany
    {
        return $this->hasMany(
            ProcurementBidComparison::class,
            'procurement_tender_id'
        );
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPackage::class,
            'procurement_package_id'
        );
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(
            ProcurementNegotiation::class,
            'procurement_tender_id'
        );
    }

    public function awards(): HasMany
    {
        return $this->hasMany(
            ProcurementAward::class,
            'procurement_tender_id'
        );
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(
            ProcurementContract::class,
            'procurement_tender_id'
        );
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(
            ProcurementPurchaseOrder::class,
            'procurement_tender_id'
        );
    }
}