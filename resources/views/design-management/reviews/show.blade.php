@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Reviews</div>
            <h3 class="mb-1">Review {{ $review->review_number }}</h3>
            <div class="text-muted">{{ $review->submittal?->subject }}</div>
            <div class="mt-2">
                @include('design-management.partials.status-badge', ['status' => $review->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.comments.create', ['project' => $project, 'review_id' => $review->id]) }}" class="btn btn-outline-primary">Add Comment</a>
            <a href="{{ route('admin.projects.design-management.reviews.index', $project) }}" class="btn btn-outline-secondary">← Reviews</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $review,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $review->isWorkflowEditable() ? route('admin.projects.design-management.reviews.edit', [$project, $review]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $review])

    <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><div class="text-muted small">Reviewer</div>{{ $review->reviewer?->name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Review Date</div>{{ $review->review_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Decision</div>{{ $review->decision ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Response Due</div>{{ $review->response_due_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-12"><div class="text-muted small">General Comments</div>{!! nl2br(e($review->general_comments ?: '—')) !!}</div>
    </div></div>

    <div class="card mb-4"><div class="card-header bg-white fw-semibold">Comments</div><div class="table-responsive">
        <table class="table mb-0"><thead class="table-light"><tr><th>No.</th><th>Category</th><th>Severity</th><th>Status</th><th>Comment</th><th></th></tr></thead>
        <tbody>
            @forelse($review->comments as $comment)
                <tr>
                    <td>{{ $comment->comment_number }}</td>
                    <td>{{ $comment->category ?? '—' }}</td>
                    <td>{{ $comment->severity }}</td>
                    <td>@include('design-management.partials.status-badge', ['status' => $comment->workflowStatus()])</td>
                    <td>{{ str()->limit($comment->comment_text, 80) }}</td>
                    <td><a href="{{ route('admin.projects.design-management.comments.show', [$project, $comment]) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No comments yet.</td></tr>
            @endforelse
        </tbody></table>
    </div></div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $review,
        'codeField' => 'review_number',
        'titleField' => 'review_number',
        'revisionShowRoute' => null,
    ])
</div>
@endsection
