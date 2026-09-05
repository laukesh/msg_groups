@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Reviews</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.reviews.create', $project) }}" class="btn btn-primary">Add Review</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>No.</th><th>Submittal</th><th>Date</th><th>Reviewer</th><th>Status</th><th>Decision</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->review_number }}</td>
                        <td>{{ $review->submittal?->submittal_number }}</td>
                        <td>{{ $review->review_date?->format('d-m-Y') ?? '—' }}</td>
                        <td>{{ $review->reviewer?->name ?? '—' }}</td>
                        <td>{{ $review->review_status }}</td>
                        <td>{{ $review->decision ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.reviews.show', [$project, $review]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.reviews.edit', [$project, $review]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
