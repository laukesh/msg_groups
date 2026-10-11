@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Approvals</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Pending Briefs</div><div class="fs-4">{{ $summary['pending_briefs'] }}</div></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Pending Submittals</div><div class="fs-4">{{ $summary['pending_submittals'] }}</div></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Pending Changes</div><div class="fs-4">{{ $summary['pending_changes'] }}</div></div></div></div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">Project Briefs Pending Approval</div>
        <div class="table-responsive">
            <table class="table mb-0"><thead class="table-light"><tr><th>Code</th><th>Title</th><th>Version</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($pendingBriefs as $brief)
                    <tr>
                        <td>{{ $brief->brief_code }}</td><td>{{ $brief->title }}</td><td>{{ $brief->version }}</td><td>{{ $brief->status }}</td>
                        <td><a href="{{ route('admin.projects.design-management.briefs.edit', [$project, $brief]) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No pending briefs.</td></tr>
                @endforelse
            </tbody></table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">Submittals Pending Approval</div>
        <div class="table-responsive">
            <table class="table mb-0"><thead class="table-light"><tr><th>No.</th><th>Subject</th><th>Discipline</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($pendingSubmittals as $submittal)
                    <tr>
                        <td>{{ $submittal->submittal_number }}</td><td>{{ $submittal->subject }}</td><td>{{ $submittal->discipline?->name ?? '—' }}</td><td>{{ $submittal->status }}</td>
                        <td><a href="{{ route('admin.projects.design-management.submittals.edit', [$project, $submittal]) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No pending submittals.</td></tr>
                @endforelse
            </tbody></table>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-semibold">Design Changes Pending Approval</div>
        <div class="table-responsive">
            <table class="table mb-0"><thead class="table-light"><tr><th>Code</th><th>Title</th><th>Cost</th><th>Time</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($pendingChanges as $change)
                    <tr>
                        <td>{{ $change->change_code }}</td><td>{{ $change->change_title }}</td>
                        <td>{{ $change->currency }} {{ number_format((float) $change->cost_impact, 2) }}</td>
                        <td>{{ $change->time_impact_days }} days</td><td>{{ $change->status }}</td>
                        <td><a href="{{ route('admin.projects.design-management.changes.show', [$project, $change]) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No pending design changes.</td></tr>
                @endforelse
            </tbody></table>
        </div>
    </div>
</div>
@endsection
