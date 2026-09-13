<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseToolboxTalkParticipant extends Model
{
    protected $table =
        'construction_hse_toolbox_talk_participants';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_toolbox_talk_id',

        'participant_name',
        'participant_type',

        'employee_code',

        'company_name',
        'designation',

        'phone',
        'email',

        'attended',

        'attendance_time',

        'signature_path',

        'remarks',

        'created_by',
        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'attended' =>
            'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Toolbox Talk
    |--------------------------------------------------------------------------
    */

    public function toolboxTalk(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseToolboxTalk::class,
            'construction_hse_toolbox_talk_id'
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


    /*
    |--------------------------------------------------------------------------
    | Attendance Helpers
    |--------------------------------------------------------------------------
    */

    public function isPresent(): bool
    {
        return $this->attended === true;
    }


    public function isAbsent(): bool
    {
        return $this->attended === false;
    }
}