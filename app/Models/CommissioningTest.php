<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissioningTest extends Model
{
    protected $table = 'commissioning_tests';

    protected $fillable = [
        'project_id',
        'commissioning_scope_id',
        'test_plan_id',
        'test_no',
        'test_date',
        'test_time',
        'test_type',
        'location',
        'expected_result',
        'actual_result',
        'measured_value',
        'measured_unit',
        'result',
        'retest_required',
        'parent_test_id',
        'status',
        'remarks',
        'attachment_path',

        'performed_by',
        'witnessed_by',
        'consultant_representative',
        'client_representative',
        'site_condition',
        'retest_date',
        'completed_at',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'test_date'      => 'date',
        'retest_date'    => 'date',
        'completed_at'   => 'datetime',
        'retest_required'=> 'boolean',
        'test_time'      => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Commissioning Scope
    |--------------------------------------------------------------------------
    */

    public function scope(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningScope::class,
            'commissioning_scope_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test Plan
    |--------------------------------------------------------------------------
    */

    public function plan(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningTestPlan::class,
            'test_plan_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Parent Test
    |--------------------------------------------------------------------------
    */

    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningTest::class,
            'parent_test_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Retests
    |--------------------------------------------------------------------------
    */

    public function retests(): HasMany
    {
        return $this->hasMany(
            CommissioningTest::class,
            'parent_test_id'
        )->latest('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
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

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Performed By
    |--------------------------------------------------------------------------
    */

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Witnessed By
    |--------------------------------------------------------------------------
    */

    public function witnessedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'witnessed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isRetest(): bool
    {
        return !is_null($this->parent_test_id);
    }

    public function hasRetests(): bool
    {
        return $this->retests()->exists();
    }
}