<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mall extends Model
{
    use SoftDeletes;

    protected $table = 'malls';

    protected $fillable = [
        'mall_code',
        'mall_name',
        'mall_type',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'opening_date',
        'total_area',
        'leasable_area',
        'parking_capacity',
        'contact_person',
        'contact_number',
        'email',
        'website',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_area' => 'decimal:2',
        'leasable_area' => 'decimal:2',
        'parking_capacity' => 'integer',
    ];
    public function buildings()
        {
            return $this->hasMany(
                Building::class,
                'mall_id'
            );
        }
}