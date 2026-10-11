@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Development Briefs</h4>
            <p class="text-muted mb-0">
                View development briefs across all projects
            </p>
        </div>

        <div class="d-flex gap-2">

            {{-- Add Development Brief - Global Create --}}
            <a
                href="{{ route('admin.development-briefs.create') }}"
                class="btn btn-primary"
                title="Add Development Brief"
            >
                <i class="fa fa-plus"></i>
                Add Development Brief
            </a>

        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.development-briefs.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Reference, title or project"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select">
                            <option value="">All Projects</option>

                            @foreach($projects as $project)
                                <option
                                    value="{{ $project->id }}"
                                    @selected(request('project_id') == $project->id)
                                >
                                    {{ $project->project_name }}
                                    @if(!empty($project->project_code))
                                        - {{ $project->project_code }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Approval Status</label>
                        <select name="approval_status" class="form-select">
                            <option value="">All Statuses</option>

                            @foreach($statuses as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(request('approval_status') === $status)
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.development-briefs.index') }}"
                            class="btn btn-secondary"
                        >
                            Reset
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Listing --}}
    <div class="card">

        <div class="card-header">
            <strong>Project Development Briefs</strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brief Reference</th>
                            <th>Project</th>
                            <th>Brief Title</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($briefs as $brief)

                            <tr>
                                <td>
                                    {{ $briefs->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $brief->brief_reference ?? 'N/A' }}
                                </td>

                                <td>
                                    @if($brief->project)
                                        <strong>
                                            {{ $brief->project->name }}
                                        </strong>

                                        @if(!empty($brief->project->project_code))
                                            <br>
                                            <small class="text-muted">
                                                {{ $brief->project->project_code }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-danger">
                                            Project Not Found
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $brief->title ?? 'Untitled Brief' }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = match($brief->approval_status) {
                                            'Approved' => 'success',
                                            'Rejected' => 'danger',
                                            'Under Review' => 'warning',
                                            'Revision Required' => 'info',
                                            'Archived' => 'dark',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $brief->approval_status ?? 'Draft' }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional($brief->created_at)->format('d-m-Y') }}
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- View using associated project ID --}}
                                        <a
                                            href="{{ route(
                                                'admin.projects.development-brief.show',
                                                [
                                                    'project' => $brief->project_id,
                                                    'developmentBrief' => $brief->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-primary"
                                            title="View Development Brief"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Edit using associated project ID --}}
                                        <a
                                            href="{{ route(
                                                'admin.projects.development-brief.edit',
                                                [
                                                    'project' => $brief->project_id,
                                                    'developmentBrief' => $brief->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-warning"
                                            title="Edit Development Brief"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a
                                            href="{{ route(
                                                'admin.projects.development-brief.index',
                                                ['project' => $project->id]
                                            ) }}"
                                            class="btn btn-info"
                                            title="View All Development Briefs"
                                        >
                                            <i class="fas fa-list"></i>
                                        </a>

                                        {{-- Delete using associated project ID --}}
                                        <form
                                            action="{{ route(
                                                'admin.projects.development-brief.destroy',
                                                [
                                                    'project' => $brief->project_id,
                                                    'developmentBrief' => $brief->id,
                                                ]
                                            ) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this Development Brief?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete Development Brief"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No development briefs found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($briefs->hasPages())
            <div class="card-footer">
                {{ $briefs->links() }}
            </div>
        @endif

    </div>

</div>

@endsection