@extends('layouts.app')

@section('title', 'Edit Snag')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Snag
            </h4>

            <p class="text-muted mb-0">
                {{ $snag->snag_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.fitout.snags.show',
                    $snag->id
                ) }}"
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

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route(
            'admin.fitout.snags.update',
            $snag->id
        ) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        <div class="row">

            {{-- ================================================= --}}
            {{-- LEFT --}}
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
                                                old(
                                                    'inspection_id',
                                                    $snag->inspection_id
                                                ) == $inspection->id
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


                            {{-- Request --}}
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

                                    @foreach($fitoutRequests as $request)

                                        <option
                                            value="{{ $request->id }}"
                                            @selected(
                                                old(
                                                    'fitout_request_id',
                                                    $snag->fitout_request_id
                                                ) == $request->id
                                            )
                                        >

                                            {{ $request->request_no }}

                                        </option>

                                    @endforeach

                                </select>

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
                                                old(
                                                    'fitout_stage_id',
                                                    $snag->fitout_stage_id
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


                {{-- Details --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Snag Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Snag Number --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Snag Number
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $snag->snag_number }}"
                                    readonly
                                >

                            </div>


                            {{-- Title --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Title
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    maxlength="200"
                                    value="{{ old(
                                        'title',
                                        $snag->title
                                    ) }}"
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
                                    required
                                >{{ old(
                                    'description',
                                    $snag->description
                                ) }}</textarea>

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

                                    @foreach([
                                        'Low',
                                        'Medium',
                                        'High',
                                        'Critical'
                                    ] as $priority)

                                        <option
                                            value="{{ $priority }}"
                                            @selected(
                                                old(
                                                    'priority',
                                                    $snag->priority
                                                ) === $priority
                                            )
                                        >
                                            {{ $priority }}
                                        </option>

                                    @endforeach

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
                                    value="{{ old(
                                        'category',
                                        $snag->category
                                    ) }}"
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
                                    value="{{ old(
                                        'location',
                                        $snag->location
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Assignment --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Assignment & Timeline
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
                                                old(
                                                    'contractor_id',
                                                    $snag->contractor_id
                                                ) == $contractor->id
                                            )
                                        >

                                            {{
                                                $contractor->company_name
                                                ??
                                                $contractor->contractor_name
                                                ??
                                                $contractor->name
                                                ??
                                                'Contractor #' .
                                                $contractor->id
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
                                                old(
                                                    'assigned_to',
                                                    $snag->assigned_to
                                                ) == $user->id
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
                                        $snag->reported_date
                                            ? $snag->reported_date
                                                ->format('Y-m-d')
                                            : ''
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
                                    value="{{ old(
                                        'due_date',
                                        $snag->due_date
                                            ? $snag->due_date
                                                ->format('Y-m-d')
                                            : ''
                                    ) }}"
                                >

                            </div>


                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Status
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    @foreach([
                                        'Open',
                                        'Assigned',
                                        'In Progress',
                                        'Resolved',
                                        'Under Verification',
                                        'Closed',
                                        'Rejected',
                                        'Reopened'
                                    ] as $status)

                                        <option
                                            value="{{ $status }}"
                                            @selected(
                                                old(
                                                    'status',
                                                    $snag->status
                                                ) === $status
                                            )
                                        >
                                            {{ $status }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Corrective Action
                                </label>

                                <textarea
                                    name="corrective_action"
                                    class="form-control"
                                    rows="3"
                                >{{ old(
                                    'corrective_action',
                                    $snag->corrective_action
                                ) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="card mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.fitout.snags.show',
                                    $snag->id
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

                                Update Snag

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Current Status
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <strong>
                                Snag:
                            </strong>

                            {{ $snag->snag_number }}

                        </div>

                        <div class="mb-3">

                            <strong>
                                Status:
                            </strong>

                            {{ $snag->status }}

                        </div>

                        <div>

                            <strong>
                                Priority:
                            </strong>

                            {{ $snag->priority }}

                        </div>

                    </div>

                </div>


                <div class="alert alert-warning">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Be careful when changing the status of a snag.
                    Workflow-specific status changes should normally
                    be performed through the workflow actions.

                </div>

            </div>

        </div>

    </form>

</div>

@endsection