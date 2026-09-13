<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class HandoverProject extends Model
{
    protected $table = 'handover_projects';

    protected $fillable = [
        'project_id',
        'handover_no',
        'title',
        'description',
        'planned_handover_date',
        'actual_handover_date',
        'readiness_percentage',
        'status',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_handover_date' => 'date',
        'actual_handover_date' => 'date',
        'readiness_percentage' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(
            HandoverRequirement::class,
            'handover_project_id'
        );
    }

    public function snags(): HasMany
    {
        return $this->hasMany(
            HandoverSnag::class,
            'handover_project_id'
        );
    }

    public function practicalCompletion(): HasOne
    {
        return $this->hasOne(
            HandoverPracticalCompletion::class,
            'handover_project_id'
        );
    }

    public function finalCompletion(): HasOne
    {
        return $this->hasOne(
            HandoverFinalCompletion::class,
            'handover_project_id'
        );
    }

    public function finalAccount(): HasOne
    {
        return $this->hasOne(
            HandoverFinalAccount::class,
            'handover_project_id'
        );
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(
            HandoverCertificate::class,
            'handover_project_id'
        );
    }

    public function assetHandover(): HasOne
    {
        return $this->hasOne(
            HandoverAssetHandover::class,
            'handover_project_id'
        );
    }
    
    public function finalAccounts(): HasMany
    {
        return $this->hasMany(
            HandoverFinalAccount::class,
            'handover_project_id'
        );
    }

}