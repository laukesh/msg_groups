<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FitoutDocument extends Model
{
    use SoftDeletes;

    protected $table = 'fitout_documents';

    /*protected $fillable = [
        'uuid',
        'fitout_request_id',
        'document_type_id',
        'document_title',
        'document_number',
        'version_no',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'submitted_by',
        'submitted_at',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'remarks',
        'created_by',
        'updated_by',
    ];*/

    protected $fillable = [
        'uuid',
        'fitout_request_id',
        'document_type_id',
        'document_title',
        'document_number',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'version_no',
        'submitted_by',
        'submitted_at',
        'approval_status',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }

    public function documentType()
    {
        return $this->belongsTo(
            DocumentType::class,
            'document_type_id'
        );
    }

    public function submittedBy()
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}