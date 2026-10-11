@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Management
            </div>

            <h4 class="mb-1">
                Procurement Plans
            </h4>

            <div class="text-muted">

                Project:

                <strong>
                    {{ $project->project_name }}
                </strong>

                @if($project->project_code)

                    <span>
                        ({{ $project->project_code }})
                    </span>

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Project Procurement --}}

            <a
                href="{{ url('/admin/projects/' . $project->id . '/procurement') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>


            {{-- Create Plan --}}

            <a
                href="{{ route(
                    'admin.projects.procurement.plans.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                New Procurement Plan
            </a>

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
         PROJECT CONTEXT
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Project Code
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_code ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Project Status
                    </div>

                    <div>
                        {{ $project->status ?? '—' }}
                    </div>

                </div>


                <div class="col-md-2 text-md-end">

                    <div class="text-muted small">
                        Total Plans
                    </div>

                    <div class="fw-semibold fs-5">
                        {{ $plans->total() }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FILTERS
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Filters
            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.procurement.plans.index',
                    $project
                ) }}"
            >

                <div class="row g-3">


                    {{-- Search --}}

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Plan number, plan title..."
                        >

                    </div>


                    {{-- Procurement Year --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Procurement Year
                        </label>

                        <input
                            type="number"
                            name="procurement_year"
                            class="form-control"
                            value="{{ request('procurement_year') }}"
                            min="2000"
                            max="2100"
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
                                'Under Review',
                                'Revision Required',
                                'Approved',
                                'Rejected',
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

                    <div class="col-md-1 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                    </div>


                    <div class="col-md-12">

                        <a
                            href="{{ route(
                                'admin.projects.procurement.plans.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            Reset Filters
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
         PROCUREMENT PLANS
    ============================================================ --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Procurement Plans
                </strong>

                <span class="badge bg-primary">
                    {{ $plans->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($plans->count())


                <div class="table-responsive">

                    <table class="table table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Plan Number
                                </th>

                                <th>
                                    Plan Title
                                </th>

                                <th>
                                    Year
                                </th>

                                <th>
                                    Estimated Value
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Prepared By
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($plans as $plan)

                                @php

                                    $statusClass = match(
                                        $plan->status
                                    ) {

                                        'Approved'
                                            => 'bg-success',

                                        'Rejected'
                                            => 'bg-danger',

                                        'Submitted'
                                            => 'bg-info',

                                        'Under Review'
                                            => 'bg-warning text-dark',

                                        'Revision Required'
                                            => 'bg-warning text-dark',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp


                                <tr>


                                    {{-- Number --}}

                                    <td>

                                        {{
                                            $plans->firstItem()
                                            + $loop->index
                                        }}

                                    </td>


                                    {{-- Plan Number --}}

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.plans.show',
                                                [
                                                    'project' =>
                                                        $project,

                                                    'procurementPlan' =>
                                                        $plan,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $plan->plan_number }}

                                        </a>

                                    </td>


                                    {{-- Title --}}

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $plan->plan_title }}
                                        </div>

                                    </td>


                                    {{-- Year --}}

                                    <td>

                                        {{ $plan->procurement_year }}

                                    </td>


                                    {{-- Estimated Value --}}

                                    <td>

                                        @if(
                                            $plan->total_estimated_value
                                            !== null
                                        )

                                            {{ $plan->currency }}

                                            {{
                                                number_format(
                                                    (float)
                                                    $plan->total_estimated_value,
                                                    2
                                                )
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $plan->status }}
                                        </span>

                                    </td>


                                    {{-- Prepared By --}}

                                    <td>

                                        {{
                                            $plan->preparedBy->name
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <div
                                            class="d-inline-flex gap-1"
                                        >


                                            {{-- View --}}

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.plans.show',
                                                    [
                                                        'project' =>
                                                            $project,

                                                        'procurementPlan' =>
                                                            $plan,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            {{-- Edit --}}

                                            @if(
                                                $plan->status !== 'Approved'
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.procurement.plans.edit',
                                                        [
                                                            'project' =>
                                                                $project,

                                                            'procurementPlan' =>
                                                                $plan,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>

                                            @endif


                                            {{-- Delete --}}

                                            @if(
                                                $plan->status === 'Draft'
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.procurement.plans.destroy',
                                                        [
                                                            'project' =>
                                                                $project,

                                                            'procurementPlan' =>
                                                                $plan,
                                                        ]
                                                    ) }}"
                                                    class="d-inline"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm(
                                                            'Delete this Procurement Plan?'
                                                        )"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            @endif


                                        </div>

                                    </td>


                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else


                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No Procurement Plans found
                        for this project.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.procurement.plans.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="ri-add-line me-1"></i>
                        Create Procurement Plan
                    </a>

                </div>


            @endif

        </div>


        @if($plans->hasPages())

            <div class="card-footer">

                {{ $plans->links() }}

            </div>

        @endif

    </div>

</div>

@endsection