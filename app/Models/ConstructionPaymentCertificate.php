<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionPaymentCertificate extends Model
{
    use SoftDeletes;

    protected $table = 'construction_payment_certificates';

    protected $fillable = [
        'project_id',
        'construction_work_order_id',
        'procurement_contract_id',

        'certificate_number',
        'certificate_date',

        'period_from',
        'period_to',

        'submitted_date',
        'submitted_by',

        'approved_by',
        'approval_date',
        'approval_remarks',

        'rejected_by',
        'rejection_date',
        'rejection_remarks',

        'paid_date',

        'gross_amount',
        'previous_certified_amount',
        'current_certified_amount',

        'retention_amount',
        'advance_recovery',
        'other_deductions',

        'net_certified_amount',

        'status',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'certificate_date' => 'date',

        'period_from' => 'date',
        'period_to' => 'date',

        'submitted_date' => 'date',

        'approval_date' => 'date',
        'rejection_date' => 'date',

        'paid_date' => 'date',

        'gross_amount' => 'decimal:2',
        'previous_certified_amount' => 'decimal:2',
        'current_certified_amount' => 'decimal:2',

        'retention_amount' => 'decimal:2',
        'advance_recovery' => 'decimal:2',
        'other_deductions' => 'decimal:2',

        'net_certified_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Construction Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder()
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function procurementContract()
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submitted By
    |--------------------------------------------------------------------------
    */

    public function submittedBy()
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Rejected By
    |--------------------------------------------------------------------------
    */

    public function rejectedBy()
    {
        return $this->belongsTo(
            User::class,
            'rejected_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator()
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

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}