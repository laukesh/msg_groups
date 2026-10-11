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
                Participants / Attendance
            </h3>

            <div class="text-muted">
                {{ $meeting->title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.show',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Meeting
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.participants.create',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-person-plus me-1"></i>
                Add Participant
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


    {{-- Attendance Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Participants
                    </div>

                    <h4 class="mb-0">
                        {{ $participants->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Present
                    </div>

                    <h4 class="mb-0 text-success">

                        {{ $participants
                            ->where('attended', true)
                            ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Absent
                    </div>

                    <h4 class="mb-0 text-danger">

                        {{ $participants
                            ->where('attended', false)
                            ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                Attendance Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $participants->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($participants->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Participant
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Employee Code
                            </th>

                            <th>
                                Company
                            </th>

                            <th>
                                Designation
                            </th>

                            <th>
                                Attendance
                            </th>

                            <th>
                                Time
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($participants as $participant)

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
                                    {{ $participant->employee_code ?? '—' }}
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


                                <td>

                                    @if($participant->attendance_time)

                                        {{ \Carbon\Carbon::parse(
                                            $participant->attendance_time
                                        )->format('h:i A') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.participants.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                                'participant' => $participant,
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

                    <i
                        class="bi bi-people"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Participants Found
                    </h6>

                    <p class="text-muted mb-3">
                        Add participants to maintain the
                        safety meeting attendance register.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.safety-meetings.participants.create',
                            [
                                'project' => $project,
                                'meeting' => $meeting,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-plus me-1"></i>
                        Add First Participant
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection