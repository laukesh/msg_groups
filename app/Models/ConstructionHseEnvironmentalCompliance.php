<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ConstructionHseEnvironmentalCompliance extends Model
{
    protected $table =
        'construction_hse_environmental_compliances';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'compliance_number',

        'compliance_title',

        'compliance_type',

        'regulatory_authority',

        'legislation_reference',

        'permit_license_number',

        'requirement_description',

        'applicable_from',

        'due_date',

        'review_date',

        'compliance_status',

        'risk_level',

        'responsible_person_id',

        'responsible_person_name',

        'evidence_available',

        'evidence_description',

        'non_compliance_details',

        'corrective_action_required',

        'corrective_action',

        'status',

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

        'applicable_from' =>
            'date',

        'due_date' =>
            'date',

        'review_date' =>
            'date',

        'evidence_available' =>
            'boolean',

        'corrective_action_required' =>
            'boolean',

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
    | Responsible Person
    |--------------------------------------------------------------------------
    */

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_person_id'
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
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isCompliant(): bool
    {
        return $this->compliance_status === 'Compliant';
    }


    public function isNonCompliant(): bool
    {
        return $this->compliance_status === 'Non-Compliant';
    }


    public function isPending(): bool
    {
        return $this->compliance_status === 'Pending';
    }


    public function requiresCorrectiveAction(): bool
    {
        return $this->corrective_action_required === true;
    }


    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        if (
            in_array(
                $this->compliance_status,
                [
                    'Compliant',
                    'Not Applicable',
                ],
                true
            )
        ) {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function actions(): HasMany
    {
        return $this->hasMany(
            ConstructionHseEnvironmentalAction::class,
            'environmental_compliance_id'
        );
    }
}