<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementCommercialEvaluation extends Model
{
    protected $table = 'procurement_commercial_evaluations';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_tender_submission_id',
        'procurement_technical_evaluation_id',

        'evaluation_number',
        'evaluation_date',

        'evaluator_id',
        'evaluator_name',

        'quoted_amount',
        'evaluated_amount',
        'tax_amount',
        'discount_amount',
        'final_evaluated_amount',

        'currency',

        'price_score',
        'maximum_price_score',

        'commercial_compliance',

        'result',

        'evaluation_summary',
        'strengths',
        'weaknesses',
        'remarks',

        'status',

        'evaluated_at',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'evaluation_date' => 'date',

        'evaluated_at' => 'datetime',

        'quoted_amount' => 'decimal:2',
        'evaluated_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_evaluated_amount' => 'decimal:2',

        'price_score' => 'decimal:2',
        'maximum_price_score' => 'decimal:2',
    ];


    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }


    public function submission(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderSubmission::class,
            'procurement_tender_submission_id'
        );
    }


    public function technicalEvaluation(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTechnicalEvaluation::class,
            'procurement_technical_evaluation_id'
        );
    }

    public function bidComparisonItems(): HasMany
    {
        return $this->hasMany(
            ProcurementBidComparisonItem::class,
            'procurement_commercial_evaluation_id'
        );
    }
}