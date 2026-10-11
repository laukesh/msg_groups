<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionQualityNcr extends Model
{
    protected $table = 'construction_quality_ncrs';

    protected $fillable = [

        'project_id',

        'ncr_number',
        'ncr_date',

        'title',
        'description',

        'location',

        'procurement_contract_id',
        'work_order_id',

        'construction_quality_itp_id',
        'construction_quality_itp_item_id',

        'construction_inspection_id',

        'severity',

        'raised_by',
        'responsible_party',

        'required_action',
        'due_date',

        'status',

        'submitted_at',

        'verified_by',
        'verified_at',
        'verification_remarks',

        'closed_by',
        'closed_at',
        'closure_remarks',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'ncr_date' =>
            'date',

        'due_date' =>
            'date',

        'submitted_at' =>
            'datetime',

        'verified_at' =>
            'datetime',

        'closed_at' =>
            'datetime',
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
    | ITP
    |--------------------------------------------------------------------------
    */

    public function itp(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionQualityItp::class,
            'construction_quality_itp_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ITP Item
    |--------------------------------------------------------------------------
    */

    public function itpItem(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionQualityItpItem::class,
            'construction_quality_itp_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Inspection
    |--------------------------------------------------------------------------
    */

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionInspection::class,
            'construction_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Raised By
    |--------------------------------------------------------------------------
    */

    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'raised_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verified By
    |--------------------------------------------------------------------------
    */

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Closed By
    |--------------------------------------------------------------------------
    */

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
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
    | Corrective Actions
    |--------------------------------------------------------------------------
    */

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionQualityNcrAction::class,
            'construction_quality_ncr_id'
        );
    }
}