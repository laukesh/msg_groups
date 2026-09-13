<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionCorrespondenceDocument extends Model
{
    use SoftDeletes;

    protected $table = 'construction_correspondence_documents';

    protected $fillable = [
        'construction_correspondence_id',

        'document_type',
        'document_title',

        'file_path',
        'file_name',
        'file_size',
        'mime_type',

        'description',

        'uploaded_by',
    ];

    protected $casts = [

        'file_size' => 'integer',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Correspondence
    |--------------------------------------------------------------------------
    */

    public function correspondence(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionCorrespondence::class,
            'construction_correspondence_id'
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
}