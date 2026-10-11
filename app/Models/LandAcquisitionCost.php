<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandAcquisitionCost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'land_id',
        'project_budget_id',
        'project_budget_item_id',
        'cost_category',
        'cost_description',
        'amount',
        'tax_amount',
        'total_amount',
        'currency',
        'cost_date',
        'payment_status',
        'paid_date',
        'reference_number',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cost_date' => 'date',
        'paid_date' => 'date',
    ];

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }

    public function budgetItem()
    {
        return $this->belongsTo(
            ProjectBudgetItem::class,
            'project_budget_item_id'
        );
    }

    public function projectBudget()
    {
        return $this->belongsTo(
            ProjectBudget::class,
            'project_budget_id'
        );
    }
}