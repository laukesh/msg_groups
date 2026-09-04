<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandZoning extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'land_id',
        'zoning_code',
        'zoning_type',
        'permitted_use',
        'restrictions',
        'authority',
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