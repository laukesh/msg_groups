@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Development Briefs</h4>
            <p class="text-muted mb-0">
                {{ $project->name ?? $project->project_name ?? 'Project #' . $project->id }}
            </p>
        </div>

        <div class="d-flex gap-2">

            {{-- Back to Project Details --}}
            <a
                href="{{ route('admin.projects.show', ['project' => $project->id]) }}"
                class="btn btn-outline-primary"
                title="Project Details"
            >
                <i class="fa fa-folder-open"></i>
                Project
            </a>

            <a href="{{ route('admin.development-briefs.index') }}"
               class="btn btn-outline-secondary">
                All Development Briefs
            </a>

            <a href="{{ route('admin.projects.development-brief.create', $project->id) }}"
               class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Add Development Brief
            </a>

        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brief Reference</th>
                            <th>Title</th>
                            <th>Version</th>
                            <th>Revision</th>
                            <th>Status</th>
                            <th>Document Date</th>
                            <th>Comments</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($developmentBriefs as $brief)

                            <tr>
                                <td>
                                    {{ $developmentBriefs->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $brief->brief_reference }}
                                </td>

                                <td>
                                    {{ $brief->brief_title }}
                                </td>

                                <td>
                                    {{ $brief->version }}
                                </td>

                                <td>
                                    {{ $brief->revision_number ?: '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $brief->approval_status }}
                                    </span>
                                </td>

                                <td>
                                    {{ $brief->document_date
                                        ? $brief->document_date->format('d-m-Y')
                                        : '-' }}
                                </td>
                                <td>
    <a
        href="{{ route(
            'admin.projects.development-brief.comments',
            [
                'project' => $project->id,
                'developmentBrief' => $brief->id,
            ]
        ) }}"
        class="badge bg-primary text-decoration-none"
        title="View Comments"
    >
        <i class="fa fa-comments"></i>
        {{ $brief->comments_count ?? 0 }}
    </a>
</td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route(
                                            'admin.projects.development-brief.show',
                                            [
                                                'project' => $project->id,
                                                'developmentBrief' => $brief->id
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ route(
                                            'admin.projects.development-brief.edit',
                                            [
                                                'project' => $project->id,
                                                'developmentBrief' => $brief->id
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route(
                                            'admin.projects.development-brief.destroy',
                                            [
                                                'project' => $project->id,
                                                'developmentBrief' => $brief->id
                                            ]
                                        ) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this brief?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center text-muted">
                                    No Development Briefs found for this project.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $developmentBriefs->links() }}

        </div>
    </div>

</div>

@endsection