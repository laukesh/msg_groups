<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProcurementMilestoneDocument extends Model
{
    protected $table = 'procurement_milestone_documents';

    protected $fillable = [
        'procurement_contract_id',
        'procurement_contract_milestone_id',
        'procurement_milestone_progress_id',

        'document_number',
        'document_title',
        'document_type',

        'file_name',
        'file_path',
        'file_extension',
        'mime_type',
        'file_size',

        'description',

        'status',

        'uploaded_by',
        'uploaded_at',

        'verified_by',
        'verified_at',
        'verification_remarks',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',

        'file_size' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Milestone
    |--------------------------------------------------------------------------
    */

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContractMilestone::class,
            'procurement_contract_milestone_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Progress Update
    |--------------------------------------------------------------------------
    */

    public function progress(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementMilestoneProgress::class,
            'procurement_milestone_progress_id'
        );
    }
}