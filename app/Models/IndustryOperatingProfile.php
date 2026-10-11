<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndustryOperatingProfile extends Model
{
    use HasFactory;

    /**
     * --------------------------------------------------------------------------
     * Table
     * --------------------------------------------------------------------------
     */
    protected $table = 'industry_operating_profiles';

    /**
     * --------------------------------------------------------------------------
     * Mass Assignable Fields
     * --------------------------------------------------------------------------
     */
    protected $fillable = [

        // Basic Information
        'profile_code',
        'profile_name',
        'industry_type',
        'description',
        'is_active',

        // Occupancy
        'occupancy_target',
        'vacancy_threshold',

        // Performance
        'performance_target',
        'utilization_target',
        'availability_target',
        'efficiency_target',

        // Condition Management
        'condition_review_frequency_months',
        'condition_warning_threshold',
        'condition_critical_threshold',

        // Financial Tracking
        'revenue_tracking',
        'operating_expense_tracking',
        'capex_tracking',

        // Lifecycle Management
        'default_asset_life_years',
        'renewal_period_years',
        'replacement_threshold',
        'lifecycle_review_frequency_months',

        // KPI
        'primary_kpi',
        'secondary_kpi',

        // Configuration
        'settings',

        // Remarks
        'remarks',

        // Audit
        'created_by',
        'updated_by',
    ];

    /**
     * --------------------------------------------------------------------------
     * Attribute Casting
     * --------------------------------------------------------------------------
     */
    protected $casts = [

        // Status
        'is_active' => 'boolean',

        // Financial Tracking
        'revenue_tracking' => 'boolean',
        'operating_expense_tracking' => 'boolean',
        'capex_tracking' => 'boolean',

        // Occupancy
        'occupancy_target' => 'decimal:2',
        'vacancy_threshold' => 'decimal:2',

        // Performance
        'performance_target' => 'decimal:2',
        'utilization_target' => 'decimal:2',
        'availability_target' => 'decimal:2',
        'efficiency_target' => 'decimal:2',

        // Condition
        'condition_warning_threshold' => 'decimal:2',
        'condition_critical_threshold' => 'decimal:2',

        // Lifecycle
        'default_asset_life_years' => 'decimal:2',
        'renewal_period_years' => 'decimal:2',
        'replacement_threshold' => 'decimal:2',

        // JSON Configuration
        'settings' => 'array',
    ];

    /**
     * --------------------------------------------------------------------------
     * Assets
     * --------------------------------------------------------------------------
     *
     * An industry operating profile can be assigned to many assets.
     *
     * assets.industry_profile_id
     *          ↓
     * industry_operating_profiles.id
     */
    public function assets(): HasMany
    {
        return $this->hasMany(
            Asset::class,
            'industry_profile_id',
            'id'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Creator
     * --------------------------------------------------------------------------
     *
     * User who created the profile.
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
     * --------------------------------------------------------------------------
     * Updater
     * --------------------------------------------------------------------------
     *
     * User who last updated the profile.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }
}