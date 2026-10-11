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
                {{ $procurementPlan->plan_title }}
            </h4>


            <div class="text-muted">

                {{ $procurementPlan->plan_number }}

                <span class="mx-1">
                    |
                </span>

                Project:

                <strong>
                    {{ $project->project_name }}
                </strong>

                @if($project->project_code)

                    ({{ $project->project_code }})

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">


            {{-- Procurement Plans --}}

            <a
                href="{{ route(
                    'admin.projects.procurement.plans.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Plans
            </a>


            {{-- Edit --}}

            @if(
                $procurementPlan->status !== 'Approved'
            )

                <a
                    href="{{ route(
                        'admin.projects.procurement.plans.edit',
                        [
                            'project' =>
                                $project,

                            'procurementPlan' =>
                                $procurementPlan,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endif


        </div>

    </div>


    {{-- ============================================================
         MESSAGES
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

        <div class="card-header">

            <strong>
                Project
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


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

                    <div>
                        {{ $project->project_code ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Plan Number
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementPlan->plan_number }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Status
                    </div>


                    @php

                        $statusClass = match(
                            $procurementPlan->status
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


                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $procurementPlan->status }}
                    </span>

                </div>


            </div>

        </div>

    </div>


    {{-- ============================================================
         PLAN INFORMATION
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Procurement Plan Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Plan Number --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Plan Number
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementPlan->plan_number }}
                    </div>

                </div>


                {{-- Plan Title --}}

                <div class="col-md-5">

                    <div class="text-muted small">
                        Plan Title
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementPlan->plan_title }}
                    </div>

                </div>


                {{-- Year --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Procurement Year
                    </div>

                    <div>
                        {{ $procurementPlan->procurement_year ?: '—' }}
                    </div>

                </div>


                {{-- Currency --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div>
                        {{ $procurementPlan->currency ?? '—' }}
                    </div>

                </div>


                {{-- Start Date --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Planned Start Date
                    </div>

                    <div>

                        {{
                            $procurementPlan->planned_start_date
                                ? $procurementPlan->planned_start_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Completion Date --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Planned Completion Date
                    </div>

                    <div>

                        {{
                            $procurementPlan->planned_completion_date
                                ? $procurementPlan->planned_completion_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Strategy --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Procurement Strategy
                    </div>

                    <div>

                        @if($procurementPlan->procurementStrategy)

                            {{
                                $procurementPlan
                                    ->procurementStrategy
                                    ->title
                                ??
                                $procurementPlan
                                    ->procurementStrategy
                                    ->strategy_title
                                ??
                                $procurementPlan
                                    ->procurementStrategy
                                    ->name
                                ??
                                'Strategy #' .
                                $procurementPlan
                                    ->procurementStrategy
                                    ->id
                            }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Estimated Value --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Total Estimated Value
                    </div>

                    <div class="fw-semibold fs-5">

                        {{ $procurementPlan->currency }}

                        {{
                            number_format(
                                (float)
                                $procurementPlan->total_estimated_value,
                                2
                            )
                        }}

                    </div>

                </div>


                {{-- Description --}}

                <div class="col-md-12">

                    <div class="text-muted small">
                        Description
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPlan->description
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Objective --}}

                <div class="col-md-12">

                    <div class="text-muted small">
                        Procurement Objective
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPlan->procurement_objective
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Remarks --}}

                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPlan->remarks
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- ============================================================
         PROCUREMENT PACKAGES
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Procurement Packages
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $procurementPlan->packages->count() }}
                </span>

            </div>


            {{-- Add Package --}}

            <a
                href="{{ route('admin.projects.procurement.packages.create', [
    'project' => $project,
    'procurementPlan' => $procurementPlan,
]) }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Package
            </a>

        </div>


        <div class="card-body">


            @if(
                $procurementPlan->packages->count() === 0
            )


                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No procurement packages have been
                        created for this plan yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.procurement.packages.create',
                            [
                                'procurement_plan_id' =>
                                    $procurementPlan->id,

                                'project_id' =>
                                    $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="ri-add-line me-1"></i>

                        Add Procurement Package

                    </a>

                </div>


            @else


                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    Package Number
                                </th>

                                <th>
                                    Package Title
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="100">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $procurementPlan->packages
                                as $package
                            )

                                <tr>

                                    <td>
                                        {{ $package->package_number ?? '—' }}
                                    </td>


                                    <td>
                                        {{ $package->package_title ?? '—' }}
                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">

                                            {{
                                                $package->status
                                                ?? '—'
                                            }}

                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.packages.show',
                                                [
                                                    'project' => $project,
                                                    'procurementPackage' => $package,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @endif

        </div>

    </div>


    {{-- ============================================================
         TENDERS
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Tenders
                </strong>

                <span class="badge bg-success ms-2">

                    {{
                        $procurementPlan->tenders
                            ? $procurementPlan->tenders->count()
                            : 0
                    }}

                </span>

            </div>

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-success"
            >
                <i class="ri-auction-line me-1"></i>
                Manage Tenders
            </a>

        </div>


        <div class="card-body">

            @if(
                !$procurementPlan->tenders
                ||
                $procurementPlan->tenders->count() === 0
            )

                <div class="text-center text-muted py-3">
                    No tenders created for this Procurement Plan.
                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Tender
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $procurementPlan->tenders
                                as $tender
                            )

                                <tr>

                                    <td>
                                        {{
                                            $tender->tender_number
                                            ?? $tender->id
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            $tender->tender_title
                                            ?? '—'
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            $tender->status
                                            ?? '—'
                                        }}
                                    </td>

                                    <td>

                                        @if(
                                            Route::has(
                                                'admin.procurement.tenders.show'
                                            )
                                        )

                                           <a
                                                href="{{ route(
                                                    'admin.projects.procurement.tenders.show',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $tender,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>


</div>

@endsection