<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingDocument extends Model
{
    protected $table = 'project_governance_meeting_documents';

    protected $fillable = [
        'project_governance_meeting_id',

        'document_name',
        'document_type',
        'description',

        'file_path',
        'original_file_name',
        'mime_type',
        'file_size',

        'uploaded_by',
        'uploaded_at',

        'status',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Meeting
    |--------------------------------------------------------------------------
    */

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernanceMeeting::class,
            'project_governance_meeting_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Uploaded By
    |--------------------------------------------------------------------------
    */

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}