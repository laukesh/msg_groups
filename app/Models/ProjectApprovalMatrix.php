<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectApprovalMatrix extends Model
{
    protected $table = 'project_approval_matrix';

    protected $fillable = [

        'project_id',

        'project_governance_id',

        'approval_code',
        'approval_type',

        'description',

        'authority_role',
        'authority_user_id',

        'minimum_amount',
        'maximum_amount',

        'currency',

        'approval_sequence',

        'requires_multiple_approvals',

        'is_mandatory',

        'status',

        'effective_date',
        'expiry_date',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'minimum_amount' => 'decimal:2',

        'maximum_amount' => 'decimal:2',

        'requires_multiple_approvals' => 'boolean',

        'is_mandatory' => 'boolean',

        'effective_date' => 'date',

        'expiry_date' => 'date',
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


    public function authorityUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'authority_user_id'
        );
    }
}