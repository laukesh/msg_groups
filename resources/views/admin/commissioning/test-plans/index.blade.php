@extends('layouts.app')

@section('title', 'Commissioning Test Plans')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-clipboard-check me-2"></i>
                Commissioning Test Plans
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                @if(!empty($project->project_code) && !empty($project->project_name))
                    -
                @endif
                {{ $project->project_name ?? '' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.commissioning.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            <a
                href="{{ route(
                    'admin.projects.commissioning.test-plans.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                New Test Plan
            </a>

        </div>

    </div>


    {{-- =========================================================
         FLASH SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Test Plans
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $totalPlans }}
                            </div>

                        </div>

                        <div class="text-primary fs-3">
                            <i class="bi bi-clipboard-data"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Draft
                            </div>

                            <div class="fs-3 fw-bold text-secondary">
                                {{ $draftPlans }}
                            </div>

                        </div>

                        <div class="text-secondary fs-3">
                            <i class="bi bi-file-earmark"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Submitted --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Pending Approval
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $submittedPlans }}
                            </div>

                        </div>

                        <div class="text-warning fs-3">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Approved
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                {{ $approvedPlans }}
                            </div>

                        </div>

                        <div class="text-success fs-3">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Completed
                            </div>

                            <div class="fs-3 fw-bold text-primary">
                                {{ $completedPlans }}
                            </div>

                        </div>

                        <div class="text-primary fs-3">
                            <i class="bi bi-check2-square"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SECONDARY KPI CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- In Progress --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                In Progress
                            </div>

                            <div class="fs-4 fw-bold text-info">
                                {{ $inProgressPlans }}
                            </div>

                        </div>

                        <i class="bi bi-play-circle fs-3 text-info"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Rejected
                            </div>

                            <div class="fs-4 fw-bold text-danger">
                                {{ $rejectedPlans }}
                            </div>

                        </div>

                        <i class="bi bi-x-circle fs-3 text-danger"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- On Hold --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                On Hold
                            </div>

                            <div class="fs-4 fw-bold text-warning">
                                {{ $onHoldPlans }}
                            </div>

                        </div>

                        <i class="bi bi-pause-circle fs-3 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approval Queue --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Approval Queue
                            </div>

                            <div class="fs-4 fw-bold text-warning">
                                {{ $submittedPlans }}
                            </div>

                        </div>

                        <i class="bi bi-person-check fs-3 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-funnel me-1"></i>
                    Filter Test Plans
                </strong>

                @if(
                    request()->filled('search') ||
                    request()->filled('status')
                )

                    <span class="badge bg-primary">
                        Filters Applied
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.commissioning.test-plans.index',
                    $project
                ) }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Test Plan No, title, discipline, test type, scope..."
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'In Progress',
                                'Completed',
                                'Rejected',
                                'On Hold'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route(
                                'admin.projects.commissioning.test-plans.index',
                                $project
                            ) }}"
                            class="btn btn-light"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         TEST PLAN REGISTER
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Test Plan Register
                    </strong>

                    <div class="text-muted small">
                        Commissioning testing plans for this project
                    </div>

                </div>

                <span class="text-muted small">
                    {{ $plans->total() }} records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Test Plan
                            </th>

                            <th>
                                Scope
                            </th>

                            <th>
                                Discipline
                            </th>

                            <th>
                                Test Type
                            </th>

                            <th>
                                Responsible
                            </th>

                            <th>
                                Planned Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th
                                class="text-end"
                                width="150"
                            >
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($plans as $plan)

                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $plans->firstItem() + $loop->index }}
                            </td>


                            {{-- Test Plan --}}
                            <td>

                                <div class="fw-semibold">

                                    <a
                                        href="{{ route(
                                            'admin.projects.commissioning.test-plans.show',
                                            [$project, $plan]
                                        ) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $plan->test_plan_no }}
                                    </a>

                                </div>

                                <div class="text-muted small">

                                    {{ \Illuminate\Support\Str::limit(
                                        $plan->title,
                                        55
                                    ) }}

                                </div>

                            </td>


                            {{-- Scope --}}
                            <td>

                                @if($plan->scope)

                                    <div class="fw-semibold">

                                        {{ $plan->scope->scope_code }}

                                    </div>

                                    <div class="text-muted small">

                                        {{ \Illuminate\Support\Str::limit(
                                            $plan->scope->scope_name,
                                            40
                                        ) }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Discipline --}}
                            <td>

                                {{ $plan->discipline ?: '-' }}

                            </td>


                            {{-- Test Type --}}
                            <td>

                                {{ $plan->test_type ?: '-' }}

                            </td>


                            {{-- Responsible --}}
                            <td>

                                @if($plan->responsibleUser)

                                    <div class="fw-semibold">

                                        {{ $plan->responsibleUser->name }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Planned Date --}}
                            <td>

                                @if($plan->planned_start_date)

                                    <div>

                                        {{ $plan->planned_start_date->format(
                                            'd M Y'
                                        ) }}

                                    </div>

                                    @if($plan->planned_completion_date)

                                        <small class="text-muted">

                                            to
                                            {{ $plan->planned_completion_date->format(
                                                'd M Y'
                                            ) }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @php

                                    $statusClass = match($plan->status) {

                                        'Draft'
                                            => 'bg-secondary',

                                        'Submitted'
                                            => 'bg-warning text-dark',

                                        'Approved'
                                            => 'bg-success',

                                        'In Progress'
                                            => 'bg-info text-dark',

                                        'Completed'
                                            => 'bg-primary',

                                        'Rejected'
                                            => 'bg-danger',

                                        'On Hold'
                                            => 'bg-dark',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{ $plan->status }}

                                </span>

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex justify-content-end gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.projects.commissioning.test-plans.show',
                                            [$project, $plan]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    @if(
                                        in_array(
                                            $plan->status,
                                            [
                                                'Draft',
                                                'Rejected',
                                                'On Hold'
                                            ],
                                            true
                                        )
                                    )

                                        <a
                                            href="{{ route(
                                                'admin.projects.commissioning.test-plans.edit',
                                                [$project, $plan]
                                            ) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                    @endif


                                    {{-- Delete --}}
                                    @if(
                                        in_array(
                                            $plan->status,
                                            [
                                                'Draft',
                                                'Rejected'
                                            ],
                                            true
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.commissioning.test-plans.destroy',
                                                [$project, $plan]
                                            ) }}"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this Test Plan?'
                                            );"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-clipboard-x fs-1 d-block mb-3"
                                    ></i>

                                    <h6>
                                        No Test Plans Found
                                    </h6>

                                    <p class="mb-3">
                                        No commissioning test plans match
                                        your current filters.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'admin.projects.commissioning.test-plans.create',
                                            $project
                                        ) }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Create Test Plan
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($plans->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        Showing
                        {{ $plans->firstItem() }}
                        to
                        {{ $plans->lastItem() }}
                        of
                        {{ $plans->total() }}
                        records

                    </div>

                    <div>
                        {{ $plans->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection