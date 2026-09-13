@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Comments</div>
            <h3 class="mb-1">Comment {{ $comment->comment_number }}</h3>
            <div class="text-muted">{{ $comment->review?->review_number }} · {{ $comment->review?->submittal?->subject }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $comment->severity }}</span>
                @include('design-management.partials.status-badge', ['status' => $comment->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.comments.index', $project) }}" class="btn btn-outline-secondary">← Comments</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $comment,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $comment->isWorkflowEditable() ? route('admin.projects.design-management.comments.edit', [$project, $comment]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $comment])

    <div class="card mb-4"><div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4"><div class="text-muted small">Category</div>{{ $comment->category ?? '—' }}</div>
            <div class="col-md-4"><div class="text-muted small">Location Reference</div>{{ $comment->location_reference ?? '—' }}</div>
            <div class="col-md-4"><div class="text-muted small">Response Required</div>{{ $comment->response_required ? 'Yes' : 'No' }}</div>
        </div>
        <div class="fw-semibold mb-2">Comment</div>
        <div class="mb-3">{!! nl2br(e($comment->comment_text)) !!}</div>
        @if($comment->consultant_response)
            <div class="fw-semibold mb-2">Consultant Response</div>
            <div>{!! nl2br(e($comment->consultant_response)) !!}</div>
        @endif
    </div></div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $comment,
        'codeField' => 'comment_number',
        'titleField' => 'comment_number',
        'revisionShowRoute' => null,
    ])
</div>
@endsection
