<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementPrequalificationCriterion extends Model
{
    protected $table = 'procurement_prequalification_criteria';

    protected $fillable = [
        'procurement_prequalification_id',
        'criterion_no',
        'criterion_name',
        'criterion_description',
        'criterion_type',
        'requirement',
        'bidder_response',
        'evaluation_result',
        'evaluator_remarks',
        'evaluated_by',
        'evaluated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
    ];

    public function prequalification(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPrequalification::class,
            'procurement_prequalification_id'
        );
    }
}