<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverRequirement extends Model
{
    protected $table = 'handover_requirements';

    /*protected $fillable = [
        'handover_project_id',
        'project_id',
        'requirement_code',
        'requirement_type',
        'title',
        'description',
        'source_module',
        'source_id',
        'responsible_user_id',
        'due_date',
        'priority',
        'is_mandatory',
        'status',
        'completed_by',
        'completed_at',
        'remarks',
        'created_by',
        'updated_by',
    ];*/

    protected $fillable = [
	    'handover_project_id',
	    'project_id',
	    'requirement_code',
	    'requirement_type',
	    'title',
	    'description',
	    'source_module',
	    'source_id',
	    'source_type',
	    'auto_sync',
	    'responsible_user_id',
	    'due_date',
	    'priority',
	    'is_mandatory',
	    'status',
	    'completed_by',
	    'completed_at',
	    'remarks',
	    'created_by',
	    'updated_by',
	];

    protected $casts = [
	    'due_date' => 'date',
	    'is_mandatory' => 'boolean',
	    'auto_sync' => 'boolean',
	    'completed_at' => 'datetime',
	];

    public function handover(): BelongsTo
    {
        return $this->belongsTo(
            HandoverProject::class,
            'handover_project_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }
}