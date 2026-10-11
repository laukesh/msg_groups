@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Development Management
            </div>

            <h4 class="mb-1">
                Procurement Management
            </h4>

            <div class="text-muted">
                Manage procurement plans, packages, tenders, evaluations,
                awards and contracts across projects.
            </div>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Projects
                    </div>

                    <h3 class="mb-1">
                        {{ $totalProjects }}
                    </h3>

                    <div class="text-muted small">
                        Development projects
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Projects
                    </div>

                    <h3 class="mb-1">
                        {{ $activeProjects }}
                    </h3>

                    <div class="text-muted small">
                        Currently active
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed Projects
                    </div>

                    <h3 class="mb-1">
                        {{ $completedProjects }}
                    </h3>

                    <div class="text-muted small">
                        Procurement completed
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Procurement Projects --}}
    <div class="card">

        <div class="card-header">

            <div>

                <strong>
                    Procurement Projects
                </strong>

                <div class="text-muted small mt-1">
                    Open a project to access its complete procurement
                    management dashboard.
                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- Search --}}
            <form
                method="GET"
                action="{{ route('admin.procurement.index') }}"
                class="row g-3 mb-4"
            >

                <div class="col-md-7">

                    <label class="form-label">
                        Search Project
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Project no., project code, project name..."
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All
                        </option>

                        <option
                            value="Active"
                            @selected($status === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Completed"
                            @selected($status === 'Completed')
                        >
                            Completed
                        </option>

                        <option
                            value="Draft"
                            @selected($status === 'Draft')
                        >
                            Draft
                        </option>

                    </select>

                </div>


                <div class="col-md-2 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Search
                    </button>

                    <a
                        href="{{ route('admin.procurement.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>


            {{-- Project Table --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>
                                Project No.
                            </th>

                            <th>
                                Project
                            </th>

                            <th>
                                Land
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($projects as $project)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $project->project_number }}
                                    </strong>

                                    @if($project->project_code)

                                        <div class="text-muted small">
                                            {{ $project->project_code }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <strong>
                                        {{ $project->project_name }}
                                    </strong>

                                </td>


                                <td>

                                    @if($project->land)

                                        {{ $project->land->land_name
                                            ?? $project->land->name
                                            ?? '—' }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $project->project_stage ?? '—' }}
                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $project->project_status }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.procurement',
                                            ['project' => $project]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    No projects found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($projects->hasPages())

                <div class="mt-3">

                    {{ $projects->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection