@extends('layouts.app')

@section('title', 'Commissioning Management')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Commissioning Management
            </h4>

            <div class="text-muted">
                Manage testing, commissioning and certification for construction projects.
            </div>
        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total Projects --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Projects
                            </div>

                            <h3 class="mb-0">
                                {{ number_format($totalProjects) }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-buildings text-primary fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active Projects --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Active Projects
                            </div>

                            <h3 class="mb-0">
                                {{ number_format($activeProjects) }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-play-circle text-success fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Tests --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Tests
                            </div>

                            <h3 class="mb-0">
                                {{ number_format($summary['total_tests']) }}
                            </h3>

                            <small class="text-success">
                                {{ number_format($summary['passed_tests']) }} Passed
                            </small>

                        </div>

                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-clipboard-check text-info fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Certificates --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Approved Certificates
                            </div>

                            <h3 class="mb-0">
                                {{ number_format($summary['approved_certificates']) }}
                            </h3>

                            <small class="text-muted">
                                of {{ number_format($summary['total_certificates']) }} total
                            </small>

                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-patch-check text-warning fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMMISSIONING SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Scopes --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Commissioning Scopes
                        </span>

                        <i class="bi bi-diagram-3 text-primary"></i>

                    </div>

                    <div class="mt-2">

                        <strong class="fs-4">
                            {{ number_format($summary['total_scopes']) }}
                        </strong>

                        <span class="text-success ms-2">
                            {{ number_format($summary['completed_scopes']) }} Completed
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Test Plans --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Test Plans
                        </span>

                        <i class="bi bi-file-earmark-text text-info"></i>

                    </div>

                    <div class="mt-2">

                        <strong class="fs-4">
                            {{ number_format($summary['total_test_plans']) }}
                        </strong>

                        <span class="text-success ms-2">
                            {{ number_format($summary['approved_test_plans']) }} Approved
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Failed Tests --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Failed Tests
                        </span>

                        <i class="bi bi-exclamation-triangle text-danger"></i>

                    </div>

                    <div class="mt-2">

                        <strong class="fs-4
                            {{ $summary['failed_tests'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($summary['failed_tests']) }}
                        </strong>

                        <span class="text-muted ms-2">
                            Pending {{ number_format($summary['pending_tests']) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Certificates --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Certificates
                        </span>

                        <i class="bi bi-award text-success"></i>

                    </div>

                    <div class="mt-2">

                        <strong class="fs-4">
                            {{ number_format($summary['approved_certificates']) }}
                        </strong>

                        <span class="text-muted ms-2">
                            Approved
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PROJECT LIST
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white py-3">

            <h5 class="mb-1">
                Commissioning Projects
            </h5>

            <div class="text-muted small">
                Open a project to access its complete commissioning dashboard.
            </div>

        </div>


        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <div class="card-body border-bottom">

            <form
                method="GET"
                action="{{ route('admin.commissioning.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-5 col-md-6">

                        <label class="form-label">
                            Search Project
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Project no, project code, project name..."
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3 col-md-4">

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
                                    {{ $status === $projectStatus ? 'selected' : '' }}
                                >
                                    {{ $projectStatus }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-4 col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a
                                href="{{ route('admin.commissioning.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
            PROJECT TABLE
        ====================================================== --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-3">
                            Project No.
                        </th>

                        <th>
                            Project
                        </th>

                        <th>
                            Land
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Scopes
                        </th>

                        <th>
                            Test Plans
                        </th>

                        <th>
                            Tests
                        </th>

                        <th>
                            Certificates
                        </th>

                        <th class="text-end pe-3">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($projects as $project)

                        <tr>

                            {{-- =================================================
                                PROJECT NUMBER
                            ================================================== --}}
                            <td class="ps-3">

                                <div class="fw-semibold text-primary">

                                    {{ $project->project_number
                                        ?? $project->project_code
                                        ?? '-' }}

                                </div>

                            </td>


                            {{-- =================================================
                                PROJECT
                            ================================================== --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $project->project_name ?? '-' }}

                                </div>

                                @if(!empty($project->project_code))

                                    <small class="text-muted">

                                        {{ $project->project_code }}

                                    </small>

                                @endif

                            </td>


                            {{-- =================================================
                                LAND
                            ================================================== --}}
                            <td>

                                @if($project->land)

                                    <div class="fw-medium">

                                        {{ $project->land->land_name
                                            ?? $project->land->name
                                            ?? '-' }}

                                    </div>

                                    @if(!empty($project->land->land_code))

                                        <small class="text-muted">

                                            {{ $project->land->land_code }}

                                        </small>

                                    @elseif(!empty($project->land->land_number))

                                        <small class="text-muted">

                                            {{ $project->land->land_number }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td>

                                @php

                                    $projectStatus =
                                        $project->project_status ?? 'Active';

                                    $statusClass = match ($projectStatus) {

                                        'Active' =>
                                            'bg-success-subtle text-success',

                                        'In Progress' =>
                                            'bg-primary-subtle text-primary',

                                        'Construction' =>
                                            'bg-info-subtle text-info',

                                        'Completed' =>
                                            'bg-success-subtle text-success',

                                        'On Hold' =>
                                            'bg-warning-subtle text-warning',

                                        'Cancelled' =>
                                            'bg-danger-subtle text-danger',

                                        default =>
                                            'bg-secondary-subtle text-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{ $projectStatus }}

                                </span>

                            </td>


                            {{-- =================================================
                                SCOPES
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $project->commissioning_scopes_count ?? 0 }}

                                </span>

                            </td>


                            {{-- =================================================
                                TEST PLANS
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $project->commissioning_test_plans_count ?? 0 }}

                                </span>

                            </td>


                            {{-- =================================================
                                TESTS
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $project->commissioning_tests_count ?? 0 }}

                                </span>

                            </td>


                            {{-- =================================================
                                CERTIFICATES
                            ================================================== --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $project->commissioning_certificates_count ?? 0 }}

                                </span>

                            </td>


                            {{-- =================================================
                                ACTION
                            ================================================== --}}
                            <td class="text-end pe-3">

                                <a
                                    href="{{ route(
                                        'admin.projects.commissioning.index',
                                        ['project' => $project->id]
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
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="mb-2">

                                    <i class="bi bi-folder2-open fs-1 text-muted"></i>

                                </div>

                                <div class="fw-semibold">
                                    No commissioning projects found.
                                </div>

                                <div class="text-muted small">
                                    Try changing your search or status filter.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($projects->hasPages())

            <div class="card-footer bg-white">

                {{ $projects->links() }}

            </div>

        @endif

    </div>

</div>

@endsection