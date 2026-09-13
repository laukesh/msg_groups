<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionRiskHistory extends Model
{
    protected $table = 'construction_risk_history';

    public $timestamps = false;

    protected $fillable = [

        'construction_risk_id',

        'action',

        'old_status',

        'new_status',

        'remarks',

        'performed_by',

        'performed_at',
    ];

    protected $casts = [

        'performed_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Risk
    |--------------------------------------------------------------------------
    */

    public function risk(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionRisk::class,
            'construction_risk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Performed By
    |--------------------------------------------------------------------------
    */

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}