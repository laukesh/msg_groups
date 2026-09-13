<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverPracticalCompletion extends Model
{
    protected $table = 'handover_practical_completions';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'completion_no',
        'planned_date',
        'submitted_date',
        'approved_date',
        'status',
        'readiness_percentage',

        'commissioning_complete',
        'snagging_complete',
        'defects_complete',
        'documents_complete',
        'requirements_complete',

        'submitted_by',
        'reviewed_by',
        'approved_by',

        'submitted_at',
        'reviewed_at',
        'approved_at',

        'rejection_reason',
        'completion_statement',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'submitted_date' => 'date',
        'approved_date' => 'date',

        'readiness_percentage' => 'decimal:2',

        'commissioning_complete' => 'boolean',
        'snagging_complete' => 'boolean',
        'defects_complete' => 'boolean',
        'documents_complete' => 'boolean',
        'requirements_complete' => 'boolean',

        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
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
    | Handover
    |--------------------------------------------------------------------------
    */

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}