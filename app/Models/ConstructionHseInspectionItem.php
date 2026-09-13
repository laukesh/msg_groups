<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ConstructionHseInspectionItem extends Model
{
    protected $table =
        'construction_hse_inspection_items';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_inspection_id',

        'item_number',

        'checklist_category',

        'checklist_question',

        'response',

        'observation',

        'severity',

        'corrective_required',

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

        'corrective_required' =>
            'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Inspection
    |--------------------------------------------------------------------------
    */

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseInspection::class,
            'construction_hse_inspection_id'
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
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isCompliant(): bool
    {
        return $this->response === 'Compliant';
    }


    public function isNonCompliant(): bool
    {
        return $this->response === 'Non-Compliant';
    }


    public function isPartiallyCompliant(): bool
    {
        return $this->response === 'Partially Compliant';
    }


    public function isNotApplicable(): bool
    {
        return $this->response === 'Not Applicable';
    }


    public function requiresCorrectiveAction(): bool
    {
        return $this->corrective_required === true;
    }


    public function isCritical(): bool
    {
        return $this->severity === 'Critical';
    }


    public function isHighSeverity(): bool
    {
        return $this->severity === 'High';
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
            'construction_hse_inspection_item_id'
        );
    }
}