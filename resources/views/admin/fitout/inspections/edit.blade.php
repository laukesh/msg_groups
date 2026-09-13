@extends('layouts.app')

@section('title', 'Edit Inspection')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Inspection
            </h4>

            <p class="text-muted mb-0">
                {{ $inspection->inspection_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.inspections.show', $inspection->id) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form
        action="{{ route('admin.fitout.inspections.update', $inspection->id) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        <div class="row">

            {{-- ================================================= --}}
            {{-- LEFT COLUMN --}}
            {{-- ================================================= --}}

            <div class="col-lg-8">


                {{-- Inspection --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Inspection Number --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Inspection Number
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $inspection->inspection_number }}"
                                    readonly
                                >

                            </div>


                            {{-- Inspection Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Inspection Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="inspection_type"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Inspection Type
                                    </option>

                                    @php

                                        $inspectionTypes = [
                                            'Initial Site Inspection',
                                            'Civil Inspection',
                                            'Electrical Inspection',
                                            'Plumbing Inspection',
                                            'HVAC Inspection',
                                            'Fire & Safety Inspection',
                                            'Shop Front Inspection',
                                            'Signage Inspection',
                                            'Final Inspection',
                                            'Re-Inspection',
                                        ];

                                    @endphp


                                    @foreach($inspectionTypes as $type)

                                        <option
                                            value="{{ $type }}"
                                            @selected(
                                                old(
                                                    'inspection_type',
                                                    $inspection->inspection_type
                                                ) === $type
                                            )
                                        >
                                            {{ $type }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Fitout Request --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Fit-Out Request
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $inspection->fitoutRequest->request_no ?? '-' }}"
                                    readonly
                                >

                                <input
                                    type="hidden"
                                    name="fitout_request_id"
                                    value="{{ $inspection->fitout_request_id }}"
                                >

                            </div>


                            {{-- Stage --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Fit-Out Stage
                                </label>

                                <select
                                    name="fitout_stage_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        No Stage
                                    </option>

                                    @foreach($stages as $stage)

                                        <option
                                            value="{{ $stage->id }}"
                                            @selected(
                                                old(
                                                    'fitout_stage_id',
                                                    $inspection->fitout_stage_id
                                                ) == $stage->id
                                            )
                                        >

                                            {{ $stage->stage_sequence }}
                                            -
                                            {{ $stage->stage_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- Schedule --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Schedule
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Scheduled Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="scheduled_date"
                                    class="form-control"
                                    value="{{ old(
                                        'scheduled_date',
                                        $inspection->scheduled_date
                                            ? $inspection->scheduled_date->format('Y-m-d')
                                            : ''
                                    ) }}"
                                    required
                                >

                            </div>


                            {{-- Time --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Scheduled Time
                                </label>

                                <input
                                    type="time"
                                    name="scheduled_time"
                                    class="form-control"
                                    value="{{ old(
                                        'scheduled_time',
                                        $inspection->scheduled_time
                                            ? \Carbon\Carbon::parse(
                                                $inspection->scheduled_time
                                            )->format('H:i')
                                            : ''
                                    ) }}"
                                >

                            </div>


                            {{-- Inspection Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Inspection Date
                                </label>

                                <input
                                    type="date"
                                    name="inspection_date"
                                    class="form-control"
                                    value="{{ old(
                                        'inspection_date',
                                        $inspection->inspection_date
                                            ? $inspection->inspection_date->format('Y-m-d')
                                            : ''
                                    ) }}"
                                >

                            </div>


                            {{-- Inspector --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Inspector
                                </label>

                                <select
                                    name="inspector_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Inspector
                                    </option>

                                    @foreach($inspectors as $inspector)

                                        <option
                                            value="{{ $inspector->id }}"
                                            @selected(
                                                old(
                                                    'inspector_id',
                                                    $inspection->inspector_id
                                                ) == $inspector->id
                                            )
                                        >

                                            {{ $inspector->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- Result --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection Result
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Result --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Result
                                </label>

                                <select
                                    name="result"
                                    class="form-select"
                                >

                                    <option value="Pending"
                                        @selected(
                                            old(
                                                'result',
                                                $inspection->result
                                            ) === 'Pending'
                                        )
                                    >
                                        Pending
                                    </option>

                                    <option value="Passed"
                                        @selected(
                                            old(
                                                'result',
                                                $inspection->result
                                            ) === 'Passed'
                                        )
                                    >
                                        Passed
                                    </option>

                                    <option value="Failed"
                                        @selected(
                                            old(
                                                'result',
                                                $inspection->result
                                            ) === 'Failed'
                                        )
                                    >
                                        Failed
                                    </option>

                                    <option value="Conditional Pass"
                                        @selected(
                                            old(
                                                'result',
                                                $inspection->result
                                            ) === 'Conditional Pass'
                                        )
                                    >
                                        Conditional Pass
                                    </option>

                                </select>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    @php

                                        $statuses = [
                                            'Scheduled',
                                            'In Progress',
                                            'Completed',
                                            'Cancelled',
                                            'Rescheduled',
                                        ];

                                    @endphp


                                    @foreach($statuses as $status)

                                        <option
                                            value="{{ $status }}"
                                            @selected(
                                                old(
                                                    'status',
                                                    $inspection->status
                                                ) === $status
                                            )
                                        >
                                            {{ $status }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Observations --}}
                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Observations
                                </label>

                                <textarea
                                    name="observations"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Enter inspection observations..."
                                >{{ old(
                                    'observations',
                                    $inspection->observations
                                ) }}</textarea>

                            </div>


                            {{-- Recommendations --}}
                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Recommendations
                                </label>

                                <textarea
                                    name="recommendations"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Enter recommendations..."
                                >{{ old(
                                    'recommendations',
                                    $inspection->recommendations
                                ) }}</textarea>

                            </div>


                            {{-- Reinspection --}}
                            <div class="col-12 mb-3">

                                <div class="form-check">

                                    <input
                                        type="hidden"
                                        name="reinspection_required"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="reinspection_required"
                                        value="1"
                                        class="form-check-input"
                                        id="reinspection_required"
                                        @checked(
                                            old(
                                                'reinspection_required',
                                                $inspection->reinspection_required
                                            )
                                        )
                                    >

                                    <label
                                        class="form-check-label"
                                        for="reinspection_required"
                                    >
                                        Re-inspection required
                                    </label>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- Submit --}}
                <div class="card mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.fitout.inspections.show',
                                    $inspection->id
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-save me-1"></i>

                                Update Inspection

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT COLUMN --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">


                {{-- Request Summary --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Request Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        @if($inspection->fitoutRequest)

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Request No.
                                </small>

                                <strong>
                                    {{ $inspection->fitoutRequest->request_no }}
                                </strong>

                            </div>


                            <div class="mb-3">

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


                            <div class="mb-3">

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


                            <div>

                                <small class="text-muted d-block">
                                    Contractor
                                </small>

                                <strong>

                                    {{
                                        $inspection->fitoutRequest->contractor->contractor_name
                                        ?? 'Not Assigned'
                                    }}

                                </strong>

                            </div>

                        @else

                            <span class="text-muted">
                                Request information unavailable.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Current Status --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Current Status
                        </h5>

                    </div>


                    <div class="card-body text-center">

                        @php

                            $statusClass = match(
                                $inspection->status
                            ) {

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


                        <span
                            class="badge {{ $statusClass }} fs-6 px-3 py-2"
                        >

                            {{ $inspection->status }}

                        </span>

                    </div>

                </div>


                {{-- Warning --}}
                @if(
                    in_array(
                        $inspection->status,
                        ['Completed', 'Cancelled']
                    )
                )

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        This inspection is
                        <strong>
                            {{ strtolower($inspection->status) }}
                        </strong>.

                        Editing lifecycle fields should be avoided.

                    </div>

                @endif

            </div>

        </div>

    </form>

</div>

@endsection