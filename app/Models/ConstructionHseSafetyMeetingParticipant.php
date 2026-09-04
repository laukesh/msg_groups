<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionHseSafetyMeetingParticipant extends Model
{
    protected $table =
        'construction_hse_safety_meeting_participants';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'construction_hse_safety_meeting_id',

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
    | Safety Meeting
    |--------------------------------------------------------------------------
    */

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionHseSafetyMeeting::class,
            'construction_hse_safety_meeting_id'
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
    | Attendance Helper
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