<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetExpense extends Model
{
    protected $table = 'asset_expenses';

    protected $fillable = [
        'asset_id',
        'expense_type',
        'expense_date',
        'vendor_name',
        'amount',
        'description',
        'is_operating_expense',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'is_operating_expense' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
     public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
}