<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractClaim extends Model
{
    protected $table = 'contract_claims';


    protected $fillable = [

        'contract_management_contract_id',

        'claim_number',
        'claim_date',

        'claim_type',

        'title',
        'description',
        'reason',

        'claimed_amount',
        'approved_amount',

        'currency',

        'submitted_by_party',

        'submission_date',
        'response_due_date',
        'resolution_date',

        'status',

        'review_remarks',
        'resolution_remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'claim_date' => 'date',

        'submission_date' => 'date',

        'response_due_date' => 'date',

        'resolution_date' => 'date',

        'claimed_amount' => 'decimal:2',

        'approved_amount' => 'decimal:2',
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