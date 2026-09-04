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
                Safety Meetings
            </h3>

            <div class="text-muted">
                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.create',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                New Safety Meeting
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


    <div class="card">

        <div class="card-header">

            <strong>
                Safety Meeting Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $meetings->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($meetings->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Meeting
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Conducted By
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($meetings as $meeting)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $meeting->meeting_number }}
                                    </a>


                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit(
                                            $meeting->title,
                                            80
                                        ) }}

                                    </div>

                                </td>


                                <td>

                                    {{ $meeting->meeting_date
                                        ? $meeting->meeting_date->format('d-m-Y')
                                        : '—'
                                    }}


                                    @if($meeting->meeting_time)

                                        <div class="small text-muted">

                                            {{ \Carbon\Carbon::parse(
                                                $meeting->meeting_time
                                            )->format('h:i A') }}

                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $meeting->meeting_type }}
                                </td>


                                <td>
                                    {{ $meeting->location ?? '—' }}
                                </td>


                                <td>

                                    {{ $meeting->conducted_by_name
                                        ?? $meeting->conductedBy?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

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


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $meeting->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
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
                        No Safety Meetings Found
                    </h6>

                    <p class="text-muted mb-3">
                        No safety meeting or toolbox talk
                        has been created for this project yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.safety-meetings.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Create First Meeting
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection