<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBudget extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Project Reference
        |--------------------------------------------------------------------------
        */

        'project_id',


        /*
        |--------------------------------------------------------------------------
        | Budget Identification
        |--------------------------------------------------------------------------
        */

        'budget_number',
        'title',
        'budget_type',
        'version_number',


        /*
        |--------------------------------------------------------------------------
        | Workflow
        |--------------------------------------------------------------------------
        */

        'status',


        /*
        |--------------------------------------------------------------------------
        | Financial Settings
        |--------------------------------------------------------------------------
        */

        'currency',

        'budget_start_date',
        'budget_end_date',


        /*
        |--------------------------------------------------------------------------
        | Budget Summary
        |--------------------------------------------------------------------------
        */

        'direct_cost',
        'indirect_cost',
        'contingency_amount',
        'total_budget',


        /*
        |--------------------------------------------------------------------------
        | Approval
        |--------------------------------------------------------------------------
        */

        'approved_date',
        'approved_by',


        /*
        |--------------------------------------------------------------------------
        | Remarks and Audit
        |--------------------------------------------------------------------------
        */

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | Numeric Fields
        |--------------------------------------------------------------------------
        */

        'version_number' => 'integer',

        'direct_cost' => 'decimal:2',

        'indirect_cost' => 'decimal:2',

        'contingency_amount' => 'decimal:2',

        'total_budget' => 'decimal:2',


        /*
        |--------------------------------------------------------------------------
        | Date Fields
        |--------------------------------------------------------------------------
        */

        'budget_start_date' => 'date',

        'budget_end_date' => 'date',

        'approved_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'Draft';

    public const STATUS_UNDER_REVIEW = 'Under Review';

    public const STATUS_APPROVED = 'Approved';

    public const STATUS_REJECTED = 'Rejected';


    public const TYPE_DEVELOPMENT = 'Development Budget';

    public const TYPE_CASH_FLOW = 'Cash Flow Budget';

    public const TYPE_FUNDING = 'Funding Budget';

    public const TYPE_OPERATING = 'Operating Budget';

    public const TYPE_OTHER = 'Other';


    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public static function budgetTypes(): array
    {
        return [
            self::TYPE_DEVELOPMENT,
            self::TYPE_CASH_FLOW,
            self::TYPE_FUNDING,
            self::TYPE_OPERATING,
            self::TYPE_OTHER,
        ];
    }


    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }


    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }


    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }


    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }


    public function canEdit(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DRAFT,
                self::STATUS_REJECTED,
            ],
            true
        );
    }


    public function canSubmit(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DRAFT,
                self::STATUS_REJECTED,
            ],
            true
        );
    }


    public function canApprove(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }


    public function canReject(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }


    public function canCreateRevision(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


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
    | Categories
    |--------------------------------------------------------------------------
    */

    public function categories(): HasMany
    {
        return $this->hasMany(
            ProjectBudgetCategory::class,
            'project_budget_id'
        )->orderBy('sequence');
    }


    /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProjectBudgetItem::class,
            'project_budget_id'
        )->orderBy('sequence');
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Packages
    |--------------------------------------------------------------------------
    */

    public function procurementPackages(): HasMany
    {
        return $this->hasMany(
            ProcurementPackage::class,
            'project_budget_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By User
    |--------------------------------------------------------------------------
    */

    public function approvedBy()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By User
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By User
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'updated_by'
        );
    }
}