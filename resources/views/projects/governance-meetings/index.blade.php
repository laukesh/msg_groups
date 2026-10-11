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
                Governance Meetings
            </h3>

            <div class="text-muted">

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
                    'admin.projects.governance-meetings.create',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Schedule Meeting
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
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalMeetings =
            $meetings->count();

        $scheduledMeetings =
            $meetings
                ->where('status', 'Scheduled')
                ->count();

        $heldMeetings =
            $meetings
                ->where('status', 'Held')
                ->count();

        $postponedMeetings =
            $meetings
                ->where('status', 'Postponed')
                ->count();

        $cancelledMeetings =
            $meetings
                ->where('status', 'Cancelled')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Meetings
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalMeetings }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Scheduled --}}

        <div class="col-md-3">

            <div class="card h-100 border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Scheduled
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $scheduledMeetings }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Held --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Held
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $heldMeetings }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Postponed / Cancelled
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $postponedMeetings + $cancelledMeetings }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MEETING REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Meeting Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Governance committee and project steering meetings.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalMeetings }}

                    {{ $totalMeetings === 1
                        ? 'meeting'
                        : 'meetings'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($meetings->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3">
                                    Meeting No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Committee
                                </th>

                                <th>
                                    Meeting Type
                                </th>

                                <th>
                                    Mode
                                </th>

                                <th>
                                    Chairperson
                                </th>

                                <th>
                                    Governance
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

                            @foreach($meetings as $meeting)

                                @php

                                    $statusClass =
                                        match(
                                            $meeting->status
                                        ) {

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
                                        match(
                                            $meeting->meeting_mode
                                        ) {

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


                                <tr>

                                    {{-- Meeting Number --}}

                                    <td class="ps-3">

                                        <a
                                            href="{{ route(
                                                'admin.projects.governance-meetings.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'meeting' =>
                                                        $meeting->id,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $meeting->meeting_number }}

                                        </a>


                                        @if(
                                            $meeting->reference_number
                                        )

                                            <div class="text-muted small">

                                                Ref:
                                                {{ $meeting->reference_number }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Date / Time --}}

                                    <td>

                                        <div class="fw-semibold">

                                            @if($meeting->meeting_date)

                                                {{ $meeting->meeting_date->format('d-m-Y') }}

                                            @else

                                                —

                                            @endif

                                        </div>


                                        @if(
                                            $meeting->start_time
                                        )

                                            <div class="text-muted small">

                                                {{
                                                    \Illuminate\Support\Carbon::parse(
                                                        $meeting->start_time
                                                    )->format('h:i A')
                                                }}


                                                @if(
                                                    $meeting->end_time
                                                )

                                                    –
                                                    {{
                                                        \Illuminate\Support\Carbon::parse(
                                                            $meeting->end_time
                                                        )->format('h:i A')
                                                    }}

                                                @endif

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Committee --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $meeting->committee_name }}

                                        </div>


                                        @if($meeting->location)

                                            <div class="text-muted small">

                                                {{ $meeting->location }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Meeting Type --}}

                                    <td>

                                        {{ $meeting->meeting_type }}

                                    </td>


                                    {{-- Meeting Mode --}}

                                    <td>

                                        <span
                                            class="badge {{ $modeClass }}"
                                        >
                                            {{ $meeting->meeting_mode }}
                                        </span>

                                    </td>


                                    {{-- Chairperson --}}

                                    <td>

                                        @if($meeting->chairperson)

                                            <div class="fw-semibold">

                                                {{ $meeting->chairperson->name }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Governance --}}

                                    <td>

                                        @if($meeting->governance)

                                            <div class="fw-semibold">

                                                {{
                                                    $meeting
                                                        ->governance
                                                        ->governance_number
                                                }}

                                            </div>

                                            <div class="text-muted small">

                                                {{
                                                    $meeting
                                                        ->governance
                                                        ->title
                                                }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Not linked
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $meeting->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,
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

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <h5>
                        No Governance Meetings
                    </h5>

                    <div class="text-muted mb-3">

                        No governance committee meetings have been
                        scheduled for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Schedule First Meeting
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mt-4 mb-5">

        <div class="card-header">

            <strong>
                Governance Meeting Lifecycle
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Scheduled
                    </div>

                    <div class="text-muted small">
                        Meeting has been planned and scheduled.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Held
                    </div>

                    <div class="text-muted small">
                        Meeting took place and minutes were recorded.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Postponed
                    </div>

                    <div class="text-muted small">
                        Meeting was deferred and can be rescheduled.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        Cancelled
                    </div>

                    <div class="text-muted small">
                        Meeting was cancelled and will not take place.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection