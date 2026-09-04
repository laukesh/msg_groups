<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionQualityItpItem extends Model
{
    protected $table = 'construction_quality_itp_items';

    protected $fillable = [

        'construction_quality_itp_id',

        'item_number',

        'activity',

        'inspection_test',

        'stage',

        'acceptance_criteria',

        'reference_standard',

        'inspection_type',

        'responsible_party',

        'hold_point',

        'witness_point',

        'required',

        'remarks',
    ];


    protected $casts = [

        'hold_point' =>
            'boolean',

        'witness_point' =>
            'boolean',

        'required' =>
            'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | ITP
    |--------------------------------------------------------------------------
    */

    public function itp(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionQualityItp::class,
            'construction_quality_itp_id'
        );
    }

    public function ncrs(): HasMany
    {
        return $this->hasMany(
            ConstructionQualityNcr::class,
            'construction_quality_itp_item_id'
        );
    }
}