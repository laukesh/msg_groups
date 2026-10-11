<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantEmergencyContact extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_emergency_contacts';

    protected $fillable = [
        'tenant_id',
        'person_name',
        'relation',
        'mobile',
        'email',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}