<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementPrequalificationCriterion extends Model
{
    protected $table = 'procurement_prequalification_criteria';

    protected $fillable = [
        'project_id',

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

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Prequalification
    |--------------------------------------------------------------------------
    */

    public function prequalification(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementPrequalification::class,
            'procurement_prequalification_id'
        );
    }
}