<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeType extends Model
{
    protected $table = 'charge_types';

    public $timestamps = false;

    protected $fillable = [
        'charge_name',
        'charge_code',
        'taxable',
        'status',
    ];

    protected $casts = [
        'taxable' => 'boolean',
        'status' => 'boolean',
    ];

    public function invoiceItems()
    {
        return $this->hasMany(
            InvoiceItem::class,
            'charge_type_id'
        );
    }

    public function taxConfigurations()
    {
        return $this->hasMany(
            TaxConfiguration::class,
            'charge_type_id'
        );
    }
}