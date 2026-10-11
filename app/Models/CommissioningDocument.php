<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissioningDocument extends Model
{
    protected $fillable = [
        'project_id',
        'commissioning_scope_id',
        'test_plan_id',
        'test_id',
        'certificate_id',

        'document_type',
        'document_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'document_version',
        'description',
        'status',

        'uploaded_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'file_size'   => 'integer',
        'approved_at' => 'datetime',
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
    | Commissioning Scope
    |--------------------------------------------------------------------------
    */
    public function scope(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningScope::class,
            'commissioning_scope_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test Plan
    |--------------------------------------------------------------------------
    */
    public function testPlan(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningTestPlan::class,
            'test_plan_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test Execution
    |--------------------------------------------------------------------------
    */
    public function test(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningTest::class,
            'test_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Certificate
    |--------------------------------------------------------------------------
    */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(
            CommissioningCertificate::class,
            'certificate_id'
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
    | Approved By
    |--------------------------------------------------------------------------
    */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Human Readable File Size
    |--------------------------------------------------------------------------
    */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}