<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class HandoverFinalCompletion extends Model
{
    protected $table = 'handover_final_completions';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'completion_no',

        'planned_date',
        'submitted_date',
        'approved_date',

        'status',
        'readiness_percentage',

        'practical_completion_complete',
        'final_account_complete',
        'final_payment_complete',
        'defects_complete',
        'documents_complete',
        'handover_certificate_complete',

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

        'practical_completion_complete' => 'boolean',
        'final_account_complete' => 'boolean',
        'final_payment_complete' => 'boolean',
        'defects_complete' => 'boolean',
        'documents_complete' => 'boolean',
        'handover_certificate_complete' => 'boolean',

        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

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

    public function certificate(): HasOne
    {
        return $this->hasOne(
            HandoverCertificate::class,
            'final_completion_id'
        );
    }
}