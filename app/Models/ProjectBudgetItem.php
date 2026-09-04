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

        'quantity' =>
            'decimal:4',

        'unit_rate' =>
            'decimal:2',

        'estimated_amount' =>
            'decimal:2',
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
    | Parent Item
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            ProjectBudgetItem::class,
            'parent_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Child Items
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            ProjectBudgetItem::class,
            'parent_item_id'
        )->orderBy('sequence');
    }
}