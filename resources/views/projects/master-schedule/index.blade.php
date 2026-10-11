@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Master Schedule
            </div>

            <h3 class="mb-1">
                Master Schedule
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>

            @if(!$masterSchedule)

                <a
                    href="{{ route(
                        'admin.projects.master-schedule.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Master Schedule
                </a>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
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


    @if(session('info'))

        <div class="alert alert-info">
            {{ session('info') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Project Context --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Project Context</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Number
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Status
                    </div>

                    <div class="mt-1">

                        @if($project->project_status === 'Active')

                            <span class="badge bg-success">
                                Active
                            </span>

                        @elseif($project->project_status === 'On Hold')

                            <span class="badge bg-warning text-dark">
                                On Hold
                            </span>

                        @elseif($project->project_status === 'Cancelled')

                            <span class="badge bg-danger">
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $project->project_status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    @if($masterSchedule)

        {{-- ===================================================== --}}
        {{-- Schedule Header --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            {{ $masterSchedule->title }}
                        </strong>

                        <div class="text-muted small mt-1">

                            {{ $masterSchedule->schedule_number }}

                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.master-schedule.show',
                                [
                                    'project' =>
                                        $project->id,

                                    'masterSchedule' =>
                                        $masterSchedule->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View
                        </a>


                        <a
                            href="{{ route(
                                'admin.projects.master-schedule.edit',
                                [
                                    'project' =>
                                        $project->id,

                                    'masterSchedule' =>
                                        $masterSchedule->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            Edit
                        </a>


                        @if($masterSchedule->status === 'Draft')

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.master-schedule.destroy',
                                    [
                                        'project' =>
                                            $project->id,

                                        'masterSchedule' =>
                                            $masterSchedule->id,
                                    ]
                                ) }}"
                                onsubmit="return confirm('Are you sure you want to delete this Master Schedule?');"
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

                        @endif

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Status --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Status
                        </div>

                        <div class="mt-1">

                            @switch($masterSchedule->status)

                                @case('Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                    @break

                                @case('Submitted')

                                    <span class="badge bg-info text-dark">
                                        Submitted
                                    </span>

                                    @break

                                @case('Under Review')

                                    <span class="badge bg-warning text-dark">
                                        Under Review
                                    </span>

                                    @break

                                @case('Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $masterSchedule->status }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Baseline Start --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Baseline Start
                        </div>

                        <div class="fw-semibold mt-1">

                            {{
                                $masterSchedule->baseline_start_date
                                    ? $masterSchedule
                                        ->baseline_start_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>


                    {{-- Baseline Completion --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Baseline Completion
                        </div>

                        <div class="fw-semibold mt-1">

                            {{
                                $masterSchedule->baseline_completion_date
                                    ? $masterSchedule
                                        ->baseline_completion_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>


                    {{-- Activity Count --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Activities
                        </div>

                        <div class="fs-5 fw-semibold mt-1">

                            {{
                                $masterSchedule
                                    ->activities
                                    ->count()
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Progress --}}
        {{-- ===================================================== --}}

        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Planned Progress
                                </div>

                                <div class="fs-4 fw-semibold">
                                    {{ number_format(
                                        $masterSchedule->planned_progress,
                                        2
                                    ) }}%
                                </div>

                            </div>

                        </div>


                        <div class="progress mt-3"
                             style="height: 10px;">

                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ min(
                                    100,
                                    max(
                                        0,
                                        $masterSchedule
                                            ->planned_progress
                                    )
                                ) }}%;"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Actual Progress
                                </div>

                                <div class="fs-4 fw-semibold">
                                    {{ number_format(
                                        $masterSchedule->actual_progress,
                                        2
                                    ) }}%
                                </div>

                            </div>

                        </div>


                        <div class="progress mt-3"
                             style="height: 10px;">

                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ min(
                                    100,
                                    max(
                                        0,
                                        $masterSchedule
                                            ->actual_progress
                                    )
                                ) }}%;"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Current Dates --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Current Schedule</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Current Start
                        </div>

                        <div class="fw-semibold">

                            {{
                                $masterSchedule->current_start_date
                                    ? $masterSchedule
                                        ->current_start_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Current Completion
                        </div>

                        <div class="fw-semibold">

                            {{
                                $masterSchedule->current_completion_date
                                    ? $masterSchedule
                                        ->current_completion_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Baseline Date
                        </div>

                        <div class="fw-semibold">

                            {{
                                $masterSchedule->baseline_date
                                    ? $masterSchedule
                                        ->baseline_date
                                        ->format('d M Y')
                                    : '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Activities --}}
        {{-- ===================================================== --}}

        <div class="card mb-5">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        Schedule Activities
                    </strong>

                    <span class="badge bg-secondary">

                        {{
                            $masterSchedule
                                ->activities
                                ->count()
                        }}

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if(
                    $masterSchedule
                        ->activities
                        ->count()
                )

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered mb-0 align-middle">

                            <thead>

                                <tr>

                                    <th style="width: 60px;">
                                        #
                                    </th>

                                    <th style="width: 120px;">
                                        Code
                                    </th>

                                    <th>
                                        Activity
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Planned Start
                                    </th>

                                    <th>
                                        Planned End
                                    </th>

                                    <th>
                                        Progress
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Milestone
                                    </th>

                                    <th style="width: 150px;">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $masterSchedule->activities
                                    as $activity
                                )

                                    <tr>

                                        <td>
                                            {{ $activity->sequence }}
                                        </td>


                                        <td>

                                            <span class="fw-semibold">
                                                {{ $activity->activity_code }}
                                            </span>

                                        </td>


                                        <td>

                                            <div
                                                class="fw-semibold"
                                                style="
                                                    padding-left:
                                                    {{ $activity->parent_activity_id
                                                        ? '20px'
                                                        : '0'
                                                    }};
                                                "
                                            >

                                                @if(
                                                    $activity->parent_activity_id
                                                )

                                                    <span class="text-muted">
                                                        ↳
                                                    </span>

                                                @endif

                                                {{ $activity->activity_name }}

                                            </div>

                                        </td>


                                        <td>
                                            {{ $activity->activity_type ?? '-' }}
                                        </td>


                                        <td>

                                            {{
                                                $activity->planned_start_date
                                                    ? $activity
                                                        ->planned_start_date
                                                        ->format('d M Y')
                                                    : '-'
                                            }}

                                        </td>


                                        <td>

                                            {{
                                                $activity->planned_end_date
                                                    ? $activity
                                                        ->planned_end_date
                                                        ->format('d M Y')
                                                    : '-'
                                            }}

                                        </td>


                                        <td style="min-width: 130px;">

                                            <div class="small mb-1">

                                                {{
                                                    number_format(
                                                        $activity->actual_progress,
                                                        1
                                                    )
                                                }}%

                                            </div>

                                            <div
                                                class="progress"
                                                style="height: 7px;"
                                            >

                                                <div
                                                    class="progress-bar"
                                                    role="progressbar"
                                                    style="width: {{ min(
                                                        100,
                                                        max(
                                                            0,
                                                            $activity
                                                                ->actual_progress
                                                        )
                                                    ) }}%;"
                                                ></div>

                                            </div>

                                        </td>


                                        <td>

                                            @switch($activity->status)

                                                @case('Completed')

                                                    <span class="badge bg-success">
                                                        Completed
                                                    </span>

                                                    @break

                                                @case('In Progress')

                                                    <span class="badge bg-primary">
                                                        In Progress
                                                    </span>

                                                    @break

                                                @case('Delayed')

                                                    <span class="badge bg-danger">
                                                        Delayed
                                                    </span>

                                                    @break

                                                @case('On Hold')

                                                    <span class="badge bg-warning text-dark">
                                                        On Hold
                                                    </span>

                                                    @break

                                                @default

                                                    <span class="badge bg-secondary">
                                                        {{ $activity->status }}
                                                    </span>

                                            @endswitch

                                        </td>


                                        <td>

                                            @if($activity->is_milestone)

                                                <span class="badge bg-info text-dark">
                                                    Milestone
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <div class="d-flex gap-1">

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.master-schedule.activities.edit',
                                                        [
                                                            'project' =>
                                                                $project->id,

                                                            'masterSchedule' =>
                                                                $masterSchedule->id,

                                                            'activity' =>
                                                                $activity->id,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.master-schedule.activities.destroy',
                                                        [
                                                            'project' =>
                                                                $project->id,

                                                            'masterSchedule' =>
                                                                $masterSchedule->id,

                                                            'activity' =>
                                                                $activity->id,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm('Delete this activity?');"
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

                    <div class="text-center py-5">

                        <h6>
                            No Schedule Activities
                        </h6>

                        <p class="text-muted mb-0">
                            Activities have not been added to this
                            Master Schedule yet.
                        </p>

                    </div>

                @endif

            </div>

        </div>


    @else

        {{-- ===================================================== --}}
        {{-- No Master Schedule --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-body text-center py-5">

                <h5>
                    Master Schedule Not Created
                </h5>

                <p class="text-muted mb-4">

                    This project does not have a Master Schedule yet.

                </p>


                <a
                    href="{{ route(
                        'admin.projects.master-schedule.create',
                        ['project' => $project->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Master Schedule
                </a>

            </div>

        </div>

    @endif

</div>

@endsection