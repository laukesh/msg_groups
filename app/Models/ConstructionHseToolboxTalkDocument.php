<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseToolboxTalkDocument extends Model
{
    protected $table =
        'construction_hse_toolbox_talk_documents';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_toolbox_talk_id',

        'document_number',

        'document_name',

        'document_type',

        'description',

        'file_path',

        'original_file_name',

        'file_size',

        'mime_type',

        'document_date',

        'uploaded_by',

        'remarks',

        'created_by',
        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'document_date' =>
            'date',

        'file_size' =>
            'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Toolbox Talk
    |--------------------------------------------------------------------------
    */

    public function toolboxTalk(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseToolboxTalk::class,
            'construction_hse_toolbox_talk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Uploaded By
    |--------------------------------------------------------------------------
    */

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | File Size
    |--------------------------------------------------------------------------
    */

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return '—';
        }

        $bytes = $this->file_size;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format(
                $bytes / 1024,
                2
            ) . ' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return number_format(
                $bytes / (1024 * 1024),
                2
            ) . ' MB';
        }

        return number_format(
            $bytes / (1024 * 1024 * 1024),
            2
        ) . ' GB';
    }
}