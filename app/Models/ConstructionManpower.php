<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionManpower extends Model
{
    use SoftDeletes;

    protected $table = 'construction_manpower';

    protected $fillable = [
        'project_id',
        'manpower_code',
        'manpower_name',
        'manpower_type',
        'trade',
        'employment_type',
        'phone',
        'joining_date',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    public function assignments()
    {
        return $this->hasMany(
            ConstructionManpowerAssignment::class,
            'manpower_id'
        );
    }

    public function entries()
    {
        return $this->hasMany(
            ConstructionManpowerEntry::class,
            'manpower_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}