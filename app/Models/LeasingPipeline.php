<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingPipeline extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leasing_pipelines';

    protected $fillable = [
        'prospect_id',
        'prospect_name',
        'company_name',
        'unit_id',
        'area',
        'expected_rent',
        'rent_period',
        'stage',
        'probability',
        'next_action',
        'owner_id',
        'forecast_opening',
        'expected_opening_date',
        'notes',
        'created_by',
        'updated_by',

    ];

    protected $casts = [

        'area' => 'decimal:2',

        'expected_rent' => 'decimal:2',

        'probability' => 'integer',

        'forecast_opening' => 'date',

        'expected_opening_date' => 'date',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | Unit
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Pipeline Owner
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    /*
    |--------------------------------------------------------------------------
    | Aging
    |--------------------------------------------------------------------------
    */

    public function getAgingDaysAttribute()
    {
        if (!$this->created_at) {
            return 0;
        }

        $endDate = $this->updated_at ?? now();

        return $this->created_at->diffInDays($endDate);
    }


    /*
    |--------------------------------------------------------------------------
    | Stage Label
    |--------------------------------------------------------------------------
    */

    public function getStageLabelAttribute()
    {
        return match ($this->stage) {

            'lead'        => 'Lead',
            'prospect'    => 'Prospect',
            'negotiation' => 'Negotiation',
            'agreement'   => 'Agreement',
            'opening'     => 'Opening',
            'won'         => 'Won',
            'lost'        => 'Lost',

            default => ucfirst(
                str_replace('_', ' ', $this->stage)
            ),

        };
    }


    /*
    |--------------------------------------------------------------------------
    | Probability Label
    |--------------------------------------------------------------------------
    */

    public function getProbabilityLabelAttribute()
    {
        return match (true) {

            $this->probability >= 70 => 'High',

            $this->probability >= 40 => 'Medium',

            default => 'Low',

        };
    }


    /*
    |--------------------------------------------------------------------------
    | Forecast Rent
    |--------------------------------------------------------------------------
    */

    public function getWeightedRentAttribute()
    {
        return round(
            ((float) $this->expected_rent *
            (float) $this->probability) / 100,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Active Pipeline
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereNotIn(
            'stage',
            ['won', 'lost']
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Won
    |--------------------------------------------------------------------------
    */

    public function scopeWon($query)
    {
        return $query->where('stage', 'won');
    }


    /*
    |--------------------------------------------------------------------------
    | Lost
    |--------------------------------------------------------------------------
    */

    public function scopeLost($query)
    {
        return $query->where('stage', 'lost');
    }


    /*
    |--------------------------------------------------------------------------
    | Stage Scope
    |--------------------------------------------------------------------------
    */

    public function scopeStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }


    /*
    |--------------------------------------------------------------------------
    | High Probability
    |--------------------------------------------------------------------------
    */

    public function scopeHighProbability($query)
    {
        return $query->where(
            'probability',
            '>=',
            70
        );
    }
    public function prospect()
    {
        return $this->belongsTo(
            LeasingProspect::class,
            'prospect_id'
        );
    }
}