<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementBidder extends Model
{
    protected $table = 'procurement_bidders';

    protected $fillable = [
        'bidder_code',
        'company_name',
        'company_registration_no',
        'gst_number',
        'pan_number',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function tenderBidders(): HasMany
    {
        return $this->hasMany(
            ProcurementTenderBidder::class,
            'procurement_bidder_id'
        );
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(
            ProcurementContract::class,
            'procurement_bidder_id'
        );
    }
}
