<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionHseToolboxTalk extends Model
{
    protected $table =
        'construction_hse_toolbox_talks';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'project_id',

        'toolbox_talk_number',

        'title',

        'talk_date',
        'talk_time',

        'location',

        'topic',

        'conducted_by',
        'conducted_by_name',

        'objectives',

        'discussion_points',

        'safety_instructions',

        'hazards_discussed',

        'precautions',

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

        'talk_date' =>
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
    | Participants
    |--------------------------------------------------------------------------
    */

    public function participants(): HasMany
    {
        return $this->hasMany(
            ConstructionHseToolboxTalkParticipant::class,
            'construction_hse_toolbox_talk_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ConstructionHseToolboxTalkDocument::class,
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
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }


    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }
}