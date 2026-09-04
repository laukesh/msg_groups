<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGovernanceMeetingAgendaItem extends Model
{
    protected $table = 'project_governance_meeting_agenda_items';


    protected $fillable = [

        'project_governance_meeting_id',

        'item_no',

        'subject',

        'description',

        'presenter_id',

        'presenter_name',

        'priority',

        'discussion',

        'outcome',

        'decision_required',

        'status',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'item_no' =>
            'integer',

        'decision_required' =>
            'boolean',

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
    | Presenter
    |--------------------------------------------------------------------------
    */

    public function presenter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'presenter_id'
        );
    }

    public function actionItems(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingActionItem::class,
            'project_governance_meeting_agenda_item_id'
        )->orderBy('action_no');
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(
            ProjectGovernanceMeetingDecision::class,
            'project_governance_meeting_agenda_item_id'
        )->orderBy('decision_no');
    }

}