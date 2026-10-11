<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseIncidentInvestigation extends Model
{
    protected $table =
        'construction_hse_incident_investigations';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_incident_id',

        'investigation_number',

        'investigation_date',

        'lead_investigator_id',
        'lead_investigator_name',

        'investigation_team',

        'immediate_cause',

        'root_cause',

        'contributing_factors',

        'unsafe_act',

        'unsafe_condition',

        'findings',

        'conclusion',

        'recommendations',

        'status',

        'reviewed_by',
        'reviewed_date',
        'review_remarks',

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

        'investigation_date' =>
            'date',

        'reviewed_date' =>
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
    | Lead Investigator
    |--------------------------------------------------------------------------
    */

    public function leadInvestigator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'lead_investigator_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewed By
    |--------------------------------------------------------------------------
    */

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
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

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }


    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }


    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }
}