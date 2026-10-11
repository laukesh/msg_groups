<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\AssetType;
use App\Models\AssetPurpose;
use App\Models\IndustryProfile;
use App\Models\OperatingModel;
use App\Models\Ownership;
use App\Models\Assets\RevenueStatus;

class Asset extends Model
{
    use SoftDeletes;

    protected $table = 'assets';

    protected $fillable = [
        'uuid',
        'asset_code',
      
        'asset_type_id',
        'asset_purpose_id',
        'industry_profile_id',
        'operating_model_id',
        'revenue_status_id',
        'ownership_id',
        'asset_name',
        'asset_category',
        'asset_type',
        'serial_number',
        'model_number',
        'manufacturer',
        'unit_id',
        'building_id',
        'floor_id',
        'zone_id',
        'location_description',
       'industry_operating_profile_id',
        'department_id',
        'assigned_to',
        'vendor_id',
        'purchase_date',
        'installation_date',
        'warranty_start_date',
        'warranty_end_date',
        'purchase_cost',
        'useful_life_years',
        'status',
        'conditions',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_date'       => 'date',
        'installation_date'   => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date'   => 'date',
        'purchase_cost'       => 'decimal:2',
        'useful_life_years'   => 'integer',
        'status'              => 'boolean',
    ];

    /**
     * Asset Category
     */
    public function assetCategory(): BelongsTo
    {
        return $this->belongsTo(
            AssetCategory::class,
            'asset_category',
            'id'
        );
    }

    /**
     * Unit
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id',
            'id'
        );
    }

    /**
     * Building
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(
            Building::class,
            'building_id',
            'id'
        );
    }

    /**
     * Floor
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(
            Floor::class,
            'floor_id',
            'id'
        );
    }

    /**
     * Zone
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(
            Zone::class,
            'zone_id',
            'id'
        );
    }

    /**
     * Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }

    /**
     * Assigned User
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to',
            'id'
        );
    }

    /**
     * Vendor
     *
     * Change Vendor::class to User::class if
     * your vendor_id actually references users.id.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'vendor_id',
            'id'
        );
    }

    /**
     * Created By
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    /**
     * Updated By
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    /**
     * Asset Documents
     *
     * Keep this only if unit documents are intentionally
     * connected through unit_id.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(
            UnitDocument::class,
            'unit_id',
            'unit_id'
        );
    }
  /*
    |--------------------------------------------------------------------------
    | Asset Expenses
    |--------------------------------------------------------------------------
    */

    public function expenses(): HasMany
    {
        return $this->hasMany(
            AssetExpense::class,
            'asset_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Asset Income
    |--------------------------------------------------------------------------
    */

    public function incomes(): HasMany
    {
        return $this->hasMany(
            AssetIncome::class,
            'asset_id'
        );
    }

    public function getTotalIncomeAttribute()
    {
        return $this->incomes()->sum('amount');
    }

    public function getOperatingExpensesAttribute()
    {
        return $this->expenses()
            ->where('is_operating_expense', true)
            ->sum('amount');
    }

    public function getNoiAttribute()
    {
        return $this->total_income - $this->operating_expenses;
    }

    public function getRoiAttribute()
    {
        if ((float) $this->purchase_cost <= 0) {
            return 0;
        }

        return ($this->noi / $this->purchase_cost) * 100;
    }
    public function assetType(): BelongsTo
    {
        return $this->belongsTo(
            AssetType::class,
            'asset_type_id'
        );
    }

    public function assetPurpose(): BelongsTo
    {
        return $this->belongsTo(
            AssetPurpose::class,
            'asset_purpose_id'
        );
    }

    public function industryProfile(): BelongsTo
    {
        return $this->belongsTo(
            IndustryProfile::class,
            'industry_profile_id'
        );
    }

    public function operatingModel(): BelongsTo
    {
        return $this->belongsTo(
            OperatingModel::class,
            'operating_model_id'
        );
    }

    public function ownership(): BelongsTo
    {
        return $this->belongsTo(
            Ownership::class,
            'ownership_id'
        );
    }

    public function revenueStatus(): BelongsTo
    {
        return $this->belongsTo(
            RevenueStatus::class,
            'revenue_status_id'
        );
    }
    public function occupancies(): HasMany
{
    return $this->hasMany(Occupancy::class);
}

// public function performances(): HasMany
// {
//     return $this->hasMany(AssetPerformance::class);
// }



public function capex(): HasMany
{
    return $this->hasMany(AssetCapex::class);
}

public function lifecycles(): HasMany
{
    return $this->hasMany(
        AssetLifecycle::class,
        'asset_id'
    );
}
public function latestLifecycle()
{
    return $this->hasOne(
        AssetLifecycle::class,
        'asset_id'
    )->latestOfMany();
}
public function industryOperatingProfile(): BelongsTo
{
    return $this->belongsTo(
        IndustryOperatingProfile::class,
        'industry_operating_profile_id'
    );
}
public function conditions(): HasMany
{
    return $this->hasMany(
        AssetCondition::class,
        'asset_id',
        'id'
    );
}

public function latestCondition(): HasOne
{
    return $this->hasOne(
        AssetCondition::class,
        'asset_id',
        'id'
    )->latestOfMany('inspection_date');
}

public function performances(): HasMany
{
    return $this->hasMany(
        AssetPerformance::class,
        'asset_id',
        'id'
    );
}

public function latestPerformance(): HasOne
{
    return $this->hasOne(
        AssetPerformance::class,
        'asset_id',
        'id'
    )->latestOfMany('performance_date');
}
}