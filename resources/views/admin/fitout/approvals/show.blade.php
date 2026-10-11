@extends('layouts.app')

@section('title', 'Approval Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Approval Details
            </h4>

            <p class="text-muted mb-0">
                Review fit-out request before taking approval action.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.fitout.approvals.pending') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            @if($approval->fitoutRequest)

                <a
                    href="{{ route('admin.fitout.requests.show', $approval->fitoutRequest->id) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-eye me-1"></i>
                    View Request
                </a>

            @endif

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

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

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Request Information --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Request
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Request Number
                            </label>

                            <div class="fw-semibold">

                                {{ $approval->fitoutRequest->request_no ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Fit-Out Type
                            </label>

                            <div class="fw-semibold">

                                {{ $approval->fitoutRequest->fitout_type ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Tenant
                            </label>

                            <div class="fw-semibold">

                                {{
                                    $approval->fitoutRequest->tenant->company_name
                                    ?? $approval->fitoutRequest->tenant->company_name
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Unit
                            </label>

                            <div class="fw-semibold">

                                {{ $approval->fitoutRequest->unit->unit_no ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Contractor
                            </label>

                            <div class="fw-semibold">

                                {{ $approval->fitoutRequest->contractor->contractor_name ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Contractor Contact
                            </label>

                            <div class="fw-semibold">

                                {{ $approval->fitoutRequest->contractor_contact ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Proposed Start
                            </label>

                            <div>

                                {{
                                    optional(
                                        $approval->fitoutRequest->proposed_start_date
                                    )->format('d M Y')
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Proposed End
                            </label>

                            <div>

                                {{
                                    optional(
                                        $approval->fitoutRequest->proposed_end_date
                                    )->format('d M Y')
                                    ?? 'N/A'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Estimated Cost
                            </label>

                            <div class="fw-semibold">

                                $ {{ number_format(
                                    (float) ($approval->fitoutRequest->estimated_cost ?? 0),
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Request Status
                            </label>

                            <div>

                                <span class="badge bg-info text-dark">

                                    {{ $approval->fitoutRequest->fitout_status ?? 'N/A' }}

                                </span>

                            </div>

                        </div>


                        <div class="col-12">

                            <label class="text-muted small">
                                Work Description
                            </label>

                            <div class="border rounded p-3 bg-light">

                                {{ $approval->fitoutRequest->work_description ?? 'No description provided.' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Documents --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Submitted Documents
                    </h5>

                </div>


                <div class="card-body p-0">

                    @if($approval->fitoutRequest?->documents?->count())

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Document
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Version
                                        </th>

                                        <th class="text-end">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $approval->fitoutRequest->documents
                                        as $document
                                    )

                                        <tr>

                                            <td>

                                                <div class="fw-semibold">

                                                    {{
                                                        $document->document_title
                                                        ?? $document->file_name
                                                        ?? 'Document'
                                                    }}

                                                </div>

                                            </td>


                                            <td>

                                                <span class="badge bg-secondary">

                                                    {{
                                                        $document->approval_status
                                                        ?? 'Pending'
                                                    }}

                                                </span>

                                            </td>


                                            <td>

                                                v{{ $document->version_no ?? 1 }}

                                            </td>


                                            <td class="text-end">

                                                @if($document->file_path)

                                                    <a
                                                        href="{{ asset('storage/' . $document->file_path) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary"
                                                    >

                                                        <i class="bi bi-file-earmark-text"></i>

                                                        View

                                                    </a>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-4 text-muted">

                            No documents submitted.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Approval History --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Approval Workflow
                    </h5>

                </div>


                <div class="card-body">

                    @if($approval->fitoutRequest?->approvals?->count())

                        <div class="row">

                            @foreach(
                                $approval->fitoutRequest->approvals
                                as $item
                            )

                                <div class="col-md-4 mb-3">

                                    <div class="border rounded p-3 h-100">

                                        <div class="d-flex justify-content-between">

                                            <strong>

                                                Level {{ $item->approval_level }}

                                            </strong>


                                            @if($item->approval_status === 'Approved')

                                                <span class="badge bg-success">
                                                    Approved
                                                </span>

                                            @elseif($item->approval_status === 'Rejected')

                                                <span class="badge bg-danger">
                                                    Rejected
                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>

                                            @endif

                                        </div>


                                        <div class="mt-2">

                                            {{ $item->approval_type }}

                                        </div>


                                        <small class="text-muted d-block mt-2">

                                            {{
                                                $item->approver->name
                                                ?? 'Not Assigned'
                                            }}

                                        </small>


                                        @if($item->approved_at)

                                            <small class="text-muted">

                                                {{
                                                    $item->approved_at->format(
                                                        'd M Y H:i'
                                                    )
                                                }}

                                            </small>

                                        @endif


                                        @if($item->rejection_reason)

                                            <div class="alert alert-danger mt-2 mb-0 py-2">

                                                <small>

                                                    <strong>
                                                        Rejection:
                                                    </strong>

                                                    {{ $item->rejection_reason }}

                                                </small>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <p class="text-muted mb-0">
                            Approval workflow has not been generated.
                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Current Approval --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Current Approval
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="text-muted small">
                            Approval Type
                        </label>

                        <div class="fw-semibold">

                            {{ $approval->approval_type }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Approval Level
                        </label>

                        <div>

                            <span class="badge bg-secondary">

                                Level {{ $approval->approval_level }}

                            </span>

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Status
                        </label>

                        <div>

                            @if($approval->approval_status === 'Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($approval->approval_status === 'Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>


                    @if($approval->approver)

                        <div class="mb-3">

                            <label class="text-muted small">
                                Approved / Reviewed By
                            </label>

                            <div>

                                {{ $approval->approver->name }}

                            </div>

                        </div>

                    @endif


                    {{-- Actions --}}
                    @if($approval->approval_status === 'Pending')

                        <hr>


                        <form
                            action="{{ route('admin.fitout.approvals.approve', $approval->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to approve this request?');"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Approve

                            </button>

                        </form>


                        <button
                            type="button"
                            class="btn btn-danger w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Reject

                        </button>

                    @endif

                </div>

            </div>


            {{-- Rejection Information --}}
            @if($approval->approval_status === 'Rejected')

                <div class="card border-danger">

                    <div class="card-header text-danger">

                        <strong>
                            Rejection Reason
                        </strong>

                    </div>

                    <div class="card-body">

                        {{ $approval->rejection_reason ?? 'No reason provided.' }}

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- Reject Modal --}}
<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('admin.fitout.approvals.reject', $approval->id) }}"
                method="POST"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Fit-Out Approval
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Rejection Reason
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="rejection_reason"
                            class="form-control"
                            rows="5"
                            required
                            maxlength="1000"
                            placeholder="Enter reason for rejection..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="bi bi-x-circle me-1"></i>

                        Reject Approval

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection