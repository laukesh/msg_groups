<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingMinutes extends Model
{
    protected $table =
        'project_governance_meeting_minutes';


    protected $fillable = [

        'project_governance_meeting_id',

        'minutes_number',

        'prepared_by',

        'prepared_date',

        'minutes_status',

        'opening_summary',

        'attendance_summary',

        'discussion_summary',

        'key_matters_discussed',

        'decisions_summary',

        'action_summary',

        'closing_summary',

        'general_remarks',

        'approved_by',

        'approval_date',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'prepared_date' =>
            'date',

        'approval_date' =>
            'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Meeting
    |--------------------------------------------------------------------------
    */

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernanceMeeting::class,
            'project_governance_meeting_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prepared By
    |--------------------------------------------------------------------------
    */

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'prepared_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
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