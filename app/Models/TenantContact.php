<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantContact extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_contacts';

    protected $fillable = [
        'tenant_id',
        'contact_name',
        'designation',
        'mobile',
        'email',
        'is_primary',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}