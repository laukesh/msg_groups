<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseEnvironmentalRecord extends Model
{
    protected $table =
        'construction_hse_environmental_records';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'record_number',

        'record_title',

        'record_type',

        'monitoring_date',

        'monitoring_time',

        'location',

        'monitoring_area',

        'environmental_parameter',

        'parameter_value',

        'unit',

        'limit_value',

        'compliance_status',

        'weather_condition',

        'observation',

        'corrective_action_required',

        'corrective_action',

        'responsible_person_id',

        'responsible_person_name',

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

        'monitoring_date' =>
            'date',

        'parameter_value' =>
            'decimal:4',

        'limit_value' =>
            'decimal:4',

        'corrective_action_required' =>
            'boolean',

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
    | Responsible Person
    |--------------------------------------------------------------------------
    */

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_person_id'
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

    public function isCompliant(): bool
    {
        return $this->compliance_status === 'Compliant';
    }


    public function isNonCompliant(): bool
    {
        return $this->compliance_status === 'Non-Compliant';
    }


    public function requiresCorrectiveAction(): bool
    {
        return $this->corrective_action_required === true;
    }

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionHseEnvironmentalAction::class,
            'environmental_record_id'
        );
    }
}