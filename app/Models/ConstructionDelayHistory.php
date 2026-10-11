<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionDelayHistory extends Model
{
    protected $table = 'construction_delay_history';

    public $timestamps = false;

    protected $fillable = [
        'construction_delay_id',
        'action',
        'old_status',
        'new_status',
        'remarks',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function delay()
    {
        return $this->belongsTo(
            ConstructionDelay::class,
            'construction_delay_id'
        );
    }

    public function performedBy()
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}