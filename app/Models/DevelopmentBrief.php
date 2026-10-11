<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DevelopmentBrief extends Model
{
    protected $table = 'development_briefs';

    protected $fillable = [
        'project_id',

        'brief_reference',
        'brief_title',
        'version',
        'revision_number',
        'document_date',

        'prepared_by',

        'business_need',
        'target_users',
        'target_use',

        'area_schedule',
        'room_unit_mix',

        'business_requirements',
        'functional_requirements',
        'operational_requirements',

        'quality_level',
        'quality_requirements',
        'design_criteria',
        'design_intent',
        'technical_requirements',

        'commercial_intent',
        'sustainability_requirements',
        'success_criteria',

        'key_constraints',
        'key_assumptions',
        'key_dependencies',
        'required_studies',

        'approvals_required',
        'approval_status',
        'approval_remarks',
        'revision_history',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'prepared_by' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'document_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(
            DevelopmentBriefDocument::class,
            'development_brief_id'
        );
    }

    public function comments(): HasMany
    {
        return $this->hasMany(
            DevelopmentBriefComment::class,
            'development_brief_id'
        );
    }

    public function versions(): HasMany
    {
        return $this->hasMany(
            DevelopmentBriefVersion::class,
            'development_brief_id'
        )->latest();
    }

    public function workflowHistory(): HasMany
    {
        return $this->hasMany(
            DevelopmentBriefWorkflowHistory::class,
            'development_brief_id'
        )->latest('performed_at');
    }
}