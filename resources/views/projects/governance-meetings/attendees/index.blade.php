@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Attendees
            </div>

            <h3 class="mb-1">
                Meeting Attendees
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                ·

                {{ $meeting->committee_name }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.show',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Meeting
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance-meetings.attendees.create',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Attendee
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
    {{-- ERRORS --}}
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
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalAttendees =
            $attendees->count();

        $presentCount =
            $attendees
                ->where('attendance_status', 'Present')
                ->count();

        $absentCount =
            $attendees
                ->where('attendance_status', 'Absent')
                ->count();

        $apologiesCount =
            $attendees
                ->where('attendance_status', 'Apologies')
                ->count();

        $invitedCount =
            $attendees
                ->where('attendance_status', 'Invited')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Attendees
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalAttendees }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Present --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Present
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $presentCount }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Absent --}}

        <div class="col-md-3">

            <div class="card h-100 border-danger">

                <div class="card-body">

                    <div class="text-muted small">
                        Absent
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $absentCount }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Invited / Apologies --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Invited / Apologies
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $invitedCount + $apologiesCount }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MEETING INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Number
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->meeting_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Date
                    </div>

                    <div class="fw-semibold">

                        @if($meeting->meeting_date)

                            {{ $meeting->meeting_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Committee
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->committee_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="fw-semibold">

                        {{ $meeting->status }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ATTENDEE REGISTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Attendee Register
                    </strong>

                    <div class="text-muted small mt-1">
                        Record participants and their attendance status.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalAttendees }}

                    {{ $totalAttendees === 1
                        ? 'attendee'
                        : 'attendees'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($attendees->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3">
                                    #
                                </th>

                                <th>
                                    Attendee
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Organization
                                </th>

                                <th>
                                    Attendance
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Remarks
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($attendees as $index => $attendee)

                                @php

                                    $attendanceClass =
                                        match(
                                            $attendee->attendance_status
                                        ) {

                                            'Present'
                                                => 'bg-success',

                                            'Absent'
                                                => 'bg-danger',

                                            'Apologies'
                                                => 'bg-warning text-dark',

                                            'Invited'
                                                => 'bg-primary',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Serial --}}

                                    <td class="ps-3">

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Attendee --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $attendee->attendee_name }}

                                        </div>


                                        @if($attendee->user)

                                            <div class="text-muted small">

                                                System User:
                                                {{ $attendee->user->name }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Role --}}

                                    <td>

                                        @if($attendee->attendee_role)

                                            {{ $attendee->attendee_role }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Organization --}}

                                    <td>

                                        @if($attendee->organization)

                                            {{ $attendee->organization }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Attendance --}}

                                    <td>

                                        <span
                                            class="badge {{ $attendanceClass }}"
                                        >
                                            {{ $attendee->attendance_status }}
                                        </span>

                                    </td>


                                    {{-- Time --}}

                                    <td>

                                        @if($attendee->joined_at)

                                            <div class="small">

                                                <span class="text-muted">
                                                    In:
                                                </span>

                                                {{
                                                    \Illuminate\Support\Carbon::parse(
                                                        $attendee->joined_at
                                                    )->format('h:i A')
                                                }}

                                            </div>

                                        @endif


                                        @if($attendee->left_at)

                                            <div class="small">

                                                <span class="text-muted">
                                                    Out:
                                                </span>

                                                {{
                                                    \Illuminate\Support\Carbon::parse(
                                                        $attendee->left_at
                                                    )->format('h:i A')
                                                }}

                                            </div>

                                        @endif


                                        @if(
                                            !$attendee->joined_at &&
                                            !$attendee->left_at
                                        )

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Remarks --}}

                                    <td>

                                        @if($attendee->remarks)

                                            <span
                                                title="{{ $attendee->remarks }}"
                                            >
                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $attendee->remarks,
                                                        50
                                                    )
                                                }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            {{-- Present --}}

                                            @if(
                                                $attendee->attendance_status
                                                    !== 'Present'
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.governance-meetings.attendees.status',
                                                        [
                                                            'project' =>
                                                                $project->id,

                                                            'meeting' =>
                                                                $meeting->id,

                                                            'attendee' =>
                                                                $attendee->id,
                                                        ]
                                                    ) }}"
                                                >

                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="attendance_status"
                                                        value="Present"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Mark Present"
                                                    >
                                                        ✓
                                                    </button>

                                                </form>

                                            @endif


                                            {{-- Edit --}}

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.attendees.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'attendee' =>
                                                            $attendee->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>


                                            {{-- Delete --}}

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.governance-meetings.attendees.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'meeting' =>
                                                            $meeting->id,

                                                        'attendee' =>
                                                            $attendee->id,
                                                    ]
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to remove this attendee?'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

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
                        No Attendees
                    </h5>

                    <div class="text-muted mb-3">

                        No participants have been added to this
                        governance meeting yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.attendees.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Attendee
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ATTENDANCE LEGEND --}}
    {{-- ========================================================= --}}

    <div class="card mb-5">

        <div class="card-header">

            <strong>
                Attendance Status
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-2">

                    <span class="badge bg-primary">
                        Invited
                    </span>

                    <span class="text-muted small ms-2">
                        Expected participant
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-success">
                        Present
                    </span>

                    <span class="text-muted small ms-2">
                        Attended
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-danger">
                        Absent
                    </span>

                    <span class="text-muted small ms-2">
                        Did not attend
                    </span>

                </div>


                <div class="col-md-3 mb-2">

                    <span class="badge bg-warning text-dark">
                        Apologies
                    </span>

                    <span class="text-muted small ms-2">
                        Unable to attend
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection