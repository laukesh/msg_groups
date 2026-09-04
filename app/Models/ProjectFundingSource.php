<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectFundingSource extends Model
{
    protected $fillable = [

        'project_funding_plan_id',

        'source_code',
        'source_name',
        'source_type',

        'provider_name',

        'planned_amount',
        'committed_amount',

        'interest_rate',
        'tenure_months',

        'sequence',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'planned_amount' => 'decimal:2',
        'committed_amount' => 'decimal:2',

        'interest_rate' => 'decimal:4',

        'tenure_months' => 'integer',
        'sequence' => 'integer',
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
    | Commitments
    |--------------------------------------------------------------------------
    */

    public function commitments(): HasMany
    {
        return $this->hasMany(
            ProjectFundingCommitment::class,
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
            'project_funding_source_id'
        )->orderBy('planned_date');
    }
}