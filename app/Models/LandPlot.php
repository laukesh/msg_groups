<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandPlot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'land_id',
        'plot_number',
        'survey_number',
        'parcel_number',
        'plot_area',
        'area_unit',
        'plot_type',
        'boundaries',
        'description',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'plot_area' => 'decimal:4',
    ];

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }
}