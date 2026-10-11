<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseInspection extends Model
{
    protected $table = 'construction_hse_inspections';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'inspection_number',
        'inspection_date',

        'inspection_type',
        'inspection_title',

        'location',

        'procurement_contract_id',

        'inspector_id',
        'inspector_name',

        'contractor_id',

        'scope',

        'status',

        'findings_summary',

        'remarks',

        'created_by',
        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'inspection_date' => 'date',

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

    public function procurementContract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Inspector
    |--------------------------------------------------------------------------
    */

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'inspector_id'
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
            Contractor::class,
            'contractor_id'
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
    | Inspection Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ConstructionHseInspectionItem::class,
            'construction_hse_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Findings
    |--------------------------------------------------------------------------
    */

    public function findings(): HasMany
    {
        return $this->hasMany(
            ConstructionHseInspectionFinding::class,
            'construction_hse_inspection_id'
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
            ConstructionHseInspectionAction::class,
            'construction_hse_inspection_id'
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
            ConstructionHseInspectionDocument::class,
            'construction_hse_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPlanned(): bool
    {
        return $this->status === 'Planned';
    }


    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function hasFindings(): bool
    {
        return $this->findings()->exists();
    }


    public function hasOpenActions(): bool
    {
        return $this->actions()
            ->whereNotIn(
                'status',
                [
                    'Completed',
                    'Closed',
                ]
            )
            ->exists();
    }


    public function isVerified(): bool
    {
        return $this->status === 'Verified';
    }


    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }
}