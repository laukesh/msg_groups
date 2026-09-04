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
                Edit Master Schedule
            </h3>

            <div class="text-muted">
                {{ $masterSchedule->schedule_number }}
                · {{ $project->project_name }}
                · {{ $project->project_number }}
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
                class="btn btn-outline-primary"
            >
                View Schedule
            </a>

            <a
                href="{{ route(
                    'admin.projects.master-schedule.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
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
            'admin.projects.master-schedule.update',
            [
                'project' => $project->id,
                'masterSchedule' => $masterSchedule->id,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Project Context --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Project Context</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-info small">

                    Project and Schedule Number are controlled by the
                    system and cannot be changed from this form.

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Project
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_name }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Project Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Schedule Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $masterSchedule->schedule_number }}"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Schedule Identification --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Schedule Identification</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label
                            for="title"
                            class="form-label"
                        >
                            Schedule Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old(
                                'title',
                                $masterSchedule->title
                            ) }}"
                            required
                        >

                        @error('title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


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
                                'Draft',
                                'Under Review',
                                'Submitted',
                                'Approved',
                                'Rejected'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'status',
                                        $masterSchedule->status
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
        {{-- Baseline Schedule --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Baseline Schedule</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-secondary small">

                    The baseline represents the approved original
                    schedule used for schedule performance comparison.

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

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
                                    $masterSchedule->baseline_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('baseline_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="baseline_completion_date"
                            class="form-label"
                        >
                            Baseline Completion Date
                        </label>

                        <input
                            type="date"
                            name="baseline_completion_date"
                            id="baseline_completion_date"
                            class="form-control @error('baseline_completion_date') is-invalid @enderror"
                            value="{{ old(
                                'baseline_completion_date',
                                optional(
                                    $masterSchedule->baseline_completion_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('baseline_completion_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="baseline_date"
                            class="form-label"
                        >
                            Baseline Approval / Freeze Date
                        </label>

                        <input
                            type="date"
                            name="baseline_date"
                            id="baseline_date"
                            class="form-control @error('baseline_date') is-invalid @enderror"
                            value="{{ old(
                                'baseline_date',
                                optional(
                                    $masterSchedule->baseline_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('baseline_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Current Schedule --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Current Schedule</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="current_start_date"
                            class="form-label"
                        >
                            Current Start Date
                        </label>

                        <input
                            type="date"
                            name="current_start_date"
                            id="current_start_date"
                            class="form-control @error('current_start_date') is-invalid @enderror"
                            value="{{ old(
                                'current_start_date',
                                optional(
                                    $masterSchedule->current_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('current_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="current_completion_date"
                            class="form-label"
                        >
                            Current Completion Date
                        </label>

                        <input
                            type="date"
                            name="current_completion_date"
                            id="current_completion_date"
                            class="form-control @error('current_completion_date') is-invalid @enderror"
                            value="{{ old(
                                'current_completion_date',
                                optional(
                                    $masterSchedule->current_completion_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('current_completion_date')

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
                <strong>Schedule Progress</strong>
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
                                $masterSchedule->planned_progress
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
                                $masterSchedule->actual_progress
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
        {{-- Approval --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Schedule Approval</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="approved_date"
                            class="form-label"
                        >
                            Approved Date
                        </label>

                        <input
                            type="date"
                            name="approved_date"
                            id="approved_date"
                            class="form-control @error('approved_date') is-invalid @enderror"
                            value="{{ old(
                                'approved_date',
                                optional(
                                    $masterSchedule->approved_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('approved_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="approved_by"
                            class="form-label"
                        >
                            Approved By ID
                        </label>

                        <input
                            type="number"
                            name="approved_by"
                            id="approved_by"
                            class="form-control @error('approved_by') is-invalid @enderror"
                            value="{{ old(
                                'approved_by',
                                $masterSchedule->approved_by
                            ) }}"
                        >

                        @error('approved_by')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Created
                        </label>

                        <div class="form-control bg-light">

                            {{
                                $masterSchedule->created_at
                                    ? $masterSchedule
                                        ->created_at
                                        ->format('d M Y H:i')
                                    : '-'
                            }}

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
                    placeholder="Additional schedule remarks"
                >{{ old(
                    'remarks',
                    $masterSchedule->remarks
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

        <div class="d-flex justify-content-end gap-2 mb-5">

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
                Update Master Schedule
            </button>

        </div>

    </form>

</div>

@endsection