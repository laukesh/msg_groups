<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectFundingCommitment extends Model
{
    protected $fillable = [

        'project_funding_plan_id',
        'project_funding_source_id',

        'commitment_number',
        'commitment_date',

        'committed_amount',
        'approved_amount',

        'provider_name',

        'reference_number',

        'status',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'commitment_date' => 'date',

        'committed_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
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
    | Tranches
    |--------------------------------------------------------------------------
    */

    public function tranches(): HasMany
    {
        return $this->hasMany(
            ProjectFundingTranche::class,
            'project_funding_commitment_id'
        )->orderBy('planned_date');
    }
}