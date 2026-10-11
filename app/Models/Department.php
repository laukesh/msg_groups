<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use SoftDeletes;

    protected $table = 'departments';

    protected $fillable = [
        'uuid',
        'department_code',
        'department_name',
        'description',
        'parent_department_id',
        'head_user_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'parent_department_id' => 'integer',
        'head_user_id'         => 'integer',
        'created_by'           => 'integer',
        'updated_by'           => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Parent Department
    |--------------------------------------------------------------------------
    */

    public function parentDepartment(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'parent_department_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Child Departments
    |--------------------------------------------------------------------------
    */

    public function childDepartments(): HasMany
    {
        return $this->hasMany(
            Department::class,
            'parent_department_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Department Head
    |--------------------------------------------------------------------------
    */

    public function headUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'head_user_id'
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
}