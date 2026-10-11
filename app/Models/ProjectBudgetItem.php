<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBudgetItem extends Model
{
    protected $fillable = [

        'project_budget_id',
        'project_budget_category_id',

        'item_code',
        'item_name',

        'parent_item_id',
        'sequence',

        'quantity',
        'unit',
        'unit_rate',
        'estimated_amount',

        'cost_type',
        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_rate' => 'decimal:2',
        'estimated_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Budget
    |--------------------------------------------------------------------------
    */

    public function budget(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudget::class,
            'project_budget_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudgetCategory::class,
            'project_budget_category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Parent and Child Items
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudgetItem::class,
            'parent_item_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            ProjectBudgetItem::class,
            'parent_item_id'
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
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    public function contracts(): HasMany
    {
        return $this->hasMany(
            ContractManagementContract::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Construction Work Orders
    |--------------------------------------------------------------------------
    */

    public function workOrders(): HasMany
    {
        return $this->hasMany(
            ConstructionWorkOrder::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Construction Variations
    |--------------------------------------------------------------------------
    */

    public function variations(): HasMany
    {
        return $this->hasMany(
            ConstructionVariation::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Construction Other Costs
    |--------------------------------------------------------------------------
    */

    public function otherCosts(): HasMany
    {
        return $this->hasMany(
            ConstructionOtherCost::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Land Acquisition Costs
    |--------------------------------------------------------------------------
    */

    public function landCosts(): HasMany
    {
        return $this->hasMany(
            LandAcquisitionCost::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Design Change Cost Impacts
    |--------------------------------------------------------------------------
    */

    public function designCostImpacts(): HasMany
    {
        return $this->hasMany(
            DesignChangeCostImpact::class,
            'project_budget_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Individual Cost Calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Total procurement package value.
     */
    public function getProcurementAmountAttribute(): float
    {
        return round(
            (float) $this->procurementPackages()
                ->sum('estimated_value'),
            2
        );
    }

    /**
     * Total contract value.
     */
    public function getContractAmountAttribute(): float
    {
        return round(
            (float) $this->contracts()
                ->sum('contract_value'),
            2
        );
    }

    /**
     * Total work order value.
     */
    public function getWorkOrderAmountAttribute(): float
    {
        return round(
            (float) $this->workOrders()
                ->sum('work_order_value'),
            2
        );
    }

    /**
     * Total approved variation amount.
     */
    public function getVariationAmountAttribute(): float
    {
        return round(
            (float) $this->variations()
                ->where('status', 'Approved')
                ->sum('amount'),
            2
        );
    }

    /**
     * Total approved other construction costs.
     */
    public function getOtherCostAmountAttribute(): float
    {
        return round(
            (float) $this->otherCosts()
                ->where('status', 'Approved')
                ->sum('amount'),
            2
        );
    }

    /**
     * Total land acquisition cost.
     */
    public function getLandCostAmountAttribute(): float
    {
        return round(
            (float) $this->landCosts()
                ->sum('total_amount'),
            2
        );
    }

    /**
     * Total approved design change cost.
     */
    public function getDesignCostAmountAttribute(): float
    {
        return round(
            (float) $this->designCostImpacts()
                ->whereNotNull('approved_amount')
                ->sum('approved_amount'),
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Commitment Calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Base committed amount.
     *
     * Procurement, contracts, and work orders are included
     * independently here.
     *
     * Additional cost categories are also included once.
     */
    public function getBaseCommittedAmountAttribute(): float
    {
        return round(
            $this->procurement_amount
            + $this->contract_amount
            + $this->work_order_amount
            + $this->variation_amount
            + $this->other_cost_amount
            + $this->land_cost_amount
            + $this->design_cost_amount,
            2
        );
    }

    /**
     * Total committed amount across the budget item.
     *
     * Important:
     * Do not add variations, land costs, other costs,
     * or design costs again because they are already
     * included in base_committed_amount.
     */
    public function getTotalCommittedAmountAttribute(): float
    {
        return round(
            $this->base_committed_amount,
            2
        );
    }

    /**
     * Remaining budget amount.
     */
    public function getRemainingBudgetAmountAttribute(): float
    {
        return round(
            (float) $this->estimated_amount
            - $this->total_committed_amount,
            2
        );
    }

    /**
     * Budget utilization percentage.
     */
    public function getBudgetUtilizationPercentageAttribute(): float
    {
        $budgetAmount = (float) $this->estimated_amount;

        if ($budgetAmount <= 0) {
            return 0;
        }

        return round(
            ($this->total_committed_amount / $budgetAmount) * 100,
            2
        );
    }

    /**
     * Check whether the budget is over-committed.
     */
    public function getIsOverCommittedAttribute(): bool
    {
        return $this->total_committed_amount
            > (float) $this->estimated_amount;
    }

    /**
     * Utilization progress bar width.
     *
     * Visual width is capped at 100%.
     */
    public function getUtilizationProgressAttribute(): float
    {
        return min(
            100,
            max(0, $this->budget_utilization_percentage)
        );
    }

    /**
     * Utilization status.
     */
    public function getUtilizationStatusAttribute(): string
    {
        $percentage = $this->budget_utilization_percentage;

        if ($percentage > 100) {
            return 'Over Budget';
        }

        if ($percentage >= 90) {
            return 'Critical';
        }

        if ($percentage >= 75) {
            return 'Warning';
        }

        return 'Healthy';
    }
}