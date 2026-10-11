<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProcurementTenderSubmission extends Model
{
    protected $table = 'procurement_tender_submissions';

    protected $fillable = [
        'project_id',
        'procurement_tender_id',
        'procurement_tender_bidder_id',
        'submission_number',
        'submission_date',
        'bid_validity_days',
        'bid_valid_until',
        'quoted_amount',
        'currency',
        'technical_submission',
        'commercial_submission',
        'compliance_declaration',
        'submission_status',
        'is_complete',
        'submitted_by',
        'submitted_by_name',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'bid_valid_until' => 'date',
        'quoted_amount' => 'decimal:2',
        'is_complete' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

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
    | Tender Bidder
    |--------------------------------------------------------------------------
    */

    public function tenderBidder(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderBidder::class,
            'procurement_tender_bidder_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Technical Evaluation
    |--------------------------------------------------------------------------
    */

    public function technicalEvaluation(): HasOne
    {
        return $this->hasOne(
            ProcurementTechnicalEvaluation::class,
            'procurement_tender_submission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Commercial Evaluation
    |--------------------------------------------------------------------------
    */

    public function commercialEvaluation(): HasOne
    {
        return $this->hasOne(
            ProcurementCommercialEvaluation::class,
            'procurement_tender_submission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bid Comparison Items
    |--------------------------------------------------------------------------
    */

    public function bidComparisonItems(): HasMany
    {
        return $this->hasMany(
            ProcurementBidComparisonItem::class,
            'procurement_tender_submission_id'
        );
    }
}