<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandOpportunity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'opportunity_no',
        'opportunity_name',
        'description',
        'source',
        'identified_date',
        'estimated_area',
        'area_unit',
        'estimated_acquisition_cost',
        'currency',
        'location_text',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'identified_date' => 'date',
        'estimated_area' => 'decimal:4',
        'estimated_acquisition_cost' => 'decimal:2',
    ];

    public function lands()
    {
        return $this->hasMany(Land::class, 'opportunity_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}