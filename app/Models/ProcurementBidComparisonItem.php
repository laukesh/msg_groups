<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementBidComparisonItem extends Model
{
    protected $table = 'procurement_bid_comparison_items';

    protected $fillable = [

        'procurement_bid_comparison_id',

        'procurement_tender_submission_id',

        'procurement_technical_evaluation_id',

        'procurement_commercial_evaluation_id',

        'bidder_rank',

        'bidder_name',

        'quoted_amount',
        'evaluated_amount',

        'tax_amount',
        'discount_amount',

        'final_evaluated_amount',

        'currency',

        'technical_score',
        'price_score',
        'overall_score',

        'commercial_compliance',

        'comparison_result',

        'is_recommended',

        'remarks',
    ];

    protected $casts = [

        'quoted_amount' => 'decimal:2',
        'evaluated_amount' => 'decimal:2',

        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',

        'final_evaluated_amount' => 'decimal:2',

        'technical_score' => 'decimal:2',
        'price_score' => 'decimal:2',
        'overall_score' => 'decimal:2',

        'bidder_rank' => 'integer',

        'is_recommended' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Comparison
    |--------------------------------------------------------------------------
    */

    public function comparison(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidComparison::class,
            'procurement_bid_comparison_id'
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
    | Technical Evaluation
    |--------------------------------------------------------------------------
    */

    public function technicalEvaluation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTechnicalEvaluation::class,
            'procurement_technical_evaluation_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Commercial Evaluation
    |--------------------------------------------------------------------------
    */

    public function commercialEvaluation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementCommercialEvaluation::class,
            'procurement_commercial_evaluation_id'
        );
    }
}