<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentBriefWorkflowHistory extends Model
{
    protected $table = 'development_brief_workflow_history';

    protected $fillable = [
        'development_brief_id',
        'from_status',
        'to_status',
        'action',
        'remarks',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function developmentBrief(): BelongsTo
    {
        return $this->belongsTo(
            DevelopmentBrief::class,
            'development_brief_id'
        );
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}