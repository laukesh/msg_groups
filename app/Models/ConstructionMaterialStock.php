<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionMaterialStock extends Model
{
    use HasFactory;

    protected $table = 'construction_material_stocks';

    protected $fillable = [
        'project_id',
        'material_id',
        'batch_number',
        'unit',
        'quantity',
        'reserved_quantity',
        'available_quantity',
        'reorder_level',
        'last_transaction_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'reserved_quantity' => 'decimal:4',
        'available_quantity' => 'decimal:4',
        'reorder_level' => 'decimal:4',
        'last_transaction_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterial::class,
            'material_id'
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(
            ConstructionMaterialTransaction::class,
            'stock_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}