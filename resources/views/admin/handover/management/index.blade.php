@extends('layouts.app')

@section('title', 'Handover & Closeout Management')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handover &amp; Closeout Management
            </h4>

            <div class="text-muted">
                Manage handover and closeout activities across all projects.
            </div>
        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                Total Projects
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $totalProjects }}
                            </h3>
                        </div>

                        <div class="text-primary fs-3">
                            <i class="ri-building-line"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                In Progress
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $inProgress }}
                            </h3>
                        </div>

                        <div class="text-warning fs-3">
                            <i class="ri-loader-4-line"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                Ready for Handover
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $ready }}
                            </h3>
                        </div>

                        <div class="text-success fs-3">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                Completed
                            </div>

                            <h3 class="mb-0 mt-2">
                                {{ $completed }}
                            </h3>
                        </div>

                        <div class="text-info fs-3">
                            <i class="ri-flag-line"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- PROJECT LIST --}}
    <div class="card border">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">
                        Projects
                    </h5>

                    <div class="text-muted small">
                        Select a project to manage its Handover &amp; Closeout process.
                    </div>
                </div>

            </div>

        </div>


        {{-- Search --}}
        <div class="card-body border-bottom">

            <form method="GET"
                  action="{{ route('admin.handover.index') }}">

                <div class="row g-2">

                    <div class="col-md-5">

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="ri-search-line"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search project name or code..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>

                    <div class="col-md-auto">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="ri-search-line me-1"></i>
                            Search

                        </button>

                    </div>

                    @if(request('search'))

                        <div class="col-md-auto">

                            <a
                                href="{{ route('admin.handover.index') }}"
                                class="btn btn-light">

                                Clear

                            </a>

                        </div>

                    @endif

                </div>

            </form>

        </div>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            Project
                        </th>

                        <th>
                            Handover No.
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Readiness
                        </th>

                        <th>
                            Planned Handover
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($projects as $project)

                        @php
                            $handover = $project->handoverProjects->first();
                        @endphp

                        <tr>

                            <td>
                                {{ $projects->firstItem() + $loop->index }}
                            </td>


                            <td>

                                <div class="fw-semibold">
                                    {{ $project->project_name }}
                                </div>

                                @if(!empty($project->project_code))

                                    <div class="text-muted small">
                                        {{ $project->project_code }}
                                    </div>

                                @endif

                            </td>


                            <td>

                                @if($handover)

                                    <span class="fw-semibold">
                                        {{ $handover->handover_no }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($handover)

                                    @php

                                        $statusClass = match($handover->status) {

                                            'Draft' => 'bg-secondary-subtle text-secondary',

                                            'In Progress' => 'bg-warning-subtle text-warning',

                                            'Ready for Handover' => 'bg-success-subtle text-success',

                                            'Submitted' => 'bg-info-subtle text-info',

                                            'Approved' => 'bg-primary-subtle text-primary',

                                            'Rejected' => 'bg-danger-subtle text-danger',

                                            'On Hold' => 'bg-dark-subtle text-dark',

                                            'Completed' => 'bg-success text-white',

                                            default => 'bg-light text-dark',

                                        };

                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $handover->status }}
                                    </span>

                                @else

                                    <span class="badge bg-light text-muted">
                                        Not Started
                                    </span>

                                @endif

                            </td>


                            <td style="min-width: 150px;">

                                @if($handover)

                                    <div class="d-flex align-items-center gap-2">

                                        <div
                                            class="progress flex-grow-1"
                                            style="height: 7px;"
                                        >

                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                style="width: {{ $handover->readiness_percentage }}%;"
                                            ></div>

                                        </div>

                                        <span class="small fw-semibold">
                                            {{ number_format($handover->readiness_percentage, 0) }}%
                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($handover && $handover->planned_handover_date)

                                    {{ $handover->planned_handover_date->format('d-m-Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                @if($handover)

                                    <a
                                        href="{{ route('admin.projects.handover.index', $project) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="ri-external-link-line me-1"></i>
                                        Open

                                    </a>

                                @else

                                    <form
                                        method="POST"
                                        action="{{ route('admin.handover.projects.start', $project) }}"
                                        class="d-inline">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary">

                                            <i class="ri-play-line me-1"></i>
                                            Start Handover

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="ri-folder-open-line fs-1 d-block mb-2"></i>

                                    No projects found.

                                </div>

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