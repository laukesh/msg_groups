@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Design Management</h4>
            <div class="text-muted">Manage design activities across development projects.</div>
        </div>
    </div>

    @include('design-management.partials.alerts')

    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Projects</div>
                <div class="fs-3 fw-semibold">{{ number_format($totalProjects) }}</div>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Active Projects</div>
                <div class="fs-3 fw-semibold">{{ number_format($activeProjects) }}</div>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Design Packages</div>
                <div class="fs-3 fw-semibold">{{ number_format($summary['total_packages']) }}</div>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Drawings</div>
                <div class="fs-3 fw-semibold">{{ number_format($summary['total_drawings']) }}</div>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Open RFIs</div>
                <div class="fs-3 fw-semibold">{{ number_format($summary['total_rfis']) }}</div>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Design Changes</div>
                <div class="fs-3 fw-semibold">{{ number_format($summary['total_changes']) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="fw-semibold">Projects</div>
            <div class="small text-muted">Open a project to access its design management workspace.</div>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.design-management.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Search Project</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Project no., code, name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            @foreach($projectStatuses as $projectStatus)
                                <option value="{{ $projectStatus }}" @selected($status === $projectStatus)>{{ $projectStatus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.design-management.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Project</th>
                        <th>Packages</th>
                        <th>Drawings</th>
                        <th>Submittals</th>
                        <th>RFIs</th>
                        <th>Changes</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $project->project_name }}</div>
                                <div class="small text-muted">{{ $project->project_number ?? $project->project_code }}</div>
                            </td>
                            <td>{{ $project->design_packages_count }}</td>
                            <td>{{ $project->design_drawings_count }}</td>
                            <td>{{ $project->design_submittals_count }}</td>
                            <td>{{ $project->design_rfis_count }}</td>
                            <td>{{ $project->design_changes_count }}</td>
                            <td><span class="badge bg-secondary">{{ $project->project_status ?? '—' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.projects.design-management.dashboard', $project) }}" class="btn btn-sm btn-outline-primary">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5">No projects found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="card-footer bg-white">{{ $projects->links() }}</div>
        @endif
    </div>
</div>
@endsection
