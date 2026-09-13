<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseIncidentWitness extends Model
{
    protected $table =
        'construction_hse_incident_witnesses';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_incident_id',

        'witness_name',
        'witness_type',

        'employee_code',

        'company_name',
        'designation',

        'phone',
        'email',

        'statement',

        'statement_date',

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

        'statement_date' => 'date',

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
}