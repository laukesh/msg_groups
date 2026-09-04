<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRisk extends Model
{
    protected $table = 'project_risks';

    protected $fillable = [

        'project_id',

        'risk_number',
        'risk_title',

        'risk_category',

        'risk_description',

        'cause',
        'consequence',

        'probability',
        'impact',

        'risk_score',
        'risk_level',

        'response_strategy',

        'mitigation_plan',
        'contingency_plan',

        'risk_owner_id',

        'target_date',

        'status',

        'residual_probability',
        'residual_impact',

        'residual_score',
        'residual_risk_level',

        'identified_date',
        'closed_date',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'risk_score' => 'integer',

        'residual_score' => 'integer',

        'target_date' => 'date',

        'identified_date' => 'date',

        'closed_date' => 'date',
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
     * Risk Owner
     *
     * This intentionally remains a plain belongsTo
     * only if your users table is mapped to User.
     */
    public function riskOwner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'risk_owner_id'
        );
    }
}