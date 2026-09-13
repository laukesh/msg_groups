@extends('layouts.app')

@section('title', 'Snag Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                {{ $snag->snag_number }}
            </h4>

            <p class="text-muted mb-0">
                {{ $snag->title }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.snags.index') }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            <a
                href="{{ route('admin.fitout.snags.edit', $snag->id) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>

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

        {{-- ================================================= --}}
        {{-- MAIN DETAILS --}}
        {{-- ================================================= --}}

        <div class="col-lg-8">


            {{-- Snag Information --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Snag Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Snag Number
                            </small>

                            <strong>
                                {{ $snag->snag_number }}
                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Title
                            </small>

                            <strong>
                                {{ $snag->title }}
                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Priority
                            </small>

                            @php

                                $priorityClass = match(
                                    $snag->priority
                                ) {

                                    'Critical' => 'bg-danger',

                                    'High' => 'bg-warning text-dark',

                                    'Medium' => 'bg-info text-dark',

                                    default => 'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $priorityClass }}">
                                {{ $snag->priority }}
                            </span>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @php

                                $statusClass = match(
                                    $snag->status
                                ) {

                                    'Open' => 'bg-secondary',

                                    'Assigned' => 'bg-primary',

                                    'In Progress' =>
                                        'bg-info text-dark',

                                    'Resolved' => 'bg-success',

                                    'Under Verification' =>
                                        'bg-warning text-dark',

                                    'Closed' => 'bg-dark',

                                    'Rejected' => 'bg-danger',

                                    'Reopened' =>
                                        'bg-warning text-dark',

                                    default => 'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ $snag->status }}
                            </span>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Category
                            </small>

                            <span>
                                {{ $snag->category ?: '-' }}
                            </span>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Location
                            </small>

                            <span>
                                {{ $snag->location ?: '-' }}
                            </span>

                        </div>


                        <div class="col-12 mb-3">

                            <small class="text-muted d-block mb-1">
                                Description
                            </small>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(e($snag->description)) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Source --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Source Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Fit-Out Request
                            </small>

                            @if($snag->fitoutRequest)

                                <strong>
                                    {{ $snag->fitoutRequest->request_no }}
                                </strong>

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Inspection
                            </small>

                            @if($snag->inspection)

                                <strong>
                                    {{ $snag->inspection->inspection_number }}
                                </strong>

                                <small class="d-block text-muted">

                                    {{ $snag->inspection->inspection_type }}

                                </small>

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Fit-Out Stage
                            </small>

                            @if($snag->fitoutStage)

                                {{ $snag->fitoutStage->stage_name }}

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Contractor
                            </small>

                            @if($snag->contractor)

                                {{
                                    $snag->contractor->company_name
                                    ??
                                    $snag->contractor->contractor_name
                                    ??
                                    $snag->contractor->name
                                    ??
                                    '-'
                                }}

                            @else

                                <span class="text-muted">
                                    Not assigned
                                </span>

                            @endif

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


                        <div class="col-md-4 mb-3">

                            <small class="text-muted d-block">
                                Reported Date
                            </small>

                            <strong>

                                {{
                                    $snag->reported_date
                                        ? $snag->reported_date->format('d M Y')
                                        : '-'
                                }}

                            </strong>

                        </div>


                        <div class="col-md-4 mb-3">

                            <small class="text-muted d-block">
                                Due Date
                            </small>

                            <strong>

                                {{
                                    $snag->due_date
                                        ? $snag->due_date->format('d M Y')
                                        : '-'
                                }}

                            </strong>

                        </div>


                        <div class="col-md-4 mb-3">

                            <small class="text-muted d-block">
                                Assigned To
                            </small>

                            <strong>

                                {{ $snag->assignedTo->name ?? 'Unassigned' }}

                            </strong>

                        </div>


                    </div>

                </div>

            </div>


            {{-- Corrective Action --}}
            @if($snag->corrective_action)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Corrective Action
                        </h5>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($snag->corrective_action)) !!}

                    </div>

                </div>

            @endif


            {{-- Verification --}}
            @if(
                $snag->verification_comments ||
                $snag->verified_by
            )

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Verification
                        </h5>

                    </div>

                    <div class="card-body">

                        @if($snag->verification_comments)

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Verification Comments
                                </small>

                                {!! nl2br(
                                    e($snag->verification_comments)
                                ) !!}

                            </div>

                        @endif


                        <div class="row">

                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Verified By
                                </small>

                                {{ $snag->verifiedBy->name ?? '-' }}

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Verified At
                                </small>

                                {{
                                    $snag->verified_at
                                        ? $snag->verified_at->format(
                                            'd M Y H:i'
                                        )
                                        : '-'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Resolve Form --}}
            @if(
                in_array(
                    $snag->status,
                    [
                        'Open',
                        'Assigned',
                        'In Progress',
                        'Reopened'
                    ]
                )
            )

                <div
                    class="card mb-4"
                    id="resolve"
                >

                    <div class="card-header">

                        <h5 class="mb-0">
                            Resolve Snag
                        </h5>

                    </div>


                    <div class="card-body">

                        <form
                            action="{{ route(
                                'admin.fitout.snags.resolve',
                                $snag->id
                            ) }}"
                            method="POST"
                        >

                            @csrf


                            <div class="mb-3">

                                <label class="form-label">
                                    Corrective Action
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    name="corrective_action"
                                    class="form-control"
                                    rows="4"
                                    required
                                    placeholder="Describe how the snag was corrected..."
                                >{{ old(
                                    'corrective_action',
                                    $snag->corrective_action
                                ) }}</textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Mark as Resolved

                            </button>

                        </form>

                    </div>

                </div>

            @endif


            {{-- Verification Form --}}
            @if($snag->status === 'Under Verification')

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Verify Snag
                        </h5>
                    </div>

                    <div class="card-body">

                        <form
                            action="{{ route(
                                'admin.fitout.snags.verify',
                                $snag->id
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    Verification Result
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="verification_status"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Result
                                    </option>

                                    <option value="Closed">
                                        Verified & Close
                                    </option>

                                    <option value="Reopened">
                                        Not Accepted - Reopen
                                    </option>

                                </select>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Verification Comments
                                </label>

                                <textarea
                                    name="verification_comments"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter verification comments..."
                                ></textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-shield-check me-1"></i>

                                Submit Verification

                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>


        {{-- ================================================= --}}
        {{-- RIGHT COLUMN --}}
        {{-- ================================================= --}}

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
                        {{ $snag->status }}
                    </span>

                </div>

            </div>


            {{-- Workflow --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Workflow
                    </h5>

                </div>


                <div class="card-body">


                    <div class="d-flex align-items-center mb-3">

                        <span class="badge bg-secondary me-2">
                            1
                        </span>

                        <span
                            class="{{ in_array(
                                $snag->status,
                                [
                                    'Open',
                                    'Assigned',
                                    'In Progress',
                                    'Resolved',
                                    'Under Verification',
                                    'Closed'
                                ]
                            ) ? 'fw-semibold' : 'text-muted' }}"
                        >
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


                    <div class="d-flex align-items-center mb-3">

                        <span class="badge bg-warning text-dark me-2">
                            5
                        </span>

                        <span>
                            Under Verification
                        </span>

                    </div>


                    <div class="d-flex align-items-center">

                        <span class="badge bg-dark me-2">
                            6
                        </span>

                        <span>
                            Closed
                        </span>

                    </div>

                </div>

            </div>


            {{-- Resolve --}}
            @if(
                in_array(
                    $snag->status,
                    [
                        'Open',
                        'Assigned',
                        'In Progress',
                        'Reopened'
                    ]
                )
            )

                <a
                    href="#resolve"
                    class="btn btn-success w-100 mb-2"
                >

                    <i class="bi bi-check-circle me-1"></i>

                    Resolve Snag

                </a>

            @endif


            {{-- Delete --}}
            @if(
                in_array(
                    $snag->status,
                    [
                        'Open',
                        'Rejected'
                    ]
                )
            )

                <form
                    action="{{ route(
                        'admin.fitout.snags.destroy',
                        $snag->id
                    ) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this snag?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger w-100"
                    >

                        <i class="bi bi-trash me-1"></i>

                        Delete Snag

                    </button>

                </form>

            @endif


        </div>

    </div>

</div>

@endsection