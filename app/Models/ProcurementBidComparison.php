<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementBidComparison extends Model
{
    protected $table = 'procurement_bid_comparisons';

    protected $fillable = [
        'procurement_tender_id',

        'comparison_number',
        'comparison_title',
        'comparison_date',

        'evaluation_basis',

        'total_bidders',
        'qualified_bidders',

        'lowest_evaluated_amount',
        'currency',

        'recommended_submission_id',

        'status',

        'prepared_by',
        'reviewed_by',
        'approved_by',
        'approval_date',

        'summary',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'comparison_date' => 'date',
        'approval_date' => 'date',

        'lowest_evaluated_amount' => 'decimal:2',

        'total_bidders' => 'integer',
        'qualified_bidders' => 'integer',
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
    | Recommended Submission
    |--------------------------------------------------------------------------
    */

    public function recommendedSubmission(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderSubmission::class,
            'recommended_submission_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Comparison Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProcurementBidComparisonItem::class,
            'procurement_bid_comparison_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prepared By
    |--------------------------------------------------------------------------
    */

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewed By
    |--------------------------------------------------------------------------
    */

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(
            ProcurementNegotiation::class,
            'procurement_bid_comparison_id'
        );
    }
}