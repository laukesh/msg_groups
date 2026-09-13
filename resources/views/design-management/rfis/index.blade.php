@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">RFIs</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.rfis.create', $project) }}" class="btn btn-primary">New RFI</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>No.</th><th>Subject</th><th>Discipline</th><th>Priority</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($rfis as $rfi)
                    <tr>
                        <td>{{ $rfi->rfi_number }}</td>
                        <td>{{ $rfi->subject }}</td>
                        <td>{{ $rfi->discipline?->name ?? '—' }}</td>
                        <td>{{ $rfi->priority }}</td>
                        <td><span class="badge bg-secondary">{{ $rfi->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.rfis.show', [$project, $rfi]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.rfis.edit', [$project, $rfi]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No RFIs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
