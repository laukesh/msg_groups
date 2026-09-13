<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProjectGovernance extends Model
{
    protected $table = 'project_governance';

    protected $fillable = [

        'project_id',

        'governance_number',
        'title',

        'governance_model',

        'project_sponsor_id',
        'project_director_id',
        'project_manager_id',

        'governance_objective',

        'decision_making_framework',

        'escalation_framework',

        'approval_framework',

        'reporting_framework',

        'meeting_framework',

        'status',

        'effective_date',
        'review_date',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'effective_date' => 'date',

        'review_date' => 'date',
    ];


    /**
     * Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /**
     * Project Sponsor
     */
    public function projectSponsor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'project_sponsor_id'
        );
    }


    /**
     * Project Director
     */
    public function projectDirector(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'project_director_id'
        );
    }


    /**
     * Project Manager
     */
    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'project_manager_id'
        );
    }

    public function approvalMatrix(): HasMany
    {
        return $this->hasMany(
            ProjectApprovalMatrix::class,
            'project_governance_id'
        );
    }

    public function decisionRegister(): HasMany
    {
        return $this->hasMany(
            ProjectDecisionRegister::class,
            'project_governance_id'
        );
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeeting::class,
            'project_governance_id'
        );
    }
}