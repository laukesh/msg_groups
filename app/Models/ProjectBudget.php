<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBudget extends Model
{
    protected $fillable = [

        'project_id',

        'budget_number',
        'title',
        'budget_type',
        'version_number',

        'status',

        'currency',

        'budget_start_date',
        'budget_end_date',

        'direct_cost',
        'indirect_cost',
        'contingency_amount',
        'total_budget',

        'approved_date',
        'approved_by',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'version_number' =>
            'integer',

        'budget_start_date' =>
            'date',

        'budget_end_date' =>
            'date',

        'approved_date' =>
            'date',

        'direct_cost' =>
            'decimal:2',

        'indirect_cost' =>
            'decimal:2',

        'contingency_amount' =>
            'decimal:2',

        'total_budget' =>
            'decimal:2',
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

    public function procurementPackages(): HasMany
    {
        return $this->hasMany(
            ProcurementPackage::class,
            'project_budget_id'
        );
    }

}