@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Master Schedule / Activity
            </div>

            <h3 class="mb-1">
                Edit Schedule Activity
            </h3>

            <div class="text-muted">
                {{ $activity->activity_code }}
                · {{ $activity->activity_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.master-schedule.show',
                    [
                        'project' => $project->id,
                        'masterSchedule' => $masterSchedule->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Schedule
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.master-schedule.activities.update',
            [
                'project' => $project->id,
                'masterSchedule' => $masterSchedule->id,
                'activity' => $activity->id,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Activity Identification --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Activity Identification</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Activity Code --}}

                    <div class="col-md-3 mb-3">

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
                            class="form-control @error('activity_code') is-invalid @enderror"
                            value="{{ old(
                                'activity_code',
                                $activity->activity_code
                            ) }}"
                            required
                        >

                        @error('activity_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Activity Name --}}

                    <div class="col-md-6 mb-3">

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
                            class="form-control @error('activity_name') is-invalid @enderror"
                            value="{{ old(
                                'activity_name',
                                $activity->activity_name
                            ) }}"
                            required
                        >

                        @error('activity_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Sequence --}}

                    <div class="col-md-3 mb-3">

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
                            class="form-control @error('sequence') is-invalid @enderror"
                            value="{{ old(
                                'sequence',
                                $activity->sequence
                            ) }}"
                            min="0"
                        >

                        @error('sequence')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="row">

                    {{-- Activity Type --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="activity_type"
                            class="form-label"
                        >
                            Activity Type
                        </label>

                        <select
                            name="activity_type"
                            id="activity_type"
                            class="form-select @error('activity_type') is-invalid @enderror"
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
                                        'activity_type',
                                        $activity->activity_type
                                    ) === $type
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                        @error('activity_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Parent Activity --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="parent_activity_id"
                            class="form-label"
                        >
                            Parent Activity
                        </label>

                        <select
                            name="parent_activity_id"
                            id="parent_activity_id"
                            class="form-select @error('parent_activity_id') is-invalid @enderror"
                        >

                            <option value="">
                                -- Top Level --
                            </option>

                            @foreach($activities as $parentActivity)

                                <option
                                    value="{{ $parentActivity->id }}"
                                    {{ old(
                                        'parent_activity_id',
                                        $activity->parent_activity_id
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

                        <div class="form-text">
                            The current activity cannot be its own parent.
                        </div>

                        @error('parent_activity_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
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
                                        $activity->status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Planned Schedule --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Planned Schedule</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_start_date"
                            class="form-label"
                        >
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="planned_start_date"
                            id="planned_start_date"
                            class="form-control @error('planned_start_date') is-invalid @enderror"
                            value="{{ old(
                                'planned_start_date',
                                optional(
                                    $activity->planned_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('planned_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_end_date"
                            class="form-label"
                        >
                            Planned End Date
                        </label>

                        <input
                            type="date"
                            name="planned_end_date"
                            id="planned_end_date"
                            class="form-control @error('planned_end_date') is-invalid @enderror"
                            value="{{ old(
                                'planned_end_date',
                                optional(
                                    $activity->planned_end_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('planned_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_duration_days"
                            class="form-label"
                        >
                            Planned Duration (Days)
                        </label>

                        <input
                            type="number"
                            name="planned_duration_days"
                            id="planned_duration_days"
                            class="form-control @error('planned_duration_days') is-invalid @enderror"
                            value="{{ old(
                                'planned_duration_days',
                                $activity->planned_duration_days
                            ) }}"
                            min="0"
                        >

                        @error('planned_duration_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="baseline_start_date"
                            class="form-label"
                        >
                            Baseline Start Date
                        </label>

                        <input
                            type="date"
                            name="baseline_start_date"
                            id="baseline_start_date"
                            class="form-control @error('baseline_start_date') is-invalid @enderror"
                            value="{{ old(
                                'baseline_start_date',
                                optional(
                                    $activity->baseline_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('baseline_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="baseline_end_date"
                            class="form-label"
                        >
                            Baseline End Date
                        </label>

                        <input
                            type="date"
                            name="baseline_end_date"
                            id="baseline_end_date"
                            class="form-control @error('baseline_end_date') is-invalid @enderror"
                            value="{{ old(
                                'baseline_end_date',
                                optional(
                                    $activity->baseline_end_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('baseline_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actual Schedule --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Actual Schedule</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="actual_start_date"
                            class="form-label"
                        >
                            Actual Start Date
                        </label>

                        <input
                            type="date"
                            name="actual_start_date"
                            id="actual_start_date"
                            class="form-control @error('actual_start_date') is-invalid @enderror"
                            value="{{ old(
                                'actual_start_date',
                                optional(
                                    $activity->actual_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('actual_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="actual_end_date"
                            class="form-label"
                        >
                            Actual End Date
                        </label>

                        <input
                            type="date"
                            name="actual_end_date"
                            id="actual_end_date"
                            class="form-control @error('actual_end_date') is-invalid @enderror"
                            value="{{ old(
                                'actual_end_date',
                                optional(
                                    $activity->actual_end_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('actual_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="actual_duration_days"
                            class="form-label"
                        >
                            Actual Duration (Days)
                        </label>

                        <input
                            type="number"
                            name="actual_duration_days"
                            id="actual_duration_days"
                            class="form-control @error('actual_duration_days') is-invalid @enderror"
                            value="{{ old(
                                'actual_duration_days',
                                $activity->actual_duration_days
                            ) }}"
                            min="0"
                        >

                        @error('actual_duration_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Progress --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Activity Progress</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="planned_progress"
                            class="form-label"
                        >
                            Planned Progress (%)
                        </label>

                        <input
                            type="number"
                            name="planned_progress"
                            id="planned_progress"
                            class="form-control @error('planned_progress') is-invalid @enderror"
                            value="{{ old(
                                'planned_progress',
                                $activity->planned_progress
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                        @error('planned_progress')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="actual_progress"
                            class="form-label"
                        >
                            Actual Progress (%)
                        </label>

                        <input
                            type="number"
                            name="actual_progress"
                            id="actual_progress"
                            class="form-control @error('actual_progress') is-invalid @enderror"
                            value="{{ old(
                                'actual_progress',
                                $activity->actual_progress
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                        @error('actual_progress')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Dependencies --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Activity Dependency</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label
                            for="predecessor_activity_id"
                            class="form-label"
                        >
                            Predecessor Activity
                        </label>

                        <select
                            name="predecessor_activity_id"
                            id="predecessor_activity_id"
                            class="form-select @error('predecessor_activity_id') is-invalid @enderror"
                        >

                            <option value="">
                                -- No Predecessor --
                            </option>

                            @foreach($activities as $otherActivity)

                                <option
                                    value="{{ $otherActivity->id }}"
                                    {{ old(
                                        'predecessor_activity_id',
                                        $activity->predecessor_activity_id
                                    ) == $otherActivity->id
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $otherActivity->activity_code }}
                                    -
                                    {{ $otherActivity->activity_name }}
                                </option>

                            @endforeach

                        </select>

                        @error('predecessor_activity_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="dependency_type"
                            class="form-label"
                        >
                            Dependency Type
                        </label>

                        <select
                            name="dependency_type"
                            id="dependency_type"
                            class="form-select @error('dependency_type') is-invalid @enderror"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            <option
                                value="FS"
                                {{ old(
                                    'dependency_type',
                                    $activity->dependency_type
                                ) === 'FS'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Finish to Start
                            </option>

                            <option
                                value="SS"
                                {{ old(
                                    'dependency_type',
                                    $activity->dependency_type
                                ) === 'SS'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Start to Start
                            </option>

                            <option
                                value="FF"
                                {{ old(
                                    'dependency_type',
                                    $activity->dependency_type
                                ) === 'FF'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Finish to Finish
                            </option>

                            <option
                                value="SF"
                                {{ old(
                                    'dependency_type',
                                    $activity->dependency_type
                                ) === 'SF'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Start to Finish
                            </option>

                        </select>

                        @error('dependency_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Responsibility --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Responsibility</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="responsible_user_id"
                            class="form-label"
                        >
                            Responsible User ID
                        </label>

                        <input
                            type="number"
                            name="responsible_user_id"
                            id="responsible_user_id"
                            class="form-control @error('responsible_user_id') is-invalid @enderror"
                            value="{{ old(
                                'responsible_user_id',
                                $activity->responsible_user_id
                            ) }}"
                        >

                        <div class="form-text">
                            This can later be connected to the
                            project's employee/user master.
                        </div>

                        @error('responsible_user_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

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
                                    'is_milestone',
                                    $activity->is_milestone
                                )
                                    ? 'checked'
                                    : ''
                                }}
                            >

                            <label
                                for="is_milestone"
                                class="form-check-label"
                            >
                                Mark as milestone
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    id="remarks"
                    rows="4"
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Activity remarks"
                >{{ old(
                    'remarks',
                    $activity->remarks
                ) }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-between mb-5">

            <div>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.master-schedule.activities.destroy',
                        [
                            'project' => $project->id,
                            'masterSchedule' => $masterSchedule->id,
                            'activity' => $activity->id,
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this activity?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Activity
                    </button>

                </form>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route(
                        'admin.projects.master-schedule.show',
                        [
                            'project' => $project->id,
                            'masterSchedule' => $masterSchedule->id,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Activity
                </button>

            </div>

        </div>

    </form>

</div>

@endsection