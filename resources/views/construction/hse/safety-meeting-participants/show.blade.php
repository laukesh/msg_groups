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
                Participant Details
            </h3>

            <div class="text-muted">
                {{ $participant->participant_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.participants.edit',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                        'participant' => $participant,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.participants.index',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Participants
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.show',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Meeting
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Participant Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Participant Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Participant Name
                    </div>

                    <strong>
                        {{ $participant->participant_name }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Participant Type
                    </div>

                    <strong>
                        {{ $participant->participant_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Employee Code
                    </div>

                    <strong>
                        {{ $participant->employee_code ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Company
                    </div>

                    <strong>
                        {{ $participant->company_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Designation
                    </div>

                    <strong>
                        {{ $participant->designation ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Phone
                    </div>

                    <strong>
                        {{ $participant->phone ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Email
                    </div>

                    <strong>
                        {{ $participant->email ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Attendance --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Attendance</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Attendance Status
                    </div>

                    @if($participant->attended)

                        <span class="badge bg-success fs-6">
                            Present
                        </span>

                    @else

                        <span class="badge bg-danger fs-6">
                            Absent
                        </span>

                    @endif

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Attendance Time
                    </div>

                    <strong>

                        @if($participant->attendance_time)

                            {{ \Carbon\Carbon::parse(
                                $participant->attendance_time
                            )->format('h:i A') }}

                        @else

                            —

                        @endif

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($participant->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $participant->remarks }}
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
                        {{ $participant->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $participant->created_at
                            ? $participant->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $participant->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $participant->updated_at
                            ? $participant->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.safety-meetings.participants.destroy',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                    'participant' => $participant,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this participant?'
            );"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete Participant
            </button>

        </form>

    </div>

</div>

@endsection