@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Design Packages</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.packages.create', $project) }}" class="btn btn-primary">Add Package</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Discipline</label>
                <select name="discipline_id" class="form-select">
                    <option value="">All</option>
                    @foreach($disciplines as $discipline)
                        <option value="{{ $discipline->id }}" @selected(($filters['discipline_id'] ?? '') == $discipline->id)>{{ $discipline->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><button class="btn btn-primary">Filter</button></div>
        </form>
    </div></div>
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Code</th><th>Package</th><th>Discipline</th><th>Consultant</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td>{{ $package->package_code }}</td>
                        <td>{{ $package->package_name }}</td>
                        <td>{{ $package->discipline?->name ?? '—' }}</td>
                        <td>{{ $package->responsibleConsultant?->company_name ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $package->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.packages.show', [$project, $package]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.packages.edit', [$project, $package]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No design packages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
