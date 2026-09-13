<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HandoverAssetHandover extends Model
{
    protected $table = 'handover_asset_handovers';

    protected $fillable = [
        'project_id',
        'handover_project_id',
        'certificate_id',
        'handover_no',
        'title',
        'description',
        'handover_date',
        'status',
        'asset_count',
        'prepared_by',
        'submitted_by',
        'reviewed_by',
        'approved_by',
        'prepared_at',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'rejection_reason',
        'handover_statement',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'handover_date' => 'date',
        'asset_count' => 'integer',
        'prepared_at' => 'datetime',
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

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(
            HandoverCertificate::class,
            'certificate_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            HandoverAssetItem::class,
            'asset_handover_id'
        );
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
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