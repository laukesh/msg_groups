<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectFundingPlan extends Model
{
    protected $fillable = [

        'project_id',
        'basis_budget_id',

        'funding_plan_number',
        'title',
        'version_number',

        'status',
        'currency',

        'total_funding_requirement',
        'total_planned_funding',
        'total_committed_funding',
        'funding_gap',

        'effective_date',

        'approved_date',
        'approved_by',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'version_number' => 'integer',

        'total_funding_requirement' => 'decimal:2',
        'total_planned_funding' => 'decimal:2',
        'total_committed_funding' => 'decimal:2',
        'funding_gap' => 'decimal:2',

        'effective_date' => 'date',
        'approved_date' => 'date',
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
    | Basis Budget
    |--------------------------------------------------------------------------
    */

    public function basisBudget(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudget::class,
            'basis_budget_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sources
    |--------------------------------------------------------------------------
    */

    public function sources()
    {
        return $this->hasMany(
            ProjectFundingSource::class,
            'project_funding_plan_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Commitments
    |--------------------------------------------------------------------------
    */

    public function commitments()
    {
        return $this->hasMany(
            ProjectFundingCommitment::class,
            'project_funding_plan_id'
        );
    }

    public function tranches()
    {
        return $this->hasMany(
            ProjectFundingTranche::class,
            'project_funding_plan_id'
        );
    }

    public function histories()
    {
        return $this->hasMany(
            ProjectFundingPlanHistory::class,
            'project_funding_plan_id'
        )->latest('performed_at');
    }

    public function getCalculatedPlannedFundingAttribute(): float
    {
        return (float) $this->sources->sum('planned_amount');
    }

    public function getCalculatedCommittedFundingAttribute(): float
    {
        return (float) $this->commitments->sum('committed_amount');
    }

    public function getCalculatedActualFundingAttribute(): float
    {
        return (float) $this->tranches->sum('actual_amount');
    }

    public function getPlannedFundingGapAttribute(): float
    {
        return max(
            (float) $this->total_funding_requirement
            - $this->calculated_planned_funding,
            0
        );
    }

    public function getCommittedFundingGapAttribute(): float
    {
        return max(
            (float) $this->total_funding_requirement
            - $this->calculated_committed_funding,
            0
        );
    }

    public function getActualFundingGapAttribute(): float
    {
        return max(
            (float) $this->total_funding_requirement
            - $this->calculated_actual_funding,
            0
        );
    }
}