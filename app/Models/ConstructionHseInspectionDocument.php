<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseInspectionDocument extends Model
{
    protected $table =
        'construction_hse_inspection_documents';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_inspection_id',

        'document_number',
        'document_type',
        'document_title',

        'file_name',
        'file_path',
        'file_type',
        'file_size',

        'document_date',

        'description',
        'remarks',

        'uploaded_by',

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
    | Inspection
    |--------------------------------------------------------------------------
    */

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseInspection::class,
            'construction_hse_inspection_id'
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
    | File Size Helper
    |--------------------------------------------------------------------------
    */

    public function formattedFileSize(): string
    {
        if (!$this->file_size) {
            return '—';
        }

        $bytes = $this->file_size;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return round(
                $bytes / (1024 * 1024),
                2
            ) . ' MB';
        }

        return round(
            $bytes / (1024 * 1024 * 1024),
            2
        ) . ' GB';
    }
}