<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantHistory extends Model
{
    protected $table = 'tenant_history';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'activity_type',
        'reference_module',
        'reference_id',
        'description',
        'activity_date',
        'performed_by',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Tenant
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Performer
    |--------------------------------------------------------------------------
    */

    public function performer()
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}