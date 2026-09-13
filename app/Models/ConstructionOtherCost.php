<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionOtherCost extends Model
{
    protected $table = 'construction_other_costs';

    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'cost_number',

        'cost_date',

        'cost_type',

        'description',

        'amount',

        'currency',

        'status',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'cost_date' => 'date',

        'amount' => 'decimal:2',
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
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
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