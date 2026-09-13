@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Follow-up
            </div>

            <h3 class="mb-1">
                Governance Follow-up
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div>

            <a
                href="{{ url()->previous() }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- ACTION ITEM SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="mb-2">

        <h5 class="mb-1">
            Action Items
        </h5>

        <div class="text-muted small">
            Follow-up status of actions arising from governance meetings.
        </div>

    </div>


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $actionCounts['total'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Open --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $actionCounts['open'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- In Progress --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $actionCounts['in_progress'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $actionCounts['overdue'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $actionCounts['completed'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Cancelled
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $actionCounts['cancelled'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DECISION SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="mb-2">

        <h5 class="mb-1">
            Decisions
        </h5>

        <div class="text-muted small">
            Current status of decisions recorded in governance meetings.
        </div>

    </div>


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $decisionCounts['total'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $decisionCounts['draft'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Deferred --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Deferred
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $decisionCounts['deferred'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $decisionCounts['approved'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $decisionCounts['rejected'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Superseded --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Superseded
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $decisionCounts['superseded'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- OVERDUE ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div>

                <strong>
                    Overdue Actions
                </strong>

                <div class="text-muted small mt-1">
                    Actions requiring immediate follow-up.
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($overdueActions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Meeting
                                </th>

                                <th>
                                    Action
                                </th>

                                <th>
                                    Responsible
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($overdueActions as $action)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if($action->meeting)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.show',
                                                    [
                                                        'project' =>
                                                            $project->id,
                                                        'meeting' =>
                                                            $action->meeting->id,
                                                    ]
                                                ) }}"
                                            >
                                                {{ $action->meeting->meeting_number }}
                                            </a>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $action->action_description }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $action->responsibleUser->name
                                                ?? $action->responsible_name
                                                ?? '—'
                                        }}

                                        @if($action->responsible_organization)

                                            <div class="text-muted small">

                                                {{ $action->responsible_organization }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        @php

                                            $priorityClass =
                                                match($action->priority) {

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

                                        @endphp


                                        <span
                                            class="badge {{ $priorityClass }}"
                                        >
                                            {{ $action->priority }}
                                        </span>

                                    </td>


                                    <td>

                                        @if($action->due_date)

                                            {{ $action->due_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-danger">
                                            {{ $action->status }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No Overdue Actions
                    </h6>

                    <p class="text-muted mb-0">
                        There are currently no overdue governance actions.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- OPEN ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Open Governance Actions
            </strong>

            <div class="text-muted small mt-1">
                Open, in-progress and overdue actions requiring tracking.
            </div>

        </div>


        <div class="card-body p-0">

            @if($openActions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Meeting
                                </th>

                                <th>
                                    Action
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

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($openActions as $action)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if($action->meeting)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.show',
                                                    [
                                                        'project' =>
                                                            $project->id,
                                                        'meeting' =>
                                                            $action->meeting->id,
                                                    ]
                                                ) }}"
                                            >
                                                {{ $action->meeting->meeting_number }}
                                            </a>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ $action->action_description }}

                                    </td>


                                    <td>

                                        {{
                                            $action->responsibleUser->name
                                                ?? $action->responsible_name
                                                ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        @if($action->due_date)

                                            {{ $action->due_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @php

                                            $statusClass =
                                                match($action->status) {

                                                    'Open'
                                                        => 'bg-primary',

                                                    'In Progress'
                                                        => 'bg-warning text-dark',

                                                    'Overdue'
                                                        => 'bg-danger',

                                                    default
                                                        => 'bg-secondary',

                                                };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $action->status }}
                                        </span>

                                    </td>

                                    <td class="text-end">

                                        @if($action->meeting)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.action-items.edit',
                                                    [
                                                        'project' => $project->id,
                                                        'meeting' => $action->meeting->id,
                                                        'actionItem' => $action->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Update
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No Open Actions
                    </h6>

                    <p class="text-muted mb-0">
                        There are no open governance actions.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PENDING DECISIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Pending Decisions
            </strong>

            <div class="text-muted small mt-1">
                Draft and deferred decisions requiring governance follow-up.
            </div>

        </div>


        <div class="card-body p-0">

            @if($pendingDecisions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Meeting
                                </th>

                                <th>
                                    Decision
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Effective Date
                                </th>
                                <th class="text-end">Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($pendingDecisions as $decision)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if($decision->meeting)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.show',
                                                    [
                                                        'project' =>
                                                            $project->id,
                                                        'meeting' =>
                                                            $decision->meeting->id,
                                                    ]
                                                ) }}"
                                            >
                                                {{ $decision->meeting->meeting_number }}
                                            </a>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $decision->decision_title }}

                                        </div>

                                        <div class="text-muted small">

                                            {{ \Illuminate\Support\Str::limit(
                                                $decision->decision_text,
                                                150
                                            ) }}

                                        </div>

                                    </td>


                                    <td>

                                        <span
                                            class="badge
                                                   bg-light
                                                   text-dark
                                                   border"
                                        >
                                            {{ $decision->decision_type }}
                                        </span>

                                    </td>


                                    <td>

                                        @php

                                            $decisionStatusClass =
                                                match(
                                                    $decision->decision_status
                                                ) {

                                                    'Draft'
                                                        => 'bg-secondary',

                                                    'Deferred'
                                                        => 'bg-warning text-dark',

                                                    'Approved'
                                                        => 'bg-success',

                                                    'Rejected'
                                                        => 'bg-danger',

                                                    'Superseded'
                                                        => 'bg-dark',

                                                    default
                                                        => 'bg-secondary',

                                                };

                                        @endphp


                                        <span
                                            class="badge
                                                   {{ $decisionStatusClass }}"
                                        >
                                            {{ $decision->decision_status }}
                                        </span>

                                    </td>


                                    <td>

                                        @if($decision->effective_date)

                                            {{ $decision->effective_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>
                                    <td class="text-end">

                                        @if($decision->meeting)

                                            <div class="d-flex justify-content-end gap-1">

                                                {{-- Edit --}}

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.governance-meetings.decisions.edit',
                                                        [
                                                            'project' => $project->id,
                                                            'meeting' => $decision->meeting->id,
                                                            'decision' => $decision->id,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                    Edit
                                                </a>


                                                {{-- Approve --}}

                                                @if($decision->decision_status !== 'Approved')

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.projects.governance-meetings.decisions.status',
                                                            [
                                                                'project' => $project->id,
                                                                'meeting' => $decision->meeting->id,
                                                                'decision' => $decision->id,
                                                            ]
                                                        ) }}"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        <input
                                                            type="hidden"
                                                            name="decision_status"
                                                            value="Approved"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-success"
                                                            onclick="return confirm(
                                                                'Approve this decision?'
                                                            )"
                                                        >
                                                            Approve
                                                        </button>

                                                    </form>

                                                @endif


                                                {{-- Defer --}}

                                                @if(
                                                    $decision->decision_status !== 'Deferred'
                                                    &&
                                                    $decision->decision_status !== 'Approved'
                                                )

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.projects.governance-meetings.decisions.status',
                                                            [
                                                                'project' => $project->id,
                                                                'meeting' => $decision->meeting->id,
                                                                'decision' => $decision->id,
                                                            ]
                                                        ) }}"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        <input
                                                            type="hidden"
                                                            name="decision_status"
                                                            value="Deferred"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-warning"
                                                            onclick="return confirm(
                                                                'Defer this decision?'
                                                            )"
                                                        >
                                                            Defer
                                                        </button>

                                                    </form>

                                                @endif

                                            </div>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No Pending Decisions
                    </h6>

                    <p class="text-muted mb-0">
                        There are currently no draft or deferred decisions.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- NAVIGATION --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-end gap-2 mb-5">

        <a
            href="{{ url()->previous() }}"
            class="btn btn-outline-secondary"
        >
            ← Back
        </a>

    </div>

</div>

@endsection