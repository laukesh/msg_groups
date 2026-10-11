<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionMaterialTransaction extends Model
{
    use HasFactory;

    protected $table = 'construction_material_transactions';

    protected $fillable = [
        'project_id',
        'material_id',
        'stock_id',
        'transaction_number',
        'transaction_type',
        'transaction_date',
        'quantity',
        'unit',
        'reference_type',
        'reference_id',
        'construction_work_order_id',
        'batch_number',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'quantity' => 'decimal:4',
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

    public function stock(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionMaterialStock::class,
            'stock_id'
        );
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Polymorphic-style reference.
     *
     * reference_type contains the model class.
     * reference_id contains the related record ID.
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        if (!class_exists($this->reference_type)) {
            return null;
        }

        return $this->reference_type::find(
            $this->reference_id
        );
    }
}