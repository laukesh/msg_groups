<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingDecision extends Model
{
    protected $table =
        'project_governance_meeting_decisions';


    protected $fillable = [

        'project_governance_meeting_id',

        'project_governance_meeting_agenda_item_id',

        'decision_no',

        'decision_title',

        'decision_text',

        'decision_type',

        'decision_status',

        'approved_by',

        'approval_date',

        'effective_date',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'decision_no' =>
            'integer',

        'approval_date' =>
            'date',

        'effective_date' =>
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
}