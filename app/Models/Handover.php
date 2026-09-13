<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Handover extends Model
{
    use SoftDeletes;

    protected $table = 'handovers';

    protected $fillable = [
        'uuid',
        'handover_number',
        'fitout_request_id',
        'unit_id',
        'tenant_id',
        'contractor_id',
        'final_inspection_id',
        'handover_date',
        'handover_type',
        'status',
        'unit_condition',
        'key_count',
        'access_card_count',
        'electricity_meter_no',
        'electricity_meter_reading',
        'water_meter_no',
        'water_meter_reading',
        'remarks',
        'tenant_accepted_by',
        'tenant_accepted_at',
        'contractor_accepted_by',
        'contractor_accepted_at',
        'mall_approved_by',
        'mall_approved_at',
        'handover_document_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'handover_date' => 'date',

        'electricity_meter_reading' => 'decimal:2',

        'water_meter_reading' => 'decimal:2',

        'tenant_accepted_at' => 'datetime',

        'contractor_accepted_at' => 'datetime',

        'mall_approved_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Request
    |--------------------------------------------------------------------------
    */

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Unit
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tenant
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contractor
    |--------------------------------------------------------------------------
    */

    public function contractor()
    {
        return $this->belongsTo(
            FitoutContractor::class,
            'contractor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Final Inspection
    |--------------------------------------------------------------------------
    */

    public function finalInspection()
    {
        return $this->belongsTo(
            Inspection::class,
            'final_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tenant Acceptance
    |--------------------------------------------------------------------------
    */

    public function tenantAcceptedBy()
    {
        return $this->belongsTo(
            User::class,
            'tenant_accepted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contractor Acceptance
    |--------------------------------------------------------------------------
    */

    public function contractorAcceptedBy()
    {
        return $this->belongsTo(
            User::class,
            'contractor_accepted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mall Approval
    |--------------------------------------------------------------------------
    */

    public function mallApprovedBy()
    {
        return $this->belongsTo(
            User::class,
            'mall_approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
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

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}