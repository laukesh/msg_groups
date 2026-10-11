<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HandoverCertificate extends Model
{
    protected $table = 'handover_certificates';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'certificate_no',
        'certificate_type',
        'title',
        'description',
        'issue_date',
        'effective_date',
        'status',
        'final_completion_id',
        'issued_by',
        'submitted_by',
        'reviewed_by',
        'approved_by',
        'issued_at',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'rejection_reason',
        'certificate_statement',
        'remarks',
        'document_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'effective_date' => 'date',
        'issued_at' => 'datetime',
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

    public function finalCompletion(): BelongsTo
    {
        return $this->belongsTo(
            HandoverFinalCompletion::class,
            'final_completion_id'
        );
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
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

    public function assetHandover(): HasOne
    {
        return $this->hasOne(
            HandoverAssetHandover::class,
            'certificate_id'
        );
    }
}