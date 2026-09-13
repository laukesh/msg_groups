<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignChangeCostImpact extends Model
{
    protected $table = 'design_change_cost_impacts';

    protected $fillable = [
        'design_change_id',
        'design_discipline_id',
        'cost_category',
        'description',
        'estimated_amount',
        'approved_amount',
        'currency',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'estimated_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
    ];

    public function designChange(): BelongsTo
    {
        return $this->belongsTo(DesignChange::class, 'design_change_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(DesignDiscipline::class, 'design_discipline_id');
    }
}
