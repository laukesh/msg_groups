@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Design Packages</div>
            <h3 class="mb-1">{{ $package->package_name }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $package->package_code }}</span>
                @if($package->version)<span class="badge bg-info text-dark">V{{ $package->version }}</span>@endif
                @include('design-management.partials.status-badge', ['status' => $package->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.packages.index', $project) }}" class="btn btn-outline-secondary">← Packages</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $package,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $package->isWorkflowEditable() ? route('admin.projects.design-management.packages.edit', [$project, $package]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $package])

    <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><div class="text-muted small">Discipline</div>{{ $package->discipline?->name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Consultant</div>{{ $package->responsibleConsultant?->company_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Planned Submission</div>{{ $package->planned_submission_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Actual Submission</div>{{ $package->actual_submission_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-12"><div class="text-muted small">Description</div>{{ $package->description ?: '—' }}</div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-6"><div class="card"><div class="card-header bg-white">Drawings ({{ $package->drawings->count() }})</div><div class="card-body p-0">
            @forelse($package->drawings as $drawing)
                <div class="px-3 py-2 border-bottom">{{ $drawing->drawing_number }} — {{ $drawing->drawing_title }}</div>
            @empty
                <div class="text-muted p-3">No drawings linked.</div>
            @endforelse
        </div></div></div>
        <div class="col-md-6"><div class="card"><div class="card-header bg-white">Submittals ({{ $package->submittals->count() }})</div><div class="card-body p-0">
            @forelse($package->submittals as $submittal)
                <div class="px-3 py-2 border-bottom">{{ $submittal->submittal_number }} — {{ $submittal->subject }}</div>
            @empty
                <div class="text-muted p-3">No submittals linked.</div>
            @endforelse
        </div></div></div>
    </div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $package,
        'codeField' => 'package_code',
        'titleField' => 'package_name',
        'revisionShowRoute' => fn ($revision) => route('admin.projects.design-management.packages.show', [$project, $revision]),
    ])
</div>
@endsection
