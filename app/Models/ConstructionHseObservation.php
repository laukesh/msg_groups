<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseObservation extends Model
{
    protected $table = 'construction_hse_observations';

    protected $fillable = [

        'project_id',

        'observation_number',
        'observation_date',
        'observation_time',

        'location',
        'category',
        'severity',
        'description',

        'contractor_id',

        'reported_by',
        'reported_by_name',

        'immediate_action',
        'corrective_action',

        'due_date',

        'responsible_user_id',

        'status',

        'closed_date',
        'closed_by',
        'closure_remarks',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [

        'observation_date' => 'date',
        'observation_time' => 'datetime:H:i',

        'due_date' => 'date',
        'closed_date' => 'date',
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
    | Contractor
    |--------------------------------------------------------------------------
    */

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementBidder::class,
            'contractor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reported By
    |--------------------------------------------------------------------------
    */

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
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
    | Corrective Actions
    |--------------------------------------------------------------------------
    */

    public function correctiveActions(): HasMany
    {
        return $this->hasMany(
            ConstructionHseCorrectiveAction::class,
            'construction_hse_observation_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Corrective Action Counts
    |--------------------------------------------------------------------------
    */

    public function getCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()->count();
    }


    public function getOpenCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()
            ->where('status', 'Open')
            ->count();
    }


    public function getInProgressCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()
            ->where('status', 'In Progress')
            ->count();
    }


    public function getResolvedCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()
            ->where('status', 'Resolved')
            ->count();
    }


    public function getVerifiedCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()
            ->where('status', 'Verified')
            ->count();
    }


    public function getClosedCorrectiveActionCountAttribute(): int
    {
        return $this->correctiveActions()
            ->where('status', 'Closed')
            ->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Corrective Action Completion
    |--------------------------------------------------------------------------
    */

    public function allCorrectiveActionsCompleted(): bool
    {
        $total = $this->correctiveActions()->count();

        if ($total === 0) {
            return false;
        }

        return $this->correctiveActions()
            ->whereNotIn(
                'status',
                [
                    'Verified',
                    'Closed',
                ]
            )
            ->count() === 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Corrective Action Fully Closed
    |--------------------------------------------------------------------------
    */

    public function allCorrectiveActionsClosed(): bool
    {
        $total = $this->correctiveActions()->count();

        if ($total === 0) {
            return false;
        }

        return $this->correctiveActions()
            ->where(
                'status',
                '!=',
                'Closed'
            )
            ->count() === 0;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }
}