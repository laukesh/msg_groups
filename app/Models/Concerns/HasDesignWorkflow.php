<?php

namespace App\Models\Concerns;

use App\Models\DesignWorkflowHistory;
use App\Models\User;
use App\Support\DesignWorkflowConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDesignWorkflow
{
    public function workflowHistories(): MorphMany
    {
        return $this->morphMany(DesignWorkflowHistory::class, 'historable')
            ->latest('performed_at');
    }

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function workflowStatus(): string
    {
        $column = $this->workflowStatusColumn();

        return (string) ($this->{$column} ?? '');
    }

    public function workflowStatusColumn(): string
    {
        return DesignWorkflowConfig::for(static::class)['status_column'];
    }

    public function isWorkflowEditable(): bool
    {
        return in_array(
            $this->workflowStatus(),
            DesignWorkflowConfig::for(static::class)['editable_statuses'],
            true
        );
    }

    public function recordWorkflowHistory(
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $remarks = null
    ): DesignWorkflowHistory {
        return $this->workflowHistories()->create([
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remarks' => $remarks,
            'performed_by' => auth()->id(),
        ]);
    }
}
