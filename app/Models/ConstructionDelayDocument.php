<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionDelayDocument extends Model
{
    use SoftDeletes;

    protected $table = 'construction_delay_documents';

    protected $fillable = [
        'construction_delay_id',
        'document_type',
        'document_title',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'description',
        'uploaded_by',
    ];

    public function delay()
    {
        return $this->belongsTo(
            ConstructionDelay::class,
            'construction_delay_id'
        );
    }

    public function uploadedBy()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}
