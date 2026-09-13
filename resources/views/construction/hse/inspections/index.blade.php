@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                HSE Inspections
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <div class="d-flex gap-2">


            <a
                href="{{ route(
                    'admin.projects.construction.hse.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to HSE
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Inspection

            </a>

        </div>

    </div>


    {{-- =========================================================
        MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Inspection Register
            </strong>


            <span class="badge bg-primary ms-2">
                {{ $inspections->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($inspections->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Inspection
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Inspector
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

                        @foreach($inspections as $inspection)

                            <tr>


                                {{-- # --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Inspection --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >

                                        {{ $inspection->inspection_number }}

                                    </a>


                                    @if($inspection->inspection_title)

                                        <div class="small text-muted">

                                            {{ $inspection->inspection_title }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Date --}}

                                <td>

                                    {{ $inspection->inspection_date
                                        ? $inspection->inspection_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                {{-- Type --}}

                                <td>
                                    {{ $inspection->inspection_type }}
                                </td>


                                {{-- Location --}}

                                <td>
                                    {{ $inspection->location ?? '—' }}
                                </td>


                                {{-- Inspector --}}

                                <td>

                                    {{ $inspection->inspector_name
                                        ?? $inspection->inspector?->name
                                        ?? '—'
                                    }}

                                </td>


                                {{-- Status --}}

                                <td>

                                    @php

                                        $statusClass =
                                            match($inspection->status) {

                                                'Planned' =>
                                                    'bg-secondary',

                                                'In Progress' =>
                                                    'bg-warning text-dark',

                                                'Completed' =>
                                                    'bg-primary',

                                                'Findings Raised' =>
                                                    'bg-danger',

                                                'Verified' =>
                                                    'bg-success',

                                                'Closed' =>
                                                    'bg-dark',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $inspection->status }}
                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
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

            @else

                <div class="text-center py-5">


                    <i
                        class="bi bi-clipboard-check"
                        style="font-size: 42px;"
                    ></i>


                    <h6 class="mt-3">
                        No Inspections Found
                    </h6>


                    <p class="text-muted">
                        No HSE inspections have been created
                        for this project yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Create First Inspection

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection