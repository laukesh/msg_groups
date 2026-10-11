@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">Drawing Register</h4>
            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code) · {{ $project->project_code }} @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @include('design-management.partials.dashboard-link')
            <a href="{{ route('admin.projects.design-management.drawings.create', $project) }}" class="btn btn-primary">Register Drawing</a>
        </div>
    </div>
    @include('design-management.partials.alerts')
    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Discipline</label>
                <select name="discipline_id" class="form-select"><option value="">All</option>
                    @foreach($disciplines as $d)<option value="{{ $d->id }}" @selected(($filters['discipline_id'] ?? '') == $d->id)>{{ $d->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select"><option value="">All</option>
                    @foreach($statuses as $s)<option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Consultant</label>
                <select name="consultant_id" class="form-select"><option value="">All</option>
                    @foreach($consultants as $c)<option value="{{ $c->id }}" @selected(($filters['consultant_id'] ?? '') == $c->id)>{{ $c->company_name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-primary">Filter</button></div>
        </form>
    </div></div>
    <div class="card"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>No.</th><th>Title</th><th>Discipline</th><th>Rev</th><th>Status</th><th>Consultant</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($drawings as $drawing)
                    <tr>
                        <td>{{ $drawing->drawing_number }}</td>
                        <td>{{ $drawing->drawing_title }}</td>
                        <td>{{ $drawing->discipline?->name ?? '—' }}</td>
                        <td>{{ $drawing->revision }}</td>
                        <td><span class="badge bg-secondary">{{ $drawing->status }}</span></td>
                        <td>{{ $drawing->preparedByConsultant?->company_name ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.design-management.drawings.show', [$project, $drawing]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.projects.design-management.drawings.edit', [$project, $drawing]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No drawings registered.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
