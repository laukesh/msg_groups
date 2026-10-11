<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandOwner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'land_id',
        'owner_type',
        'owner_name',
        'ownership_percentage',
        'ownership_start_date',
        'ownership_end_date',
        'title_reference',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'ownership_percentage' => 'decimal:4',
        'ownership_start_date' => 'date',
        'ownership_end_date' => 'date',
    ];

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }
}