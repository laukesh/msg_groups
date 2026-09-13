<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementTenderDocument extends Model
{
    protected $table = 'procurement_tender_documents';

    protected $fillable = [
        'procurement_tender_id',

        'document_number',
        'document_title',
        'document_type',

        'version',
        'issue_date',

        'file_name',
        'file_path',

        'description',

        'status',

        'uploaded_by',
        'uploaded_by_name',

        'remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];


    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }
}