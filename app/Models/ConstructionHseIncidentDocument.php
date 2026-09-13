<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseIncidentDocument extends Model
{
    protected $table =
        'construction_hse_incident_documents';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_incident_id',

        'document_title',
        'document_type',
        'description',

        'file_name',
        'file_path',
        'file_type',
        'file_size',

        'is_evidence',
        'document_date',

        'uploaded_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'is_evidence' =>
            'boolean',

        'document_date' =>
            'date',

        'file_size' =>
            'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Incident
    |--------------------------------------------------------------------------
    */

    public function incident(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseIncident::class,
            'construction_hse_incident_id'
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


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isEvidence(): bool
    {
        return $this->is_evidence === true;
    }


    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $size = $this->file_size;

        if ($size >= 1024 * 1024) {

            return number_format(
                $size / (1024 * 1024),
                2
            ) . ' MB';
        }

        if ($size >= 1024) {

            return number_format(
                $size / 1024,
                2
            ) . ' KB';
        }

        return $size . ' Bytes';
    }
}