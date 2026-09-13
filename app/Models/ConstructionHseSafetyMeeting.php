<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseSafetyMeeting extends Model
{
    protected $table =
        'construction_hse_safety_meetings';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'meeting_number',

        'meeting_date',
        'meeting_time',

        'meeting_type',

        'title',

        'location',

        'conducted_by',
        'conducted_by_name',

        'meeting_objective',

        'agenda',

        'discussion_points',

        'safety_instructions',

        'actions_commitments',

        'next_meeting_date',

        'status',

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

        'meeting_date' =>
            'date',

        'next_meeting_date' =>
            'date',

    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Conducted By
    |--------------------------------------------------------------------------
    */

    public function conductedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'conducted_by'
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
    | Participants
    |--------------------------------------------------------------------------
    */

    public function participants(): HasMany
    {
        return $this->hasMany(
            ConstructionHseSafetyMeetingParticipant::class,
            'construction_hse_safety_meeting_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isScheduled(): bool
    {
        return $this->status === 'Scheduled';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }
    
    public function documents(): HasMany
    {
        return $this->hasMany(
            ConstructionHseSafetyMeetingDocument::class,
            'construction_hse_safety_meeting_id'
        );
    }
}