@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings
            </div>

            <h3 class="mb-1">
                {{ $meeting->meeting_number }}
            </h3>

            <div class="text-muted">

                {{ $meeting->committee_name }}

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
                    'admin.projects.governance-meetings.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Meeting Register
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance-meetings.edit',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $statusClass =
            match($meeting->status) {

                'Scheduled'
                    => 'bg-primary',

                'Held'
                    => 'bg-success',

                'Postponed'
                    => 'bg-warning text-dark',

                'Cancelled'
                    => 'bg-danger',

                default
                    => 'bg-secondary',

            };


        $modeClass =
            match($meeting->meeting_mode) {

                'Physical'
                    => 'bg-light text-dark border',

                'Virtual'
                    => 'bg-info text-dark',

                'Hybrid'
                    => 'bg-secondary',

                default
                    => 'bg-light text-dark border',

            };

    @endphp


    <div class="row g-3 mb-4">

        {{-- Meeting Number --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Meeting Number
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $meeting->meeting_number }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Meeting Date --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Meeting Date
                    </div>

                    <div class="fw-semibold fs-5 mt-1">

                        @if($meeting->meeting_date)

                            {{ $meeting->meeting_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Mode --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Meeting Mode
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $modeClass }} fs-6"
                        >
                            {{ $meeting->meeting_mode }}
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
                            {{ $meeting->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MEETING DETAILS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Meeting Type --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Meeting Type
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $meeting->meeting_type }}
                    </div>

                </div>


                {{-- Committee --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Committee
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $meeting->committee_name }}
                    </div>

                </div>


                {{-- Location --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Location
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->location)

                            {{ $meeting->location }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Date --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Date
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->meeting_date)

                            {{ $meeting->meeting_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Time --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Time
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->start_time)

                            {{
                                \Illuminate\Support\Carbon::parse(
                                    $meeting->start_time
                                )->format('h:i A')
                            }}

                            @if($meeting->end_time)

                                –

                                {{
                                    \Illuminate\Support\Carbon::parse(
                                        $meeting->end_time
                                    )->format('h:i A')
                                }}

                            @endif

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Reference --}}

                <div class="col-md-4 mb-4">

                    <div class="text-muted small">
                        Reference Number
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->reference_number)

                            {{ $meeting->reference_number }}

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
    {{-- GOVERNANCE & OFFICIALS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Governance & Meeting Officials
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Governance --}}

                <div class="col-md-6 mb-4">

                    <div class="text-muted small">
                        Governance Framework
                    </div>


                    @if($meeting->governance)

                        <div class="fw-semibold mt-1">

                            {{ $meeting->governance->governance_number }}

                            ·

                            {{ $meeting->governance->title }}

                        </div>


                        <div class="text-muted small mt-1">

                            Status:
                            {{ $meeting->governance->status }}

                        </div>


                        <a
                            href="{{ route(
                                'admin.projects.governance.show',
                                [
                                    'project' =>
                                        $project->id,

                                    'governance' =>
                                        $meeting
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


                {{-- Meeting Mode --}}

                <div class="col-md-6 mb-4">

                    <div class="text-muted small">
                        Meeting Mode
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $modeClass }} fs-6"
                        >
                            {{ $meeting->meeting_mode }}
                        </span>

                    </div>

                </div>


                {{-- Chairperson --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Chairperson
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->chairperson)

                            {{ $meeting->chairperson->name }}

                        @else

                            <span class="text-muted">
                                Not assigned
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Secretary --}}

                <div class="col-md-6 mb-3">

                    <div class="text-muted small">
                        Secretary
                    </div>

                    <div class="fw-semibold mt-1">

                        @if($meeting->secretary)

                            {{ $meeting->secretary->name }}

                        @else

                            <span class="text-muted">
                                Not assigned
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AGENDA --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting Agenda
            </strong>

        </div>


        <div class="card-body">

            @if($meeting->agenda)

                <div>

                    {!! nl2br(
                        e($meeting->agenda)
                    ) !!}

                </div>

            @else

                <div class="text-muted">

                    No agenda has been recorded.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MEETING MINUTES --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Meeting Minutes
                    </strong>

                    <div class="text-muted small mt-1">
                        Official record of discussions, decisions
                        and actions from this meeting.
                    </div>

                </div>

                <div class="d-flex gap-2">

                    @if($meeting->officialMinutes)

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.minutes.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View Minutes
                        </a>

                        @if(
                            $meeting->officialMinutes &&
                            in_array(
                                $meeting->officialMinutes->minutes_status,
                                ['Draft', 'Rejected']
                            )
                        )

                            <a
                                href="{{ route(
                                    'admin.projects.governance-meetings.minutes.edit',
                                    [
                                        'project' => $project->id,
                                        'meeting' => $meeting->id,
                                        'minutes' => $meeting->officialMinutes->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                Edit Minutes
                            </a>

                        @endif

                    @else

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.minutes.create',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            + Create Minutes
                        </a>

                    @endif

                </div>

            </div>

        </div>

        <div class="card-body">

            @if($meeting->officialMinutes)

                @php

                    $minutes = $meeting->officialMinutes;

                    $minutesStatusClass = match(
                        $minutes->minutes_status
                    ) {
                        'Draft' => 'bg-warning text-dark',
                        'Submitted' => 'bg-primary',
                        'Approved' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        default => 'bg-secondary',
                    };

                @endphp

                <div class="row g-3">

                    {{-- Minutes Number --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Minutes Number
                            </div>

                            <div class="fw-semibold mt-1">
                                {{ $minutes->minutes_number }}
                            </div>

                        </div>

                    </div>

                    {{-- Status --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Status
                            </div>

                            <div class="mt-2">

                                <span class="badge {{ $minutesStatusClass }}">
                                    {{ $minutes->minutes_status }}
                                </span>

                            </div>

                        </div>

                    </div>

                    {{-- Prepared By --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Prepared By
                            </div>

                            <div class="fw-semibold mt-1">
                                {{ $minutes->preparer->name ?? '—' }}
                            </div>

                            @if($minutes->prepared_date)

                                <div class="text-muted small">
                                    {{ $minutes->prepared_date->format('d-m-Y') }}
                                </div>

                            @endif

                        </div>

                    </div>

                    {{-- Approved By --}}

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">
                                Approved By
                            </div>

                            @if($minutes->approver)

                                <div class="fw-semibold mt-1">
                                    {{ $minutes->approver->name }}
                                </div>

                                @if($minutes->approval_date)

                                    <div class="text-success small">
                                        {{ $minutes->approval_date->format('d-m-Y') }}
                                    </div>

                                @endif

                            @else

                                <div class="text-muted mt-1">
                                    Not approved
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

                {{-- Quick Summary --}}

                @if(
                    $minutes->discussion_summary ||
                    $minutes->decisions_summary ||
                    $minutes->action_summary
                )

                    <div class="border-top mt-4 pt-4">

                        <div class="row g-4">

                            @if($minutes->discussion_summary)

                                <div class="col-md-4">

                                    <div class="text-muted small mb-1">
                                        Discussion Summary
                                    </div>

                                    <div
                                        style="
                                            max-height:100px;
                                            overflow:hidden;
                                        "
                                    >
                                        {!! nl2br(e($minutes->discussion_summary)) !!}
                                    </div>

                                </div>

                            @endif

                            @if($minutes->decisions_summary)

                                <div class="col-md-4">

                                    <div class="text-muted small mb-1">
                                        Decisions Summary
                                    </div>

                                    <div
                                        style="
                                            max-height:100px;
                                            overflow:hidden;
                                        "
                                    >
                                        {!! nl2br(e($minutes->decisions_summary)) !!}
                                    </div>

                                </div>

                            @endif

                            @if($minutes->action_summary)

                                <div class="col-md-4">

                                    <div class="text-muted small mb-1">
                                        Action Summary
                                    </div>

                                    <div
                                        style="
                                            max-height:100px;
                                            overflow:hidden;
                                        "
                                    >
                                        {!! nl2br(e($minutes->action_summary)) !!}
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif

            @else

                <div class="text-center py-4">

                    <div class="mb-3">

                        <span
                            class="d-inline-flex
                                   align-items-center
                                   justify-content-center
                                   rounded-circle
                                   border"
                            style="
                                width:56px;
                                height:56px;
                                font-size:24px;
                            "
                        >
                            📝
                        </span>

                    </div>

                    <h6>
                        Minutes Not Prepared
                    </h6>

                    <p class="text-muted small mb-3">
                        No official meeting minutes have been
                        prepared for this meeting.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.minutes.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Create Meeting Minutes
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- MEETING DOCUMENTS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Meeting Documents
                    </strong>

                    <div class="text-muted small mt-1">
                        Documents and supporting files attached to this meeting.
                    </div>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.documents.index',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Documents
                    </a>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.documents.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Upload Document
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            @if($meeting->documents && $meeting->documents->count())

                <div class="row g-3">

                    @foreach(
                        $meeting->documents->take(5)
                        as $document
                    )

                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div
                                    class="d-flex
                                           justify-content-between
                                           align-items-start"
                                >

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $document->document_name }}

                                        </div>


                                        @if($document->document_type)

                                            <span
                                                class="badge
                                                       bg-light
                                                       text-dark
                                                       border
                                                       mt-1"
                                            >
                                                {{ $document->document_type }}
                                            </span>

                                        @endif

                                    </div>


                                    @if($document->status === 'Active')

                                        <span
                                            class="badge bg-success"
                                        >
                                            Active
                                        </span>

                                    @else

                                        <span
                                            class="badge bg-secondary"
                                        >
                                            Archived
                                        </span>

                                    @endif

                                </div>


                                @if($document->original_file_name)

                                    <div class="text-muted small mt-2">

                                        {{ $document->original_file_name }}

                                    </div>

                                @endif


                                <div class="text-muted small mt-1">

                                    Uploaded by:

                                    {{ $document->uploader->name ?? '—' }}

                                    @if($document->uploaded_at)

                                        ·
                                        {{ $document->uploaded_at->format('d-m-Y') }}

                                    @endif

                                </div>


                                <div class="mt-3">

                                    @if($document->status === 'Active')

                                        <a
                                            href="{{ route(
                                                'admin.projects.governance-meetings.documents.download',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                    'meeting' =>
                                                        $meeting->id,
                                                    'document' =>
                                                        $document->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Download
                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>


                @if($meeting->documents->count() > 5)

                    <div class="border-top mt-4 pt-3 text-center">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.documents.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All
                            {{ $meeting->documents->count() }}
                            Documents
                        </a>

                    </div>

                @endif

            @else

                <div class="text-center py-4">

                    <div class="mb-3">

                        <span
                            class="d-inline-flex
                                   align-items-center
                                   justify-content-center
                                   rounded-circle
                                   border"
                            style="
                                width:56px;
                                height:56px;
                                font-size:24px;
                            "
                        >
                            📎
                        </span>

                    </div>


                    <h6>
                        No Documents
                    </h6>


                    <p class="text-muted small mb-3">

                        No documents have been uploaded
                        for this governance meeting yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.documents.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Upload First Document
                    </a>

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
                Meeting Status
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
                        {{ $meeting->status }}
                    </span>

                </div>


                <div class="d-flex gap-2">

                    {{-- Scheduled --}}

                    @if(
                        $meeting->status === 'Postponed'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Scheduled"
                            >

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Reschedule
                            </button>

                        </form>

                    @endif


                    {{-- Held --}}

                    @if(
                        $meeting->status === 'Scheduled'
                    )

                        @if($meeting->officialMinutes)

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.governance-meetings.status',
                                    [
                                        'project' =>
                                            $project->id,

                                        'meeting' =>
                                            $meeting->id,
                                    ]
                                ) }}"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="status"
                                    value="Held"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    Mark Held
                                </button>

                            </form>

                        @else

                            <span
                                class="text-muted small align-self-center"
                            >
                                Add minutes before marking Held.
                            </span>

                        @endif


                        {{-- Postpone --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Postponed"
                            >

                            <button
                                type="submit"
                                class="btn btn-outline-warning"
                            >
                                Postpone
                            </button>

                        </form>


                        {{-- Cancel --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,
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
                                onclick="return confirm(
                                    'Are you sure you want to cancel this meeting?'
                                );"
                            >
                                Cancel Meeting
                            </button>

                        </form>

                    @endif


                    {{-- Reschedule Cancelled --}}

                    @if(
                        $meeting->status === 'Cancelled'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.governance-meetings.status',
                                [
                                    'project' =>
                                        $project->id,

                                    'meeting' =>
                                        $meeting->id,
                                ]
                            ) }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="status"
                                value="Scheduled"
                            >

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Reopen / Reschedule
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FUTURE MEETING COMPONENTS --}}
    {{-- ========================================================= --}}

    {{-- ========================================================= --}}
    {{-- MEETING WORKSTREAMS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- ===================================================== --}}
        {{-- ATTENDEES --}}
        {{-- ===================================================== --}}

        <div class="col-md-6 col-xl-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h6 class="fw-semibold mb-1">
                                Attendees
                            </h6>

                            <div class="text-muted small">
                                Participants and attendance.
                            </div>

                        </div>


                        <span class="badge bg-primary fs-6">
                            {{ $meeting->attendees_count }}
                        </span>

                    </div>


                    <div class="mt-4 d-flex flex-wrap gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.attendees.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.attendees.create',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            + Add
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- AGENDA ITEMS --}}
        {{-- ===================================================== --}}

        <div class="col-md-6 col-xl-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h6 class="fw-semibold mb-1">
                                Agenda Items
                            </h6>

                            <div class="text-muted small">
                                Matters for discussion.
                            </div>

                        </div>


                        <span class="badge bg-info text-dark fs-6">
                            {{ $meeting->agenda_items_count }}
                        </span>

                    </div>


                    <div class="mt-4 d-flex flex-wrap gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.agenda-items.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.agenda-items.create',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            + Add
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DECISIONS --}}
        {{-- ===================================================== --}}

        <div class="col-md-6 col-xl-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h6 class="fw-semibold mb-1">
                                Decisions
                            </h6>

                            <div class="text-muted small">
                                Formal decisions and resolutions.
                            </div>

                        </div>


                        <span class="badge bg-success fs-6">
                            {{ $meeting->decisions_count }}
                        </span>

                    </div>


                    <div class="mt-4 d-flex flex-wrap gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.decisions.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.decisions.create',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            + Add
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTION ITEMS --}}
        {{-- ===================================================== --}}

        <div class="col-md-6 col-xl-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h6 class="fw-semibold mb-1">
                                Action Items
                            </h6>

                            <div class="text-muted small">
                                Responsibilities and commitments.
                            </div>

                        </div>


                        <span class="badge bg-warning text-dark fs-6">
                            {{ $meeting->action_items_count }}
                        </span>

                    </div>


                    <div class="mt-4 d-flex flex-wrap gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.action-items.index',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.governance-meetings.action-items.create',
                                [
                                    'project' => $project->id,
                                    'meeting' => $meeting->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            + Add
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REMARKS --}}
    {{-- ========================================================= --}}

    @if($meeting->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e($meeting->remarks)
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
                        Delete Meeting
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance-meetings.destroy',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this meeting?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Meeting
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection