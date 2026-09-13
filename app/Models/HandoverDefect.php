<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverDefect extends Model
{
    protected $table = 'handover_defects';

    protected $fillable = [
        'project_id',
        'handover_project_id',

        'defect_no',

        'snag_id',
        'procurement_contract_id',

        'category',
        'discipline',

        'title',
        'description',

        'location',
        'building',
        'floor',
        'zone',
        'unit',

        'priority',

        'warranty_related',

        'reported_date',
        'due_date',

        'raised_by',
        'assigned_to',

        'status',

        'rectification_details',

        'rectified_by',
        'rectified_at',

        'verified_by',
        'verified_at',

        'rejection_reason',

        'remarks',
        'attachment_path',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'warranty_related' => 'boolean',

        'reported_date' => 'date',
        'due_date' => 'date',

        'rectified_at' => 'datetime',
        'verified_at' => 'datetime',
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
    | Handover
    |--------------------------------------------------------------------------
    */

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Source Snag
    |--------------------------------------------------------------------------
    */

    public function snag(): BelongsTo
    {
        return $this->belongsTo(
            HandoverSnag::class,
            'snag_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function procurementContract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'raised_by'
        );
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    public function rectifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'rectified_by'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}