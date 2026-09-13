<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantAddress extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_addresses';

    protected $fillable = [
        'tenant_id',
        'address_type',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'pincode',
        'is_default',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}