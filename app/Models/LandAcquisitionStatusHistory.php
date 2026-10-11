<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandAcquisitionStatusHistory extends Model
{
    protected $fillable = [

        'land_id',

        'from_status',

        'to_status',

        'remarks',

        'changed_by',

        'changed_at',

    ];


    protected $casts = [

        'changed_at' => 'datetime',

    ];


    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }


    public function changedBy()
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}