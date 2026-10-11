<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionClaimHistory extends Model
{
    public $timestamps = false;

    protected $table = 'construction_claim_history';

    protected $fillable = [
        'construction_claim_id',
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

    public function claim()
    {
        return $this->belongsTo(
            ConstructionClaim::class,
            'construction_claim_id'
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