<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingActionItem extends Model
{
    protected $table =
        'project_governance_meeting_action_items';


    protected $fillable = [

        'project_governance_meeting_id',

        'project_governance_meeting_agenda_item_id',

        'action_no',

        'action_description',

        'responsible_user_id',

        'responsible_name',

        'responsible_organization',

        'priority',

        'due_date',

        'status',

        'completion_date',

        'completion_remarks',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'action_no' =>
            'integer',

        'due_date' =>
            'date',

        'completion_date' =>
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
    | Source Agenda Item
    |--------------------------------------------------------------------------
    */

    public function agendaItem(): BelongsTo
    {
        return $this->belongsTo(
            ProjectGovernanceMeetingAgendaItem::class,
            'project_governance_meeting_agenda_item_id'
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
}