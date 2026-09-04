<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractExtensionOfTime extends Model
{
    protected $table = 'contract_extensions_of_time';

    protected $fillable = [

        'contract_management_contract_id',

        'eot_number',

        'request_date',

        'reason_type',

        'title',

        'description',

        'delay_start_date',
        'delay_end_date',

        'requested_days',
        'approved_days',

        'original_completion_date',
        'revised_completion_date',

        'status',

        'submitted_by_party',

        'submission_date',
        'response_due_date',
        'decision_date',

        'review_remarks',
        'decision_remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'request_date' => 'date',

        'delay_start_date' => 'date',
        'delay_end_date' => 'date',

        'requested_days' => 'integer',
        'approved_days' => 'integer',

        'original_completion_date' => 'date',
        'revised_completion_date' => 'date',

        'submission_date' => 'date',
        'response_due_date' => 'date',
        'decision_date' => 'date',
    ];


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
}