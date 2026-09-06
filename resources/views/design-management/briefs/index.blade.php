@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Project Briefs</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.briefs.create', $project) }}" class="btn btn-primary">Add Brief</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Code</th><th>Title</th><th>Version</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($briefs as $brief)
                        <tr>
                            <td>{{ $brief->brief_code }}</td>
                            <td>{{ $brief->title }}</td>
                            <td>{{ $brief->version }}</td>
                            <td>@include('design-management.partials.status-badge', ['status' => $brief->status])</td>
                            <td class="text-end">
                                <a href="{{ route('admin.projects.design-management.briefs.show', [$project, $brief]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                @if($brief->isEditable())
                                    <a href="{{ route('admin.projects.design-management.briefs.edit', [$project, $brief]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No design briefs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
