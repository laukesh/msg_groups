<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBudgetCategory extends Model
{
    protected $fillable = [

        'project_budget_id',

        'category_code',
        'category_name',

        'sequence',

        'description',
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
    | Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            ProjectBudgetItem::class,
            'project_budget_category_id'
        )->orderBy('sequence');
    }
}