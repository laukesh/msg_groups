@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Toolbox Talk Details
            </h3>

            <div class="text-muted">

                {{ $toolboxTalk->toolbox_talk_number }}

                <span class="mx-1">•</span>

                {{ $toolboxTalk->title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.edit',
                    [
                        'project' => $project,
                        'toolboxTalk' => $toolboxTalk,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Toolbox Talks
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Basic Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Toolbox Talk Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Toolbox Talk Number
                    </div>

                    <strong>
                        {{ $toolboxTalk->toolbox_talk_number }}
                    </strong>

                </div>


                <div class="col-md-8">

                    <div class="text-muted small">
                        Title
                    </div>

                    <strong>
                        {{ $toolboxTalk->title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Talk Date
                    </div>

                    <strong>

                        {{ $toolboxTalk->talk_date
                            ? $toolboxTalk->talk_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Talk Time
                    </div>

                    <strong>
                        {{ $toolboxTalk->talk_time ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Location
                    </div>

                    <strong>
                        {{ $toolboxTalk->location ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Topic
                    </div>

                    <strong>
                        {{ $toolboxTalk->topic ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Conducted By
                    </div>

                    <strong>

                        {{ $toolboxTalk->conductedBy?->name
                            ?? $toolboxTalk->conducted_by_name
                            ?? '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php

                        $statusClass = match(
                            $toolboxTalk->status
                        ) {

                            'Draft' =>
                                'bg-secondary',

                            'Completed' =>
                                'bg-success',

                            'Cancelled' =>
                                'bg-danger',

                            default =>
                                'bg-secondary',

                        };

                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ $toolboxTalk->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Objectives --}}

    @if($toolboxTalk->objectives)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Objectives</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $toolboxTalk->objectives }}
            </div>

        </div>

    @endif


    {{-- Discussion and Safety --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Discussion Points</strong>
                </div>

                <div
                    class="card-body"
                    style="white-space:pre-line;"
                >
                    {{ $toolboxTalk->discussion_points ?? '—' }}
                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Safety Instructions</strong>
                </div>

                <div
                    class="card-body"
                    style="white-space:pre-line;"
                >
                    {{ $toolboxTalk->safety_instructions ?? '—' }}
                </div>

            </div>

        </div>

    </div>


    {{-- Hazards and Precautions --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Hazards Discussed</strong>
                </div>

                <div
                    class="card-body"
                    style="white-space:pre-line;"
                >
                    {{ $toolboxTalk->hazards_discussed ?? '—' }}
                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Precautions / Control Measures</strong>
                </div>

                <div
                    class="card-body"
                    style="white-space:pre-line;"
                >
                    {{ $toolboxTalk->precautions ?? '—' }}
                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($toolboxTalk->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $toolboxTalk->remarks }}
            </div>

        </div>

    @endif


    {{-- Record Information --}}

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
                        {{ $toolboxTalk->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $toolboxTalk->created_at
                            ? $toolboxTalk->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $toolboxTalk->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $toolboxTalk->updated_at
                            ? $toolboxTalk->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- Participants / Attendance --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <strong>
                    Participants / Attendance
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $toolboxTalk->participants->count() }}
                </span>
            </div>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.participants.index',
                    [
                        'project' => $project,
                        'toolboxTalk' => $toolboxTalk,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-people me-1"></i>
                Manage Attendance
            </a>

        </div>


        <div class="card-body">

            @if($toolboxTalk->participants->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Participant</th>
                            <th>Type</th>
                            <th>Company</th>
                            <th>Designation</th>
                            <th>Attendance</th>
                        </tr>

                        </thead>

                        <tbody>

                        @foreach(
                            $toolboxTalk->participants->take(10)
                            as $participant
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.participants.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
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


                @if($toolboxTalk->participants->count() > 10)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.toolbox-talks.participants.index',
                                [
                                    'project' => $project,
                                    'toolboxTalk' => $toolboxTalk,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            View All Participants
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

                    <p class="text-muted mb-3">
                        Add employees, contractors and other attendees
                        to maintain the attendance register.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.toolbox-talks.participants.create',
                            [
                                'project' => $project,
                                'toolboxTalk' => $toolboxTalk,
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
                    Documents / Attachments
                </strong>

                <span class="badge bg-primary ms-2">
                    {{ $toolboxTalk->documents->count() }}
                </span>
            </div>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.documents.index',
                    [
                        'project' => $project,
                        'toolboxTalk' => $toolboxTalk,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-files me-1"></i>
                Manage Documents
            </a>

        </div>


        <div class="card-body">

            @if($toolboxTalk->documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-sm table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>File Size</th>
                            <th class="text-end">Action</th>
                        </tr>

                        </thead>

                        <tbody>

                        @foreach(
                            $toolboxTalk->documents->take(10)
                            as $document
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.documents.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $document->document_name }}
                                    </a>

                                    <div class="small text-muted">

                                        {{ $document->document_number }}

                                    </div>

                                </td>

                                <td>
                                    {{ $document->document_type ?? '—' }}
                                </td>

                                <td>

                                    {{ $document->document_date
                                        ? $document->document_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>

                                <td>
                                    {{ $document->file_size_formatted }}
                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.documents.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.documents.download',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-success"
                                    >
                                        <i class="fa fa-download"></i>
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                @if($toolboxTalk->documents->count() > 10)

                    <div class="text-end mt-3">

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.toolbox-talks.documents.index',
                                [
                                    'project' => $project,
                                    'toolboxTalk' => $toolboxTalk,
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
                        No Documents Attached
                    </h6>

                    <p class="text-muted mb-3">
                        Upload attendance sheets, presentations,
                        photos or other toolbox talk documents.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.toolbox-talks.documents.create',
                            [
                                'project' => $project,
                                'toolboxTalk' => $toolboxTalk,
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


    {{-- Delete --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.toolbox-talks.destroy',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this toolbox talk?'
            );"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete Toolbox Talk
            </button>

        </form>

    </div>

</div>

@endsection