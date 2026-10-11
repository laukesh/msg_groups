@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small mb-1">Design Management / Submittals</div>
            <h3 class="mb-1">{{ $submittal->subject }}</h3>
            <div class="text-muted">{{ $project->project_name }}</div>
            <div class="mt-2">
                <span class="badge bg-secondary">{{ $submittal->submittal_number }}</span>
                @if($submittal->revision)<span class="badge bg-info text-dark">{{ $submittal->revision }}</span>@endif
                @include('design-management.partials.status-badge', ['status' => $submittal->workflowStatus()])
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('admin.projects.design-management.reviews.create', ['project' => $project, 'submittal_id' => $submittal->id]) }}" class="btn btn-outline-primary">Add Review</a>
            <a href="{{ route('admin.projects.design-management.submittals.index', $project) }}" class="btn btn-outline-secondary">← Submittals</a>
            @include('design-management.partials.workflow-actions', [
                'record' => $submittal,
                'config' => $workflowConfig,
                'routes' => $workflowRoutes,
                'editUrl' => $submittal->isWorkflowEditable() ? route('admin.projects.design-management.submittals.edit', [$project, $submittal]) : null,
            ])
        </div>
    </div>

    @include('design-management.partials.alerts')
    @include('design-management.partials.workflow-governance', ['record' => $submittal])

    <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><div class="text-muted small">Discipline</div>{{ $submittal->discipline?->name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Consultant</div>{{ $submittal->consultant?->company_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Package</div>{{ $submittal->designPackage?->package_name ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Decision</div>{{ $submittal->final_decision ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Submission Date</div>{{ $submittal->submission_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-md-3"><div class="text-muted small">Due Date</div>{{ $submittal->due_date?->format('d M Y') ?? '—' }}</div>
        <div class="col-12"><div class="text-muted small">Description</div>{{ $submittal->description ?: '—' }}</div>
    </div></div>

    <div class="card mb-4"><div class="card-header bg-white fw-semibold">Reviews</div><div class="table-responsive">
        <table class="table mb-0"><thead class="table-light"><tr><th>No.</th><th>Date</th><th>Reviewer</th><th>Status</th><th>Decision</th><th></th></tr></thead>
        <tbody>
            @forelse($submittal->reviews as $review)
                <tr>
                    <td>{{ $review->review_number }}</td>
                    <td>{{ $review->review_date?->format('d-m-Y') ?? '—' }}</td>
                    <td>{{ $review->reviewer?->name ?? '—' }}</td>
                    <td>@include('design-management.partials.status-badge', ['status' => $review->workflowStatus()])</td>
                    <td>{{ $review->decision ?? '—' }}</td>
                    <td><a href="{{ route('admin.projects.design-management.reviews.show', [$project, $review]) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No reviews yet.</td></tr>
            @endforelse
        </tbody></table>
    </div></div>

    @include('design-management.partials.workflow-show-footer', [
        'record' => $submittal,
        'codeField' => 'submittal_number',
        'titleField' => 'subject',
        'versionField' => 'revision',
        'revisionShowRoute' => fn ($revision) => route('admin.projects.design-management.submittals.show', [$project, $revision]),
    ])
</div>
@endsection
