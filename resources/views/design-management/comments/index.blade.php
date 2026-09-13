@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Comments</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.comments.create', $project) }}" class="btn btn-primary">Add Comment</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>No.</th><th>Review</th><th>Category</th><th>Severity</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($comments as $comment)
                    <tr>
                        <td>{{ $comment->comment_number }}</td>
                        <td>{{ $comment->review?->review_number }}</td>
                        <td>{{ $comment->category ?? '—' }}</td>
                        <td>{{ $comment->severity }}</td>
                        <td>{{ $comment->status }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.comments.show', [$project, $comment]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.comments.edit', [$project, $comment]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No comments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
