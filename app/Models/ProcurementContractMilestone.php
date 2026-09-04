<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class ProcurementContractMilestone extends Model
{
    protected $table = 'procurement_contract_milestones';

    protected $fillable = [

        'procurement_contract_id',

        'milestone_number',
        'milestone_title',

        'description',

        'planned_start_date',
        'planned_end_date',

        'actual_start_date',
        'actual_end_date',

        'milestone_amount',
        'currency',

        'progress_percentage',

        'status',

        'deliverable_required',
        'deliverable_description',

        'responsible_user_id',

        'completed_by',
        'completed_at',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'planned_start_date' => 'date',
        'planned_end_date' => 'date',

        'actual_start_date' => 'date',
        'actual_end_date' => 'date',

        'milestone_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',

        'deliverable_required' => 'boolean',

        'completed_at' => 'datetime',
    ];


    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }

    public function progressUpdates(): HasMany
	{
	    return $this->hasMany(
	        ProcurementMilestoneProgress::class,
	        'procurement_contract_milestone_id'
	    );
	}

	public function latestProgress(): HasOne
	{
	    return $this->hasOne(
	        ProcurementMilestoneProgress::class,
	        'procurement_contract_milestone_id'
	    )->latestOfMany('id');
	}

    public function documents(): HasMany
    {
        return $this->hasMany(
            ProcurementMilestoneDocument::class,
            'procurement_contract_milestone_id'
        );
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(
            ProcurementContractInvoice::class,
            'procurement_contract_milestone_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Completed By
    |--------------------------------------------------------------------------
    */

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }
}