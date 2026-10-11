<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentBriefComment extends Model
{
    protected $table = 'development_brief_comments';

    protected $fillable = [
        'development_brief_id',
        'user_id',
        'comment',
        'comment_type',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function developmentBrief(): BelongsTo
    {
        return $this->belongsTo(
            DevelopmentBrief::class,
            'development_brief_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }
}