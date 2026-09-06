@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / RFIs</div>
            <h3 class="mb-1">{{ $rfi->subject }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $rfi->rfi_number }}</span>
                <span class="badge bg-warning text-dark">{{ $rfi->priority }}</span>
                @include('design-management.partials.status-badge', ['status' => $rfi->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.rfis.index', $project) }}" class="btn btn-outline-secondary">← RFIs</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $rfi,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $rfi->isWorkflowEditable() ? route('admin.projects.design-management.rfis.edit', [$project, $rfi]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $rfi])

    <div class="card mb-4"><div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3"><div class="text-muted small">Discipline</div>{{ $rfi->discipline?->name ?? '—' }}</div>
            <div class="col-md-3"><div class="text-muted small">Consultant</div>{{ $rfi->consultant?->company_name ?? '—' }}</div>
            <div class="col-md-3"><div class="text-muted small">Raised Date</div>{{ $rfi->raised_date?->format('d M Y') ?? '—' }}</div>
            <div class="col-md-3"><div class="text-muted small">Required Response</div>{{ $rfi->required_response_date?->format('d M Y') ?? '—' }}</div>
        </div>
        <div class="fw-semibold mb-2">Question</div>
        <div class="mb-3">{!! nl2br(e($rfi->question)) !!}</div>
        @if($rfi->response)
            <div class="fw-semibold mb-2">Response</div>
            <div>{!! nl2br(e($rfi->response)) !!}</div>
        @endif
    </div></div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $rfi,
        'codeField' => 'rfi_number',
        'titleField' => 'subject',
        'revisionShowRoute' => null,
    ])
</div>
@endsection
