<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueAuditLog extends Model
{
    protected $table = 'revenue_audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module',
        'action',
        'reference_type',
        'reference_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}