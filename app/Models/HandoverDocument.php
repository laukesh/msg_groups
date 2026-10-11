<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverDocument extends Model
{
    protected $table = 'handover_documents';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'requirement_id',

        'document_no',
        'document_type',
        'title',
        'description',
        'document_version',

        'file_name',
        'file_path',
        'mime_type',
        'file_size',

        'status',

        'uploaded_by',
        'uploaded_at',

        'submitted_by',
        'submitted_at',

        'reviewed_by',
        'reviewed_at',

        'approved_by',
        'approved_at',

        'rejection_reason',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
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
    | Requirement
    |--------------------------------------------------------------------------
    */

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            HandoverRequirement::class,
            'requirement_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
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
}