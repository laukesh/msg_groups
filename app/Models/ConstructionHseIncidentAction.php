<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseIncidentAction extends Model
{
    protected $table =
        'construction_hse_incident_actions';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_incident_id',

        'action_number',
        'action_type',

        'action_description',

        'responsible_user_id',
        'responsible_name',

        'due_date',

        'status',

        'completed_date',
        'completed_by',
        'completion_remarks',

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

        'due_date' =>
            'date',

        'completed_date' =>
            'date',

        'verified_date' =>
            'date',

    ];


    /*
    |--------------------------------------------------------------------------
    | Incident
    |--------------------------------------------------------------------------
    */

    public function incident(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseIncident::class,
            'construction_hse_incident_id'
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
    | Completed By
    |--------------------------------------------------------------------------
    */

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
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


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
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


    public function isVerified(): bool
    {
        return $this->verification_status === 'Verified';
    }


    public function isRejected(): bool
    {
        return $this->verification_status === 'Rejected';
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
                    'Completed',
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