@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Submittals</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.submittals.create', $project) }}" class="btn btn-primary">New Submittal</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>No.</th><th>Subject</th><th>Discipline</th><th>Consultant</th><th>Rev</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($submittals as $submittal)
                    <tr>
                        <td>{{ $submittal->submittal_number }}</td>
                        <td>{{ $submittal->subject }}</td>
                        <td>{{ $submittal->discipline?->name ?? '—' }}</td>
                        <td>{{ $submittal->consultant?->company_name ?? '—' }}</td>
                        <td>{{ $submittal->revision ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $submittal->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.submittals.show', [$project, $submittal]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.submittals.edit', [$project, $submittal]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No submittals yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
