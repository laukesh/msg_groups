<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseIncident extends Model
{
    protected $table = 'construction_hse_incidents';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'incident_number',

        'incident_date',
        'incident_time',

        'location',

        'incident_type',
        'severity',

        'description',

        'contractor_id',

        'reported_by',
        'reported_by_name',

        'immediate_action',

        'injury_occurred',
        'injury_details',

        'property_damage',
        'property_damage_details',

        'work_stopped',
        'work_stoppage_details',

        'investigation_date',
        'investigator_id',

        'status',

        'closed_date',
        'closed_by',
        'closure_remarks',

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

        'incident_date' => 'date',

        'incident_time' => 'string',

        'investigation_date' => 'date',

        'closed_date' => 'date',

        'injury_occurred' => 'boolean',

        'property_damage' => 'boolean',

        'work_stopped' => 'boolean',
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
    | Contractor
    |
    | Contractor is managed through ProcurementBidder
    |--------------------------------------------------------------------------
    */

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidder::class,
            'contractor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reporter
    |--------------------------------------------------------------------------
    */

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Investigator
    |--------------------------------------------------------------------------
    */

    public function investigator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'investigator_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Closed By
    |--------------------------------------------------------------------------
    */

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
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
    | Incident Persons
    |--------------------------------------------------------------------------
    |
    | Will be implemented in Step 2.
    |--------------------------------------------------------------------------
    */

    public function persons(): HasMany
    {
        return $this->hasMany(
            ConstructionHseIncidentPerson::class,
            'construction_hse_incident_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Witnesses
    |--------------------------------------------------------------------------
    |
    | Will be implemented in Step 3.
    |--------------------------------------------------------------------------
    */

    public function witnesses(): HasMany
    {
        return $this->hasMany(
            ConstructionHseIncidentWitness::class,
            'construction_hse_incident_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Investigations
    |--------------------------------------------------------------------------
    |
    | Will be implemented in Step 4.
    |--------------------------------------------------------------------------
    */

    public function investigations(): HasMany
    {
        return $this->hasMany(
            ConstructionHseIncidentInvestigation::class,
            'construction_hse_incident_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Incident Actions
    |--------------------------------------------------------------------------
    |
    | Will be implemented in Step 5.
    |--------------------------------------------------------------------------
    */

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionHseIncidentAction::class,
            'construction_hse_incident_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Incident Documents
    |--------------------------------------------------------------------------
    |
    | Will be implemented later.
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ConstructionHseIncidentDocument::class,
            'construction_hse_incident_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isReported(): bool
    {
        return $this->status === 'Reported';
    }


    public function isUnderInvestigation(): bool
    {
        return $this->status === 'Under Investigation';
    }


    public function isInvestigationCompleted(): bool
    {
        return $this->status === 'Investigation Completed';
    }


    public function isActionsAssigned(): bool
    {
        return $this->status === 'Actions Assigned';
    }


    public function isActionsCompleted(): bool
    {
        return $this->status === 'Actions Completed';
    }


    public function isVerified(): bool
    {
        return $this->status === 'Verified';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }
}