<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseIncidentPerson extends Model
{
    protected $table =
        'construction_hse_incident_persons';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_incident_id',

        'person_name',
        'person_type',

        'employee_code',
        'company_name',
        'designation',

        'phone',

        'injury_occurred',

        'injury_type',
        'body_part_affected',
        'injury_severity',

        'treatment_type',
        'medical_facility',

        'hospitalized',
        'hospitalization_date',

        'lost_work_days',

        'returned_to_work',
        'return_to_work_date',

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

        'injury_occurred' =>
            'boolean',

        'hospitalized' =>
            'boolean',

        'returned_to_work' =>
            'boolean',

        'hospitalization_date' =>
            'date',

        'return_to_work_date' =>
            'date',

        'lost_work_days' =>
            'integer',
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

    public function hasInjury(): bool
    {
        return $this->injury_occurred === true;
    }


    public function wasHospitalized(): bool
    {
        return $this->hospitalized === true;
    }


    public function hasLostWorkTime(): bool
    {
        return $this->lost_work_days > 0;
    }
}