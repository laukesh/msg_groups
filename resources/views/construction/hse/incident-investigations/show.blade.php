@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Investigation Details
            </h3>


            <div class="text-muted">

                {{ $investigation->investigation_number }}

                &nbsp; | &nbsp;

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- =================================================
                EDIT
            ================================================== --}}

            @if(
                in_array(
                    $investigation->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.investigations.edit',
                        [
                            'project' => $project,
                            'incident' => $incident,
                            'investigation' => $investigation,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="bi bi-pencil me-1"></i>

                    Edit

                </a>

            @endif


            {{-- =================================================
                INVESTIGATIONS
            ================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.investigations.index',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-list me-1"></i>

                Investigations

            </a>


            {{-- =================================================
                INCIDENT
            ================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                Incident

            </a>

        </div>

    </div>



    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

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



    {{-- =========================================================
        STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Investigation Number
                    </div>


                    <h4 class="mb-1">
                        {{ $investigation->investigation_number }}
                    </h4>


                    <div class="text-muted">

                        Investigation Date:

                        {{ $investigation->investigation_date
                            ? $investigation->investigation_date->format('d-m-Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <div class="text-muted small mb-1">
                        Investigation Status
                    </div>


                    @switch($investigation->status)

                        @case('Draft')

                            <span class="badge bg-secondary fs-6">
                                Draft
                            </span>

                            @break


                        @case('Submitted')

                            <span class="badge bg-warning text-dark fs-6">
                                Submitted
                            </span>

                            @break


                        @case('Approved')

                            <span class="badge bg-success fs-6">
                                Approved
                            </span>

                            @break


                        @case('Rejected')

                            <span class="badge bg-danger fs-6">
                                Rejected
                            </span>

                            @break


                        @default

                            <span class="badge bg-secondary fs-6">
                                {{ $investigation->status }}
                            </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        INCIDENT STATUS
    ========================================================== --}}

    @if($investigation->status === 'Approved')

        <div class="alert alert-success mb-4">

            <i class="bi bi-check-circle me-1"></i>

            <strong>
                Investigation Completed
            </strong>


            <div class="mt-1">

                This investigation has been approved.

                The incident has now moved to

                <strong>
                    Investigation Completed
                </strong>

                and is ready for incident actions.

            </div>

        </div>

    @endif



    @if($investigation->status === 'Rejected')

        <div class="alert alert-danger mb-4">

            <i class="bi bi-exclamation-triangle me-1"></i>

            <strong>
                Investigation Rejected
            </strong>


            <div class="mt-1">

                Please review the rejection remarks,
                update the investigation and resubmit it.

            </div>

        </div>

    @endif



    {{-- =========================================================
        INVESTIGATOR
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investigation Team
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Lead Investigator
                    </div>


                    <div class="fw-semibold">

                        {{ $investigation->lead_investigator_name
                            ?? $investigation->leadInvestigator?->name
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-8">

                    <div class="text-muted small">
                        Investigation Team
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->investigation_team
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        CAUSE ANALYSIS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Cause Analysis
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Immediate Cause --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Immediate Cause
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->immediate_cause
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Root Cause --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Root Cause
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->root_cause
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Contributing Factors --}}

                <div class="col-12">

                    <div class="text-muted small">
                        Contributing Factors
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->contributing_factors
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        UNSAFE ACT / CONDITION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Unsafe Act / Condition
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Unsafe Act
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->unsafe_act
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Unsafe Condition
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->unsafe_condition
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        FINDINGS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investigation Findings
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $investigation->findings
                    ?? '—'
                )
            ) !!}

        </div>

    </div>



    {{-- =========================================================
        CONCLUSION / RECOMMENDATIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Conclusion & Recommendations
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Conclusion
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->conclusion
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Recommendations
                    </div>


                    <div>

                        {!! nl2br(
                            e(
                                $investigation->recommendations
                                ?? '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REVIEW
    ========================================================== --}}

    @if(
        $investigation->reviewed_by ||
        $investigation->reviewed_date ||
        $investigation->review_remarks
    )

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Review
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Reviewed By
                        </div>


                        <div class="fw-semibold">

                            {{ $investigation->reviewer?->name
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Review Date
                        </div>


                        <div class="fw-semibold">

                            {{ $investigation->reviewed_date
                                ? $investigation->reviewed_date->format('d-m-Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="col-12">

                        <div class="text-muted small">
                            Review Remarks
                        </div>


                        <div>

                            {!! nl2br(
                                e(
                                    $investigation->review_remarks
                                    ?? '—'
                                )
                            ) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($investigation->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e($investigation->remarks)
                ) !!}

            </div>

        </div>

    @endif



    {{-- =========================================================
        WORKFLOW
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investigation Workflow
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- =================================================
                    DRAFT → SUBMITTED
                ================================================== --}}

                @if($investigation->status === 'Draft')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.investigations.submit',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'investigation' => $investigation,
                            ]
                        ) }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm(
                                'Submit this investigation for review?'
                            )"
                        >

                            <i class="bi bi-send me-1"></i>

                            Submit for Review

                        </button>

                    </form>

                @endif



                {{-- =================================================
                    SUBMITTED → APPROVED / REJECTED
                ================================================== --}}

                @if($investigation->status === 'Submitted')

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#approveModal"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Approve

                    </button>


                    <button
                        type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal"
                    >

                        <i class="bi bi-x-lg me-1"></i>

                        Reject

                    </button>

                @endif



                {{-- =================================================
                    APPROVED → INCIDENT ACTIONS
                ================================================== --}}

                @if(
                    $investigation->status === 'Approved'
                )

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.actions.index',
                            [
                                'project' => $project,
                                'incident' => $incident,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-list-check me-1"></i>

                        Manage Incident Actions

                    </a>

                @endif



                {{-- =================================================
                    DELETE DRAFT
                ================================================== --}}

                @if($investigation->status === 'Draft')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.hse.incidents.investigations.destroy',
                            [
                                'project' => $project,
                                'incident' => $incident,
                                'investigation' => $investigation,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this investigation?'
                        );"
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>



{{-- =============================================================
    APPROVE MODAL
============================================================= --}}

<div
    class="modal fade"
    id="approveModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.incidents.investigations.approve',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'investigation' => $investigation,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Approve Investigation
                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-info">

                        Approving this investigation will move the
                        incident to:

                        <strong>
                            Investigation Completed
                        </strong>

                    </div>


                    <label class="form-label">

                        Review Remarks

                    </label>


                    <textarea
                        name="review_remarks"
                        rows="4"
                        class="form-control"
                        placeholder="Enter approval remarks"
                    >{{ old('review_remarks') }}</textarea>

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
                        class="btn btn-success"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Approve Investigation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =============================================================
    REJECT MODAL
============================================================= --}}

<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.incidents.investigations.reject',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'investigation' => $investigation,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Investigation
                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        The investigation will be marked as
                        <strong>Rejected</strong>.

                        It can then be edited and resubmitted.

                    </div>


                    <label class="form-label">

                        Rejection Remarks

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <textarea
                        name="review_remarks"
                        rows="4"
                        class="form-control"
                        placeholder="Explain why the investigation is being rejected"
                        required
                    >{{ old('review_remarks') }}</textarea>

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

                        <i class="bi bi-x-lg me-1"></i>

                        Reject Investigation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection