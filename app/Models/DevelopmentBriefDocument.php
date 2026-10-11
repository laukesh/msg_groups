<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentBriefDocument extends Model
{
    protected $table = 'development_brief_documents';

    protected $fillable = [
        'development_brief_id',
        'document_title',
        'document_type',
        'description',
        'document_date',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'document_version',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'document_date' => 'date',
        'uploaded_at' => 'datetime',
    ];

    public function developmentBrief(): BelongsTo
    {
        return $this->belongsTo(
            DevelopmentBrief::class,
            'development_brief_id'
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}