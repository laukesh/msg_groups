<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentBriefVersion extends Model
{
    protected $table = 'development_brief_versions';

    protected $fillable = [
        'development_brief_id',
        'version',
        'revision_number',
        'change_summary',
        'snapshot_data',
        'status',
        'created_by',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
    ];

    public function developmentBrief(): BelongsTo
    {
        return $this->belongsTo(
            DevelopmentBrief::class,
            'development_brief_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}