<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'documentable_type',

        'documentable_id',

        'document_type',

        'document_number',

        'title',

        'description',

        'file_name',

        'file_path',

        'file_extension',

        'mime_type',

        'file_size',

        'version',

        'approval_status',

        'document_date',

        'expiry_date',

        'owner_id',

        'is_current',

        'remarks',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'document_date' => 'date',

        'expiry_date' => 'date',

        'is_current' => 'boolean',

    ];


    public function documentable()
    {
        return $this->morphTo();
    }


    public function owner()
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }


    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}