@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1 fw-semibold">
                Health, Safety & Environment
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Construction Dashboard

            </a>

        </div>

    </div>


    {{-- =========================================================
        HSE INTRODUCTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4 overflow-hidden">

        <div class="card-body p-4 p-lg-5">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center mb-3">

                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="
                                width:58px;
                                height:58px;
                                background:#e8f5e9;
                             ">

                            <i class="bi bi-shield-check text-success"
                               style="font-size:30px;"></i>

                        </div>

                        <div>

                            <div class="text-uppercase text-success fw-semibold small">
                                Construction HSE Management
                            </div>

                            <h4 class="mb-0 fw-semibold">
                                Safe Project. Safe People. Safe Environment.
                            </h4>

                        </div>

                    </div>


                    <p class="text-muted mb-3" style="max-width:850px;">

                        The Health, Safety & Environment module provides a
                        centralized platform for managing safety activities
                        throughout the construction lifecycle. It helps project
                        teams identify hazards, record observations, investigate
                        incidents, conduct inspections, communicate safety
                        requirements and monitor environmental compliance.

                    </p>


                    <p class="text-muted mb-0" style="max-width:850px;">

                        HSE teams can maintain structured records of site
                        observations, incidents, inspections, toolbox talks,
                        safety meetings and environmental activities while
                        ensuring that corrective and preventive actions are
                        properly tracked through closure.

                    </p>

                </div>


                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="rounded-3 p-4"
                         style="background:#f8f9fa;">

                        <div class="d-flex align-items-start mb-3">

                            <i class="bi bi-bullseye text-primary fs-4 me-3"></i>

                            <div>

                                <div class="fw-semibold">
                                    HSE Objective
                                </div>

                                <div class="small text-muted mt-1">
                                    Prevent incidents, protect people,
                                    maintain compliance and minimize
                                    environmental impact.
                                </div>

                            </div>

                        </div>


                        <div class="d-flex align-items-start mb-3">

                            <i class="bi bi-exclamation-triangle text-warning fs-4 me-3"></i>

                            <div>

                                <div class="fw-semibold">
                                    Risk Management
                                </div>

                                <div class="small text-muted mt-1">
                                    Identify hazards and ensure corrective
                                    actions are followed through.
                                </div>

                            </div>

                        </div>


                        <div class="d-flex align-items-start">

                            <i class="bi bi-check-circle text-success fs-4 me-3"></i>

                            <div>

                                <div class="fw-semibold">
                                    Compliance
                                </div>

                                <div class="small text-muted mt-1">
                                    Maintain safety, inspection and
                                    environmental records.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        HSE PRINCIPLES
    ========================================================== --}}

    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-end mb-3">

            <div>

                <h5 class="mb-1 fw-semibold">
                    HSE Management Areas
                </h5>

                <div class="text-muted small">
                    Manage key health, safety and environmental activities
                    from one place.
                </div>

            </div>

        </div>


        <div class="row g-3">


            {{-- OBSERVATIONS --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#eef4ff;
                                 ">

                                <i class="bi bi-eye text-primary fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    Safety Observations
                                </h6>

                                <p class="text-muted small mb-3">
                                    Record unsafe acts, unsafe conditions,
                                    positive observations and corrective
                                    actions identified at site.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.observations.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- INCIDENTS --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#fff0f0;
                                 ">

                                <i class="bi bi-exclamation-triangle text-danger fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    Incidents
                                </h6>

                                <p class="text-muted small mb-3">
                                    Report, investigate and manage incidents,
                                    near misses and their corrective actions.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.incidents.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-danger">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- INSPECTIONS --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#f3efff;
                                 ">

                                <i class="bi bi-clipboard-check text-primary fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    HSE Inspections
                                </h6>

                                <p class="text-muted small mb-3">
                                    Conduct site safety inspections,
                                    document findings and track actions
                                    through closure.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.inspections.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TOOLBOX TALKS --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#fff8e6;
                                 ">

                                <i class="bi bi-people text-warning fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    Toolbox Talks
                                </h6>

                                <p class="text-muted small mb-3">
                                    Record daily safety briefings,
                                    toolbox talks, topics discussed and
                                    worker participation.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.toolbox-talks.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-warning">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SAFETY MEETINGS --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#eefaf7;
                                 ">

                                <i class="bi bi-calendar-check text-success fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    Safety Meetings
                                </h6>

                                <p class="text-muted small mb-3">
                                    Manage HSE meetings, participants,
                                    minutes, decisions and assigned
                                    safety actions.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.safety-meetings.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-success">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ENVIRONMENTAL --}}

            <div class="col-xl-4 col-lg-6">

                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-start">

                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                 style="
                                    width:48px;
                                    height:48px;
                                    background:#eaf7ee;
                                 ">

                                <i class="bi bi-tree text-success fs-4"></i>

                            </div>


                            <div class="flex-grow-1">

                                <h6 class="mb-1 fw-semibold">
                                    Environmental Management
                                </h6>

                                <p class="text-muted small mb-3">
                                    Maintain environmental monitoring,
                                    compliance records and site
                                    environmental activities.
                                </p>

                                <a href="{{ route(
                                    'admin.projects.construction.hse.environmental.records.index',
                                    ['project' => $project]
                                ) }}"
                                   class="btn btn-sm btn-outline-success">

                                    Open Module
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        HSE COMMITMENT
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-shield-fill-check text-success fs-2 me-3"></i>

                        <div>

                            <h5 class="mb-1 fw-semibold">
                                HSE Commitment
                            </h5>

                            <p class="text-muted mb-0">

                                Safety is an integral part of project execution.
                                Every observation, incident, inspection,
                                briefing and environmental record contributes
                                to creating a safer and more controlled
                                construction environment.

                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4 mt-3 mt-lg-0">

                    <div class="d-flex justify-content-lg-end gap-4">

                        <div class="text-center">

                            <div class="fw-semibold">
                                Prevention
                            </div>

                            <small class="text-muted">
                                Identify Risks
                            </small>

                        </div>


                        <div class="text-center">

                            <div class="fw-semibold">
                                Action
                            </div>

                            <small class="text-muted">
                                Correct Findings
                            </small>

                        </div>


                        <div class="text-center">

                            <div class="fw-semibold">
                                Closure
                            </div>

                            <small class="text-muted">
                                Verify Actions
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection