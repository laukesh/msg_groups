<?php

namespace App\Http\Controllers\Admin\DesignManagement\Concerns;

use App\Models\Project;
use App\Support\DesignWorkflowConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait ManagesDesignWorkflow
{
    abstract protected function workflowModelClass(): string;

    protected function workflowConfig(): array
    {
        return DesignWorkflowConfig::for($this->workflowModelClass());
    }

    protected function ensureWorkflowEditable(Model $record): void
    {
        abort_unless(
            method_exists($record, 'isWorkflowEditable') && $record->isWorkflowEditable(),
            403,
            'This record cannot be edited in its current status.'
        );
    }

    protected function loadWorkflowRelations(Model $record): void
    {
        $record->load([
            'preparer',
            'submitter',
            'rejector',
            'workflowHistories.performer',
        ]);

        if (method_exists($record, 'approver')) {
            $record->load('approver');
        }
    }

    protected function revisionHistory(Project $project, Model $record)
    {
        $config = $this->workflowConfig();

        if (! ($config['supports_revision'] ?? false)) {
            return collect([$record]);
        }

        $parentColumn = $config['parent_column'] ?? 'parent_id';
        $rootId = $record->{$parentColumn} ?? $record->id;

        $query = $this->workflowModelClass()::query();

        if (Schema::hasColumn($record->getTable(), 'project_id')) {
            $query->where('project_id', $project->id);
        }

        return $query
            ->where(function ($builder) use ($parentColumn, $rootId, $record) {
                $builder->where('id', $rootId)
                    ->orWhere($parentColumn, $rootId);
            })
            ->orderByDesc($config['version_number_column'] ?? 'version_number')
            ->get();
    }

    public function submitWorkflow(Project $project, Model $record): RedirectResponse
    {
        $config = $this->workflowConfig();
        $statusColumn = $config['status_column'];
        $currentStatus = $record->{$statusColumn};

        abort_unless(
            in_array($currentStatus, $config['submit_from'], true),
            422,
            'This record cannot be submitted in its current status.'
        );

        $record->update([
            $statusColumn => $config['submit_to'],
            'submitted_at' => now(),
            'submitted_by' => auth()->id(),
            'rejected_at' => null,
            'rejected_by' => null,
            'rejection_reason' => null,
            'updated_by' => auth()->id(),
        ]);

        $record->recordWorkflowHistory('Submitted', $currentStatus, $config['submit_to']);

        return back()->with('success', $config['entity_label'] . ' submitted for review.');
    }

    public function approveWorkflow(Request $request, Project $project, Model $record): RedirectResponse
    {
        $config = $this->workflowConfig();
        $statusColumn = $config['status_column'];
        $currentStatus = $record->{$statusColumn};

        abort_unless(
            in_array($currentStatus, $config['approve_from'], true),
            422,
            'This record cannot be approved in its current status.'
        );

        $validated = $request->validate([
            'approval_remarks' => 'nullable|string|max:2000',
        ]);

        $approvedAtColumn = $config['approved_at_column'] ?? 'approved_at';
        $approvedByColumn = $config['approved_by_column'] ?? 'approved_by';

        $update = [
            $statusColumn => $config['approve_to'],
            $approvedAtColumn => now(),
            $approvedByColumn => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        if (array_key_exists('approval_remarks', $validated)) {
            $update['approval_remarks'] = $validated['approval_remarks'] ?? $record->approval_remarks;
        }

        $record->update($update);

        $record->recordWorkflowHistory(
            'Approved',
            $currentStatus,
            $config['approve_to'],
            $validated['approval_remarks'] ?? null
        );

        return back()->with('success', $config['entity_label'] . ' approved successfully.');
    }

    public function rejectWorkflow(Request $request, Project $project, Model $record): RedirectResponse
    {
        $config = $this->workflowConfig();
        $statusColumn = $config['status_column'];
        $currentStatus = $record->{$statusColumn};

        abort_unless(
            in_array($currentStatus, $config['reject_from'], true),
            422,
            'This record cannot be rejected in its current status.'
        );

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:2000',
        ]);

        $record->update([
            $statusColumn => $config['reject_to'],
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        $record->recordWorkflowHistory(
            'Rejected',
            $currentStatus,
            $config['reject_to'],
            $validated['rejection_reason'] ?? null
        );

        return back()->with('success', $config['entity_label'] . ' rejected.');
    }

    public function revisionWorkflow(Project $project, Model $record): RedirectResponse
    {
        $config = $this->workflowConfig();

        abort_unless($config['supports_revision'] ?? false, 422, 'Revisions are not supported for this record.');
        abort_unless(
            in_array($record->{$config['status_column']}, $config['revision_from'] ?? ['Approved'], true),
            422,
            'Only approved records can create a revision.'
        );

        $parentColumn = $config['parent_column'] ?? 'parent_id';
        $versionNumberColumn = $config['version_number_column'] ?? 'version_number';
        $codeField = $config['code_field'] ?? null;

        $nextVersion = ((int) $this->workflowModelClass()::query()
            ->when(
                Schema::hasColumn($record->getTable(), 'project_id'),
                fn ($q) => $q->where('project_id', $project->id)
            )
            ->max($versionNumberColumn) ?? 0) + 1;

        $exclude = ['id', 'created_at', 'updated_at'];

        if ($codeField) {
            $exclude[] = $codeField;
        }

        $newRecord = $record->replicate($exclude);
        $newRecord->{$parentColumn} = $record->{$parentColumn} ?? $record->id;
        $newRecord->{$versionNumberColumn} = $nextVersion;

        if ($config['version_column'] && $config['revision_prefix']) {
            $newRecord->{$config['version_column']} = $config['revision_prefix'] . str_pad((string) ($nextVersion - 1), 2, '0', STR_PAD_LEFT);
        } elseif ($config['version_column']) {
            $newRecord->{$config['version_column']} = $nextVersion . '.0';
        }

        $newRecord->{$config['status_column']} = $config['initial_status'];
        $newRecord->prepared_by = auth()->id();
        $newRecord->prepared_at = now();
        $newRecord->submitted_at = null;
        $newRecord->submitted_by = null;
        $newRecord->rejected_at = null;
        $newRecord->rejected_by = null;
        $newRecord->rejection_reason = null;
        $newRecord->created_by = auth()->id();
        $newRecord->updated_by = auth()->id();

        $approvedAtColumn = $config['approved_at_column'] ?? 'approved_at';
        $approvedByColumn = $config['approved_by_column'] ?? 'approved_by';
        $newRecord->{$approvedAtColumn} = null;
        $newRecord->{$approvedByColumn} = null;
        $newRecord->approval_remarks = null;

        if ($codeField) {
            $newRecord->{$codeField} = null;
        }

        $newRecord->save();

        $newRecord->recordWorkflowHistory(
            'Revision Created',
            null,
            $config['initial_status'],
            'Revision created from ' . ($codeField ? $record->{$codeField} : ('#' . $record->id))
        );

        $editRoute = $this->workflowEditRoute($project, $newRecord);

        return redirect()
            ->to($editRoute)
            ->with('success', $config['entity_label'] . ' revision created successfully.');
    }

    abstract protected function workflowRoutePrefix(): string;

    protected function workflowViewData(Project $project, Model $record): array
    {
        $prefix = $this->workflowRoutePrefix();
        $params = [$project, $record];
        $config = $this->workflowConfig();

        return [
            'workflowConfig' => $config,
            'workflowRoutes' => [
                'submit' => route("admin.projects.design-management.{$prefix}.submit", $params),
                'approve' => route("admin.projects.design-management.{$prefix}.approve", $params),
                'reject' => route("admin.projects.design-management.{$prefix}.reject", $params),
                'revision' => ($config['supports_revision'] ?? false)
                    ? route("admin.projects.design-management.{$prefix}.revision", $params)
                    : null,
            ],
            'revisions' => $this->revisionHistory($project, $record),
        ];
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return url()->previous();
    }

    protected function initializeWorkflowOnCreate(array &$data, string $modelClass): void
    {
        $config = DesignWorkflowConfig::for($modelClass);
        $statusColumn = $config['status_column'];

        $data[$statusColumn] = $config['initial_status'];
        $data['prepared_by'] = auth()->id();
        $data['prepared_at'] = now();
    }
}
