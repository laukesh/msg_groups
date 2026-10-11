<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionQualityNcrAction extends Model
{
    protected $table = 'construction_quality_ncr_actions';

    protected $fillable = [

        'construction_quality_ncr_id',

        'action_type',
        'action_description',
        'action_date',

        'responsible_party',
        'responsible_user_id',

        'due_date',
        'completed_date',

        'status',

        'verification_remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'action_date' =>
            'date',

        'due_date' =>
            'date',

        'completed_date' =>
            'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | NCR
    |--------------------------------------------------------------------------
    */

    public function ncr(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionQualityNcr::class,
            'construction_quality_ncr_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
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
    | Updated By
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