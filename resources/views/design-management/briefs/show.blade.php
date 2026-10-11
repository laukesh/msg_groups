@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Project Brief</div>
            <h3 class="mb-1">{{ $brief->title }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $brief->brief_code }}</span>
                <span class="badge bg-info text-dark">V{{ $brief->version }}</span>
                @include('design-management.partials.status-badge', ['status' => $brief->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.briefs.index', $project) }}" class="btn btn-outline-secondary">← Briefs</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $brief,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $brief->isWorkflowEditable() ? route('admin.projects.design-management.briefs.edit', [$project, $brief]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $brief])

    @foreach([
        'project_requirements' => 'Project Requirements',
        'design_objectives' => 'Design Objectives',
        'functional_requirements' => 'Functional Requirements',
        'technical_requirements' => 'Technical Requirements',
        'design_standards' => 'Design Standards',
        'authority_requirements' => 'Authority Requirements',
    ] as $field => $label)
        <div class="card mb-4">
            <div class="card-header"><strong>{{ $label }}</strong></div>
            <div class="card-body">{!! nl2br(e($brief->$field ?: '—')) !!}</div>
        </div>
    @endforeach

    @if($brief->remarks)
        <div class="card mb-4">
            <div class="card-header"><strong>Remarks</strong></div>
            <div class="card-body">{{ $brief->remarks }}</div>
        </div>
    @endif

    @include('design-management.partials.workflow-show-footer', [
        'record' => $brief,
        'codeField' => 'brief_code',
        'titleField' => 'title',
        'revisionShowRoute' => fn ($revision) => route('admin.projects.design-management.briefs.show', [$project, $revision]),
    ])
</div>
@endsection
