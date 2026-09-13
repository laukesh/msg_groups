<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaseHistory extends Model
{
    protected $table = 'lease_history';

    public $timestamps = false;

    protected $fillable = [
        'lease_agreement_id',
        'activity_type',
        'reference_module',
        'reference_id',
        'activity_title',
        'activity_description',
        'old_value',
        'new_value',
        'activity_date',
        'performed_by',
        'ip_address',
        'device_info',
        'remarks',
        'created_at',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }

    public function performer()
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}