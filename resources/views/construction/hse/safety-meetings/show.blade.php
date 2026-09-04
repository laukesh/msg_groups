@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Meeting:
                <strong>
                    {{ $meeting->meeting_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                {{ $meeting->title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.edit',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Meetings
            </a>

        </div>

    </div>


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


    @php

        $statusClass =
            match($meeting->status) {

                'Draft' =>
                    'bg-secondary',

                'Scheduled' =>
                    'bg-warning text-dark',

                'Completed' =>
                    'bg-success',

                'Cancelled' =>
                    'bg-danger',

                default =>
                    'bg-secondary',

            };

    @endphp


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Safety Meeting
                    </div>

                    <h4 class="mb-2">
                        {{ $meeting->meeting_number }}
                    </h4>

                    <div class="text-muted">
                        {{ $meeting->meeting_type }}
                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $meeting->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MEETING DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Meeting Details</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Number
                    </div>

                    <strong>
                        {{ $meeting->meeting_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Date
                    </div>

                    <strong>

                        {{ $meeting->meeting_date
                            ? $meeting->meeting_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Time
                    </div>

                    <strong>

                        @if($meeting->meeting_time)

                            {{ \Carbon\Carbon::parse(
                                $meeting->meeting_time
                            )->format('h:i A') }}

                        @else

                            —

                        @endif

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Type
                    </div>

                    <strong>
                        {{ $meeting->meeting_type }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Location
                    </div>

                    <strong>
                        {{ $meeting->location ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Conducted By
                    </div>

                    <strong>

                        {{ $meeting->conducted_by_name
                            ?? $meeting->conductedBy?->name
                            ?? '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Next Meeting Date
                    </div>

                    <strong>

                        {{ $meeting->next_meeting_date
                            ? $meeting->next_meeting_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OBJECTIVE
    ========================================================== --}}

    @if($meeting->meeting_objective)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Meeting Objective</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->meeting_objective }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        AGENDA
    ========================================================== --}}

    @if($meeting->agenda)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Agenda</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->agenda }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        DISCUSSION
    ========================================================== --}}

    @if($meeting->discussion_points)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Discussion Points</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->discussion_points }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        SAFETY INSTRUCTIONS
    ========================================================== --}}

    @if($meeting->safety_instructions)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Safety Instructions</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->safety_instructions }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        ACTIONS / COMMITMENTS
    ========================================================== --}}

    @if($meeting->actions_commitments)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Actions / Commitments</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->actions_commitments }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        PARTICIPANTS
    ========================================================== --}}

    {{-- Participants / Attendance --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Participants / Attendance
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $meeting->participants->count() }}
                </span>

            </div>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.participants.index',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-people me-1"></i>
                Manage Participants
            </a>

        </div>


        <div class="card-body">

            @if($meeting->participants->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Name</th>

                            <th>Type</th>

                            <th>Company</th>

                            <th>Designation</th>

                            <th>Attendance</th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $meeting->participants->take(10)
                            as $participant
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.participants.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                                'participant' => $participant,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $participant->participant_name }}
                                    </a>

                                </td>

                                <td>
                                    {{ $participant->participant_type ?? '—' }}
                                </td>

                                <td>
                                    {{ $participant->company_name ?? '—' }}
                                </td>

                                <td>
                                    {{ $participant->designation ?? '—' }}
                                </td>

                                <td>

                                    @if($participant->attended)

                                        <span class="badge bg-success">
                                            Present
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Absent
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                @if($meeting->participants->count() > 10)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.safety-meetings.participants.index',
                                [
                                    'project' => $project,
                                    'meeting' => $meeting,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All
                        </a>

                    </div>

                @endif

            @else

                <div class="text-center py-4">

                    <i
                        class="bi bi-people"
                        style="font-size:36px;"
                    ></i>

                    <h6 class="mt-2">
                        No Participants Added
                    </h6>

                    <p class="text-muted">
                        Add participants to maintain the attendance
                        register for this safety meeting.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.safety-meetings.participants.create',
                            [
                                'project' => $project,
                                'meeting' => $meeting,
                            ]
                        ) }}"
                        class="btn btn-primary btn-sm"
                    >
                        <i class="bi bi-person-plus me-1"></i>
                        Add Participant
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- Documents --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Documents
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $meeting->documents->count() }}
                </span>

            </div>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.documents.index',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-folder2-open me-1"></i>
                Manage Documents
            </a>

        </div>


        <div class="card-body">

            @if($meeting->documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th class="text-end">Action</th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $meeting->documents->take(10)
                            as $document
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.documents.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $document->document_name }}
                                    </a>

                                    @if($document->original_file_name)

                                        <div class="small text-muted">
                                            {{ $document->original_file_name }}
                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $document->document_type ?? '—' }}
                                </td>


                                <td>
                                    {{ $document->file_size_formatted }}
                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.documents.download',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-success"
                                    >
                                        <i class="bi bi-download"></i>
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                @if($meeting->documents->count() > 10)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.safety-meetings.documents.index',
                                [
                                    'project' => $project,
                                    'meeting' => $meeting,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All Documents
                        </a>

                    </div>

                @endif

            @else

                <div class="text-center py-4">

                    <i
                        class="bi bi-file-earmark-text"
                        style="font-size:36px;"
                    ></i>

                    <h6 class="mt-2">
                        No Documents Added
                    </h6>

                    <p class="text-muted">
                        Upload meeting minutes, attendance sheets,
                        photographs or other supporting documents.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.safety-meetings.documents.create',
                            [
                                'project' => $project,
                                'meeting' => $meeting,
                            ]
                        ) }}"
                        class="btn btn-primary btn-sm"
                    >
                        <i class="bi bi-upload me-1"></i>
                        Upload Document
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($meeting->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $meeting->remarks }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        RECORD INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Record Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $meeting->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $meeting->created_at
                            ? $meeting->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $meeting->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $meeting->updated_at
                            ? $meeting->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    @if($meeting->status !== 'Completed')

        <div class="d-flex justify-content-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.safety-meetings.destroy',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this safety meeting?'
                );"
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="btn btn-outline-danger"
                >

                    <i class="bi bi-trash me-1"></i>

                    Delete Meeting

                </button>

            </form>

        </div>

    @endif

</div>

@endsection