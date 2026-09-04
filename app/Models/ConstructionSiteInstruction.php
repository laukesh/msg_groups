<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionSiteInstruction extends Model
{
    protected $table = 'construction_site_instructions';

    protected $fillable = [

        'project_id',

        'instruction_number',
        'instruction_date',
        'instruction_type',

        'subject',
        'description',

        'issued_by',
        'issued_to',

        'contractor_id',
        'procurement_contract_id',
        'consultant_id',

        'work_order_id',
        'schedule_activity_id',

        'location',
        'priority',

        'required_action',
        'due_date',

        'status',

        'acknowledgement_date',
        'compliance_date',
        'response',

        'closed_date',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'instruction_date' =>
            'date',

        'due_date' =>
            'date',

        'acknowledgement_date' =>
            'datetime',

        'compliance_date' =>
            'date',

        'closed_date' =>
            'date',
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
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'work_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Issued By
    |--------------------------------------------------------------------------
    */

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Issued To
    |--------------------------------------------------------------------------
    */

    public function issuedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'issued_to'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
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

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(
            ProjectConsultant::class,
            'consultant_id'
        );
    }
}