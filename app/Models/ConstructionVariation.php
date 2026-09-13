<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionVariation extends Model
{
    protected $table = 'construction_variations';


    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'procurement_contract_id',

        'variation_number',

        'variation_date',

        'variation_type',

        'title',

        'description',

        'reason',

        'amount',

        'currency',

        'status',

        'submitted_at',
        'submitted_by',

        'approved_at',
        'approved_by',

        'rejected_at',
        'rejected_by',

        'rejection_remarks',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'variation_date' => 'date',

        'amount' => 'decimal:2',

        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
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

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
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