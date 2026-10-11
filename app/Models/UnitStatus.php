<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitStatus extends Model
{
    protected $table = 'unit_statuses';

    protected $fillable = [
        'status_name',
        'description',
        'color_code',
        'sort_order',
        'is_active'
    ];

    public function units()
    {
        return $this->hasMany(Unit::class, 'unit_status_id');
    }
}
