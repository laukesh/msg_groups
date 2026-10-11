@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0">
                    {{ $fitoutRequest->request_no }}
                </h4>

                @php
                    $statusClass = match ($fitoutRequest->fitout_status) {
                        'Draft' => 'secondary',
                        'Submitted' => 'info',
                        'Under Review' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'In Progress' => 'primary',
                        'Completed' => 'success',
                        'Closed' => 'dark',
                        default => 'secondary',
                    };
                @endphp

                <span class="badge bg-{{ $statusClass }}">
                    {{ $fitoutRequest->fitout_status }}
                </span>

            </div>

            <p class="text-muted mb-0 mt-1">
                Fit-Out Request Details
            </p>

        </div>

        <div class="d-flex gap-2">

            @if($fitoutRequest->fitout_status === 'Draft')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.submit', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-send me-1"></i>
                        Submit

                    </button>

                </form>

            @endif


            @if($fitoutRequest->fitout_status === 'Submitted')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.startReview', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-warning">

                        <i class="bi bi-search me-1"></i>
                        Start Review

                    </button>

                </form>

            @endif


            @if($fitoutRequest->fitout_status === 'Under Review')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.approve', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-lg me-1"></i>
                        Approve

                    </button>

                </form>


                <button type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectFitoutModal">

                    <i class="bi bi-x-lg me-1"></i>
                    Reject

                </button>

            @endif


            @if($fitoutRequest->fitout_status === 'Approved')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.start', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-play-fill me-1"></i>
                        Start Fit-Out

                    </button>

                </form>

            @endif


            @if($fitoutRequest->fitout_status === 'In Progress')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.complete', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>
                        Mark Completed

                    </button>

                </form>

            @endif


            @if($fitoutRequest->fitout_status === 'Completed')

                <form method="POST"
                      action="{{ route('admin.fitout.requests.close', $fitoutRequest->id) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-dark">

                        <i class="bi bi-lock me-1"></i>
                        Close

                    </button>

                </form>

            @endif
            @if($fitoutRequest->stages->count() > 0)

                <a href="{{ route(
                    'admin.fitout.stages.index',
                    $fitoutRequest->id
                ) }}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-list-check me-1"></i>

                    Stages

                </a>

            @endif

            @if(
                !$fitoutRequest->approvals->count() &&
                in_array(
                    $fitoutRequest->fitout_status,
                    ['Submitted', 'Under Review']
                )
            )

                <form
                    action="{{ route('admin.fitout.requests.generate-approval', $fitoutRequest->id) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Generate the approval workflow for this Fit-Out Request?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-diagram-3"></i>

                        Generate Approval Workflow

                    </button>

                </form>

            @endif

            <a href="{{ route('admin.fitout.requests.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BASIC INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Request Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Request No.
                    </small>

                    <strong>
                        {{ $fitoutRequest->request_no }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Fit-Out Type
                    </small>

                    <strong>
                        {{ $fitoutRequest->fitout_type }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Proposed Start
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->proposed_start_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Proposed End
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->proposed_end_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual Start
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->actual_start_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual End
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->actual_end_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Estimated Cost
                    </small>

                    <strong>
                        $ {{ number_format((float) $fitoutRequest->estimated_cost, 2) }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Work Permit No.
                    </small>

                    <strong>
                        {{ $fitoutRequest->work_permit_no ?? '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LEASE / TENANT / UNIT --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Lease & Tenant Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Lease Agreement
                    </small>

                    <strong>
                        {{ $fitoutRequest->leaseAgreement->agreement_no ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Tenant
                    </small>

                    <strong>
                        {{ $fitoutRequest->tenant->company_name ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Tenant Code
                    </small>

                    <strong>
                        {{ $fitoutRequest->tenant->tenant_code ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Unit
                    </small>

                    <strong>
                        {{ $fitoutRequest->unit->unit_no ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Lease Start
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->leaseAgreement?->lease_start_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Lease End
                    </small>

                    <strong>
                        {{ optional($fitoutRequest->leaseAgreement?->lease_end_date)->format('d M Y') ?? '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CONTRACTOR --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Contractor Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contractor
                    </small>

                    <strong>
                        {{ $fitoutRequest->contractor->contractor_name
                            ?? $fitoutRequest->contractor_name
                            ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contractor Code
                    </small>

                    <strong>
                        {{ $fitoutRequest->contractor->contractor_code ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contact
                    </small>

                    <strong>
                        {{ $fitoutRequest->contractor->mobile
                            ?? $fitoutRequest->contractor_contact
                            ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Insurance
                    </small>

                    @if($fitoutRequest->insurance_verified === 'Yes')

                        <span class="badge bg-success">
                            Verified
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            Not Verified
                        </span>

                    @endif

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Safety Induction
                    </small>

                    @if($fitoutRequest->safety_induction_completed === 'Yes')

                        <span class="badge bg-success">
                            Completed
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- WORK DESCRIPTION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Work Details
            </strong>

        </div>


        <div class="card-body">

            <div class="mb-3">

                <label class="text-muted">
                    Work Description
                </label>

                <div class="mt-1">

                    {!! nl2br(e(
                        $fitoutRequest->work_description ?? '-'
                    )) !!}

                </div>

            </div>


            <div>

                <label class="text-muted">
                    Remarks
                </label>

                <div class="mt-1">

                    {!! nl2br(e(
                        $fitoutRequest->remarks ?? '-'
                    )) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STAGES --}}
    {{-- ========================================================= --}}

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Fit-Out Stages</h5>

            <span class="badge bg-primary">
                {{ $fitoutRequest->stages->count() }} Stages
            </span>
        </div>

        <div class="card-body p-0">

            @if($fitoutRequest->stages->count())

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Stage</th>
                                <th>Contractor</th>
                                <th>Planned Start</th>
                                <th>Planned End</th>
                                <th>Actual Start</th>
                                <th>Actual End</th>
                                <th width="150">Progress</th>
                                <th>Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($fitoutRequest->stages as $stage)
                                <tr>

                                    <td>
                                        {{ $stage->stage_sequence }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $stage->stage_name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $stage->contractor?->contractor_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $stage->planned_start_date?->format('d-m-Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $stage->planned_end_date?->format('d-m-Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $stage->actual_start_date?->format('d-m-Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $stage->actual_end_date?->format('d-m-Y') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="progress" style="height: 18px;">
                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                style="width: {{ $stage->completion_percentage }}%;"
                                            >
                                                {{ number_format($stage->completion_percentage, 0) }}%
                                            </div>
                                        </div>

                                    </td>

                                    <td>
                                        @php
                                            $statusClass = match($stage->stage_status) {
                                                'Completed' => 'success',
                                                'In Progress' => 'primary',
                                                'On Hold' => 'warning',
                                                'Cancelled' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $stage->stage_status }}
                                        </span>
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('admin.fitout.stages.show', $stage->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else

                <div class="p-4 text-center text-muted">
                    No stages generated for this fit-out request.
                </div>

            @endif

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- DOCUMENTS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Documents
            </strong>

            <span class="badge bg-secondary">
                {{ $fitoutRequest->documents->count() }}
            </span>

        </div>


        <div class="card-body">

            @if($fitoutRequest->documents->count())

                <div class="table-responsive">

                    <table class="table table-sm">

                        <thead>

                            <tr>

                                <th>
                                    Document
                                </th>

                                <th>
                                    Document No.
                                </th>

                                <th>
                                    Version
                                </th>

                                <th>
                                    Approval Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($fitoutRequest->documents as $document)

                                <tr>

                                    <td>
                                        {{ $document->document_title }}
                                    </td>

                                    <td>
                                        {{ $document->document_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $document->version_no ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $document->approval_status }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-muted">
                    No documents uploaded.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVALS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Approvals
            </strong>

            <span class="badge bg-secondary">
                {{ $fitoutRequest->approvals->count() }}
            </span>

        </div>


        <div class="card-body">

            @if($fitoutRequest->approvals->count())

                <div class="table-responsive">

                    <table class="table table-sm">

                        <thead>

                            <tr>

                                <th>
                                    Level
                                </th>

                                <th>
                                    Approval Type
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action Date
                                </th>

                                <th>
                                    Comments
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($fitoutRequest->approvals as $approval)

                                <tr>

                                    <td>
                                        {{ $approval->approval_level }}
                                    </td>

                                    <td>
                                        {{ $approval->approval_type }}
                                    </td>

                                    <td>
                                        {{ $approval->status }}
                                    </td>

                                    <td>
                                        {{ optional($approval->action_at)->format('d M Y H:i') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $approval->comments ?? '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-muted">
                    No approvals created.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INSPECTIONS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Inspections
            </strong>

            <span class="badge bg-secondary">
                {{ $fitoutRequest->inspections->count() }}
            </span>

        </div>


        <div class="card-body">

            @if($fitoutRequest->inspections->count())

                <div class="table-responsive">

                    <table class="table table-sm">

                        <thead>

                            <tr>

                                <th>
                                    Inspection No.
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Scheduled Date
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

                            @foreach($fitoutRequest->inspections as $inspection)

                                <tr>

                                    <td>
                                        {{ $inspection->inspection_number }}
                                    </td>

                                    <td>
                                        {{ $inspection->inspection_type }}
                                    </td>

                                    <td>
                                        {{ optional($inspection->scheduled_date)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $inspection->result ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $inspection->status }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-muted">
                    No inspections created.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SNAGS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Snags
            </strong>

            <span class="badge bg-secondary">
                {{ $fitoutRequest->snags->count() }}
            </span>

        </div>


        <div class="card-body">

            @if($fitoutRequest->snags->count())

                <div class="table-responsive">

                    <table class="table table-sm">

                        <thead>

                            <tr>

                                <th>
                                    Snag No.
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($fitoutRequest->snags as $snag)

                                <tr>

                                    <td>
                                        {{ $snag->snag_number }}
                                    </td>

                                    <td>
                                        {{ $snag->title }}
                                    </td>

                                    <td>
                                        {{ $snag->priority }}
                                    </td>

                                    <td>
                                        {{ optional($snag->due_date)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $snag->status }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-muted">
                    No snags recorded.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- HANDOVERS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <strong>
                Handovers
            </strong>

            <span class="badge bg-secondary">
                {{ $fitoutRequest->handovers->count() }}
            </span>

        </div>


        <div class="card-body">

            @if($fitoutRequest->handovers->count())

                <div class="table-responsive">

                    <table class="table table-sm">

                        <thead>

                            <tr>

                                <th>
                                    Handover No.
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Handover Date
                                </th>

                                <th>
                                    Condition
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($fitoutRequest->handovers as $handover)

                                <tr>

                                    <td>
                                        {{ $handover->handover_number }}
                                    </td>

                                    <td>
                                        {{ $handover->handover_type }}
                                    </td>

                                    <td>
                                        {{ optional($handover->handover_date)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $handover->unit_condition ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $handover->status }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-muted">
                    No handovers recorded.
                </div>

            @endif

        </div>

    </div>

</div>



@endsection

@if($fitoutRequest->fitout_status === 'Under Review')

<div class="modal fade"
     id="rejectFitoutModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route('admin.fitout.requests.reject', $fitoutRequest->id) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Fit-Out Request
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <label class="form-label">

                        Rejection Reason
                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="rejection_reason"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Reject Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif