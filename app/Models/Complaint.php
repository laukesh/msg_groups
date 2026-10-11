<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Complaint extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'complaints';

    protected $fillable = [
        'complaint_number',
        'tenant_id',
        'raised_by',
        'unit_id',
        'department_id',
        'complaint_category',
        'subject',
        'description',
        'priority',
        'assigned_to',
        'service_request_id',
        'resolution_notes',
        'resolved_at',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'uuid' => 'string',
        'tenant_id' => 'decimal:2',
        'raised_by' => 'decimal:2',
        'unit_id' => 'decimal:2',
        'department_id' => 'decimal:2',
        'assigned_to' => 'decimal:2',
        'service_request_id' => 'decimal:2',
        'resolved_at' => 'datetime'
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
