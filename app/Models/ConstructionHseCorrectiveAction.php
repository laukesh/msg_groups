<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseCorrectiveAction extends Model
{
    protected $table = 'construction_hse_corrective_actions';

    protected $fillable = [

        'construction_hse_observation_id',

        'action_number',
        'action_description',

        'responsible_user_id',
        'responsible_name',

        'due_date',

        'status',

        'completed_date',
        'completed_by',

        'verification_status',
        'verified_date',
        'verified_by',
        'verification_remarks',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'due_date' => 'date',

        'completed_date' => 'date',

        'verified_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Observation
    |--------------------------------------------------------------------------
    */

    public function observation(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseObservation::class,
            'construction_hse_observation_id'
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
    | Creator
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
    | Updater
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


    public function isResolved(): bool
    {
        return $this->status === 'Resolved';
    }


    public function isVerified(): bool
    {
        return $this->status === 'Verified'
            && $this->verification_status === 'Verified';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }


    /*
    |--------------------------------------------------------------------------
    | Overdue
    |--------------------------------------------------------------------------
    */

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        if (
            in_array(
                $this->status,
                [
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


    /*
    |--------------------------------------------------------------------------
    | Days Remaining
    |--------------------------------------------------------------------------
    */

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->due_date) {
            return null;
        }

        if (
            in_array(
                $this->status,
                [
                    'Verified',
                    'Closed',
                ],
                true
            )
        ) {
            return 0;
        }

        return now()->startOfDay()
            ->diffInDays(
                $this->due_date,
                false
            );
    }
}