@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                HSE Incident Details
            </h3>

            <p class="text-muted mb-0">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </p>

        </div>


        <div class="d-flex gap-2">

            {{-- Edit --}}
            @if($incident->status !== 'Closed')

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.edit',
                        [
                            'project' => $project,
                            'incident' => $incident
                        ]
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="bi bi-pencil me-1"></i>

                    Edit

                </a>

            @endif


            {{-- Back --}}
            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.index',
                    $project
                ) }}"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Incidents

            </a>

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
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

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
        INCIDENT HEADER
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Incident Number
                    </div>

                    <h3 class="mb-1">
                        {{ $incident->incident_number }}
                    </h3>

                    <div class="text-muted">

                        {{ $incident->incident_type }}

                        @if($incident->incident_date)

                            |

                            {{ $incident->incident_date->format('d-m-Y') }}

                        @endif

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    {{-- Severity --}}

                    <div class="mb-2">

                        @switch($incident->severity)

                            @case('Critical')

                                <span class="badge bg-danger fs-6">
                                    Critical
                                </span>

                                @break

                            @case('High')

                                <span class="badge bg-warning text-dark fs-6">
                                    High
                                </span>

                                @break

                            @case('Medium')

                                <span class="badge bg-info text-dark fs-6">
                                    Medium
                                </span>

                                @break

                            @default

                                <span class="badge bg-success fs-6">
                                    Low
                                </span>

                        @endswitch

                    </div>


                    {{-- Status --}}

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
        WORKFLOW ACTIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Incident Workflow
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- =================================================
                    REPORTED
                ================================================== --}}

                @if($incident->status === 'Reported')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.start',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                            onclick="return confirm(
                                'Start investigation for this incident?'
                            )"
                        >

                            <i class="bi bi-search me-1"></i>

                            Start Investigation

                        </button>

                    </form>

                @endif



                {{-- =================================================
                    UNDER INVESTIGATION
                ================================================== --}}

                @if($incident->status === 'Under Investigation')

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.investigations.index',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-search me-1"></i>

                        Manage Investigation

                    </a>

                @endif



                {{-- =================================================
                    INVESTIGATION COMPLETED
                ================================================== --}}

                @if($incident->status === 'Investigation Completed')

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.actions.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Action

                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.actions.index',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="bi bi-list-check me-1"></i>

                        Manage Actions

                    </a>

                @endif



                {{-- =================================================
                    ACTIONS ASSIGNED
                ================================================== --}}

                @if($incident->status === 'Actions Assigned')

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.actions.index',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-list-check me-1"></i>

                        Manage Actions

                    </a>

                @endif



                {{-- =================================================
                    ACTIONS COMPLETED
                ================================================== --}}

                @if($incident->status === 'Actions Completed')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.verify',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Verify this incident?'
                            )"
                        >

                            <i class="bi bi-check-circle me-1"></i>

                            Verify Incident

                        </button>

                    </form>

                @endif



                {{-- =================================================
                    VERIFIED
                ================================================== --}}

                @if($incident->status === 'Verified')

                    <button
                        type="button"
                        class="btn btn-dark"
                        data-bs-toggle="modal"
                        data-bs-target="#closeIncidentModal"
                    >

                        <i class="bi bi-lock me-1"></i>

                        Close Incident

                    </button>

                @endif



                {{-- =================================================
                    CLOSED
                ================================================== --}}

                @if($incident->status === 'Closed')

                    <span class="badge bg-dark fs-6 p-2">

                        <i class="bi bi-check2-all me-1"></i>

                        Incident Closed

                    </span>

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
        BASIC INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Incident Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                {{-- Date --}}

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Incident Date
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->incident_date
                            ? $incident->incident_date->format('d-m-Y')
                            : '—'
                        }}

                    </div>

                </div>


                {{-- Time --}}

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Incident Time
                    </small>

                    <div class="fw-semibold">

                        @if($incident->incident_time)

                            {{ \Carbon\Carbon::parse(
                                $incident->incident_time
                            )->format('h:i A') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Location --}}

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Location
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->location ?? '—' }}

                    </div>

                </div>


                {{-- Type --}}

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Incident Type
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->incident_type ?? '—' }}

                    </div>

                </div>


                {{-- Contractor --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Contractor
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->contract?->bidder_name ?? '—' }}

                    </div>

                </div>


                {{-- Reported By --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Reported By
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->reported_by_name
                            ?? $incident->reporter?->name
                            ?? '—'
                        }}

                    </div>

                </div>


                {{-- Created --}}

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Reported On
                    </small>

                    <div class="fw-semibold">

                        {{ $incident->created_at
                            ? $incident->created_at->format('d-m-Y h:i A')
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        DESCRIPTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Incident Description
            </strong>

        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $incident->description ?? '—'
                )
            ) !!}

        </div>

    </div>



    {{-- =========================================================
        IMMEDIATE ACTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Immediate Action Taken
            </strong>

        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $incident->immediate_action ?? '—'
                )
            ) !!}

        </div>

    </div>



    {{-- =========================================================
        INJURY INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Injury Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Injury Occurred
                    </small>

                    <div>

                        @if($incident->injury_occurred)

                            <span class="badge bg-danger">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-8">

                    <small class="text-muted">
                        Injury Details
                    </small>

                    <div>

                        {!! nl2br(
                            e(
                                $incident->injury_details ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PROPERTY DAMAGE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Property / Equipment Damage
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Damage Occurred
                    </small>

                    <div>

                        @if($incident->property_damage)

                            <span class="badge bg-danger">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-8">

                    <small class="text-muted">
                        Damage Details
                    </small>

                    <div>

                        {!! nl2br(
                            e(
                                $incident->property_damage_details
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        WORK STOPPAGE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Work Stoppage
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Work Stopped
                    </small>

                    <div>

                        @if($incident->work_stopped)

                            <span class="badge bg-danger">
                                Yes
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-8">

                    <small class="text-muted">
                        Work Stoppage Details
                    </small>

                    <div>

                        {!! nl2br(
                            e(
                                $incident->work_stoppage_details
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
    PERSONS INVOLVED
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Persons Involved
                    </strong>

                    <span class="badge bg-primary ms-2">
                        {{ $incident->persons?->count() ?? 0 }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if($incident->persons?->count())

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.persons.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="bi bi-list me-1"></i>
                            View All
                        </a>

                    @endif


                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.persons.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Add Person
                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($incident->persons?->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Person
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Company
                                </th>

                                <th>
                                    Designation
                                </th>

                                <th>
                                    Injury
                                </th>

                                <th>
                                    Injury Severity
                                </th>

                                <th>
                                    Hospitalized
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($incident->persons as $person)

                                <tr>

                                    {{-- Person --}}

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $person->person_name }}
                                        </div>

                                        @if($person->employee_code)

                                            <div class="small text-muted">
                                                Employee Code:
                                                {{ $person->employee_code }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ $person->person_type ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- Company --}}

                                    <td>
                                        {{ $person->company_name ?? '—' }}
                                    </td>


                                    {{-- Designation --}}

                                    <td>
                                        {{ $person->designation ?? '—' }}
                                    </td>


                                    {{-- Injury --}}

                                    <td>

                                        @if($person->injury_occurred)

                                            <span class="badge bg-danger">
                                                Yes
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                No
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Injury Severity --}}

                                    <td>

                                        @if($person->injury_occurred)

                                            {{ $person->injury_severity ?? '—' }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Hospitalized --}}

                                    <td>

                                        @if($person->hospitalized)

                                            <span class="badge bg-danger">
                                                Yes
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                No
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.incidents.persons.show',
                                                [
                                                    'project' => $project,
                                                    'incident' => $incident,
                                                    'person' => $person,
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

                    <div class="mb-3">

                        <i
                            class="bi bi-person"
                            style="font-size: 36px;"
                        ></i>

                    </div>


                    <h6 class="mb-1">
                        No Persons Involved
                    </h6>


                    <p class="text-muted mb-3">
                        No person has been associated with this incident yet.
                    </p>


                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.persons.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-primary btn-sm"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Add First Person
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

    {{-- =========================================================
    WITNESSES
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Witnesses
                    </strong>

                    <span class="badge bg-primary ms-2">
                        {{ $incident->witnesses?->count() ?? 0 }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if($incident->witnesses?->count())

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.witnesses.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="bi bi-list me-1"></i>
                            View All
                        </a>

                    @endif


                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.witnesses.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Add Witness
                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($incident->witnesses?->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Witness
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Company
                                </th>

                                <th>
                                    Designation
                                </th>

                                <th>
                                    Phone
                                </th>

                                <th>
                                    Statement Date
                                </th>

                                <th>
                                    Statement
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($incident->witnesses as $witness)

                                <tr>

                                    {{-- Witness --}}

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $witness->witness_name }}
                                        </div>

                                        @if($witness->employee_code)

                                            <div class="small text-muted">
                                                Employee Code:
                                                {{ $witness->employee_code }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ $witness->witness_type ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- Company --}}

                                    <td>
                                        {{ $witness->company_name ?? '—' }}
                                    </td>


                                    {{-- Designation --}}

                                    <td>
                                        {{ $witness->designation ?? '—' }}
                                    </td>


                                    {{-- Phone --}}

                                    <td>
                                        {{ $witness->phone ?? '—' }}
                                    </td>


                                    {{-- Statement Date --}}

                                    <td>

                                        {{ $witness->statement_date
                                            ? $witness->statement_date->format('d-m-Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- Statement --}}

                                    <td>

                                        @if($witness->statement)

                                            <span class="badge bg-success">
                                                Available
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.incidents.witnesses.show',
                                                [
                                                    'project' => $project,
                                                    'incident' => $incident,
                                                    'witness' => $witness,
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

                    <div class="mb-3">

                        <i
                            class="bi bi-eye"
                            style="font-size: 36px;"
                        ></i>

                    </div>


                    <h6 class="mb-1">
                        No Witnesses
                    </h6>


                    <p class="text-muted mb-3">
                        No witness has been recorded for this incident yet.
                    </p>


                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.witnesses.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-primary btn-sm"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Add First Witness
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        INVESTIGATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Investigation
                    </strong>

                    @if($incident->investigations?->count())

                        <span class="badge bg-primary ms-2">
                            {{ $incident->investigations->count() }}
                        </span>

                    @endif

                </div>


                <div class="d-flex gap-2">

                    {{-- View All --}}

                    @if($incident->investigations?->count())

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.investigations.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >

                            View All

                        </a>

                    @endif


                    {{-- =================================================
                        CREATE INVESTIGATION
                        Only when incident is Reported
                    ================================================== --}}

                    @if($incident->status === 'Reported')

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

                            Add Investigation

                        </a>

                    @endif


                    {{-- =================================================
                        MANAGE INVESTIGATION
                        ================================================== --}}

                    @if($incident->status === 'Under Investigation')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.investigations.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >

                            <i class="bi bi-search me-1"></i>

                            Manage Investigation

                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body">

            @php

                $investigation =
                    $incident->investigations
                        ?->sortByDesc('id')
                        ?->first();

            @endphp


            @if($investigation)

                <div class="row g-3">


                    {{-- Investigation Number --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Investigation No.
                        </div>

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.investigations.show',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                    'investigation' => $investigation,
                                ]
                            ) }}"
                            class="fw-semibold"
                        >

                            {{ $investigation->investigation_number }}

                        </a>

                    </div>


                    {{-- Investigation Date --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Investigation Date
                        </div>

                        <div class="fw-semibold">

                            {{ $investigation->investigation_date
                                ? $investigation->investigation_date->format('d-m-Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    {{-- Lead Investigator --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Lead Investigator
                        </div>

                        <div class="fw-semibold">

                            {{ $investigation->lead_investigator_name
                                ?? $investigation->leadInvestigator?->name
                                ?? '—'
                            }}

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Status
                        </div>


                        @switch($investigation->status)

                            @case('Draft')

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                                @break

                            @case('Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary">
                                    {{ $investigation->status }}
                                </span>

                        @endswitch

                    </div>

                </div>


                @if($investigation->findings)

                    <hr>

                    <div>

                        <div class="text-muted small mb-1">
                            Findings
                        </div>

                        {!! nl2br(
                            e(
                                \Illuminate\Support\Str::limit(
                                    $investigation->findings,
                                    300
                                )
                            )
                        ) !!}

                    </div>

                @endif


            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No investigation has been created yet.

                    </div>


                    {{-- Only Reported incident can start investigation --}}

                    @if($incident->status === 'Reported')

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

                            <i class="bi bi-search me-1"></i>

                            Start Investigation

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        INCIDENT ACTIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Incident Actions
                    </strong>

                    @if($incident->actions)

                        <span class="badge bg-primary ms-2">
                            {{ $incident->actions->count() }}
                        </span>

                    @endif

                </div>


                {{-- =================================================
                    ACTION BUTTONS
                ================================================== --}}

                <div class="d-flex gap-2">


                    {{-- =================================================
                        IMPORTANT:
                        Add Action is ONLY allowed after
                        Investigation Completed.
                    ================================================== --}}

                    @if($incident->status === 'Investigation Completed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.actions.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Add Action

                        </a>

                    @endif


                    {{-- =================================================
                        Manage Actions
                        Available once actions exist
                    ================================================== --}}

                    @if($incident->actions && $incident->actions->count())

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.actions.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >

                            <i class="bi bi-list-check me-1"></i>

                            View All

                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($incident->actions && $incident->actions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Action No.
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Responsible
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Verification
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($incident->actions as $action)

                            <tr>


                                {{-- Action Number --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.actions.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'action' => $action,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $action->action_number }}

                                    </a>

                                </td>



                                {{-- Type --}}

                                <td>

                                    <span class="badge bg-info text-dark">

                                        {{ $action->action_type ?? '—' }}

                                    </span>

                                </td>



                                {{-- Description --}}

                                <td style="min-width: 250px;">

                                    {{ \Illuminate\Support\Str::limit(
                                        $action->action_description,
                                        100
                                    ) }}

                                </td>



                                {{-- Responsible --}}

                                <td>

                                    {{ $action->responsible_name
                                        ?? $action->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>



                                {{-- Due Date --}}

                                <td>

                                    {{ $action->due_date
                                        ? $action->due_date->format('d-m-Y')
                                        : '—'
                                    }}

                                    @if($action->isOverdue())

                                        <div class="mt-1">

                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>

                                        </div>

                                    @endif

                                </td>



                                {{-- Status --}}

                                <td>

                                    @php

                                        $actionStatusClass =
                                            match($action->status) {

                                                'Open' =>
                                                    'bg-secondary',

                                                'In Progress' =>
                                                    'bg-warning text-dark',

                                                'Completed' =>
                                                    'bg-primary',

                                                'Closed' =>
                                                    'bg-dark',

                                                default =>
                                                    'bg-secondary',
                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $actionStatusClass }}"
                                    >

                                        {{ $action->status }}

                                    </span>

                                </td>



                                {{-- Verification --}}

                                <td>

                                    @php

                                        $verificationClass =
                                            match(
                                                $action->verification_status
                                            ) {

                                                'Verified' =>
                                                    'bg-success',

                                                'Rejected' =>
                                                    'bg-danger',

                                                'Pending' =>
                                                    'bg-warning text-dark',

                                                default =>
                                                    'bg-secondary',
                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $verificationClass }}"
                                    >

                                        {{ $action->verification_status ?? '—' }}

                                    </span>

                                </td>



                                {{-- Action --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.actions.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'action' => $action,
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

                    <div class="mb-3">

                        <i
                            class="bi bi-check2-square"
                            style="font-size: 36px;"
                        ></i>

                    </div>


                    <h6 class="mb-1">
                        No Incident Actions
                    </h6>


                    <p class="text-muted mb-3">

                        No corrective or preventive actions have
                        been assigned to this incident yet.

                    </p>


                    {{-- =================================================
                        ONLY SHOW ADD FIRST ACTION AFTER
                        INVESTIGATION IS COMPLETED
                    ================================================== --}}

                    @if($incident->status === 'Investigation Completed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.actions.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Add First Action

                        </a>

                    @elseif(
                        in_array(
                            $incident->status,
                            [
                                'Reported',
                                'Under Investigation'
                            ],
                            true
                        )
                    )

                        <div class="text-muted">

                            Actions can be assigned after
                            the investigation is completed.

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        DOCUMENTS & EVIDENCE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Documents & Evidence
                    </strong>

                    @if($incident->documents)

                        <span class="badge bg-primary ms-2">
                            {{ $incident->documents->count() }}
                        </span>

                    @endif

                </div>


                <div class="d-flex gap-2">

                    {{-- Upload --}}

                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.documents.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >

                            <i class="bi bi-upload me-1"></i>

                            Upload Document

                        </a>

                    @endif


                    {{-- View All --}}

                    @if($incident->documents && $incident->documents->count())

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.documents.index',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >

                            View All

                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($incident->documents && $incident->documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Evidence
                            </th>

                            <th>
                                Uploaded
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $incident->documents->take(5)
                            as $document
                        )

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $document->document_title }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $document->file_name }}

                                    </div>

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $document->document_type }}

                                    </span>

                                </td>


                                <td>

                                    @if($document->is_evidence)

                                        <span class="badge bg-danger">
                                            Evidence
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            Supporting
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $document->created_at
                                        ? $document->created_at->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.documents.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fa fa-download"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4">

                    <div class="text-muted mb-3">

                        No documents or evidence uploaded.

                    </div>


                    @if($incident->status !== 'Closed')

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.incidents.documents.create',
                                [
                                    'project' => $project,
                                    'incident' => $incident,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >

                            <i class="bi bi-upload me-1"></i>

                            Upload First Document

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $incident->remarks ?? '—'
                )
            ) !!}

        </div>

    </div>



    {{-- =========================================================
        CLOSURE INFORMATION
    ========================================================== --}}

    @if($incident->status === 'Closed')

        <div class="card mb-4 border-dark">

            <div class="card-header">

                <strong>
                    Closure Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <small class="text-muted">
                            Closed Date
                        </small>

                        <div class="fw-semibold">

                            {{ $incident->closed_date
                                ? $incident->closed_date->format('d-m-Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <small class="text-muted">
                            Closed By
                        </small>

                        <div class="fw-semibold">

                            {{ $incident->closedBy?->name ?? '—' }}

                        </div>

                    </div>


                    <div class="col-md-12">

                        <small class="text-muted">
                            Closure Remarks
                        </small>

                        <div class="mt-1">

                            {!! nl2br(
                                e(
                                    $incident->closure_remarks ?? '—'
                                )
                            ) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        CLOSE INCIDENT MODAL
    ========================================================== --}}

    @if($incident->status === 'Verified')

        <div
            class="modal fade"
            id="closeIncidentModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.close',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="modal-header">

                            <h5 class="modal-title">
                                Close Incident
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <div class="alert alert-warning">

                                <i class="bi bi-exclamation-triangle me-1"></i>

                                Once the incident is closed,
                                it cannot be edited through the
                                normal workflow.

                            </div>


                            <div class="mb-3">

                                <label class="form-label">

                                    Closure Remarks

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <textarea
                                    name="closure_remarks"
                                    class="form-control"
                                    rows="4"
                                    required
                                >{{ old('closure_remarks') }}</textarea>

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >

                                Cancel

                            </button>


                            <button
                                type="submit"
                                class="btn btn-dark"
                            >

                                <i class="bi bi-lock me-1"></i>

                                Close Incident

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        DELETE
    ========================================================== --}}

    @if($incident->status === 'Reported')

        <div class="card border-danger mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong class="text-danger">
                            Delete Incident
                        </strong>

                        <div class="text-muted">

                            Only reported incidents can be deleted.

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.destroy',
                            [
                                'project' => $project,
                                'incident' => $incident
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this incident?'
                        );"
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection