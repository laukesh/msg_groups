<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractManagementDocument extends Model
{
    protected $table = 'contract_management_documents';

    protected $fillable = [

        'project_id',
        'contract_management_contract_id',

        'document_number',
        'document_title',
        'document_type',

        'document_date',
        'document_version',

        'file_name',
        'file_path',
        'file_size',
        'mime_type',

        'description',

        'status',

        'uploaded_by',
        'updated_by',
    ];


    protected $casts = [

        'document_date' => 'date',

        'file_size' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ContractManagementContract::class,
            'contract_management_contract_id'
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

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '—';
        }

        $size = (float) $this->file_size;

        if ($size >= 1073741824) {
            return number_format(
                $size / 1073741824,
                2
            ) . ' GB';
        }

        if ($size >= 1048576) {
            return number_format(
                $size / 1048576,
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