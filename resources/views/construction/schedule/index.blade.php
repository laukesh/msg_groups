@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Project Schedule
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.schedule.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Add Activity
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


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Not Started
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $summary['not_started'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $summary['in_progress'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $summary['completed'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Delayed
                    </div>

                    <div class="fs-3 fw-semibold text-danger">
                        {{ $summary['delayed'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Schedule Register --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Schedule Activity Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($activities->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Activity
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Planned
                                </th>

                                <th>
                                    Duration
                                </th>

                                <th>
                                    Progress
                                </th>

                                <th>
                                    Responsible
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

                            @foreach(
                                $activities
                                as $activity
                            )

                                @php

                                    $statusClass =
                                        match(
                                            $activity->status
                                        ) {

                                            'Completed' =>
                                                'bg-success',

                                            'In Progress' =>
                                                'bg-primary',

                                            'Delayed' =>
                                                'bg-danger',

                                            'On Hold' =>
                                                'bg-warning text-dark',

                                            'Cancelled' =>
                                                'bg-dark',

                                            default =>
                                                'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.schedule.show',
                                                [
                                                    'project' => $project,
                                                    'activity' => $activity,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >
                                            {{ $activity->activity_code }}
                                        </a>

                                        <div class="small text-muted">

                                            {{ $activity->activity_name }}

                                            @if($activity->phase)
                                                · {{ $activity->phase }}
                                            @endif

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $activity
                                                ->workOrder
                                                ?->work_order_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <div class="small">

                                            {{
                                                $activity
                                                    ->planned_start_date
                                                    ?->format('d-m-Y')
                                                ?? '—'
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            to

                                            {{
                                                $activity
                                                    ->planned_finish_date
                                                    ?->format('d-m-Y')
                                                ?? '—'
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        @if($activity->duration_days)

                                            {{
                                                $activity->duration_days
                                            }}
                                            days

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td style="min-width: 120px;">

                                        <div class="small fw-semibold">

                                            {{
                                                number_format(
                                                    (float)
                                                    $activity
                                                        ->progress_percentage,
                                                    2
                                                )
                                            }}%

                                        </div>


                                        <div
                                            class="progress"
                                            style="height: 6px;"
                                        >

                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                style="
                                                    width:
                                                    {{
                                                        min(
                                                            100,
                                                            max(
                                                                0,
                                                                (float)
                                                                $activity
                                                                    ->progress_percentage
                                                            )
                                                        )
                                                    }}%;
                                                "
                                            ></div>

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $activity
                                                ->responsibleUser
                                                ?->name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $activity->status }}
                                        </span>

                                        @if(
                                            $activity->delay_days > 0
                                        )

                                            <div class="small text-danger mt-1">

                                                +{{ $activity->delay_days }}
                                                days

                                            </div>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        <div
                                            class="
                                                d-flex
                                                justify-content-end
                                                gap-1
                                            "
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.construction.schedule.show',
                                                    [
                                                        'project' => $project,
                                                        'activity' => $activity,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if(
                                                $activity->canEdit()
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.construction.schedule.edit',
                                                        [
                                                            'project' => $project,
                                                            'activity' => $activity,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No schedule activities found.
                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.construction.schedule.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Add First Activity
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection