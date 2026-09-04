<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandDevelopmentRight extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'land_id',
        'right_type',
        'description',
        'permitted_use',
        'restrictions',
        'authority',
        'reference_no',
        'effective_date',
        'expiry_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }
}