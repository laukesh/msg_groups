<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionQualityItp extends Model
{
    protected $table = 'construction_quality_itps';

    protected $fillable = [

        'project_id',

        'itp_number',
        'title',
        'itp_type',

        'description',

        'procurement_contract_id',
        'work_order_id',

        'prepared_by',
        'prepared_date',

        'status',

        'approved_by',
        'approved_date',
        'approval_remarks',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'prepared_date' =>
            'date',

        'approved_date' =>
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
    | Prepared By
    |--------------------------------------------------------------------------
    */

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
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


    /*
    |--------------------------------------------------------------------------
    | ITP Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ConstructionQualityItpItem::class,
            'construction_quality_itp_id'
        )->orderBy('item_number');
    }

    public function ncrs(): HasMany
    {
        return $this->hasMany(
            ConstructionQualityNcr::class,
            'construction_quality_itp_id'
        );
    }
}