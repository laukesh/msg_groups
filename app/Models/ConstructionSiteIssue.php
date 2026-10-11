<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionSiteIssue extends Model
{
    protected $table = 'construction_site_issues';

    protected $fillable = [

        'project_id',

        'construction_work_order_id',

        'construction_progress_update_id',

        'issue_number',

        'issue_date',

        'issue_type',

        'category',

        'title',

        'description',

        'priority',

        'raised_by',

        'assigned_to',

        'due_date',

        'corrective_action',

        'resolution',

        'status',

        'resolution_date',

        'remarks',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'issue_date' =>
            'date',

        'due_date' =>
            'date',

        'resolution_date' =>
            'date',
    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionWorkOrder::class,
            'construction_work_order_id'
        );
    }


    public function progress(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionProgressUpdate::class,
            'construction_progress_update_id'
        );
    }


    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'raised_by'
        );
    }


    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    public function isOpen(): bool
    {
        return in_array(
            $this->status,
            [
                'Open',
                'In Progress',
                'Reopened',
            ],
            true
        );
    }


    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        if (!$this->isOpen()) {
            return false;
        }

        return $this->due_date->isPast();
    }
}