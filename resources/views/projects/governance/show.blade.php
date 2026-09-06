@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance
            </div>

            <h3 class="mb-1">
                {{ $governance->title }}
            </h3>

            <div class="text-muted">

                {{ $governance->governance_number }}

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
                    'admin.projects.governance.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Governance
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance.edit',
                    [
                        'project' => $project->id,
                        'governance' => $governance->id,
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
    {{-- STATUS SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $statusClass =
            match($governance->status) {

                'Active'
                    => 'bg-success',

                'Draft'
                    => 'bg-warning text-dark',

                'Under Review'
                    => 'bg-info text-dark',

                'Superseded'
                    => 'bg-secondary',

                'Closed'
                    => 'bg-dark',

                default
                    => 'bg-secondary',

            };

    @endphp


    <div class="row g-3 mb-4">

        {{-- Governance Model --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Governance Model
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $governance->governance_model }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Status --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $governance->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Effective Date --}}

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Effective Date
                    </div>

                    <div class="fw-semibold fs-5 mt-1">

                        @if($governance->effective_date)

                            {{ $governance->effective_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE LEADERSHIP --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance Leadership
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Sponsor --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Project Sponsor
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($governance->projectSponsor)

                            {{ $governance->projectSponsor->name }}

                        @else

                            <span class="text-muted">
                                Not Assigned
                            </span>

                        @endif

                    </div>

                    <div class="text-muted small mt-1">
                        Executive project authority
                    </div>

                </div>


                {{-- Director --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Project Director
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($governance->projectDirector)

                            {{ $governance->projectDirector->name }}

                        @else

                            <span class="text-muted">
                                Not Assigned
                            </span>

                        @endif

                    </div>

                    <div class="text-muted small mt-1">
                        Strategic project leadership
                    </div>

                </div>


                {{-- Manager --}}

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Project Manager
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($governance->projectManager)

                            {{ $governance->projectManager->name }}

                        @else

                            <span class="text-muted">
                                Not Assigned
                            </span>

                        @endif

                    </div>

                    <div class="text-muted small mt-1">
                        Day-to-day project management
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE OBJECTIVE --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance Objective
            </strong>

        </div>


        <div class="card-body">

            @if($governance->governance_objective)

                {!! nl2br(
                    e($governance->governance_objective)
                ) !!}

            @else

                <span class="text-muted">
                    No governance objective has been defined.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION MAKING --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Decision-Making Framework
            </strong>

        </div>


        <div class="card-body">

            @if($governance->decision_making_framework)

                {!! nl2br(
                    e($governance->decision_making_framework)
                ) !!}

            @else

                <span class="text-muted">
                    No decision-making framework has been defined.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVAL FRAMEWORK --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval Framework
            </strong>

        </div>


        <div class="card-body">

            @if($governance->approval_framework)

                {!! nl2br(
                    e($governance->approval_framework)
                ) !!}

            @else

                <span class="text-muted">
                    No approval framework has been defined.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ESCALATION FRAMEWORK --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Escalation Framework
            </strong>

        </div>


        <div class="card-body">

            @if($governance->escalation_framework)

                {!! nl2br(
                    e($governance->escalation_framework)
                ) !!}

            @else

                <span class="text-muted">
                    No escalation framework has been defined.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REPORTING FRAMEWORK --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Reporting Framework
            </strong>

        </div>


        <div class="card-body">

            @if($governance->reporting_framework)

                {!! nl2br(
                    e($governance->reporting_framework)
                ) !!}

            @else

                <span class="text-muted">
                    No reporting framework has been defined.
                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MEETING FRAMEWORK --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting & Committee Framework
            </strong>

        </div>


        <div class="card-body">

            @if($governance->meeting_framework)

                {!! nl2br(
                    e($governance->meeting_framework)
                ) !!}

            @else

                <span class="text-muted">
                    No meeting or committee framework has been defined.
                </span>

            @endif

        </div>

    </div>
    {{-- ========================================================= --}}
    {{-- APPROVAL MATRIX --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Approval Matrix
                    </strong>

                    <div class="text-muted small mt-1">
                        Approval authority, financial limits and
                        approval sequence for this governance framework.
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.approval-matrix.index',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.approval-matrix.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Approval Rule
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @php

                $approvalRules = $governance->approvalMatrix
                    ->sortBy([
                        ['approval_sequence', 'asc'],
                        ['id', 'asc'],
                    ]);

            @endphp


            @if($approvalRules->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3">
                                    Sequence
                                </th>

                                <th>
                                    Approval Code
                                </th>

                                <th>
                                    Approval Type
                                </th>

                                <th>
                                    Authority
                                </th>

                                <th>
                                    Amount Range
                                </th>

                                <th>
                                    Mandatory
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($approvalRules as $approvalRule)

                                @php

                                    $approvalStatusClass =
                                        match(
                                            $approvalRule->status
                                        ) {

                                            'Active'
                                                => 'bg-success',

                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Inactive'
                                                => 'bg-secondary',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Sequence --}}

                                    <td class="ps-3">

                                        <span
                                            class="badge bg-light text-dark border"
                                        >
                                            {{ $approvalRule->approval_sequence }}
                                        </span>

                                    </td>


                                    {{-- Code --}}

                                    <td>

                                        <strong>
                                            {{ $approvalRule->approval_code }}
                                        </strong>

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $approvalRule->approval_type }}

                                        </div>


                                        @if($approvalRule->description)

                                            <div class="text-muted small">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $approvalRule->description,
                                                    70
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Authority --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $approvalRule->authority_role }}

                                        </div>


                                        @if($approvalRule->authorityUser)

                                            <div class="text-muted small">

                                                {{ $approvalRule->authorityUser->name }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Amount Range --}}

                                    <td>

                                        @if(
                                            $approvalRule->minimum_amount !== null ||
                                            $approvalRule->maximum_amount !== null
                                        )

                                            {{ $approvalRule->currency }}


                                            @if(
                                                $approvalRule->minimum_amount !== null
                                            )

                                                {{ number_format(
                                                    $approvalRule->minimum_amount,
                                                    2
                                                ) }}

                                            @else

                                                0.00

                                            @endif


                                            <span class="text-muted">
                                                –
                                            </span>


                                            @if(
                                                $approvalRule->maximum_amount !== null
                                            )

                                                {{ $approvalRule->currency }}

                                                {{ number_format(
                                                    $approvalRule->maximum_amount,
                                                    2
                                                ) }}

                                            @else

                                                No Limit

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                No Limit
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Mandatory --}}

                                    <td>

                                        @if($approvalRule->is_mandatory)

                                            <span class="badge bg-danger">
                                                Mandatory
                                            </span>

                                        @else

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                Optional
                                            </span>

                                        @endif


                                        @if(
                                            $approvalRule
                                                ->requires_multiple_approvals
                                        )

                                            <div class="mt-1">

                                                <span
                                                    class="badge bg-warning text-dark"
                                                >
                                                    Multiple
                                                </span>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $approvalStatusClass }}"
                                        >
                                            {{ $approvalRule->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.approval-matrix.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'approvalMatrix' =>
                                                            $approvalRule->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.approval-matrix.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'approvalMatrix' =>
                                                            $approvalRule->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <h5>
                        No Approval Rules
                    </h5>

                    <div class="text-muted mb-3">

                        No approval authority has been configured
                        for this governance framework.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.approval-matrix.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Approval Rule
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- DECISION REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Decision Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Formal project decisions recorded under this
                        governance framework.
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
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View All
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.decision-register.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Decision
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @php

                $decisions = $governance->decisionRegister
                    ->sortByDesc('decision_date')
                    ->values();

            @endphp


            @if($decisions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3">
                                    Decision No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Decision Maker
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Impact
                                </th>

                                <th>
                                    Implementation
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($decisions as $decision)

                                @php

                                    $priorityClass =
                                        match(
                                            $decision->priority
                                        ) {

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
                                        match(
                                            $decision->status
                                        ) {

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


                                <tr>

                                    {{-- Decision Number --}}

                                    <td class="ps-3">

                                        <a
                                            href="{{ route(
                                                'admin.projects.decision-register.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'decision' =>
                                                        $decision->id,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $decision->decision_number }}

                                        </a>

                                    </td>


                                    {{-- Date --}}

                                    <td>

                                        @if($decision->decision_date)

                                            {{ $decision->decision_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Subject --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $decision->subject }}

                                        </div>


                                        <div class="text-muted small">

                                            {{ $decision->decision_type }}

                                        </div>

                                    </td>


                                    {{-- Decision Maker --}}

                                    <td>

                                        @if($decision->decisionMaker)

                                            <div class="fw-semibold">

                                                {{ $decision->decisionMaker->name }}

                                            </div>

                                        @endif


                                        @if($decision->decision_maker_role)

                                            <div class="text-muted small">

                                                {{ $decision->decision_maker_role }}

                                            </div>

                                        @elseif(!$decision->decisionMaker)

                                            <span class="text-muted">
                                                Not specified
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Priority --}}

                                    <td>

                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $decision->priority }}
                                        </span>

                                    </td>


                                    {{-- Impact --}}

                                    <td>

                                        @if(
                                            $decision->financial_impact !== null
                                        )

                                            <div class="fw-semibold">

                                                ${{ number_format(
                                                    $decision->financial_impact,
                                                    2
                                                ) }}

                                            </div>

                                        @endif


                                        @if(
                                            $decision->schedule_impact_days !== null
                                        )

                                            <div class="text-muted small">

                                                @if(
                                                    $decision
                                                        ->schedule_impact_days > 0
                                                )

                                                    +{{
                                                        $decision
                                                            ->schedule_impact_days
                                                    }}
                                                    days

                                                @elseif(
                                                    $decision
                                                        ->schedule_impact_days < 0
                                                )

                                                    {{
                                                        $decision
                                                            ->schedule_impact_days
                                                    }}
                                                    days

                                                @else

                                                    0 days

                                                @endif

                                            </div>

                                        @endif


                                        @if(
                                            $decision->financial_impact === null &&
                                            $decision->schedule_impact_days === null
                                        )

                                            <span class="text-muted">
                                                No impact recorded
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Implementation --}}

                                    <td>

                                        @if(
                                            $decision->implementation_required
                                        )

                                            <span
                                                class="badge bg-info text-dark"
                                            >
                                                Required
                                            </span>


                                            @if(
                                                $decision
                                                    ->implementation_due_date
                                            )

                                                <div class="text-muted small mt-1">

                                                    Due:
                                                    {{
                                                        $decision
                                                            ->implementation_due_date
                                                            ->format('d-m-Y')
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                Not Required
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $decision->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.decision-register.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'decision' =>
                                                            $decision->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.decision-register.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'decision' =>
                                                            $decision->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <h5>
                        No Decisions Registered
                    </h5>

                    <div class="text-muted mb-3">

                        No formal project decisions have been
                        recorded under this governance framework.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.decision-register.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Register First Decision
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GOVERNANCE PERIOD --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance Period
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Effective Date
                    </div>

                    <div class="fw-semibold">

                        @if($governance->effective_date)

                            {{ $governance->effective_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Review Date
                    </div>

                    <div class="fw-semibold">

                        @if($governance->review_date)

                            {{ $governance->review_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($governance->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e($governance->remarks)
                ) !!}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS ACTION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance Status
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
                        {{ $governance->status }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if($governance->status !== 'Active')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'governance' =>
                                        $governance->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Active"
                            >

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Mark Active
                            </button>

                        </form>

                    @endif


                    @if($governance->status === 'Active')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'governance' =>
                                        $governance->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Under Review"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-warning"
                            >
                                Send for Review
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DELETE --}}
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
                        Delete Governance Framework
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance.destroy',
                        [
                            'project' =>
                                $project->id,

                            'governance' =>
                                $governance->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this governance framework?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Governance
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection