<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class ProjectGovernanceMeeting extends Model
{
    protected $table = 'project_governance_meetings';

    protected $fillable = [

        'project_id',

        'project_governance_id',

        'meeting_number',

        'meeting_date',

        'start_time',

        'end_time',

        'meeting_type',

        'committee_name',

        'location',

        'meeting_mode',

        'chairperson_id',

        'secretary_id',

        'agenda',

        'minutes',

        'status',

        'reference_number',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'meeting_date' => 'date',

    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    public function governance(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernance::class,
            'project_governance_id'
        );
    }


    public function chairperson(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'chairperson_id'
        );
    }


    public function secretary(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'secretary_id'
        );
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingAttendee::class,
            'project_governance_meeting_id'
        );
    }

    public function agendaItems(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingAgendaItem::class,
            'project_governance_meeting_id'
        )->orderBy('item_no');
    }

    public function actionItems(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingActionItem::class,
            'project_governance_meeting_id'
        )->orderBy('action_no');
    }
    
    public function decisions(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingDecision::class,
            'project_governance_meeting_id'
        )->orderBy('decision_no');
    }

    /*public function minutes(): HasOne
    {
        return $this->hasOne(
            ProjectGovernanceMeetingMinutes::class,
            'project_governance_meeting_id'
        );
    }*/

    public function officialMinutes(): HasOne
    {
        return $this->hasOne(
            ProjectGovernanceMeetingMinutes::class,
            'project_governance_meeting_id'
        );
    }


    public function meeting(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernanceMeeting::class,
            'project_governance_meeting_id'
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingDocument::class,
            'project_governance_meeting_id'
        )->latest();
    }

}