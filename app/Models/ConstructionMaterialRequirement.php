<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterialRequirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'construction_material_requirements';

    protected $fillable = [
        'project_id',
        'construction_work_order_id',
        'material_id',
        'required_quantity',
        'unit',
        'required_date',
        'purpose',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'required_quantity' => 'decimal:4',
        'required_date' => 'date',
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
    | Work Order
    |--------------------------------------------------------------------------
    */

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Material
    |--------------------------------------------------------------------------
    */

    public function material(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterial::class,
            'material_id'
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

    public function requestItems(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialRequestItem::class,
            'material_requirement_id'
        );
    }
}