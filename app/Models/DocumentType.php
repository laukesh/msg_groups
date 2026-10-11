<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'document_types';

    protected $fillable = [
        'document_name',
        'is_mandatory',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'is_mandatory' => 'boolean',
        'status' => 'boolean',
    ];

    public function leaseDocuments()
    {
        return $this->hasMany(
            LeaseDocument::class,
            'document_type_id'
        );
    }
}