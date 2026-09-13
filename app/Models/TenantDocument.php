<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantDocument extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_documents';

    protected $fillable = [
        'tenant_id',
        'document_type_id',
        'document_number',
        'file_name',
        'file_path',
        'issue_date',
        'expiry_date',
        'verification_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }

    public function documentType()
    {
        return $this->belongsTo(
            DocumentType::class,
            'document_type_id'
        );
    }
}