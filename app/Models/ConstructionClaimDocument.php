<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionClaimDocument extends Model
{
    use SoftDeletes;

    protected $table = 'construction_claim_documents';

    protected $fillable = [
        'construction_claim_id',
        'document_type',
        'document_title',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'description',
        'uploaded_by',
    ];

    public function claim()
    {
        return $this->belongsTo(
            ConstructionClaim::class,
            'construction_claim_id'
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