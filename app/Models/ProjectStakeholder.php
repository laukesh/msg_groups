<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStakeholder extends Model
{
    protected $table = 'project_stakeholders';

    protected $fillable = [

        'project_id',

        'stakeholder_number',
        'stakeholder_name',

        'stakeholder_type',

        'organization_name',

        'role',

        'contact_person',

        'email',
        'phone',

        'influence_level',
        'interest_level',

        'engagement_level',

        'priority',

        'stakeholder_needs',

        'expectations',

        'concerns',

        'engagement_strategy',

        'communication_requirements',

        'communication_frequency',

        'stakeholder_owner_id',

        'status',

        'remarks',

        'created_by',
        'updated_by',
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
     * Stakeholder Owner
     */
    public function stakeholderOwner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'stakeholder_owner_id'
        );
    }
}