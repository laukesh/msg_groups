@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                HSE
            </h3>

            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? $project->name ?? 'Project' }}

            </div>

        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>
        </div>

    </div>


    {{-- =========================================================
        HSE MODULES
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
            OBSERVATIONS
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-start">

                        <div class="me-3">

                            <i
                                class="bi bi-eye"
                                style="font-size: 30px;"
                            ></i>

                        </div>


                        <div class="flex-grow-1">

                            <h5 class="mb-1">
                                Observations
                            </h5>

                            <p class="text-muted mb-3">
                                Record and manage site safety observations
                                and corrective actions.
                            </p>


                            <a
                                href="{{ route(
                                    'admin.projects.construction.hse.observations.index',
                                    [
                                        'project' => $project,
                                    ]
                                ) }}"
                                class="btn btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            INCIDENTS
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-start">

                        <div class="me-3">

                            <i
                                class="bi bi-exclamation-triangle"
                                style="font-size: 30px;"
                            ></i>

                        </div>


                        <div class="flex-grow-1">

                            <h5 class="mb-1">
                                Incidents
                            </h5>

                            <p class="text-muted mb-3">
                                Report, investigate and close
                                construction HSE incidents.
                            </p>


                            <a
                                href="{{ route(
                                    'admin.projects.construction.hse.incidents.index',
                                    [
                                        'project' => $project,
                                    ]
                                ) }}"
                                class="btn btn-outline-primary"
                            >
                                Open Module
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            INSPECTIONS
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <h5 class="mb-1">
                        Inspections
                    </h5>

                    <p class="text-muted mb-3">
                        Manage construction safety inspections
                        and inspection findings.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.index',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        Open Module
                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TOOLBOX TALKS
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <h5 class="mb-1">
                        Toolbox Talks
                    </h5>

                    <p class="text-muted mb-3">
                        Record toolbox talks, safety briefings
                        and worker participation.
                    </p>

                     <a
                        href="{{ route(
                            'admin.projects.construction.hse.toolbox-talks.index',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        Open Module
                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SAFETY MEETINGS
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <h5 class="mb-1">
                        Safety Meetings
                    </h5>

                    <p class="text-muted mb-3">
                        Manage HSE meetings, participants,
                        minutes and actions.
                    </p>

                    <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Open Module
            </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ENVIRONMENTAL
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <h5 class="mb-1">
                        Environmental
                    </h5>

                    <p class="text-muted mb-3">
                        Environmental monitoring,
                        compliance and records.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.environmental.records.index',
                            ['project' => $project]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        Open Module
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection