<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementTechnicalEvaluation extends Model
{
    protected $table = 'procurement_technical_evaluations';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_tender_submission_id',

        'evaluation_number',
        'evaluation_date',

        'evaluator_id',
        'evaluator_name',

        'technical_score',
        'maximum_score',
        'passing_score',

        'result',
        'technical_compliance',

        'strengths',
        'weaknesses',
        'evaluation_summary',
        'remarks',

        'status',
        'evaluated_at',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'evaluated_at' => 'datetime',

        'technical_score' => 'decimal:2',
        'maximum_score' => 'decimal:2',
        'passing_score' => 'decimal:2',
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

    public function commercialEvaluation(): HasOne
    {
        return $this->hasOne(
            ProcurementCommercialEvaluation::class,
            'procurement_technical_evaluation_id'
        );
    }

    public function bidComparisonItems(): HasMany
    {
        return $this->hasMany(
            ProcurementBidComparisonItem::class,
            'procurement_technical_evaluation_id'
        );
    }
}