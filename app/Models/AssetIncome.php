<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetIncome extends Model
{
    protected $table = 'asset_incomes';

    protected $fillable = [
        'asset_id',
        'income_type',
        'income_date',
        'billing_period_from',
        'billing_period_to',
        'tenant_id',
        'lease_agreement_id',
        'amount',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'income_date' => 'date',
        'billing_period_from' => 'date',
        'billing_period_to' => 'date',
        'amount' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function leaseAgreement(): BelongsTo
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}