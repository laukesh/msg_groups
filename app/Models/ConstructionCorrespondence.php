<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionCorrespondence extends Model
{
    use SoftDeletes;

    protected $table = 'construction_correspondence';

    protected $fillable = [
        'project_id',
        'construction_work_order_id',
        'procurement_contract_id',
        'construction_claim_id',
        'construction_delay_id',
        'construction_risk_id',

        'correspondence_number',
        'reference_number',
        'correspondence_type',

        'correspondence_date',
        'received_date',
        'sent_date',

        'subject',

        'sender_type',
        'sender_name',
        'sender_organization',

        'receiver_type',
        'receiver_name',
        'receiver_organization',

        'communication_method',

        'priority',
        'status',

        'response_required',
        'response_due_date',
        'response_date',

        'action_required',
        'action_description',

        'assigned_to',

        'responsible_party_type',
        'responsible_party_name',

        'description',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'correspondence_date' => 'date',

        'received_date' => 'date',

        'sent_date' => 'date',

        'response_due_date' => 'date',

        'response_date' => 'date',

        'response_required' => 'boolean',

        'action_required' => 'boolean',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
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

    public function procurementContract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Claim
    |--------------------------------------------------------------------------
    */

    public function claim(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionClaim::class,
            'construction_claim_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delay
    |--------------------------------------------------------------------------
    */

    public function delay(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionDelay::class,
            'construction_delay_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Risk
    |--------------------------------------------------------------------------
    */

    public function risk(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionRisk::class,
            'construction_risk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned User
    |--------------------------------------------------------------------------
    */

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
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
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ConstructionCorrespondenceDocument::class,
            'construction_correspondence_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function history(): HasMany
    {
        return $this->hasMany(
            ConstructionCorrespondenceHistory::class,
            'construction_correspondence_id'
        )->latest('performed_at');
    }
}