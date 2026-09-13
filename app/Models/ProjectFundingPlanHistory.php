<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFundingPlanHistory extends Model
{
    protected $table = 'project_funding_plan_histories';

    public $timestamps = false;

    protected $fillable = [
        'project_funding_plan_id',
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


    public function fundingPlan(): BelongsTo
    {
        return $this->belongsTo(
            ProjectFundingPlan::class,
            'project_funding_plan_id'
        );
    }
}