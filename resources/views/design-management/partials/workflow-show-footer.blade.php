@php
    $codeField = $codeField ?? 'brief_code';
    $titleField = $titleField ?? 'title';
    $versionField = $versionField ?? 'version';
@endphp

@if(!empty($revisions) && ($workflowConfig['supports_revision'] ?? false) && $revisions->count() > 1)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Revision History</strong>
        <span class="text-muted small">{{ $revisions->count() }} version(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Prepared</th>
                        <th>Approved</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revisions as $revision)
                        <tr class="{{ $revision->id === $record->id ? 'table-primary' : '' }}">
                            <td>
                                <strong>V{{ $revision->{$versionField} ?? $revision->version_number }}</strong>
                                @if($revision->id === $record->id)
                                    <span class="badge bg-primary ms-1">Current</span>
                                @endif
                            </td>
                            <td>{{ $revision->{$codeField} ?? '—' }}</td>
                            <td>{{ $revision->{$titleField} ?? '—' }}</td>
                            <td>@include('design-management.partials.status-badge', ['status' => $revision->workflowStatus()])</td>
                            <td>{{ $revision->prepared_at?->format('d M Y') ?? '—' }}</td>
                            <td>{{ ($revision->approved_at ?? $revision->approval_date ?? null)?->format('d M Y') ?? '—' }}</td>
                            <td class="text-end">
                                @if(!empty($revisionShowRoute))
                                    <a href="{{ $revisionShowRoute($revision) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@include('design-management.partials.activity-history', ['histories' => $record->workflowHistories])

@if(!$record->isWorkflowEditable() && in_array($record->workflowStatus(), ['Approved', 'Resolved', 'Answered', 'Responded', 'Closed'], true))
    <div class="alert alert-info">
        <strong>Approved Record</strong>
        <div class="mt-1">This record is read-only in its current status. Create a revision if changes are required.</div>
    </div>
@endif

@include('design-management.partials.workflow-modals', [
    'record' => $record,
    'config' => $workflowConfig,
    'routes' => $workflowRoutes,
])
