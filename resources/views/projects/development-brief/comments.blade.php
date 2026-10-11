@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Brief Comments</h4>

            <p class="text-muted mb-0">
                Development Brief:
                {{ $developmentBrief->brief_reference ?? 'Brief #' . $developmentBrief->id }}
            </p>
        </div>

        <div>
            <a
                href="{{ route(
                    'admin.projects.development-brief.show',
                    [
                        'project' => $projectId,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fa fa-arrow-left"></i>
                Back to Brief
            </a>
        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Comment --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>
                <i class="fa fa-comment"></i>
                Add Comment
            </strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route(
                    'admin.projects.development-brief.comments.store',
                    [
                        'project' => $projectId,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                ) }}"
                method="POST"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">
                            Comment Type <span class="text-danger">*</span>
                        </label>

                        <select
                            name="comment_type"
                            class="form-select @error('comment_type') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Comment Type</option>

                            <option value="General"
                                {{ old('comment_type') == 'General' ? 'selected' : '' }}>
                                General
                            </option>

                            <option value="Clarification"
                                {{ old('comment_type') == 'Clarification' ? 'selected' : '' }}>
                                Clarification
                            </option>

                            <option value="Revision Required"
                                {{ old('comment_type') == 'Revision Required' ? 'selected' : '' }}>
                                Revision Required
                            </option>

                            <option value="Approval"
                                {{ old('comment_type') == 'Approval' ? 'selected' : '' }}>
                                Approval
                            </option>
                        </select>

                        @error('comment_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">
                            Comment <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="comment"
                            rows="3"
                            class="form-control @error('comment') is-invalid @enderror"
                            placeholder="Enter your comment..."
                            required
                        >{{ old('comment') }}</textarea>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Add Comment
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Comments Listing --}}
    <div class="card">

        <div class="card-header">
            <strong>
                <i class="fa fa-comments"></i>
                Comments History
            </strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Comment</th>
                            <th>Type</th>
                            <th>Raised By</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th width="230">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($comments as $comment)

                            <tr>

                                <td>
                                    {{ $comments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {!! nl2br(e($comment->comment)) !!}
                                </td>

                                <td>
                                    @php
                                        $typeClass = match($comment->comment_type) {
                                            'Clarification' => 'bg-info',
                                            'Revision Required' => 'bg-warning text-dark',
                                            'Approval' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $typeClass }}">
                                        {{ $comment->comment_type }}
                                    </span>
                                </td>

                                <td>
                                    {{ $comment->user->name ?? 'System' }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = match($comment->status) {
                                            'Resolved' => 'bg-success',
                                            'Closed' => 'bg-dark',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $comment->status }}
                                    </span>

                                    @if($comment->resolved_at)
                                        <br>
                                        <small class="text-muted">
                                            {{ $comment->resolved_at->format('d-m-Y H:i') }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ optional($comment->created_at)->format('d-m-Y H:i') }}
                                </td>

                                <td>

                                    <div class="d-flex gap-1 flex-wrap">

                                        {{-- Status Update --}}
                                        @if($comment->status !== 'Resolved')

                                            <form
                                                action="{{ route(
                                                    'admin.projects.development-brief.comments.status',
                                                    [
                                                        'project' => $projectId,
                                                        'developmentBrief' => $developmentBrief->id,
                                                        'comment' => $comment->id,
                                                    ]
                                                ) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Resolved"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-success"
                                                    title="Mark as Resolved"
                                                >
                                                    <i class="fa fa-check"></i>
                                                    Resolve
                                                </button>

                                            </form>

                                        @else

                                            <form
                                                action="{{ route(
                                                    'admin.projects.development-brief.comments.status',
                                                    [
                                                        'project' => $projectId,
                                                        'developmentBrief' => $developmentBrief->id,
                                                        'comment' => $comment->id,
                                                    ]
                                                ) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Open"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-warning"
                                                    title="Reopen Comment"
                                                >
                                                    <i class="fa fa-undo"></i>
                                                    Reopen
                                                </button>

                                            </form>

                                        @endif

                                        {{-- Close Comment --}}
                                        @if($comment->status !== 'Closed')

                                            <form
                                                action="{{ route(
                                                    'admin.projects.development-brief.comments.status',
                                                    [
                                                        'project' => $projectId,
                                                        'developmentBrief' => $developmentBrief->id,
                                                        'comment' => $comment->id,
                                                    ]
                                                ) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Closed"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-secondary"
                                                    title="Close Comment"
                                                >
                                                    <i class="fa fa-lock"></i>
                                                </button>

                                            </form>

                                        @endif

                                        {{-- Delete --}}
                                        <form
                                            action="{{ route(
                                                'admin.projects.development-brief.comments.destroy',
                                                [
                                                    'project' => $projectId,
                                                    'developmentBrief' => $developmentBrief->id,
                                                    'comment' => $comment->id,
                                                ]
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete Comment"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                    @if($comment->resolvedBy)
                                        <small class="text-muted d-block mt-1">
                                            Resolved by:
                                            {{ $comment->resolvedBy->name ?? '-' }}
                                        </small>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No comments found for this Development Brief.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($comments->hasPages())
            <div class="card-footer">
                {{ $comments->links() }}
            </div>
        @endif

    </div>

</div>

@endsection