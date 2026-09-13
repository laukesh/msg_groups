@extends('layouts.app')

@section('title', 'Inspection Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0">
                    {{ $inspection->inspection_number }}
                </h4>

                @php

                    $statusClass = match($inspection->status) {

                        'Scheduled' =>
                            'bg-primary',

                        'In Progress' =>
                            'bg-warning text-dark',

                        'Completed' =>
                            'bg-success',

                        'Cancelled' =>
                            'bg-danger',

                        'Rescheduled' =>
                            'bg-info text-dark',

                        default =>
                            'bg-secondary',

                    };

                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ $inspection->status }}
                </span>

            </div>

            <p class="text-muted mb-0 mt-1">
                {{ $inspection->inspection_type }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.inspections.index') }}"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>


            @if(
                !in_array(
                    $inspection->status,
                    ['Completed', 'Cancelled']
                )
            )

                <a
                    href="{{ route('admin.fitout.inspections.edit', $inspection->id) }}"
                    class="btn btn-outline-primary"
                >

                    <i class="bi bi-pencil me-1"></i>

                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="row">


        {{-- ========================================================= --}}
        {{-- LEFT COLUMN --}}
        {{-- ========================================================= --}}

        <div class="col-lg-8">


            {{-- Inspection Information --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Inspection Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Inspection Number
                            </small>

                            <strong>
                                {{ $inspection->inspection_number }}
                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Inspection Type
                            </small>

                            <strong>
                                {{ $inspection->inspection_type }}
                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Scheduled Date
                            </small>

                            <strong>

                                {{ $inspection->scheduled_date
                                    ? $inspection->scheduled_date->format('d M Y')
                                    : '-' }}

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Scheduled Time
                            </small>

                            <strong>

                                @if($inspection->scheduled_time)

                                    {{ \Carbon\Carbon::parse(
                                        $inspection->scheduled_time
                                    )->format('h:i A') }}

                                @else

                                    -

                                @endif

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Inspection Date
                            </small>

                            <strong>

                                {{ $inspection->inspection_date
                                    ? $inspection->inspection_date->format('d M Y')
                                    : '-' }}

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Inspector
                            </small>

                            <strong>

                                {{ $inspection->inspector->name
                                    ?? 'Not Assigned' }}

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Result
                            </small>

                            @php

                                $resultClass = match(
                                    $inspection->result
                                ) {

                                    'Passed' =>
                                        'bg-success',

                                    'Failed' =>
                                        'bg-danger',

                                    'Conditional Pass' =>
                                        'bg-warning text-dark',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $resultClass }}">

                                {{ $inspection->result ?? 'Pending' }}

                            </span>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Re-Inspection Required
                            </small>

                            @if($inspection->reinspection_required)

                                <span class="badge bg-warning text-dark">
                                    Yes
                                </span>

                            @else

                                <span class="badge bg-light text-dark border">
                                    No
                                </span>

                            @endif

                        </div>


                    </div>

                </div>

            </div>


            {{-- Fit-Out Request --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Request
                    </h5>

                </div>


                <div class="card-body">

                    @if($inspection->fitoutRequest)

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Request Number
                                </small>

                                <a
                                    href="{{ route(
                                        'admin.fitout.requests.show',
                                        $inspection->fitoutRequest->id
                                    ) }}"
                                    class="fw-semibold text-decoration-none"
                                >

                                    {{ $inspection->fitoutRequest->request_no }}

                                </a>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Fit-Out Type
                                </small>

                                <strong>
                                    {{ $inspection->fitoutRequest->fitout_type }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Tenant
                                </small>

                                <strong>

                                    @if($inspection->fitoutRequest->tenant)

                                        {{
                                            $inspection->fitoutRequest->tenant->company_name
                                            ??
                                            $inspection->fitoutRequest->tenant->company_name
                                            ??
                                            '-'
                                        }}

                                    @else

                                        -

                                    @endif

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Unit
                                </small>

                                <strong>

                                    @if($inspection->fitoutRequest->unit)

                                        {{
                                            $inspection->fitoutRequest->unit->unit_no
                                            ??
                                            $inspection->fitoutRequest->unit->name
                                            ??
                                            '-'
                                        }}

                                    @else

                                        -

                                    @endif

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Contractor
                                </small>

                                <strong>

                                    @if($inspection->fitoutRequest->contractor)

                                        {{
                                            $inspection->fitoutRequest->contractor->contractor_name
                                        }}

                                    @else

                                        Not Assigned

                                    @endif

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Fit-Out Status
                                </small>

                                <span class="badge bg-light text-dark border">

                                    {{ $inspection->fitoutRequest->fitout_status }}

                                </span>

                            </div>


                        </div>

                    @else

                        <div class="text-muted">
                            Fit-out request not found.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Stage --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Stage
                    </h5>

                </div>


                <div class="card-body">

                    @if($inspection->fitoutStage)

                        <div class="row">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Sequence
                                </small>

                                <strong>

                                    Stage
                                    {{ $inspection->fitoutStage->stage_sequence }}

                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Stage Name
                                </small>

                                <strong>
                                    {{ $inspection->fitoutStage->stage_name }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Stage Status
                                </small>

                                <span class="badge bg-light text-dark border">

                                    {{ $inspection->fitoutStage->stage_status }}

                                </span>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            No stage assigned to this inspection.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Observations --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Observations
                    </h5>

                </div>


                <div class="card-body">

                    @if($inspection->observations)

                        <div class="mb-0">

                            {!! nl2br(e($inspection->observations)) !!}

                        </div>

                    @else

                        <span class="text-muted">
                            No observations recorded.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Recommendations --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recommendations
                    </h5>

                </div>


                <div class="card-body">

                    @if($inspection->recommendations)

                        <div>

                            {!! nl2br(e($inspection->recommendations)) !!}

                        </div>

                    @else

                        <span class="text-muted">
                            No recommendations recorded.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Re-Inspection History --}}
            @if(
                $inspection->parentInspection ||
                $inspection->reinspections->count()
            )

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection History
                        </h5>

                    </div>


                    <div class="card-body">


                        @if($inspection->parentInspection)

                            <div class="mb-3">

                                <div class="small text-muted mb-1">
                                    Parent Inspection
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.fitout.inspections.show',
                                        $inspection->parentInspection->id
                                    ) }}"
                                    class="fw-semibold"
                                >

                                    {{ $inspection->parentInspection->inspection_number }}

                                </a>

                                <span class="text-muted ms-2">

                                    {{ $inspection->parentInspection->inspection_type }}

                                </span>

                            </div>

                        @endif


                        @if($inspection->reinspections->count())

                            <div>

                                <div class="small text-muted mb-2">
                                    Re-Inspections
                                </div>


                                <div class="table-responsive">

                                    <table class="table table-sm align-middle">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Inspection No.
                                                </th>

                                                <th>
                                                    Date
                                                </th>

                                                <th>
                                                    Result
                                                </th>

                                                <th>
                                                    Status
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach(
                                                $inspection->reinspections
                                                as $reinspection
                                            )

                                                <tr>

                                                    <td>

                                                        <a
                                                            href="{{ route(
                                                                'admin.fitout.inspections.show',
                                                                $reinspection->id
                                                            ) }}"
                                                        >

                                                            {{
                                                                $reinspection->inspection_number
                                                            }}

                                                        </a>

                                                    </td>


                                                    <td>

                                                        {{
                                                            $reinspection->scheduled_date
                                                            ? $reinspection->scheduled_date->format('d M Y')
                                                            : '-'
                                                        }}

                                                    </td>


                                                    <td>

                                                        {{
                                                            $reinspection->result
                                                            ?? 'Pending'
                                                        }}

                                                    </td>


                                                    <td>

                                                        {{
                                                            $reinspection->status
                                                        }}

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            @endif

            @if($inspection->report_file_path)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection Report
                        </h5>

                    </div>

                    <div class="card-body">

                        <a
                            href="{{ asset('storage/' . $inspection->report_file_path) }}"
                            target="_blank"
                            class="btn btn-outline-primary"
                        >

                            <i class="bi bi-file-earmark-text me-1"></i>

                            View Inspection Report

                        </a>

                        <a
                            href="{{ asset('storage/' . $inspection->report_file_path) }}"
                            download
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-download me-1"></i>

                            Download

                        </a>

                    </div>

                </div>

            @endif


            {{-- Complete Inspection --}}
            @if($inspection->status === 'In Progress')

                <div
                    class="card mb-4"
                    id="completeInspection"
                >

                    <div class="card-header">

                        <h5 class="mb-0">
                            Complete Inspection
                        </h5>

                    </div>


                    <div class="card-body">

                        <form
                            action="{{ route(
                                'admin.fitout.inspections.complete',
                                $inspection->id
                            ) }}"
                            method="POST"
                        >

                            @csrf


                            <div class="mb-3">

                                <label class="form-label">
                                    Result
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="result"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Result
                                    </option>

                                    <option value="Passed">
                                        Passed
                                    </option>

                                    <option value="Failed">
                                        Failed
                                    </option>

                                    <option value="Conditional Pass">
                                        Conditional Pass
                                    </option>

                                </select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Observations
                                </label>

                                <textarea
                                    name="observations"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter inspection observations..."
                                >{{ $inspection->observations }}</textarea>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Recommendations
                                </label>

                                <textarea
                                    name="recommendations"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter recommendations..."
                                >{{ $inspection->recommendations }}</textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Inspection Report
                                </label>

                                <input
                                    type="file"
                                    name="report_file"
                                    class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                >

                                <div class="form-text">
                                    Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.
                                    Maximum size: 10 MB.
                                </div>

                            </div>


                            <div class="form-check mb-3">

                                <input
                                    type="hidden"
                                    name="reinspection_required"
                                    value="0"
                                >

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="reinspection_required"
                                    value="1"
                                    id="reinspection_required"
                                >

                                <label
                                    class="form-check-label"
                                    for="reinspection_required"
                                >

                                    Re-inspection required

                                </label>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Complete Inspection

                            </button>

                        </form>

                    </div>

                </div>

            @endif


            {{-- Reschedule --}}
            @if(
                in_array(
                    $inspection->status,
                    ['Scheduled', 'Rescheduled']
                )
            )

                <div
                    class="card mb-4"
                    id="rescheduleInspection"
                >

                    <div class="card-header">

                        <h5 class="mb-0">
                            Reschedule Inspection
                        </h5>

                    </div>


                    <div class="card-body">

                        <form
                            action="{{ route(
                                'admin.fitout.inspections.reschedule',
                                $inspection->id
                            ) }}"
                            method="POST"
                        >

                            @csrf


                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        New Date
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="scheduled_date"
                                        class="form-control"
                                        value="{{
                                            $inspection->scheduled_date
                                            ? $inspection->scheduled_date->format('Y-m-d')
                                            : ''
                                        }}"
                                        required
                                    >

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        New Time
                                    </label>

                                    <input
                                        type="time"
                                        name="scheduled_time"
                                        class="form-control"
                                        value="{{
                                            $inspection->scheduled_time
                                            ? \Carbon\Carbon::parse(
                                                $inspection->scheduled_time
                                            )->format('H:i')
                                            : ''
                                        }}"
                                    >

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-info text-dark"
                            >

                                <i class="bi bi-calendar-event me-1"></i>

                                Reschedule

                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- RIGHT COLUMN --}}
        {{-- ========================================================= --}}

        <div class="col-lg-4">


            {{-- Current Status --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Current Status
                    </h5>

                </div>


                <div class="card-body text-center">

                    <span
                        class="badge {{ $statusClass }} fs-6 px-3 py-2"
                    >

                        {{ $inspection->status }}

                    </span>


                    @if($inspection->completed_at)

                        <div class="small text-muted mt-3">

                            Completed on

                            <strong>

                                {{
                                    $inspection->completed_at
                                    ->format('d M Y h:i A')
                                }}

                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Actions --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Actions
                    </h5>

                </div>


                <div class="card-body">


                    {{-- Start --}}
                    @if($inspection->status === 'Scheduled')

                        <form
                            action="{{ route(
                                'admin.fitout.inspections.start',
                                $inspection->id
                            ) }}"
                            method="POST"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >

                                <i class="bi bi-play-circle me-1"></i>

                                Start Inspection

                            </button>

                        </form>

                    @endif


                    {{-- Reinspection --}}
                    @if(
                        $inspection->status === 'Completed' &&
                        $inspection->reinspection_required
                    )

                        <a
                            href="{{ route(
                                'admin.fitout.inspections.reinspection.create',
                                $inspection->id
                            ) }}"
                            class="btn btn-warning w-100 mb-2"
                        >

                            <i class="bi bi-arrow-repeat me-1"></i>

                            Create Re-Inspection

                        </a>

                    @endif


                    {{-- Cancel --}}
                    @if(
                        !in_array(
                            $inspection->status,
                            ['Completed', 'Cancelled']
                        )
                    )

                        <form
                            action="{{ route(
                                'admin.fitout.inspections.cancel',
                                $inspection->id
                            ) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to cancel this inspection?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >

                                <i class="bi bi-x-circle me-1"></i>

                                Cancel Inspection

                            </button>

                        </form>

                    @endif


                    @if(
                        $inspection->status === 'Completed'
                    )

                        <div class="alert alert-success mt-3 mb-0">

                            <i class="bi bi-check-circle me-1"></i>

                            This inspection has been completed.

                        </div>

                    @endif


                    @if(
                        $inspection->status === 'Cancelled'
                    )

                        <div class="alert alert-danger mt-3 mb-0">

                            <i class="bi bi-x-circle me-1"></i>

                            This inspection has been cancelled.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Audit --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Audit Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created At
                        </small>

                        <strong>

                            {{
                                $inspection->created_at
                                ? $inspection->created_at->format(
                                    'd M Y h:i A'
                                )
                                : '-'
                            }}

                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        <strong>

                            {{
                                $inspection->updated_at
                                ? $inspection->updated_at->format(
                                    'd M Y h:i A'
                                )
                                : '-'
                            }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection