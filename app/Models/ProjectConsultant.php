<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectConsultant extends Model
{
    protected $table = 'project_consultants';

    protected $fillable = [

        'project_id',

        /*
        |--------------------------------------------------------------------------
        | Consultant Identification
        |--------------------------------------------------------------------------
        */

        'consultant_code',
        'consultant_type',
        'consultant_role',
        'appointment_type',
        'discipline',
        'specialization',

        'company_name',
        'consultant_name',

        /*
        |--------------------------------------------------------------------------
        | Professional Information
        |--------------------------------------------------------------------------
        */

        'registration_no',
        'gst_number',
        'pan_number',

        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */

        'contact_person',
        'contact_designation',
        'email',
        'phone',
        'alternate_phone',
        'website',

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        'address',
        'city',
        'state',
        'country',
        'postal_code',

        /*
        |--------------------------------------------------------------------------
        | Appointment
        |--------------------------------------------------------------------------
        */

        'appointment_date',
        'start_date',
        'end_date',

        /*
        |--------------------------------------------------------------------------
        | Scope & Responsibilities
        |--------------------------------------------------------------------------
        */

        'scope_of_services',
        'responsibilities',

        /*
        |--------------------------------------------------------------------------
        | Contract Summary
        |--------------------------------------------------------------------------
        */

        'contract_value',
        'currency',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',
        'remarks',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'appointment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',

        'contract_value' => 'decimal:2',
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
}