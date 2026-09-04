<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFundingTranche extends Model
{
    protected $fillable = [

        'project_funding_plan_id',
        'project_funding_source_id',
        'project_funding_commitment_id',

        'tranche_number',

        'planned_date',
        'planned_amount',

        'expected_date',

        'actual_amount',
        'actual_date',

        'status',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'tranche_number' => 'integer',

        'planned_date' => 'date',
        'expected_date' => 'date',
        'actual_date' => 'date',

        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Funding Plan
    |--------------------------------------------------------------------------
    */

    public function fundingPlan(): BelongsTo
    {
        return $this->belongsTo(
            ProjectFundingPlan::class,
            'project_funding_plan_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Funding Source
    |--------------------------------------------------------------------------
    */

    public function source(): BelongsTo
    {
        return $this->belongsTo(
            ProjectFundingSource::class,
            'project_funding_source_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Commitment
    |--------------------------------------------------------------------------
    */

    public function commitment(): BelongsTo
    {
        return $this->belongsTo(
            ProjectFundingCommitment::class,
            'project_funding_commitment_id'
        );
    }
}