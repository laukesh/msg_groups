<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FitoutRequest extends Model
{
    use SoftDeletes;

    protected $table = 'fitout_requests';

    protected $fillable = [
        'uuid',
        'request_no',
        'lease_agreement_id',
        'tenant_id',
        'unit_id',
        'contractor_id',
        'fitout_type',
        'work_description',
        'proposed_start_date',
        'proposed_end_date',
        'actual_start_date',
        'actual_end_date',
        'estimated_cost',
        'contractor_name',
        'contractor_contact',
        'safety_induction_completed',
        'insurance_verified',
        'work_permit_no',
        'fitout_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'proposed_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',

        'estimated_cost' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Lease Agreement
    |--------------------------------------------------------------------------
    */

    public function leaseAgreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
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
    | Stages
    |--------------------------------------------------------------------------
    */

    public function stages()
    {
        return $this->hasMany(
            FitoutStage::class,
            'fitout_request_id'
        )->orderBy('stage_sequence');
    }


    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents()
    {
        return $this->hasMany(
            FitoutDocument::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approvals
    |--------------------------------------------------------------------------
    */

    public function approvals()
    {
        return $this->hasMany(
            FitoutApproval::class,
            'fitout_request_id'
        )->orderBy('approval_level');
    }


    /*
    |--------------------------------------------------------------------------
    | Inspections
    |--------------------------------------------------------------------------
    */

    public function inspections()
    {
        return $this->hasMany(
            Inspection::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Snags
    |--------------------------------------------------------------------------
    */

    public function snags()
    {
        return $this->hasMany(
            SnagList::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Handovers
    |--------------------------------------------------------------------------
    */

    public function handovers()
    {
        return $this->hasMany(
            Handover::class,
            'fitout_request_id'
        );
    }

    public function latestInspection()
    {
        return $this->hasOne(
            Inspection::class,
            'fitout_request_id'
        )->latestOfMany();
    }
}