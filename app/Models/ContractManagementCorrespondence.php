<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractManagementCorrespondence extends Model
{
    protected $table = 'contract_management_correspondence';

    protected $fillable = [

        'project_id',
        'contract_management_contract_id',

        'correspondence_number',
        'correspondence_date',

        'direction',
        'communication_type',

        'subject',

        'from_party',
        'to_party',
        'cc_party',

        'reference_number',

        'related_correspondence_id',

        'response_required',
        'response_due_date',
        'response_date',

        'priority',
        'status',

        'description',
        'remarks',

        'file_name',
        'file_path',
        'file_size',
        'mime_type',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'correspondence_date' => 'date',

        'response_due_date' => 'date',

        'response_date' => 'date',

        'response_required' => 'boolean',

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
    | Related Correspondence
    |--------------------------------------------------------------------------
    */

    public function relatedCorrespondence(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'related_correspondence_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updater
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
    | Formatted File Size
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


    /*
    |--------------------------------------------------------------------------
    | Response Overdue
    |--------------------------------------------------------------------------
    */

    public function isResponseOverdue(): bool
    {
        if (
            !$this->response_required ||
            !$this->response_due_date ||
            $this->response_date
        ) {
            return false;
        }

        return $this->response_due_date
            ->startOfDay()
            ->isPast();
    }
}