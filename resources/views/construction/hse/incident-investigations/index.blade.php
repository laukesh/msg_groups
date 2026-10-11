@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Investigations
            </h3>


            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- =================================================
                INCIDENT
            ================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Incident

            </a>


            {{-- =================================================
                NEW INVESTIGATION
                Only during investigation phase
            ================================================== --}}

            @if(
                in_array(
                    $incident->status,
                    [
                        'Reported',
                        'Under Investigation',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.investigations.create',
                        [
                            'project' => $project,
                            'incident' => $incident,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    New Investigation

                </a>

            @endif

        </div>

    </div>



    {{-- =========================================================
        SUCCESS
    ========================================================== --}}

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



    {{-- =========================================================
        ERROR
    ========================================================== --}}

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



    {{-- =========================================================
        INCIDENT STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Incident Status
                    </div>


                    <div class="fw-semibold">

                        {{ $incident->incident_number }}

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    @switch($incident->status)

                        @case('Reported')

                            <span class="badge bg-primary fs-6">
                                Reported
                            </span>

                            @break


                        @case('Under Investigation')

                            <span class="badge bg-warning text-dark fs-6">
                                Under Investigation
                            </span>

                            @break


                        @case('Investigation Completed')

                            <span class="badge bg-info text-dark fs-6">
                                Investigation Completed
                            </span>

                            @break


                        @case('Actions Assigned')

                            <span class="badge bg-secondary fs-6">
                                Actions Assigned
                            </span>

                            @break


                        @case('Actions Completed')

                            <span class="badge bg-primary fs-6">
                                Actions Completed
                            </span>

                            @break


                        @case('Verified')

                            <span class="badge bg-success fs-6">
                                Verified
                            </span>

                            @break


                        @case('Closed')

                            <span class="badge bg-dark fs-6">
                                Closed
                            </span>

                            @break


                        @default

                            <span class="badge bg-secondary fs-6">
                                {{ $incident->status }}
                            </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        INVESTIGATION REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Investigation Register
                    </strong>


                    <span class="badge bg-primary ms-2">

                        {{ $investigations->count() }}

                    </span>

                </div>


                {{-- =================================================
                    CREATE INVESTIGATION
                    ================================================== --}}

                @if(
                    in_array(
                        $incident->status,
                        [
                            'Reported',
                            'Under Investigation',
                        ],
                        true
                    )
                )

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.investigations.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        New Investigation

                    </a>

                @endif

            </div>

        </div>



        <div class="card-body p-0">

            @if($investigations->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>


                            <th>
                                Investigation
                            </th>


                            <th>
                                Date
                            </th>


                            <th>
                                Lead Investigator
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

                        @foreach(
                            $investigations as $investigation
                        )

                            <tr>

                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>



                                {{-- =================================================
                                    INVESTIGATION NUMBER
                                ================================================== --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.investigations.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'investigation' => $investigation,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $investigation->investigation_number }}

                                    </a>

                                </td>



                                {{-- =================================================
                                    DATE
                                ================================================== --}}

                                <td>

                                    {{ $investigation->investigation_date
                                        ? $investigation->investigation_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>



                                {{-- =================================================
                                    LEAD INVESTIGATOR
                                ================================================== --}}

                                <td>

                                    {{ $investigation->lead_investigator_name
                                        ?? $investigation->leadInvestigator?->name
                                        ?? '—'
                                    }}

                                </td>



                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @php

                                        $statusClass =
                                            match($investigation->status) {

                                                'Draft' =>
                                                    'bg-secondary',

                                                'Submitted' =>
                                                    'bg-warning text-dark',

                                                'Approved' =>
                                                    'bg-success',

                                                'Rejected' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $investigation->status }}

                                    </span>

                                </td>



                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.investigations.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'investigation' => $investigation,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="bi bi-eye me-1"></i>

                                        View

                                    </a>


                                    {{-- Edit only Draft / Rejected --}}

                                    @if(
                                        in_array(
                                            $investigation->status,
                                            [
                                                'Draft',
                                                'Rejected',
                                            ],
                                            true
                                        )
                                    )

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.incidents.investigations.edit',
                                                [
                                                    'project' => $project,
                                                    'incident' => $incident,
                                                    'investigation' => $investigation,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                        >

                                            <i class="bi bi-pencil me-1"></i>

                                            Edit

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <i
                        class="bi bi-search"
                        style="font-size: 40px;"
                    ></i>


                    <h6 class="mt-3">

                        No Investigation Found

                    </h6>


                    <p class="text-muted">

                        No investigation has been created
                        for this incident yet.

                    </p>


                    {{-- =================================================
                        CREATE FIRST INVESTIGATION
                    ================================================== --}}

                    @if(
                        in_array(
                            $incident->status,
                            [
                                'Reported',
                                'Under Investigation',
                            ],
                            true
                        )
                    )

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.investigations.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Create First Investigation

                        </a>

                    @else

                        <div class="text-muted">

                            This incident is no longer in the
                            investigation phase.

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection