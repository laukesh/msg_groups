@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Inspections
            </h4>

            <div class="text-muted">
                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.construction.inspections.create',
                $project
            ) }}"
            class="btn btn-primary"
        >
            + New Inspection
        </a>

    </div>


    {{-- Success Message --}}
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


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Inspections
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Planned
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['planned'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Scheduled
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['scheduled'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Passed
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $summary['passed'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Failed
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $summary['failed'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Closed
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['closed'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Inspection List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Inspection Register
                </h5>

                <span class="text-muted small">
                    {{ $inspections->count() }} record(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($inspections->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Inspection No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Consultant
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Result
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($inspections as $inspection)

                                @php

                                    $statusClass = match(
                                        $inspection->status
                                    ) {

                                        'Planned' =>
                                            'bg-secondary',

                                        'Scheduled' =>
                                            'bg-primary',

                                        'Conducted' =>
                                            'bg-warning text-dark',

                                        'Closed' =>
                                            'bg-success',

                                        default =>
                                            'bg-light text-dark',

                                    };


                                    $resultClass = match(
                                        $inspection->result
                                    ) {

                                        'Passed' =>
                                            'bg-success',

                                        'Failed' =>
                                            'bg-danger',

                                        'Conditional' =>
                                            'bg-warning text-dark',

                                        default =>
                                            'bg-light text-dark',

                                    };

                                @endphp


                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.inspections.show',
                                                [
                                                    'project' =>
                                                        $project,

                                                    'inspection' =>
                                                        $inspection,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $inspection->inspection_number }}
                                        </a>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ optional(
                                            $inspection->inspection_date
                                        )->format('d M Y') ?? '—' }}

                                    </td>


                                    {{-- Title --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $inspection->title }}

                                        </div>

                                        @if($inspection->inspection_type)

                                            <div class="small text-muted">

                                                {{ $inspection->inspection_type }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Contractor --}}
                                    <td>

                                        @if(
                                            $inspection->contract?->bidder
                                        )

                                            <div class="fw-semibold">

                                                {{
                                                    $inspection
                                                        ->contract
                                                        ->bidder
                                                        ->company_name
                                                }}

                                            </div>

                                            <div class="small text-muted">

                                                {{
                                                    $inspection
                                                        ->contract
                                                        ->contract_number
                                                }}

                                            </div>

                                        @elseif(
                                            $inspection
                                                ->contract
                                                ?->bidder_name
                                        )

                                            {{
                                                $inspection
                                                    ->contract
                                                    ->bidder_name
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Consultant --}}
                                    <td>

                                        @if($inspection->consultant)

                                            <div class="fw-semibold">

                                                {{
                                                    $inspection
                                                        ->consultant
                                                        ->company_name
                                                }}

                                            </div>

                                            @if(
                                                $inspection
                                                    ->consultant
                                                    ->consultant_name
                                            )

                                                <div class="small text-muted">

                                                    {{
                                                        $inspection
                                                            ->consultant
                                                            ->consultant_name
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Location --}}
                                    <td>

                                        {{ $inspection->location ?? '—' }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $inspection->status }}
                                        </span>

                                    </td>


                                    {{-- Result --}}
                                    <td>

                                        @if($inspection->result)

                                            <span
                                                class="badge {{ $resultClass }}"
                                            >
                                                {{ $inspection->result }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end">

                                        <div class="dropdown">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown"
                                            >
                                                Actions
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.projects.construction.inspections.show',
                                                            [
                                                                'project' =>
                                                                    $project,

                                                                'inspection' =>
                                                                    $inspection,
                                                            ]
                                                        ) }}"
                                                    >
                                                        View
                                                    </a>

                                                </li>


                                                @if(
                                                    in_array(
                                                        $inspection->status,
                                                        [
                                                            'Planned',
                                                            'Scheduled',
                                                        ],
                                                        true
                                                    )
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route(
                                                                'admin.projects.construction.inspections.edit',
                                                                [
                                                                    'project' =>
                                                                        $project,

                                                                    'inspection' =>
                                                                        $inspection,
                                                                ]
                                                            ) }}"
                                                        >
                                                            Edit
                                                        </a>

                                                    </li>

                                                @endif


                                                @if(
                                                    $inspection->status ===
                                                    'Planned'
                                                )

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.construction.inspections.destroy',
                                                                [
                                                                    'project' =>
                                                                        $project,

                                                                    'inspection' =>
                                                                        $inspection,
                                                                ]
                                                            ) }}"
                                                            onsubmit="return confirm(
                                                                'Delete this inspection?'
                                                            );"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >
                                                                Delete
                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="fs-1 text-muted mb-3">
                        📋
                    </div>

                    <h5>
                        No Inspections Found
                    </h5>

                    <p class="text-muted mb-3">
                        No inspections have been created for this project yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.inspections.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Create First Inspection
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection