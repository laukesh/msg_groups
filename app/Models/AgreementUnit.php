<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementUnit extends Model
{
    use SoftDeletes;

    protected $table = 'agreement_units';

    protected $fillable = [
        'agreement_id',
        'unit_id',
        'agreed_rent',
        'agreed_cam_rate',
        'agreed_security_deposit',
        'fitout_period_days',
        'rent_free_days',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'agreed_rent' => 'decimal:2',
        'agreed_cam_rate' => 'decimal:2',
        'agreed_security_deposit' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Lease Agreement
    |--------------------------------------------------------------------------
    */

    public function agreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'agreement_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Unit
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }
}