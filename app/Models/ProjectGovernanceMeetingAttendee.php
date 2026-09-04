<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingAttendee extends Model
{
    protected $table = 'project_governance_meeting_attendees';

    protected $fillable = [

        'project_governance_meeting_id',

        'user_id',

        'attendee_name',

        'attendee_role',

        'organization',

        'attendance_status',

        'joined_at',

        'left_at',

        'remarks',

        'created_by',

        'updated_by',
    ];


    public function meeting(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernanceMeeting::class,
            'project_governance_meeting_id'
        );
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}