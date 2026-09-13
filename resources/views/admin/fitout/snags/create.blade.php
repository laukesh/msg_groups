@extends('layouts.app')

@section('title', 'Create Snag')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Snag
            </h4>

            <p class="text-muted mb-0">
                Report a fit-out issue or defect.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.snags.index') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
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
        action="{{ route('admin.fitout.snags.store') }}"
        method="POST"
    >

        @csrf


        <div class="row">

            {{-- ================================================= --}}
            {{-- LEFT COLUMN --}}
            {{-- ================================================= --}}

            <div class="col-lg-8">


                {{-- Source --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Snag Source
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Inspection --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Inspection
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="inspection_id"
                                    id="inspection_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Inspection
                                    </option>

                                    @foreach($inspections as $inspection)

                                        <option
                                            value="{{ $inspection->id }}"
                                            data-request-id="{{ $inspection->fitout_request_id }}"
                                            @selected(
                                                old('inspection_id') == $inspection->id
                                            )
                                        >

                                            {{ $inspection->inspection_number }}
                                            -
                                            {{ $inspection->inspection_type }}

                                            @if($inspection->fitoutRequest)

                                                |
                                                {{ $inspection->fitoutRequest->request_no }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Fitout Request --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Fit-Out Request
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="fitout_request_id"
                                    id="fitout_request_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Fit-Out Request
                                    </option>

                                    @foreach($inspections->pluck('fitoutRequest')->filter()->unique('id') as $request)

                                        <option
                                            value="{{ $request->id }}"
                                            @selected(
                                                old('fitout_request_id') == $request->id
                                            )
                                        >

                                            {{ $request->request_no }}

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">
                                    Select the request associated with this inspection.
                                </small>

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
                                        Select Stage
                                    </option>

                                    @foreach($stages as $stage)

                                        <option
                                            value="{{ $stage->id }}"
                                            @selected(
                                                old('fitout_stage_id') == $stage->id
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


                {{-- Snag Details --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Snag Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Title --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Title
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    maxlength="200"
                                    value="{{ old('title') }}"
                                    placeholder="Enter snag title"
                                    required
                                >

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Description
                                    <span class="text-danger">*</span>

                                </label>

                                <textarea
                                    name="description"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Describe the issue or defect in detail..."
                                    required
                                >{{ old('description') }}</textarea>

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Priority
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="priority"
                                    class="form-select"
                                    required
                                >

                                    <option value="Low"
                                        @selected(
                                            old('priority', 'Medium') === 'Low'
                                        )
                                    >
                                        Low
                                    </option>

                                    <option value="Medium"
                                        @selected(
                                            old('priority', 'Medium') === 'Medium'
                                        )
                                    >
                                        Medium
                                    </option>

                                    <option value="High"
                                        @selected(
                                            old('priority') === 'High'
                                        )
                                    >
                                        High
                                    </option>

                                    <option value="Critical"
                                        @selected(
                                            old('priority') === 'Critical'
                                        )
                                    >
                                        Critical
                                    </option>

                                </select>

                            </div>


                            {{-- Category --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <input
                                    type="text"
                                    name="category"
                                    class="form-control"
                                    maxlength="100"
                                    value="{{ old('category') }}"
                                    placeholder="e.g. Civil, Electrical"
                                >

                            </div>


                            {{-- Location --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Location
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    class="form-control"
                                    maxlength="200"
                                    value="{{ old('location') }}"
                                    placeholder="e.g. Shop front, ceiling"
                                >

                            </div>


                        </div>

                    </div>

                </div>


                {{-- Assignment --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Assignment & Deadline
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Contractor --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Contractor
                                </label>

                                <select
                                    name="contractor_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Contractor
                                    </option>

                                    @foreach($contractors as $contractor)

                                        <option
                                            value="{{ $contractor->id }}"
                                            @selected(
                                                old('contractor_id') == $contractor->id
                                            )
                                        >

                                            {{
                                                $contractor->company_name
                                                ?? $contractor->contractor_name
                                                ?? $contractor->name
                                                ?? 'Contractor #' . $contractor->id
                                            }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Assigned To --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Assigned To
                                </label>

                                <select
                                    name="assigned_to"
                                    class="form-select"
                                >

                                    <option value="">
                                        Unassigned
                                    </option>

                                    @foreach($users as $user)

                                        <option
                                            value="{{ $user->id }}"
                                            @selected(
                                                old('assigned_to') == $user->id
                                            )
                                        >

                                            {{ $user->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Reported Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Reported Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="reported_date"
                                    class="form-control"
                                    value="{{ old(
                                        'reported_date',
                                        now()->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                            </div>


                            {{-- Due Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Due Date
                                </label>

                                <input
                                    type="date"
                                    name="due_date"
                                    class="form-control"
                                    value="{{ old('due_date') }}"
                                >

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
                                    'admin.fitout.snags.index'
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

                                Create Snag

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT COLUMN --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">


                {{-- Priority Information --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Priority Guide
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="mb-3">

                            <span class="badge bg-danger">
                                Critical
                            </span>

                            <div class="small text-muted mt-1">
                                Immediate safety, compliance or major
                                operational impact.
                            </div>

                        </div>


                        <div class="mb-3">

                            <span class="badge bg-warning text-dark">
                                High
                            </span>

                            <div class="small text-muted mt-1">
                                Significant issue requiring urgent correction.
                            </div>

                        </div>


                        <div class="mb-3">

                            <span class="badge bg-info text-dark">
                                Medium
                            </span>

                            <div class="small text-muted mt-1">
                                Normal fit-out defect requiring correction.
                            </div>

                        </div>


                        <div>

                            <span class="badge bg-secondary">
                                Low
                            </span>

                            <div class="small text-muted mt-1">
                                Minor cosmetic or non-critical issue.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Workflow --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Snag Workflow
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex align-items-center mb-3">

                            <span class="badge bg-secondary me-2">
                                1
                            </span>

                            <span>
                                Open
                            </span>

                        </div>


                        <div class="d-flex align-items-center mb-3">

                            <span class="badge bg-primary me-2">
                                2
                            </span>

                            <span>
                                Assigned
                            </span>

                        </div>


                        <div class="d-flex align-items-center mb-3">

                            <span class="badge bg-info text-dark me-2">
                                3
                            </span>

                            <span>
                                In Progress
                            </span>

                        </div>


                        <div class="d-flex align-items-center mb-3">

                            <span class="badge bg-success me-2">
                                4
                            </span>

                            <span>
                                Resolved
                            </span>

                        </div>


                        <div class="d-flex align-items-center">

                            <span class="badge bg-dark me-2">
                                5
                            </span>

                            <span>
                                Closed
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Information --}}
                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    A snag should normally be created against an
                    inspection so that the issue can be traced back
                    to its source.

                </div>

            </div>

        </div>

    </form>

</div>

@endsection