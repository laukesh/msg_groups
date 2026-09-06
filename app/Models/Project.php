<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Source / Lifecycle References
        |--------------------------------------------------------------------------
        */

        'land_id',

        'feasibility_assessment_id',

        'investment_decision_id',


        /*
        |--------------------------------------------------------------------------
        | Project Identity
        |--------------------------------------------------------------------------
        */

        'project_number',

        'project_code',

        'project_name',

        'project_type',

        'project_description',


        /*
        |--------------------------------------------------------------------------
        | Project Lifecycle
        |--------------------------------------------------------------------------
        */

        'project_stage',

        'project_status',

        'project_priority',


        /*
        |--------------------------------------------------------------------------
        | Responsibility
        |--------------------------------------------------------------------------
        */

        'project_sponsor_id',

        'project_director_id',

        'project_manager_id',

        'development_manager_id',


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        'approval_date',

        'project_initiation_date',

        'project_start_date',

        'planned_completion_date',

        'actual_completion_date',


        /*
        |--------------------------------------------------------------------------
        | Development Scope
        |--------------------------------------------------------------------------
        */

        'development_objective',

        'scope_summary',

        'development_scope',


        /*
        |--------------------------------------------------------------------------
        | Development Area
        |--------------------------------------------------------------------------
        */

        'development_area',

        'planned_gla',

        'planned_nla',

        'planned_leasable_area',


        /*
        |--------------------------------------------------------------------------
        | Additional
        |--------------------------------------------------------------------------
        */

        'remarks',


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'approval_date' =>
            'date',

        'project_initiation_date' =>
            'date',

        'project_start_date' =>
            'date',

        'planned_completion_date' =>
            'date',

        'actual_completion_date' =>
            'date',

        'development_area' =>
            'decimal:2',

        'planned_gla' =>
            'decimal:2',

        'planned_nla' =>
            'decimal:2',

        'planned_leasable_area' =>
            'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Land
    |--------------------------------------------------------------------------
    */

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Feasibility Assessment
    |--------------------------------------------------------------------------
    */

    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class,
            'feasibility_assessment_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Investment Decision
    |--------------------------------------------------------------------------
    */

    public function investmentDecision()
    {
        return $this->belongsTo(
            InvestmentDecision::class,
            'investment_decision_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Development Planning
    |--------------------------------------------------------------------------
    |
    | These relationships will be used as the remaining
    | Development Planning modules are created.
    |
    |--------------------------------------------------------------------------
    */

    public function developmentStrategy()
    {
        return $this->hasOne(
            DevelopmentStrategy::class,
            'project_id'
        );
    }


    public function masterSchedule()
    {
        return $this->hasOne(
            MasterSchedule::class,
            'project_id'
        );
    }


    public function budget()
    {
        return $this->hasOne(
            ProjectBudget::class,
            'project_id'
        );
    }


    public function fundingPlans()
    {
        return $this->hasMany(
            ProjectFundingPlan::class,
            'project_id'
        )->orderByDesc('version_number');
    }

    public function approvedFundingPlan()
    {
        return $this->hasOne(
            ProjectFundingPlan::class,
            'project_id'
        )
        ->where('status', 'Approved')
        ->latestOfMany('version_number');
    }


    public function deliveryStrategy()
    {
        return $this->hasOne(
            DeliveryStrategy::class,
            'project_id'
        );
    }

    public function deliveryStrategies()
    {
        return $this->hasMany(
            ProjectDeliveryStrategy::class,
            'project_id'
        );
    }


    public function procurementStrategy()
    {
        return $this->hasOne(
            ProcurementStrategy::class,
            'project_id'
        );
    }

    public function procurementStrategies()
    {
        return $this->hasMany(
            ProjectProcurementStrategy::class,
            'project_id'
        );
    }


    public function contractStrategy()
    {
        return $this->hasOne(
            ContractStrategy::class,
            'project_id'
        );
    }

    public function contractStrategies()
    {
        return $this->hasMany(
            ProjectContractStrategy::class,
            'project_id'
        );
    }


    public function riskRegister()
    {
        return $this->hasMany(
            ProjectRisk::class,
            'project_id'
        );
    }


    public function stakeholders()
    {
        return $this->hasMany(
            ProjectStakeholder::class,
            'project_id'
        );
    }


    public function governance()
    {
        return $this->hasMany(
            ProjectGovernance::class,
            'project_id'
        );
    }

    public function approvedBudget()
    {
        return $this->hasOne(
            ProjectBudget::class,
            'project_id'
        )
        ->where('status', 'Approved')
        ->latestOfMany('version_number');
    }

    public function budgets()
    {
        return $this->hasMany(
            ProjectBudget::class,
            'project_id'
        )->orderByDesc('version_number');
    }

    public function risks()
    {
        return $this->hasMany(
            ProjectRisk::class,
            'project_id'
        );
    }

    public function approvalMatrix()
    {
        return $this->hasMany(
            ProjectApprovalMatrix::class,
            'project_id'
        );
    }

    public function decisionRegister()
    {
        return $this->hasMany(
            ProjectDecisionRegister::class,
            'project_id'
        );
    }

    public function governanceMeetings()
    {
        return $this->hasMany(
            ProjectGovernanceMeeting::class,
            'project_id'
        );
    }


    /**
     * Project status workflow history.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(
            ProjectStatusHistory::class,
            'project_id'
        )->orderByDesc('performed_at');
    }

    public function constructionWorkOrders(): HasMany
    {
        return $this->hasMany(
            ConstructionWorkOrder::class,
            'project_id'
        );
    }

    public function constructionSiteReports(): HasMany
    {
        return $this->hasMany(
            ConstructionSiteReport::class,
            'project_id'
        );
    }

    public function constructionScheduleActivities(): HasMany
    {
        return $this->hasMany(
            ConstructionScheduleActivity::class,
            'project_id'
        );
    }

    /**
     * Construction Progress Updates
     */
    public function constructionProgressUpdates(): HasMany
    {
        return $this->hasMany(
            ConstructionProgressUpdate::class,
            'project_id'
        );
    }

    public function constructionProgressEntries(): HasMany
    {
        return $this->hasMany(
            ConstructionProgressEntry::class,
            'project_id'
        );
    }

    public function constructionOtherCosts(): HasMany
    {
        return $this->hasMany(
            ConstructionOtherCost::class,
            'project_id'
        );
    }

    public function constructionVariations(): HasMany
    {
        return $this->hasMany(
            ConstructionVariation::class,
            'project_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Design Management
    |--------------------------------------------------------------------------
    */

    public function designProjectBriefs(): HasMany
    {
        return $this->hasMany(
            DesignProjectBrief::class,
            'project_id'
        );
    }

    public function designPackages(): HasMany
    {
        return $this->hasMany(
            DesignPackage::class,
            'project_id'
        );
    }

    public function designDrawings(): HasMany
    {
        return $this->hasMany(
            DesignDrawing::class,
            'project_id'
        );
    }

    public function designSubmittals(): HasMany
    {
        return $this->hasMany(
            DesignSubmittal::class,
            'project_id'
        );
    }

    public function designRfis(): HasMany
    {
        return $this->hasMany(
            DesignRfi::class,
            'project_id'
        );
    }

    public function designChanges(): HasMany
    {
        return $this->hasMany(
            DesignChange::class,
            'project_id'
        );
    }

}