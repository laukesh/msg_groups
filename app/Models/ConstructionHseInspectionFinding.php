<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseInspectionFinding extends Model
{
    protected $table =
        'construction_hse_inspection_findings';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_inspection_id',
        'construction_hse_inspection_item_id',

        'finding_number',

        'finding_date',

        'finding_type',

        'finding_title',

        'finding_description',

        'severity',

        'immediate_action',

        'recommended_action',

        'responsible_user_id',
        'responsible_name',

        'due_date',

        'status',

        'verification_status',

        'verified_date',
        'verified_by',
        'verification_remarks',

        'remarks',

        'created_by',
        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'finding_date' => 'date',

        'due_date' => 'date',

        'verified_date' => 'date',

    ];


    /*
    |--------------------------------------------------------------------------
    | Inspection
    |--------------------------------------------------------------------------
    */

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseInspection::class,
            'construction_hse_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Checklist Item
    |--------------------------------------------------------------------------
    */

    public function inspectionItem(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseInspectionItem::class,
            'construction_hse_inspection_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verified By
    |--------------------------------------------------------------------------
    */

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
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

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Corrective Actions
    |--------------------------------------------------------------------------
    |
    | This relationship will be used when we build the next
    | Corrective Actions module.
    |
    */

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionHseInspectionAction::class,
            'construction_hse_inspection_finding_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isOpen(): bool
    {
        return $this->status === 'Open';
    }


    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }


    public function isResolved(): bool
    {
        return $this->status === 'Resolved';
    }


    public function isVerified(): bool
    {
        return $this->status === 'Verified';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }


    /*
    |--------------------------------------------------------------------------
    | Verification Helpers
    |--------------------------------------------------------------------------
    */

    public function isPendingVerification(): bool
    {
        return $this->verification_status === 'Pending';
    }


    public function verificationCompleted(): bool
    {
        return $this->verification_status === 'Verified';
    }


    public function verificationRejected(): bool
    {
        return $this->verification_status === 'Rejected';
    }


    /*
    |--------------------------------------------------------------------------
    | Severity Helpers
    |--------------------------------------------------------------------------
    */

    public function isCritical(): bool
    {
        return $this->severity === 'Critical';
    }


    public function isHighSeverity(): bool
    {
        return $this->severity === 'High';
    }


    /*
    |--------------------------------------------------------------------------
    | Overdue
    |--------------------------------------------------------------------------
    */

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        if (
            in_array(
                $this->status,
                [
                    'Resolved',
                    'Verified',
                    'Closed',
                ],
                true
            )
        ) {
            return false;
        }

        return $this->due_date->isPast();
    }
}