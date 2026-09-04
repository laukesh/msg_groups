@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Construction Management
            </h4>

            <div class="text-muted">
                Monitor construction projects, progress, work orders
                and site execution.
            </div>

        </div>

    </div>


    {{-- ============================================================
         SUMMARY CARDS
    ============================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total Projects --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Projects
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalProjects) }}
                    </div>

                    <div class="small text-muted">
                        Development projects
                    </div>

                </div>

            </div>

        </div>


        {{-- Active Projects --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Projects
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($activeProjects) }}
                    </div>

                    <div class="small text-muted">
                        Currently active
                    </div>

                </div>

            </div>

        </div>


        {{-- Delayed --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Delayed Projects
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($delayedProjects) }}
                    </div>

                    <div class="small text-muted">
                        Require attention
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($completedProjects) }}
                    </div>

                    <div class="small text-muted">
                        Completed projects
                    </div>

                </div>

            </div>

        </div>


        {{-- Average Progress --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Average Progress
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format(
                            (float) $averageProgress,
                            2
                        ) }}%
                    </div>

                    <div class="small text-muted">
                        Latest recorded progress
                    </div>

                </div>

            </div>

        </div>


        {{-- Work Orders --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Work Orders
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalWorkOrders) }}
                    </div>

                    <div class="small text-muted">
                        {{ number_format($activeWorkOrders) }}
                        active
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         CONSTRUCTION PIPELINE
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header bg-white">

            <div class="fw-semibold">
                Construction Pipeline
            </div>

            <div class="small text-muted">
                Project lifecycle from development to completion.
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3 align-items-center text-center">

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="small text-muted">
                            Projects
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($totalProjects) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-1 d-none d-md-block">
                    <span class="fs-4 text-muted">
                        →
                    </span>
                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="small text-muted">
                            Active Construction
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($activeProjects) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-1 d-none d-md-block">
                    <span class="fs-4 text-muted">
                        →
                    </span>
                </div>


                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="small text-muted">
                            Completed
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($completedProjects) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PROJECT PROGRESS + RECENT ACTIVITY
    ============================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Project Progress --}}

        <div class="col-lg-8">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <div class="fw-semibold">
                        Construction Progress
                    </div>

                    <div class="small text-muted">
                        Latest recorded progress by project.
                    </div>

                </div>


                <div class="card-body">

                    @forelse($progressProjects as $project)

                        @php

                            $latest =
                                $project
                                    ->constructionProgressUpdates
                                    ->first();

                            $progress =
                                $latest
                                    ? (float) $latest->progress_percentage
                                    : 0;

                        @endphp


                        <div class="mb-4">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <div class="fw-semibold">
                                        {{ $project->project_name }}
                                    </div>

                                    @if($project->project_no)
                                        <div class="small text-muted">
                                            {{ $project->project_no }}
                                        </div>
                                    @endif

                                </div>


                                <div class="fw-semibold">

                                    {{ number_format(
                                        $progress,
                                        2
                                    ) }}%

                                </div>

                            </div>


                            <div
                                class="progress mt-2"
                                style="height: 8px;"
                            >

                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ min(
                                        max($progress, 0),
                                        100
                                    ) }}%;"
                                ></div>

                            </div>


                            @if($latest)

                                <div class="small text-muted mt-1">

                                    Last updated:
                                    {{ $latest->progress_date
                                        ? $latest->progress_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </div>

                            @else

                                <div class="small text-muted mt-1">
                                    No progress recorded.
                                </div>

                            @endif

                        </div>

                    @empty

                        <div class="text-center text-muted py-5">
                            No construction progress recorded.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Recent Activity --}}

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <div class="fw-semibold">
                        Recent Construction Activity
                    </div>

                </div>


                <div class="card-body p-0">

                    @forelse($recentProgress as $progress)

                        <div class="px-3 py-3 border-bottom">

                            <div class="fw-semibold">

                                {{ $progress->project?->project_name
                                    ?? 'Project'
                                }}

                            </div>


                            <div class="small text-muted">

                                Progress updated to

                                <strong>
                                    {{ number_format(
                                        (float) $progress->progress_percentage,
                                        2
                                    ) }}%
                                </strong>

                            </div>


                            <div class="small text-muted mt-1">

                                {{ $progress->progress_date
                                    ? $progress->progress_date->format('d-m-Y')
                                    : '—'
                                }}

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-5">

                            No recent construction activity.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PROJECT REGISTER
    ============================================================= --}}

    <div class="card">

        <div class="card-header bg-white">

            <div class="fw-semibold">
                Construction Projects
            </div>

            <div class="small text-muted">
                Open a project to access its complete construction
                management dashboard.
            </div>

        </div>


        {{-- Search --}}

        <div class="card-body border-bottom">

            <form
                method="GET"
                action="{{ route(
                    'admin.construction.index'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search Project
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Project no., project name..."
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

                            @foreach($projectStatuses as $projectStatus)

                                <option
                                    value="{{ $projectStatus }}"
                                    @selected(
                                        $status === $projectStatus
                                    )
                                >
                                    {{ $projectStatus }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route(
                                    'admin.construction.index'
                                ) }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>


        {{-- Project Table --}}

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

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
                            Progress
                        </th>

                        <th>
                            Work Orders
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

                        @php

                            $latest =
                                $project
                                    ->constructionProgressUpdates
                                    ->first();

                            $progress =
                                $latest
                                    ? (float) $latest->progress_percentage
                                    : 0;

                        @endphp


                        <tr>

                            {{-- Project Number --}}

                            <td>

                                <div class="fw-semibold">

                                    {{ $project->project_number
                                        ?? '—'
                                    }}

                                </div>

                            </td>


                            {{-- Project --}}

                            <td>

                                <div class="fw-semibold">
                                    {{ $project->project_name }}
                                </div>

                            </td>


                            {{-- Land --}}

                            <td>

                                @if($project->land)

                                    <div>
                                        {{ $project->land->land_name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $project->land->land_code }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Stage --}}

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $project->project_stage
                                        ?? '—'
                                    }}

                                </span>

                            </td>


                            {{-- Progress --}}

                            <td style="min-width: 150px;">

                                <div class="d-flex justify-content-between">

                                    <span class="small">
                                        {{ number_format(
                                            $progress,
                                            2
                                        ) }}%
                                    </span>

                                </div>


                                <div
                                    class="progress mt-1"
                                    style="height: 6px;"
                                >

                                    <div
                                        class="progress-bar"
                                        style="width: {{ min(
                                            max($progress, 0),
                                            100
                                        ) }}%;"
                                    ></div>

                                </div>

                            </td>


                            {{-- Work Orders --}}

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $project->construction_work_orders_count }}

                                </span>

                            </td>


                            {{-- Status --}}

                            <td>

                                @if($project->project_status)

                                    <span class="badge bg-secondary">
                                        {{ $project->project_status }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}

                            <td class="text-end">

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.dashboard',
                                        [
                                            'project' => $project,
                                        ]
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
                                colspan="8"
                                class="text-center text-muted py-5"
                            >

                                No construction projects found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        @if($projects->hasPages())

            <div class="card-footer bg-white">

                {{ $projects->links() }}

            </div>

        @endif

    </div>

</div>

@endsection