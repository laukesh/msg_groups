<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseEnvironmentalAction extends Model
{
    protected $table =
        'construction_hse_environmental_actions';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'environmental_record_id',
        'environmental_compliance_id',

        'action_number',

        'action_title',

        'action_type',

        'priority',

        'action_description',

        'root_cause',

        'preventive_action',

        'assigned_to',
        'assigned_to_name',

        'assigned_date',

        'due_date',

        'completion_date',

        'completion_remarks',

        'verification_required',

        'verification_status',

        'verified_by',

        'verified_at',

        'verification_remarks',

        'status',

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

        'assigned_date' =>
            'date',

        'due_date' =>
            'date',

        'completion_date' =>
            'date',

        'verification_required' =>
            'boolean',

        'verified_at' =>
            'datetime',

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
    | Environmental Record
    |--------------------------------------------------------------------------
    */

    public function environmentalRecord(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseEnvironmentalRecord::class,
            'environmental_record_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Environmental Compliance
    |--------------------------------------------------------------------------
    */

    public function environmentalCompliance(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseEnvironmentalCompliance::class,
            'environmental_compliance_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned To
    |--------------------------------------------------------------------------
    */

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verified By
    |--------------------------------------------------------------------------
    */

    public function verifier(): BelongsTo
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
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isOpen(): bool
    {
        return $this->status === 'Open';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }


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


    public function isVerified(): bool
    {
        return $this->verification_status === 'Verified';
    }


    public function requiresVerification(): bool
    {
        return $this->verification_required === true;
    }
}