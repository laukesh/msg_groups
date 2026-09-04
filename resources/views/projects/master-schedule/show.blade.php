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
                {{ $masterSchedule->title }}
            </h3>

            <div class="text-muted">
                {{ $masterSchedule->schedule_number }}
                ·
                {{ $project->project_name }}
                ·
                {{ $project->project_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.master-schedule.edit',
                    [
                        'project' => $project->id,
                        'masterSchedule' => $masterSchedule->id,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Edit Schedule
            </a>


            <a
                href="{{ route(
                    'admin.projects.master-schedule.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Schedule
            </a>

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
    {{-- Schedule Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Status --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Schedule Status
                    </div>

                    <div class="mt-2">

                        @switch($masterSchedule->status)

                            @case('Approved')

                                <span class="badge bg-success fs-6">
                                    Approved
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-info text-dark fs-6">
                                    Submitted
                                </span>

                                @break

                            @case('Under Review')

                                <span class="badge bg-warning text-dark fs-6">
                                    Under Review
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary fs-6">
                                    {{ $masterSchedule->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>


        {{-- Activities --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Activities
                    </div>

                    <div class="fs-4 fw-semibold mt-1">
                        {{ $masterSchedule->activities->count() }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Planned Progress --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Planned Progress
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ number_format(
                            $masterSchedule->planned_progress,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>


        {{-- Actual Progress --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Actual Progress
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ number_format(
                            $masterSchedule->actual_progress,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Schedule Dates --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Schedule Dates</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

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


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Start
                    </div>

                    <div class="fw-semibold mt-1">

                        {{
                            $masterSchedule->current_start_date
                                ? $masterSchedule
                                    ->current_start_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Completion
                    </div>

                    <div class="fw-semibold mt-1">

                        {{
                            $masterSchedule->current_completion_date
                                ? $masterSchedule
                                    ->current_completion_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Progress Comparison --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Schedule Progress</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Planned
                        </span>

                        <strong>
                            {{ number_format(
                                $masterSchedule->planned_progress,
                                2
                            ) }}%
                        </strong>

                    </div>


                    <div
                        class="progress"
                        style="height: 12px;"
                    >

                        <div
                            class="progress-bar"
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


                <div class="col-md-6">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Actual
                        </span>

                        <strong>
                            {{ number_format(
                                $masterSchedule->actual_progress,
                                2
                            ) }}%
                        </strong>

                    </div>


                    <div
                        class="progress"
                        style="height: 12px;"
                    >

                        <div
                            class="progress-bar"
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


    {{-- ========================================================= --}}
    {{-- Activities Header --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Schedule Activities
                    </strong>

                    <div class="text-muted small">
                        Project execution activities and milestones
                    </div>

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- ================================================= --}}
            {{-- Add Activity --}}
            {{-- ================================================= --}}

            <div class="border rounded p-3 mb-4">

                <h6 class="mb-3">
                    Add Schedule Activity
                </h6>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.master-schedule.activities.store',
                        [
                            'project' =>
                                $project->id,

                            'masterSchedule' =>
                                $masterSchedule->id,
                        ]
                    ) }}"
                >

                    @csrf


                    <div class="row">

                        {{-- Code --}}

                        <div class="col-md-2 mb-3">

                            <label
                                for="activity_code"
                                class="form-label"
                            >
                                Activity Code
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="activity_code"
                                id="activity_code"
                                class="form-control"
                                value="{{ old('activity_code') }}"
                                placeholder="1.1"
                                required
                            >

                        </div>


                        {{-- Activity Name --}}

                        <div class="col-md-4 mb-3">

                            <label
                                for="activity_name"
                                class="form-label"
                            >
                                Activity Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="activity_name"
                                id="activity_name"
                                class="form-control"
                                value="{{ old('activity_name') }}"
                                placeholder="Activity name"
                                required
                            >

                        </div>


                        {{-- Parent --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="parent_activity_id"
                                class="form-label"
                            >
                                Parent Activity
                            </label>

                            <select
                                name="parent_activity_id"
                                id="parent_activity_id"
                                class="form-select"
                            >

                                <option value="">
                                    -- Top Level --
                                </option>

                                @foreach(
                                    $masterSchedule->activities
                                    as $parentActivity
                                )

                                    <option
                                        value="{{ $parentActivity->id }}"
                                        {{ old(
                                            'parent_activity_id'
                                        ) == $parentActivity->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $parentActivity->activity_code }}
                                        -
                                        {{ $parentActivity->activity_name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Type --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="activity_type"
                                class="form-label"
                            >
                                Activity Type
                            </label>

                            <select
                                name="activity_type"
                                id="activity_type"
                                class="form-select"
                            >

                                <option value="">
                                    -- Select --
                                </option>

                                @foreach([
                                    'Planning',
                                    'Design',
                                    'Approval',
                                    'Procurement',
                                    'Construction',
                                    'Testing',
                                    'Commissioning',
                                    'Handover',
                                    'Other'
                                ] as $type)

                                    <option
                                        value="{{ $type }}"
                                        {{ old(
                                            'activity_type'
                                        ) === $type
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Planned Start --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="planned_start_date"
                                class="form-label"
                            >
                                Planned Start
                            </label>

                            <input
                                type="date"
                                name="planned_start_date"
                                id="planned_start_date"
                                class="form-control"
                                value="{{ old(
                                    'planned_start_date'
                                ) }}"
                            >

                        </div>


                        {{-- Planned End --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="planned_end_date"
                                class="form-label"
                            >
                                Planned End
                            </label>

                            <input
                                type="date"
                                name="planned_end_date"
                                id="planned_end_date"
                                class="form-control"
                                value="{{ old(
                                    'planned_end_date'
                                ) }}"
                            >

                        </div>


                        {{-- Sequence --}}

                        <div class="col-md-2 mb-3">

                            <label
                                for="sequence"
                                class="form-label"
                            >
                                Sequence
                            </label>

                            <input
                                type="number"
                                name="sequence"
                                id="sequence"
                                class="form-control"
                                value="{{ old(
                                    'sequence',
                                    (
                                        $masterSchedule
                                            ->activities
                                            ->max('sequence')
                                        ?? 0
                                    ) + 1
                                ) }}"
                                min="0"
                            >

                        </div>


                        {{-- Planned Progress --}}

                        <div class="col-md-2 mb-3">

                            <label
                                for="planned_progress"
                                class="form-label"
                            >
                                Planned %
                            </label>

                            <input
                                type="number"
                                name="planned_progress"
                                id="planned_progress"
                                class="form-control"
                                value="{{ old(
                                    'planned_progress',
                                    0
                                ) }}"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                        </div>


                        {{-- Milestone --}}

                        <div class="col-md-2 mb-3">

                            <label class="form-label d-block">
                                Milestone
                            </label>

                            <div class="form-check mt-2">

                                <input
                                    type="checkbox"
                                    name="is_milestone"
                                    value="1"
                                    id="is_milestone"
                                    class="form-check-input"
                                    {{ old(
                                        'is_milestone'
                                    )
                                        ? 'checked'
                                        : ''
                                    }}
                                >

                                <label
                                    for="is_milestone"
                                    class="form-check-label"
                                >
                                    Yes
                                </label>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Predecessor --}}

                        <div class="col-md-5 mb-3">

                            <label
                                for="predecessor_activity_id"
                                class="form-label"
                            >
                                Predecessor
                            </label>

                            <select
                                name="predecessor_activity_id"
                                id="predecessor_activity_id"
                                class="form-select"
                            >

                                <option value="">
                                    -- No Predecessor --
                                </option>

                                @foreach(
                                    $masterSchedule->activities
                                    as $predecessor
                                )

                                    <option
                                        value="{{ $predecessor->id }}"
                                        {{ old(
                                            'predecessor_activity_id'
                                        ) == $predecessor->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $predecessor->activity_code }}
                                        -
                                        {{ $predecessor->activity_name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Dependency --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="dependency_type"
                                class="form-label"
                            >
                                Dependency
                            </label>

                            <select
                                name="dependency_type"
                                id="dependency_type"
                                class="form-select"
                            >

                                <option value="">
                                    -- Select --
                                </option>

                                <option
                                    value="FS"
                                    {{ old('dependency_type') === 'FS'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Finish to Start
                                </option>

                                <option
                                    value="SS"
                                    {{ old('dependency_type') === 'SS'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Start to Start
                                </option>

                                <option
                                    value="FF"
                                    {{ old('dependency_type') === 'FF'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Finish to Finish
                                </option>

                                <option
                                    value="SF"
                                    {{ old('dependency_type') === 'SF'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Start to Finish
                                </option>

                            </select>

                        </div>


                        {{-- Status --}}

                        <div class="col-md-2 mb-3">

                            <label
                                for="status"
                                class="form-label"
                            >
                                Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select"
                            >

                                @foreach([
                                    'Not Started',
                                    'In Progress',
                                    'Completed',
                                    'Delayed',
                                    'On Hold'
                                ] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old(
                                            'status',
                                            'Not Started'
                                        ) === $status
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $status }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Submit --}}

                        <div class="col-md-2 mb-3 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                + Add Activity
                            </button>

                        </div>

                    </div>


                    <div class="mb-0">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            rows="2"
                            class="form-control"
                            placeholder="Optional remarks"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- Activity Table --}}
            {{-- ================================================= --}}

            @if(
                $masterSchedule
                    ->activities
                    ->count()
            )

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Activity
                                </th>

                                <th>
                                    Parent
                                </th>

                                <th>
                                    Planned Dates
                                </th>

                                <th>
                                    Actual Dates
                                </th>

                                <th>
                                    Progress
                                </th>

                                <th>
                                    Dependency
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
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
                                        <strong>
                                            {{ $activity->activity_code }}
                                        </strong>
                                    </td>


                                    <td>

                                        <div
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


                                            @if($activity->is_milestone)

                                                <span class="badge bg-info text-dark ms-1">
                                                    Milestone
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    <td>

                                        @if($activity->parent)

                                            {{ $activity->parent->activity_code }}
                                            -
                                            {{ $activity->parent->activity_name }}

                                        @else

                                            <span class="text-muted">
                                                Top Level
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $activity->planned_start_date
                                                ? $activity
                                                    ->planned_start_date
                                                    ->format('d M Y')
                                                : '-'
                                        }}

                                        <br>

                                        <span class="text-muted">
                                            to
                                        </span>

                                        <br>

                                        {{
                                            $activity->planned_end_date
                                                ? $activity
                                                    ->planned_end_date
                                                    ->format('d M Y')
                                                : '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $activity->actual_start_date
                                                ? $activity
                                                    ->actual_start_date
                                                    ->format('d M Y')
                                                : '-'
                                        }}

                                        <br>

                                        <span class="text-muted">
                                            to
                                        </span>

                                        <br>

                                        {{
                                            $activity->actual_end_date
                                                ? $activity
                                                    ->actual_end_date
                                                    ->format('d M Y')
                                                : '-'
                                        }}

                                    </td>


                                    <td style="min-width:130px;">

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
                                            style="height:7px;"
                                        >

                                            <div
                                                class="progress-bar"
                                                style="
                                                    width:
                                                    {{ min(
                                                        100,
                                                        max(
                                                            0,
                                                            $activity
                                                                ->actual_progress
                                                        )
                                                    ) }}%;
                                                "
                                            ></div>

                                        </div>

                                    </td>


                                    <td>

                                        @if($activity->predecessor)

                                            <div class="small">

                                                <strong>
                                                    {{ $activity
                                                        ->predecessor
                                                        ->activity_code
                                                    }}
                                                </strong>

                                                <br>

                                                {{ $activity->dependency_type ?? '-' }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

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
                                            class="btn btn-sm btn-outline-primary"
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
                                            class="d-inline"
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

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No activities added
                    </h6>

                    <p class="text-muted mb-0">
                        Use the form above to add the first
                        schedule activity.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Approval / Control --}}
    {{-- ========================================================= --}}

    <div class="card mb-5">

        <div class="card-header">
            <strong>Schedule Control</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Baseline Freeze Date
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


                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $masterSchedule->approved_date
                                ? $masterSchedule
                                    ->approved_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved By
                    </div>

                    <div class="fw-semibold">

                        {{
                            $masterSchedule->approved_by
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>


            @if($masterSchedule->remarks)

                <hr>

                <div>

                    <div class="text-muted small mb-1">
                        Remarks
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $masterSchedule->remarks
                            )
                        ) !!}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection