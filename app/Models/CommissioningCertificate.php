<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissioningCertificate extends Model
{
    protected $table = 'commissioning_certificates';

    protected $fillable = [
        'project_id',
        'commissioning_scope_id',

        'certificate_no',
        'certificate_type',

        'description',

        'issue_date',
        'valid_until',

        'status',

        'document_reference',
        'document_path',

        'issued_by',
        'approved_by',
        'approved_at',

        'submitted_at',

        'rejection_reason',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date'   => 'date',
        'valid_until'  => 'date',
        'approved_at'  => 'datetime',
        'submitted_at' => 'datetime',
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
    | Commissioning Scope
    |--------------------------------------------------------------------------
    */

    public function scope(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningScope::class,
            'commissioning_scope_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Issued By
    |--------------------------------------------------------------------------
    */

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            CommissioningDocument::class,
            'certificate_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public function isExpired(): bool
    {
        return $this->valid_until
            && $this->valid_until->isPast();
    }
}