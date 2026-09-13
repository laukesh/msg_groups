@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Decision Register
            </div>

            <h3 class="mb-1">
                {{ $decision->subject }}
            </h3>

            <div class="text-muted">

                {{ $decision->decision_number }}

                ·

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.decision-register.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Decision Register
            </a>


            <a
                href="{{ route(
                    'admin.projects.decision-register.edit',
                    [
                        'project' => $project->id,
                        'decision' => $decision->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS / PRIORITY SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $priorityClass =
            match($decision->priority) {

                'Critical'
                    => 'bg-danger',

                'High'
                    => 'bg-warning text-dark',

                'Medium'
                    => 'bg-primary',

                'Low'
                    => 'bg-secondary',

                default
                    => 'bg-secondary',

            };


        $statusClass =
            match($decision->status) {

                'Approved'
                    => 'bg-primary',

                'Implemented'
                    => 'bg-success',

                'Draft'
                    => 'bg-warning text-dark',

                'Superseded'
                    => 'bg-secondary',

                'Cancelled'
                    => 'bg-danger',

                default
                    => 'bg-secondary',

            };

    @endphp


    <div class="row g-3 mb-4">

        {{-- Decision Number --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Decision Number
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $decision->decision_number }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Decision Date --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Decision Date
                    </div>

                    <div class="fw-semibold fs-5 mt-1">

                        @if($decision->decision_date)

                            {{ $decision->decision_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Priority --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $priorityClass }} fs-6"
                        >
                            {{ $decision->priority }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Status --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $decision->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION DETAILS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Decision Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Decision Type --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Decision Type
                    </div>

                    <div class="fw-semibold">
                        {{ $decision->decision_type }}
                    </div>

                </div>


                {{-- Decision Date --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Decision Date
                    </div>

                    <div class="fw-semibold">

                        @if($decision->decision_date)

                            {{ $decision->decision_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Reference --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Reference Number
                    </div>

                    <div class="fw-semibold">

                        @if($decision->reference_number)

                            {{ $decision->reference_number }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Subject --}}

            <div class="mb-4">

                <div class="text-muted small">
                    Subject
                </div>

                <div class="fw-semibold fs-5 mt-1">
                    {{ $decision->subject }}
                </div>

            </div>


            {{-- Decision --}}

            <div class="mb-4">

                <div class="text-muted small">
                    Decision
                </div>

                <div class="mt-2">

                    {!! nl2br(
                        e($decision->decision)
                    ) !!}

                </div>

            </div>


            {{-- Rationale --}}

            @if($decision->rationale)

                <div>

                    <div class="text-muted small">
                        Rationale
                    </div>

                    <div class="mt-2">

                        {!! nl2br(
                            e($decision->rationale)
                        ) !!}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE & AUTHORITY --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance & Decision Authority
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Governance --}}

                <div class="col-md-6 mb-4">

                    <div class="text-muted small">
                        Governance Framework
                    </div>


                    @if($decision->governance)

                        <div class="fw-semibold mt-1">

                            {{ $decision->governance->governance_number }}

                            ·

                            {{ $decision->governance->title }}

                        </div>


                        <div class="text-muted small mt-1">

                            Status:
                            {{ $decision->governance->status }}

                        </div>


                        <a
                            href="{{ route(
                                'admin.projects.governance.show',
                                [
                                    'project' =>
                                        $project->id,

                                    'governance' =>
                                        $decision
                                            ->governance
                                            ->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary mt-2"
                        >
                            View Governance
                        </a>

                    @else

                        <span class="text-muted">
                            Not Linked
                        </span>

                    @endif

                </div>


                {{-- Decision Maker Role --}}

                <div class="col-md-6 mb-4">

                    <div class="text-muted small">
                        Decision Maker Role
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($decision->decision_maker_role)

                            {{ $decision->decision_maker_role }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Decision Maker --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Decision Maker
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($decision->decisionMaker)

                            {{ $decision->decisionMaker->name }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- IMPACT --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project Impact
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Financial Impact --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Financial Impact
                    </div>

                    <div class="fw-semibold fs-5 mt-1">

                        @if($decision->financial_impact !== null)

                            ${{ number_format(
                                $decision->financial_impact,
                                2
                            ) }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Schedule Impact --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Schedule Impact
                    </div>

                    <div class="fw-semibold fs-5 mt-1">

                        @if($decision->schedule_impact_days !== null)

                            @if(
                                $decision->schedule_impact_days > 0
                            )

                                +{{ $decision->schedule_impact_days }}
                                days

                            @elseif(
                                $decision->schedule_impact_days < 0
                            )

                                {{ $decision->schedule_impact_days }}
                                days

                            @else

                                0 days

                            @endif

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Priority --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $priorityClass }} fs-6"
                        >
                            {{ $decision->priority }}
                        </span>

                    </div>

                </div>

            </div>


            @if($decision->impact_description)

                <div class="mt-3">

                    <div class="text-muted small">
                        Impact Description
                    </div>

                    <div class="mt-2">

                        {!! nl2br(
                            e(
                                $decision->impact_description
                            )
                        ) !!}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- IMPLEMENTATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Implementation
            </strong>

        </div>


        <div class="card-body">

            @if($decision->implementation_required)

                <div class="row">

                    {{-- Required --}}

                    <div class="col-md-3 mb-3">

                        <div class="text-muted small">
                            Implementation Required
                        </div>

                        <div class="mt-2">

                            <span class="badge bg-info text-dark">
                                Yes
                            </span>

                        </div>

                    </div>


                    {{-- Owner --}}

                    <div class="col-md-3 mb-3">

                        <div class="text-muted small">
                            Implementation Owner
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($decision->implementationOwner)

                                {{ $decision->implementationOwner->name }}

                            @else

                                <span class="text-muted">
                                    Not Assigned
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Due Date --}}

                    <div class="col-md-3 mb-3">

                        <div class="text-muted small">
                            Due Date
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($decision->implementation_due_date)

                                {{ $decision->implementation_due_date->format('d-m-Y') }}

                            @else

                                <span class="text-muted">
                                    Not Defined
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Implemented Date --}}

                    <div class="col-md-3 mb-3">

                        <div class="text-muted small">
                            Implemented Date
                        </div>

                        <div class="fw-semibold mt-1">

                            @if($decision->implemented_date)

                                {{ $decision->implemented_date->format('d-m-Y') }}

                            @else

                                <span class="text-muted">
                                    Not Implemented
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @else

                <div class="text-muted">

                    This decision does not require separate
                    implementation tracking.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Decision Status
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="text-muted">
                        Current Status:
                    </span>

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $decision->status }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if($decision->status === 'Draft')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.decision-register.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'decision' =>
                                        $decision->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Approved"
                            >

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Approve
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.decision-register.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'decision' =>
                                        $decision->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Cancelled"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                            >
                                Cancel
                            </button>

                        </form>

                    @endif


                    @if(
                        $decision->status === 'Approved' &&
                        $decision->implementation_required
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.decision-register.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'decision' =>
                                        $decision->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Implemented"
                            >

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Mark Implemented
                            </button>

                        </form>

                    @endif


                    @if(
                        $decision->status === 'Approved' &&
                        !$decision->implementation_required
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.decision-register.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'decision' =>
                                        $decision->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Implemented"
                            >

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Mark Implemented
                            </button>

                        </form>

                    @endif


                    @if(
                        $decision->status === 'Approved' ||
                        $decision->status === 'Implemented'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.decision-register.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'decision' =>
                                        $decision->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Superseded"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-secondary"
                            >
                                Supersede
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($decision->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e($decision->remarks)
                ) !!}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- DANGER ZONE --}}
    {{-- ========================================================= --}}

    <div class="card border-danger mb-5">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Delete Decision
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.decision-register.destroy',
                        [
                            'project' =>
                                $project->id,

                            'decision' =>
                                $decision->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this decision?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Decision
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection