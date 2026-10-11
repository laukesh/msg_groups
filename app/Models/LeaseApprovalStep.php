<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaseApprovalStep extends Model
{
    protected $table = 'lease_approval_steps';

    protected $fillable = [
        'lease_approval_id',
        'step_name',
        'approver_user_id',
        'decision',
        'comments',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    public function leaseApproval()
    {
        return $this->belongsTo(LeaseApproval::class, 'lease_approval_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
