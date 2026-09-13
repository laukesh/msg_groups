@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Drawings</div>
            <h3 class="mb-1">{{ $drawing->drawing_title }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $drawing->drawing_number }}</span>
                @if($drawing->revision)<span class="badge bg-info text-dark">{{ $drawing->revision }}</span>@endif
                @include('design-management.partials.status-badge', ['status' => $drawing->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.drawings.index', $project) }}" class="btn btn-outline-secondary">← Drawings</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $drawing,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $drawing->isWorkflowEditable() ? route('admin.projects.design-management.drawings.edit', [$project, $drawing]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $drawing])

    <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><div class="text-muted small">Discipline</div>{{ $drawing->discipline?->name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Package</div>{{ $drawing->designPackage?->package_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Consultant</div>{{ $drawing->preparedByConsultant?->company_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Drawing Type</div>{{ $drawing->drawing_type ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Current Revision</div>{{ $drawing->current_revision ? 'Yes' : 'No' }}</div>
        <div class="col-md-3"><div class="text-muted small">File</div>{{ $drawing->file_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Planned Date</div>{{ $drawing->planned_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Submitted Date</div>{{ $drawing->submitted_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-12"><div class="text-muted small">Remarks</div>{{ $drawing->remarks ?: '—' }}</div>
    </div></div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $drawing,
        'codeField' => 'drawing_number',
        'titleField' => 'drawing_title',
        'versionField' => 'revision',
        'revisionShowRoute' => fn ($revision) => route('admin.projects.design-management.drawings.show', [$project, $revision]),
    ])
</div>
@endsection
