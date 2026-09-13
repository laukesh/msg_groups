@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Submittal Details
            </h4>

            <div class="text-muted">
                {{ $submittal->submittal_number }}
            </div>
        </div>

        <div class="d-flex gap-2">

            @if(
                in_array(
                    $submittal->status,
                    ['Draft', 'Revise & Resubmit'],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.submittals.edit',
                        [
                            'project' => $project,
                            'submittal' => $submittal,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif

            <a
                href="{{ route(
                    'admin.projects.construction.submittals.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Success --}}
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


    {{-- Main Information --}}
    <div class="row g-4">

        <div class="col-lg-8">

            {{-- Basic Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Submittal Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Submittal Number
                            </div>

                            <div class="fw-semibold">
                                {{ $submittal->submittal_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Submittal Date
                            </div>

                            <div>
                                {{ optional(
                                    $submittal->submittal_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div>
                                {{ $submittal->submittal_type ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Priority
                            </div>

                            <div>
                                {{ $submittal->priority }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Title
                            </div>

                            <div class="fs-5 fw-semibold">
                                {{ $submittal->title }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e(
                                        $submittal->description
                                        ?? 'No description provided.'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Project / Construction Details --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Construction Details
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Contractor --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contractor
                            </div>

                            @if($submittal->contract?->bidder)

                                <div class="fw-semibold">

                                    {{ $submittal->contract->bidder->company_name }}

                                </div>

                                <div class="small text-muted">

                                    {{ $submittal->contract->contract_number }}

                                </div>

                            @elseif($submittal->contract?->bidder_name)

                                {{ $submittal->contract->bidder_name }}

                            @else

                                —

                            @endif

                        </div>


                        {{-- Consultant --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Consultant
                            </div>

                            @if($submittal->consultant)

                                <div class="fw-semibold">

                                    {{ $submittal->consultant->company_name }}

                                </div>

                                @if($submittal->consultant->consultant_name)

                                    <div class="small text-muted">

                                        {{ $submittal->consultant->consultant_name }}

                                    </div>

                                @endif

                            @else

                                —

                            @endif

                        </div>


                        {{-- Work Order --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($submittal->workOrder)

                                <div class="fw-semibold">

                                    {{ $submittal->workOrder->work_order_number }}

                                </div>

                                <div class="small text-muted">

                                    {{ $submittal->workOrder->work_order_title }}

                                </div>

                            @else

                                —

                            @endif

                        </div>


                        {{-- Schedule Activity --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Schedule Activity
                            </div>

                            @if($submittal->scheduleActivity)

                                {{ $submittal->scheduleActivity->activity_name
                                    ?? $submittal->scheduleActivity->name
                                    ?? 'Activity #' . $submittal->schedule_activity_id
                                }}

                            @else

                                —

                            @endif

                        </div>


                        {{-- Location --}}
                        <div class="col-md-12">

                            <div class="text-muted small">
                                Location
                            </div>

                            <div>
                                {{ $submittal->location ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Document Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Document Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document Reference
                            </div>

                            <div>
                                {{ $submittal->document_reference ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Revision
                            </div>

                            <div>
                                {{ $submittal->revision_number ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Submitted By
                            </div>

                            <div>
                                {{ $submittal->submittedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Submitted To
                            </div>

                            <div>
                                {{ $submittal->submittedTo?->name ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Review Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Review Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Submission Date
                            </div>

                            <div>
                                {{ optional(
                                    $submittal->submission_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Review Due Date
                            </div>

                            <div>

                                {{ optional(
                                    $submittal->review_due_date
                                )->format('d M Y') ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Review Date
                            </div>

                            <div>
                                {{ optional(
                                    $submittal->review_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small mb-1">
                                Review Comments
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e(
                                        $submittal->review_comments
                                        ?? 'No review comments.'
                                    )
                                ) !!}

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small mb-1">
                                Response
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e(
                                        $submittal->response
                                        ?? 'No response.'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Remarks --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Remarks
                    </h5>

                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $submittal->remarks
                            ?? 'No remarks.'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        {{-- Right Side --}}
        <div class="col-lg-4">

            {{-- Status --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Status
                    </h5>

                </div>

                <div class="card-body text-center">

                    @php

                        $statusClass = match(
                            $submittal->status
                        ) {

                            'Approved' =>
                                'bg-success',

                            'Approved With Comments' =>
                                'bg-info text-dark',

                            'Submitted' =>
                                'bg-primary',

                            'Under Review' =>
                                'bg-warning text-dark',

                            'Revise & Resubmit' =>
                                'bg-warning text-dark',

                            'Rejected' =>
                                'bg-danger',

                            'Closed' =>
                                'bg-secondary',

                            'Cancelled' =>
                                'bg-dark',

                            default =>
                                'bg-light text-dark',
                        };

                    @endphp

                    <span
                        class="badge {{ $statusClass }} fs-6 px-3 py-2"
                    >
                        {{ $submittal->status }}
                    </span>

                </div>

            </div>
            {{-- Workflow Actions --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        Workflow Actions
                    </h5>
                </div>

                <div class="card-body">

                    {{-- ========================================================= --}}
                    {{-- DRAFT --}}
                    {{-- ========================================================= --}}

                    @if(
                        in_array(
                            $submittal->status,
                            ['Draft', 'Revise & Resubmit'],
                            true
                        )
                    )

                        <div class="mb-3">

                            <div class="text-muted small mb-2">
                                This submittal is ready for submission.
                            </div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.construction.submittals.submit',
                                    [
                                        'project' => $project,
                                        'submittal' => $submittal,
                                    ]
                                ) }}"
                                onsubmit="return confirm(
                                    'Submit this submittal for review?'
                                );"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    Submit for Review
                                </button>

                            </form>

                        </div>

                    @endif


                    {{-- ========================================================= --}}
                    {{-- SUBMITTED --}}
                    {{-- ========================================================= --}}

                    @if($submittal->status === 'Submitted')

                        <div class="mb-3">

                            <div class="text-muted small mb-2">
                                This submittal is waiting for review.
                            </div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.construction.submittals.start-review',
                                    [
                                        'project' => $project,
                                        'submittal' => $submittal,
                                    ]
                                ) }}"
                                onsubmit="return confirm(
                                    'Start review of this submittal?'
                                );"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-warning w-100"
                                >
                                    Start Review
                                </button>

                            </form>

                        </div>

                    @endif


                    {{-- ========================================================= --}}
                    {{-- UNDER REVIEW --}}
                    {{-- ========================================================= --}}

                    @if($submittal->status === 'Under Review')

                        <div class="d-grid gap-2">

                            {{-- Approve --}}
                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.construction.submittals.approve',
                                    [
                                        'project' => $project,
                                        'submittal' => $submittal,
                                    ]
                                ) }}"
                                onsubmit="return confirm(
                                    'Approve this submittal?'
                                );"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success w-100"
                                >
                                    Approve
                                </button>

                            </form>


                            {{-- Approve With Comments --}}
                            <button
                                type="button"
                                class="btn btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#approveWithCommentsModal"
                            >
                                Approve With Comments
                            </button>


                            {{-- Revise --}}
                            <button
                                type="button"
                                class="btn btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#reviseSubmittalModal"
                            >
                                Revise & Resubmit
                            </button>


                            {{-- Reject --}}
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectSubmittalModal"
                            >
                                Reject
                            </button>

                        </div>

                    @endif


                    {{-- ========================================================= --}}
                    {{-- FINAL STATUS --}}
                    {{-- ========================================================= --}}

                    @if(
                        in_array(
                            $submittal->status,
                            [
                                'Approved',
                                'Approved With Comments',
                                'Rejected',
                            ],
                            true
                        )
                    )

                        <div class="text-center">

                            @if($submittal->status === 'Approved')

                                <div class="text-success mb-2">
                                    <i class="bi bi-check-circle-fill fs-2"></i>
                                </div>

                                <div class="fw-semibold">
                                    Submittal Approved
                                </div>

                            @elseif(
                                $submittal->status ===
                                'Approved With Comments'
                            )

                                <div class="text-info mb-2">
                                    <i class="bi bi-check-circle fs-2"></i>
                                </div>

                                <div class="fw-semibold">
                                    Approved With Comments
                                </div>

                            @else

                                <div class="text-danger mb-2">
                                    <i class="bi bi-x-circle-fill fs-2"></i>
                                </div>

                                <div class="fw-semibold">
                                    Submittal Rejected
                                </div>

                            @endif

                        </div>

                    @endif

                </div>

            </div>


            {{-- Approval --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Approval
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Approval Date
                        </div>

                        <div>
                            {{ optional(
                                $submittal->approval_date
                            )->format('d M Y') ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Approved By
                        </div>

                        <div>
                            {{ $submittal->approvedBy?->name ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Audit --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div>
                            {{ $submittal->creator?->name ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>
                            {{ optional(
                                $submittal->created_at
                            )->format('d M Y H:i') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
{{-- ================================================================ --}}
{{-- APPROVE WITH COMMENTS MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="approveWithCommentsModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Approve With Comments
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.submittals.approve-with-comments',
                    [
                        'project' => $project,
                        'submittal' => $submittal,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Review Comments
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="review_comments"
                            rows="5"
                            class="form-control"
                            placeholder="Enter approval comments..."
                            required
                        ></textarea>

                    </div>

                    <div class="alert alert-info mb-0">

                        The submittal will be marked as
                        <strong>Approved With Comments</strong>.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-info"
                    >
                        Approve With Comments
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- REVISE & RESUBMIT MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="reviseSubmittalModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Request Revision
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.submittals.revise',
                    [
                        'project' => $project,
                        'submittal' => $submittal,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Revision Comments
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="review_comments"
                            rows="5"
                            class="form-control"
                            placeholder="Explain what needs to be revised..."
                            required
                        ></textarea>

                    </div>


                    <div class="alert alert-warning mb-0">

                        The submittal will be returned to the
                        contractor for revision and resubmission.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Request Revision
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- REJECT MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="rejectSubmittalModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-danger">
                    Reject Submittal
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.submittals.reject',
                    [
                        'project' => $project,
                        'submittal' => $submittal,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Rejection Reason
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="review_comments"
                            rows="5"
                            class="form-control"
                            placeholder="Enter reason for rejection..."
                            required
                        ></textarea>

                    </div>


                    <div class="alert alert-danger mb-0">

                        This action will mark the submittal as
                        <strong>Rejected</strong>.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject Submittal
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection