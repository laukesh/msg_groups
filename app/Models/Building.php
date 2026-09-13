<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings';

    protected $fillable = [
        'mall_id',
        'building_code',
        'building_name',
        'description',
        'total_floors',
        'total_units',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'mall_id'       => 'integer',
        'total_floors'  => 'integer',
        'total_units'   => 'integer',
        'status'        => 'integer',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mall
    |--------------------------------------------------------------------------
    */

    public function mall()
    {
        return $this->belongsTo(
            Mall::class,
            'mall_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator()
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

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Floors
    |--------------------------------------------------------------------------
    */

    public function floors()
    {
        return $this->hasMany(
            Floor::class,
            'building_id'
        );
    }
}