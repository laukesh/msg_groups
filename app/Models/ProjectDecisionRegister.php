<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDecisionRegister extends Model
{
    protected $table = 'project_decision_register';

    protected $fillable = [

        'project_id',

        'project_governance_id',

        'decision_number',

        'decision_date',

        'decision_type',

        'subject',

        'decision',

        'rationale',

        'decision_maker_role',

        'decision_maker_id',

        'priority',

        'impact_description',

        'financial_impact',

        'schedule_impact_days',

        'status',

        'implementation_required',

        'implementation_owner_id',

        'implementation_due_date',

        'implemented_date',

        'reference_number',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'decision_date' => 'date',

        'financial_impact' => 'decimal:2',

        'schedule_impact_days' => 'integer',

        'implementation_required' => 'boolean',

        'implementation_due_date' => 'date',

        'implemented_date' => 'date',
    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    public function governance(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernance::class,
            'project_governance_id'
        );
    }


    public function decisionMaker(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'decision_maker_id'
        );
    }


    public function implementationOwner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'implementation_owner_id'
        );
    }
}