<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxConfiguration extends Model
{
    use SoftDeletes;

    protected $table = 'tax_configurations';

    protected $fillable = [
        'uuid',
        'charge_type_id',
        'tax_name',
        'tax_type',
        'hsn_sac_code',
        'tax_percentage',
        'effective_from',
        'effective_to',
        'is_default',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function chargeType()
    {
        return $this->belongsTo(
            ChargeType::class,
            'charge_type_id'
        );
    }
}