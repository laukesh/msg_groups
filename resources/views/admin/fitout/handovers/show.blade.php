@extends('layouts.app')

@section('title', 'Handover Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handover Details
            </h4>

            <p class="text-muted mb-0">
                {{ $handover->handover_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.handovers.index') }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            <a
                href="{{ route(
                    'admin.fitout.handovers.edit',
                    $handover->id
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>

        </div>

    </div>


    {{-- Success --}}
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


    {{-- ========================================================= --}}
    {{-- STATUS HEADER --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-4">

                    <small class="text-muted">
                        Handover Number
                    </small>

                    <h4 class="mb-0">
                        {{ $handover->handover_number }}
                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Status
                    </small>

                    @php

                        $statusClass = match(
                            $handover->status
                        ) {

                            'Pending' =>
                                'bg-warning text-dark',

                            'Scheduled' =>
                                'bg-info text-dark',

                            'In Progress' =>
                                'bg-primary',

                            'Accepted' =>
                                'bg-success',

                            'Completed' =>
                                'bg-success',

                            'Rejected' =>
                                'bg-danger',

                            'Cancelled' =>
                                'bg-secondary',

                            default =>
                                'bg-secondary',

                        };

                    @endphp

                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $handover->status }}
                    </span>

                </div>


                <div class="col-md-4 text-md-end">

                    <small class="text-muted d-block">
                        Handover Type
                    </small>

                    <strong>
                        {{ $handover->handover_type }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    <div class="row">


        {{-- ===================================================== --}}
        {{-- LEFT COLUMN --}}
        {{-- ===================================================== --}}

        <div class="col-lg-8">


            {{-- ================================================= --}}
            {{-- FIT-OUT INFORMATION --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Fit-Out Request
                            </small>

                            @if($handover->fitoutRequest)

                                <strong>
                                    {{ $handover->fitoutRequest->request_no }}
                                </strong>

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Unit
                            </small>

                            <strong>

                                @if($handover->unit)

                                    {{ $handover->unit->unit_no }}

                                @else

                                    -

                                @endif

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Tenant
                            </small>

                            <strong>

                                @if($handover->tenant)

                                    {{
                                        $handover->tenant->company_name
                                        ??
                                        $handover->tenant->tenant_name
                                        ??
                                        $handover->tenant->name
                                        ??
                                        'Tenant #' .
                                        $handover->tenant->id
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

                                @if($handover->contractor)

                                    {{
                                        $handover->contractor->company_name
                                        ??
                                        $handover->contractor->contractor_name
                                        ??
                                        $handover->contractor->name
                                        ??
                                        'Contractor #' .
                                        $handover->contractor->id
                                    }}

                                @else

                                    -

                                @endif

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INSPECTION --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Final Inspection
                    </h5>

                </div>


                <div class="card-body">

                    @if($handover->finalInspection)

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Inspection Number
                                </small>

                                <strong>
                                    {{
                                        $handover
                                            ->finalInspection
                                            ->inspection_number
                                    }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Inspection Type
                                </small>

                                {{
                                    $handover
                                        ->finalInspection
                                        ->inspection_type
                                }}

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Inspection Date
                                </small>

                                {{
                                    $handover
                                        ->finalInspection
                                        ->inspection_date
                                        ?->format('d M Y')
                                    ?? '-'
                                }}

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    Result
                                </small>

                                @php

                                    $inspectionResult =
                                        $handover
                                            ->finalInspection
                                            ->result;

                                @endphp

                                <span
                                    class="badge {{
                                        $inspectionResult === 'Passed'
                                            ? 'bg-success'
                                            : (
                                                $inspectionResult === 'Failed'
                                                    ? 'bg-danger'
                                                    : 'bg-warning text-dark'
                                            )
                                    }}"
                                >

                                    {{ $inspectionResult }}

                                </span>

                            </div>


                            @if(
                                $handover
                                    ->finalInspection
                                    ->observations
                            )

                                <div class="col-md-12">

                                    <small class="text-muted d-block">
                                        Observations
                                    </small>

                                    <p class="mb-0">
                                        {{
                                            $handover
                                                ->finalInspection
                                                ->observations
                                        }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    @else

                        <div class="text-muted">

                            <i class="bi bi-exclamation-circle me-1"></i>

                            No final inspection has been linked.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- UNIT CONDITION --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Unit Condition
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Condition
                            </small>

                            @php

                                $conditionClass = match(
                                    $handover->unit_condition
                                ) {

                                    'Good' =>
                                        'bg-success',

                                    'Minor Issues' =>
                                        'bg-warning text-dark',

                                    'Major Issues' =>
                                        'bg-danger',

                                    'Not Ready' =>
                                        'bg-secondary',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            @if($handover->unit_condition)

                                <span
                                    class="badge {{ $conditionClass }}"
                                >
                                    {{ $handover->unit_condition }}
                                </span>

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-3 mb-3">

                            <small class="text-muted d-block">
                                Key Count
                            </small>

                            <strong>
                                {{ $handover->key_count ?? 0 }}
                            </strong>

                        </div>


                        <div class="col-md-3 mb-3">

                            <small class="text-muted d-block">
                                Access Cards
                            </small>

                            <strong>
                                {{ $handover->access_card_count ?? 0 }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- METERS --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Meter Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Electricity --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <h6 class="mb-3">
                                    <i class="bi bi-lightning-charge me-1"></i>
                                    Electricity
                                </h6>


                                <div class="mb-2">

                                    <small class="text-muted">
                                        Meter Number
                                    </small>

                                    <div>

                                        {{
                                            $handover
                                                ->electricity_meter_no
                                            ?? '-'
                                        }}

                                    </div>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Reading
                                    </small>

                                    <div class="fw-semibold">

                                        {{
                                            $handover
                                                ->electricity_meter_reading
                                            ?? '-'
                                        }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Water --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <h6 class="mb-3">
                                    <i class="bi bi-droplet me-1"></i>
                                    Water
                                </h6>


                                <div class="mb-2">

                                    <small class="text-muted">
                                        Meter Number
                                    </small>

                                    <div>

                                        {{
                                            $handover
                                                ->water_meter_no
                                            ?? '-'
                                        }}

                                    </div>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Reading
                                    </small>

                                    <div class="fw-semibold">

                                        {{
                                            $handover
                                                ->water_meter_reading
                                            ?? '-'
                                        }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- REMARKS --}}
            {{-- ================================================= --}}

            @if($handover->remarks)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Remarks
                        </h5>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($handover->remarks)) !!}

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- DOCUMENT --}}
            {{-- ================================================= --}}

            @if($handover->handover_document_path)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Handover Document
                        </h5>

                    </div>

                    <div class="card-body">

                        <a
                            href="{{ asset(
                                'storage/' .
                                $handover->handover_document_path
                            ) }}"
                            target="_blank"
                            class="btn btn-outline-primary"
                        >

                            <i class="bi bi-file-earmark-pdf me-1"></i>

                            View Handover Document

                        </a>

                    </div>

                </div>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- RIGHT COLUMN --}}
        {{-- ===================================================== --}}

        <div class="col-lg-4">


            {{-- ================================================= --}}
            {{-- HANDOVER DATE --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Handover Schedule
                    </h5>

                </div>


                <div class="card-body">

                    <small class="text-muted d-block">
                        Handover Date
                    </small>

                    <h5 class="mb-0">

                        {{
                            $handover->handover_date
                                ? $handover->handover_date
                                    ->format('d M Y')
                                : 'Not Scheduled'
                        }}

                    </h5>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ACCEPTANCE --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Acceptance & Approval
                    </h5>

                </div>


                <div class="card-body">


                    {{-- Tenant --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">

                            <strong>
                                Tenant
                            </strong>

                            @if($handover->tenant_accepted_at)

                                <span class="badge bg-success">
                                    Accepted
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>


                        @if($handover->tenantAcceptedBy)

                            <small class="text-muted d-block mt-1">

                                By:
                                {{ $handover->tenantAcceptedBy->name }}

                                <br>

                                {{
                                    $handover
                                        ->tenant_accepted_at
                                        ?->format('d M Y H:i')
                                }}

                            </small>

                        @endif

                    </div>


                    {{-- Contractor --}}
                    <div class="mb-4">

                        <div class="d-flex justify-content-between">

                            <strong>
                                Contractor
                            </strong>

                            @if($handover->contractor_accepted_at)

                                <span class="badge bg-success">
                                    Accepted
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>


                        @if($handover->contractorAcceptedBy)

                            <small class="text-muted d-block mt-1">

                                By:
                                {{ $handover->contractorAcceptedBy->name }}

                                <br>

                                {{
                                    $handover
                                        ->contractor_accepted_at
                                        ?->format('d M Y H:i')
                                }}

                            </small>

                        @endif

                    </div>


                    {{-- Mall --}}
                    <div>

                        <div class="d-flex justify-content-between">

                            <strong>
                                Mall Management
                            </strong>

                            @if($handover->mall_approved_at)

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>


                        @if($handover->mallApprovedBy)

                            <small class="text-muted d-block mt-1">

                                By:
                                {{ $handover->mallApprovedBy->name }}

                                <br>

                                {{
                                    $handover
                                        ->mall_approved_at
                                        ?->format('d M Y H:i')
                                }}

                            </small>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- WORKFLOW ACTIONS --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        Handover Workflow
                    </h5>
                </div>

                <div class="card-body">

                    {{-- ============================================= --}}
                    {{-- PENDING --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Pending')

                        <div class="mb-3">

                            <div class="alert alert-warning mb-3">

                                <i class="bi bi-clock me-1"></i>

                                This handover is pending scheduling.

                            </div>

                            <form
                                action="{{ route(
                                    'admin.fitout.handovers.schedule',
                                    $handover->id
                                ) }}"
                                method="POST"
                            >

                                @csrf

                                <label class="form-label">
                                    Handover Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="handover_date"
                                    class="form-control mb-2"
                                    value="{{ old(
                                        'handover_date',
                                        optional(
                                            $handover->handover_date
                                        )->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >

                                    <i class="bi bi-calendar-check me-1"></i>

                                    Schedule Handover

                                </button>

                            </form>

                        </div>

                    @endif


                    {{-- ============================================= --}}
                    {{-- SCHEDULED --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Scheduled')

                        <div class="alert alert-info">

                            <i class="bi bi-calendar-check me-1"></i>

                            Handover is scheduled for

                            <strong>
                                {{
                                    $handover->handover_date
                                        ? $handover->handover_date
                                            ->format('d M Y')
                                        : '-'
                                }}
                            </strong>

                        </div>


                        <form
                            action="{{ route(
                                'admin.fitout.handovers.start',
                                $handover->id
                            ) }}"
                            method="POST"
                            onsubmit="return confirm(
                                'Start this handover?'
                            );"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >

                                <i class="bi bi-play-circle me-1"></i>

                                Start Handover

                            </button>

                        </form>

                    @endif


                    {{-- ============================================= --}}
                    {{-- IN PROGRESS --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'In Progress')

                        <div class="alert alert-primary">

                            <i class="bi bi-arrow-repeat me-1"></i>

                            Handover is currently in progress.

                        </div>


                        {{-- Tenant Acceptance --}}
                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <strong>
                                    Tenant Acceptance
                                </strong>

                                @if($handover->tenant_accepted_at)

                                    <span class="badge bg-success">
                                        Accepted
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </div>


                            @if($handover->tenant_accepted_at)

                                <small class="text-muted">

                                    Accepted by:

                                    {{
                                        $handover->tenantAcceptedBy->name
                                        ?? '-'
                                    }}

                                    <br>

                                    {{
                                        $handover->tenant_accepted_at
                                            ->format('d M Y H:i')
                                    }}

                                </small>

                            @else

                                <form
                                    action="{{ route(
                                        'admin.fitout.handovers.tenant-accept',
                                        $handover->id
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Record tenant acceptance?'
                                    );"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                    >

                                        <i class="bi bi-check-circle me-1"></i>

                                        Tenant Accept

                                    </button>

                                </form>

                            @endif

                        </div>


                        {{-- Contractor Acceptance --}}
                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <strong>
                                    Contractor Acceptance
                                </strong>

                                @if($handover->contractor_accepted_at)

                                    <span class="badge bg-success">
                                        Accepted
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </div>


                            @if($handover->contractor_accepted_at)

                                <small class="text-muted">

                                    Accepted by:

                                    {{
                                        $handover->contractorAcceptedBy->name
                                        ?? '-'
                                    }}

                                    <br>

                                    {{
                                        $handover->contractor_accepted_at
                                            ->format('d M Y H:i')
                                    }}

                                </small>

                            @else

                                <form
                                    action="{{ route(
                                        'admin.fitout.handovers.contractor-accept',
                                        $handover->id
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Record contractor acceptance?'
                                    );"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                    >

                                        <i class="bi bi-check-circle me-1"></i>

                                        Contractor Accept

                                    </button>

                                </form>

                            @endif

                        </div>


                        {{-- Mall Approval --}}
                        <div class="border rounded p-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <strong>
                                    Mall Management Approval
                                </strong>

                                @if($handover->mall_approved_at)

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </div>


                            @if($handover->mall_approved_at)

                                <small class="text-muted">

                                    Approved by:

                                    {{
                                        $handover->mallApprovedBy->name
                                        ?? '-'
                                    }}

                                    <br>

                                    {{
                                        $handover->mall_approved_at
                                            ->format('d M Y H:i')
                                    }}

                                </small>

                            @elseif(
                                $handover->tenant_accepted_at &&
                                $handover->contractor_accepted_at
                            )

                                <form
                                    action="{{ route(
                                        'admin.fitout.handovers.approve',
                                        $handover->id
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Approve this handover?'
                                    );"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-primary w-100"
                                    >

                                        <i class="bi bi-shield-check me-1"></i>

                                        Approve Handover

                                    </button>

                                </form>

                            @else

                                <small class="text-muted">

                                    Tenant and contractor acceptance are required
                                    before mall approval.

                                </small>

                            @endif

                        </div>

                    @endif


                    {{-- ============================================= --}}
                    {{-- ACCEPTED --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Accepted')

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Handover has been approved by Mall Management.

                        </div>


                        <form
                            action="{{ route(
                                'admin.fitout.handovers.complete',
                                $handover->id
                            ) }}"
                            method="POST"
                            onsubmit="return confirm(
                                'Complete this handover?'
                            );"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >

                                <i class="bi bi-check2-all me-1"></i>

                                Complete Handover

                            </button>

                        </form>

                    @endif


                    {{-- ============================================= --}}
                    {{-- COMPLETED --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Completed')

                        <div class="alert alert-success mb-0">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            <strong>
                                Handover Completed
                            </strong>

                            <div class="small mt-1">

                                All required handover approvals have been completed.

                            </div>

                        </div>

                    @endif


                    {{-- ============================================= --}}
                    {{-- REJECTED --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Rejected')

                        <div class="alert alert-danger mb-0">

                            <i class="bi bi-x-circle me-1"></i>

                            <strong>
                                Handover Rejected
                            </strong>

                            <div class="small mt-1">

                                This handover has been rejected.

                            </div>

                        </div>

                    @endif


                    {{-- ============================================= --}}
                    {{-- CANCELLED --}}
                    {{-- ============================================= --}}

                    @if($handover->status === 'Cancelled')

                        <div class="alert alert-secondary mb-0">

                            <i class="bi bi-slash-circle me-1"></i>

                            <strong>
                                Handover Cancelled
                            </strong>

                        </div>

                    @endif

                    @if($handover->status === 'Completed')

                        <a
                            href="{{ route(
                                'admin.fitout.handovers.certificate',
                                $handover->id
                            ) }}"
                            target="_blank"
                            class="btn btn-dark w-100 mt-2"
                        >

                            <i class="bi bi-file-earmark-text me-1"></i>

                            Handover Certificate

                        </a>

                    @endif

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RECORD INFORMATION --}}
            {{-- ================================================= --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created By
                        </small>

                        <strong>
                            {{ $handover->createdBy->name ?? '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created At
                        </small>

                        {{
                            $handover->created_at
                                ? $handover->created_at
                                    ->format('d M Y H:i')
                                : '-'
                        }}

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        {{
                            $handover->updated_at
                                ? $handover->updated_at
                                    ->format('d M Y H:i')
                                : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection