@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">
                Design Management
            </div>

            <h4 class="fw-bold mb-1">
                Design Packages
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code)
                    <span class="mx-1">•</span>
                    {{ $project->project_code }}
                @endif
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @include('design-management.partials.dashboard-link')

            <a href="{{ route('admin.projects.design-management.packages.create', $project) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Design Package
            </a>
        </div>

    </div>


    @include('design-management.partials.alerts')


    {{-- =========================================================
        INTRO / MODULE DESCRIPTION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center mb-2">

                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary
                                    d-flex align-items-center justify-content-center me-3"
                             style="width:48px;height:48px;">

                            <i class="bi bi-boxes fs-4"></i>

                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Design Package Register
                            </h5>

                            <div class="text-muted small">
                                Manage design deliverables, disciplines, consultants,
                                submissions and approval workflow for this project.
                            </div>
                        </div>

                    </div>

                    <p class="text-muted mb-0 mt-3">
                        Design packages provide structured control over project design
                        deliverables from preparation and submission through review,
                        approval and revision.
                    </p>

                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">

                    <div class="border rounded-3 p-3 bg-light">

                        <div class="small text-muted mb-1">
                            Project Design Packages
                        </div>

                        <div class="d-flex align-items-end gap-2">
                            <span class="fs-3 fw-bold">
                                {{ $packages->count() }}
                            </span>

                            <span class="text-muted small mb-1">
                                packages
                            </span>
                        </div>

                        <div class="small text-muted mt-1">
                            Current filtered register
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small fw-semibold">
                                TOTAL PACKAGES
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $totalPackages }}
                            </div>

                            <div class="text-muted small mt-1">
                                Design register
                            </div>
                        </div>

                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary
                                    d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            <i class="bi bi-boxes"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small fw-semibold">
                                DRAFT
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $draftPackages }}
                            </div>

                            <div class="text-muted small mt-1">
                                In preparation
                            </div>
                        </div>

                        <div class="rounded-3 bg-secondary bg-opacity-10 text-secondary
                                    d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            <i class="bi bi-pencil-square"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Submitted --}}
        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small fw-semibold">
                                SUBMITTED
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $submittedPackages }}
                            </div>

                            <div class="text-muted small mt-1">
                                Awaiting review
                            </div>
                        </div>

                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning
                                    d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            <i class="bi bi-send"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small fw-semibold">
                                APPROVED
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $approvedPackages }}
                            </div>

                            <div class="text-muted small mt-1">
                                Approved packages
                            </div>
                        </div>

                        <div class="rounded-3 bg-success bg-opacity-10 text-success
                                    d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            <i class="bi bi-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small fw-semibold">
                                REJECTED
                            </div>

                            <div class="fs-3 fw-bold mt-1">
                                {{ $rejectedPackages }}
                            </div>

                            <div class="text-muted small mt-1">
                                Requires revision
                            </div>
                        </div>

                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger
                                    d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            <i class="bi bi-exclamation-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STATUS OVERVIEW
    ========================================================== --}}
    @if($totalPackages > 0)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h6 class="fw-bold mb-1">
                            Package Status Overview
                        </h6>

                        <div class="text-muted small">
                            Current workflow distribution
                        </div>
                    </div>

                    <span class="badge bg-light text-dark border">
                        {{ $totalPackages }} Packages
                    </span>

                </div>

                <div class="progress mb-3"
                     style="height:10px;">

                    @if($approvedPackages > 0)
                        <div class="progress-bar bg-success"
                             style="width: {{ ($approvedPackages / $totalPackages) * 100 }}%">
                        </div>
                    @endif

                    @if($submittedPackages > 0)
                        <div class="progress-bar bg-warning"
                             style="width: {{ ($submittedPackages / $totalPackages) * 100 }}%">
                        </div>
                    @endif

                    @if($draftPackages > 0)
                        <div class="progress-bar bg-secondary"
                             style="width: {{ ($draftPackages / $totalPackages) * 100 }}%">
                        </div>
                    @endif

                    @if($rejectedPackages > 0)
                        <div class="progress-bar bg-danger"
                             style="width: {{ ($rejectedPackages / $totalPackages) * 100 }}%">
                        </div>
                    @endif

                </div>

                <div class="d-flex flex-wrap gap-4 small">

                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-success"
                              style="width:9px;height:9px;"></span>
                        Approved
                        <strong>{{ $approvedPackages }}</strong>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-warning"
                              style="width:9px;height:9px;"></span>
                        Submitted
                        <strong>{{ $submittedPackages }}</strong>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-secondary"
                              style="width:9px;height:9px;"></span>
                        Draft
                        <strong>{{ $draftPackages }}</strong>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-danger"
                              style="width:9px;height:9px;"></span>
                        Rejected
                        <strong>{{ $rejectedPackages }}</strong>
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="fw-bold mb-1">
                        Filter Design Packages
                    </h6>

                    <div class="text-muted small">
                        Narrow the register by discipline or workflow status.
                    </div>
                </div>

                @if(!empty($filters['discipline_id']) || !empty($filters['status']))

                    <a href="{{ route('admin.projects.design-management.packages.index', $project) }}"
                       class="btn btn-sm btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Clear Filters

                    </a>

                @endif

            </div>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    {{-- Discipline --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold small">
                            Design Discipline
                        </label>

                        <select name="discipline_id"
                                class="form-select">

                            <option value="">
                                All Disciplines
                            </option>

                            @foreach($disciplines as $discipline)

                                <option value="{{ $discipline->id }}"
                                    @selected(($filters['discipline_id'] ?? '') == $discipline->id)>

                                    {{ $discipline->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold small">
                            Workflow Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach($statuses as $status)

                                <option value="{{ $status }}"
                                    @selected(($filters['status'] ?? '') === $status)>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-4">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-funnel me-1"></i>
                                Apply Filters

                            </button>

                            @if(!empty($filters['discipline_id']) || !empty($filters['status']))

                                <a href="{{ route('admin.projects.design-management.packages.index', $project) }}"
                                   class="btn btn-outline-secondary">

                                    Reset

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PACKAGE REGISTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>
                    <h6 class="fw-bold mb-1">
                        Design Package Register
                    </h6>

                    <div class="text-muted small">
                        {{ $packages->count() }}
                        {{ \Illuminate\Support\Str::plural('package', $packages->count()) }}
                        displayed
                    </div>
                </div>

                <a href="{{ route('admin.projects.design-management.packages.create', $project) }}"
                   class="btn btn-sm btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>
                    New Package

                </a>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-4">
                            Package
                        </th>

                        <th>
                            Discipline
                        </th>

                        <th>
                            Consultant
                        </th>

                        <th>
                            Version
                        </th>

                        <th>
                            Submission
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end pe-4">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($packages as $package)

                        <tr>

                            {{-- Package --}}
                            <td class="ps-4">

                                <div class="d-flex align-items-start">

                                    <div class="rounded-2 bg-primary bg-opacity-10 text-primary
                                                d-flex align-items-center justify-content-center me-3"
                                         style="width:38px;height:38px;">

                                        <i class="bi bi-box"></i>

                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $package->package_name }}
                                        </div>

                                        <div class="text-muted small">

                                            @if($package->package_code)
                                                {{ $package->package_code }}
                                            @else
                                                Package #{{ $package->id }}
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Discipline --}}
                            <td>

                                @if($package->discipline)

                                    <span class="badge bg-light text-dark border">
                                        {{ $package->discipline->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Consultant --}}
                            <td>

                                @if($package->responsibleConsultant)

                                    <div class="fw-medium">
                                        {{ $package->responsibleConsultant->company_name }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        Not assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Version --}}
                            <td>

                                <span class="badge bg-light text-dark border">
                                    v{{ $package->version ?? '—' }}
                                </span>

                            </td>


                            {{-- Submission --}}
                            <td>

                                @if($package->actual_submission_date)

                                    <div class="fw-medium">
                                        {{ \Carbon\Carbon::parse($package->actual_submission_date)->format('d M Y') }}
                                    </div>

                                    <div class="text-success small">
                                        Submitted
                                    </div>

                                @elseif($package->planned_submission_date)

                                    <div class="fw-medium">
                                        {{ \Carbon\Carbon::parse($package->planned_submission_date)->format('d M Y') }}
                                    </div>

                                    <div class="text-muted small">
                                        Planned
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @include(
                                    'design-management.partials.status-badge',
                                    ['status' => $package->status]
                                )

                            </td>


                            {{-- Actions --}}
                            <td class="text-end pe-4">

                                <div class="d-inline-flex gap-1">

                                    <a href="{{ route('admin.projects.design-management.packages.show', [$project, $package]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Package">

                                        <i class="bi bi-eye"></i>
                                        <span class="d-none d-xl-inline ms-1">
                                            View
                                        </span>

                                    </a>


                                    @if(in_array($package->status, ['Draft', 'Rejected']))
                                        <a href="{{ route('admin.projects.design-management.packages.edit', [$project, $package]) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Edit Package">

                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-xl-inline ms-1">
                                                Edit
                                            </span>

                                        </a>
                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="mb-3">

                                    <div class="rounded-circle bg-light d-inline-flex
                                                align-items-center justify-content-center"
                                         style="width:64px;height:64px;">

                                        <i class="bi bi-boxes fs-3 text-muted"></i>

                                    </div>

                                </div>

                                <h6 class="fw-bold">
                                    No Design Packages Found
                                </h6>

                                <p class="text-muted small mb-3">

                                    @if(!empty($filters['discipline_id']) || !empty($filters['status']))

                                        No packages match the selected filters.

                                    @else

                                        No design packages have been created
                                        for this project yet.

                                    @endif

                                </p>

                                @if(!empty($filters['discipline_id']) || !empty($filters['status']))

                                    <a href="{{ route('admin.projects.design-management.packages.index', $project) }}"
                                       class="btn btn-sm btn-outline-secondary">

                                        Clear Filters

                                    </a>

                                @else

                                    <a href="{{ route('admin.projects.design-management.packages.create', $project) }}"
                                       class="btn btn-sm btn-primary">

                                        <i class="bi bi-plus-lg me-1"></i>
                                        Create First Package

                                    </a>

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
        INFORMATION FOOTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-4">

                    <div class="d-flex">

                        <div class="text-primary me-3">
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>

                        <div>
                            <div class="fw-semibold mb-1">
                                Structured Deliverables
                            </div>

                            <div class="text-muted small">
                                Organize design outputs by discipline,
                                package and responsible consultant.
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="d-flex">

                        <div class="text-primary me-3">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>

                        <div>
                            <div class="fw-semibold mb-1">
                                Controlled Workflow
                            </div>

                            <div class="text-muted small">
                                Manage package submission, approval,
                                rejection and revision through a controlled workflow.
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="d-flex">

                        <div class="text-primary me-3">
                            <i class="bi bi-file-earmark-check fs-4"></i>
                        </div>

                        <div>
                            <div class="fw-semibold mb-1">
                                Design Coordination
                            </div>

                            <div class="text-muted small">
                                Maintain visibility of design submissions
                                and consultant responsibilities throughout the project.
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection