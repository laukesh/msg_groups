@extends('layouts.app')

@section('title', 'Development Brief Versions')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">
                Development Brief Versions
            </h1>

            <p class="text-muted mb-1">
                {{ $developmentBrief->brief_title }}
            </p>

            <small class="text-muted">
                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? ('Project #' . $project->id) }}
                </strong>
            </small>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route(
                    'admin.projects.development-brief.show',
                    [$project, $developmentBrief]
                ) }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                Back to Brief
            </a>

        </div>

    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Create Version --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle"></i>
                        Create New Version
                    </h5>
                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.development-brief.versions.store',
                            [$project, $developmentBrief]
                        ) }}"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Version <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="version"
                                class="form-control"
                                value="{{ old('version', $developmentBrief->version ?: '1.0') }}"
                                placeholder="Example: 1.0"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Revision Number
                            </label>

                            <input
                                type="text"
                                name="revision_number"
                                class="form-control"
                                value="{{ old('revision_number', $developmentBrief->revision_number) }}"
                                placeholder="Example: Rev-01"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >
                                <option value="Draft">Draft</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Superseded">Superseded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Change Summary
                            </label>

                            <textarea
                                name="change_summary"
                                class="form-control"
                                rows="5"
                                placeholder="Describe what changed in this version..."
                            >{{ old('change_summary') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="fas fa-save"></i>
                            Save Version Snapshot
                        </button>

                    </form>

                </div>

            </div>

        </div>

        {{-- Version History --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i>
                        Version History
                    </h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Version</th>
                                    <th>Revision</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($versions as $version)

                                    <tr>
                                        <td>
                                            {{ $loop->iteration + (($versions->currentPage() - 1) * $versions->perPage()) }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $version->version }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $version->revision_number ?: '—' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $version->status ?: '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $version->creator->name
                                                ?? $version->creator->full_name
                                                ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $version->created_at
                                                ? $version->created_at->format('d M Y h:i A')
                                                : '—' }}
                                        </td>

                                        <td class="text-end">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.development-brief.versions.show',
                                                    [
                                                        'project' => $project->id,
                                                        'developmentBrief' => $developmentBrief->id,
                                                        'version' => $version->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>

                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="7"
                                            class="text-center text-muted py-4"
                                        >
                                            No versions created yet.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                @if($versions->hasPages())
                    <div class="card-footer">
                        {{ $versions->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection