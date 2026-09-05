@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Design Changes</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.changes.create', $project) }}" class="btn btn-primary">New Design Change</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Code</th><th>Title</th><th>Type</th><th>Cost Impact</th><th>Time (days)</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($changes as $change)
                    <tr>
                        <td>{{ $change->change_code }}</td>
                        <td>{{ $change->change_title }}</td>
                        <td>{{ $change->change_type ?? '—' }}</td>
                        <td>{{ $change->currency }} {{ number_format((float) $change->cost_impact, 2) }}</td>
                        <td>{{ $change->time_impact_days }}</td>
                        <td><span class="badge bg-secondary">{{ $change->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.changes.show', [$project, $change]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.changes.edit', [$project, $change]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No design changes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
