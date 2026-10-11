<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CamCharge extends Model
{
    use SoftDeletes;

    protected $table = 'cam_charges';

    protected $guarded = ['id'];

    protected $dates = [
        'period_start',
        'period_end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Example relationships - adjust to your actual models
    public function leaseAgreement()
    {
        return $this->belongsTo(\App\Models\LeaseAgreement::class, 'lease_agreement_id');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
    }

    public function invoiceItem()
    {
        return $this->belongsTo(\App\Models\InvoiceItem::class, 'invoice_item_id');
    }
}
