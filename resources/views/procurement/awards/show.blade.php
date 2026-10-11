@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php

        $loaIssued = $award->status === 'LOA Issued';

        $statusClass =
            match($award->status) {

                'Draft' =>
                    'bg-secondary',

                'Under Review' =>
                    'bg-warning text-dark',

                'Approved' =>
                    'bg-success',

                'LOA Issued' =>
                    'bg-primary',

                'Rejected' =>
                    'bg-danger',

                default =>
                    'bg-secondary',
            };

    @endphp


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">

                Project:
                <strong>
                    {{ $project->project_name }}
                </strong>

                <span class="mx-1">
                    |
                </span>

                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

            </div>


            <h4 class="mb-1">
                {{ $award->award_number }}
            </h4>


            <div class="text-muted">
                {{ $award->award_title }}
            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Tender --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Tender
            </a>


            {{-- Awards --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.awards.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Awards
            </a>


            {{-- Edit --}}
            @if(
                in_array(
                    $award->status,
                    [
                        'Draft',
                        'Under Review',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.awards.edit',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'award' => $award,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endif

            {{-- Purchase Orders --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.purchase-orders.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-file-list-3-line me-1"></i>
                Purchase Orders
            </a>


            {{-- Create Purchase Order --}}
            @if($award->status === 'LOA Issued')

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.purchase-orders.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-add-line me-1"></i>
                    Create Purchase Order
                </a>

            @endif


            {{-- Submit --}}
            @if($award->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement.tenders.awards.submit',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'award' => $award,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Submit this Award for approval?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="ri-send-plane-line me-1"></i>
                        Submit
                    </button>

                </form>

            @endif


            {{-- Approve --}}
            @if($award->status === 'Under Review')

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#approveAwardModal"
                >
                    <i class="ri-check-line me-1"></i>
                    Approve
                </button>

            @endif


            {{-- Issue LOA --}}
            @if($award->status === 'Approved')

                <button
                    type="button"
                    class="btn btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#issueLoaModal"
                >
                    <i class="ri-mail-send-line me-1"></i>
                    Issue LOA
                </button>

            @endif

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>

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

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Final Lock --}}
    @if($loaIssued)

        <div class="alert alert-warning">

            <strong>
                <i class="ri-lock-line me-1"></i>
                Award Finalized
            </strong>

            <div class="small mt-1">
                The Letter of Award has been issued.
                This Award is now view-only.
            </div>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Awarded Bidder
                    </small>

                    <h6 class="mt-2 mb-0">
                        {{ $award->bidder_name }}
                    </h6>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Award Amount
                    </small>

                    <h5 class="mt-2 mb-0">

                        {{ number_format(
                            (float) $award->awarded_amount,
                            2
                        ) }}

                        <small class="text-muted">
                            {{ $award->currency }}
                        </small>

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Award Date
                    </small>

                    <h6 class="mt-2 mb-0">
                        {{
                            $award->award_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}
                    </h6>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div class="mt-2">

                        <span class="badge {{ $statusClass }}">
                            {{ $award->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Award Information --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Award Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Project
                    </small>

                    <strong>
                        {{ $project->project_name }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Tender
                    </small>

                    <strong>
                        {{ $procurementTender->tender_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Number
                    </small>

                    <strong>
                        {{ $award->award_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Title
                    </small>

                    <strong>
                        {{ $award->award_title }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Type
                    </small>

                    <strong>
                        {{ $award->award_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract Required
                    </small>

                    <strong>
                        {{ $award->contract_required ? 'Yes' : 'No' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Awarded Bidder
                    </small>

                    <strong>
                        {{ $award->bidder_name }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Awarded Amount
                    </small>

                    <strong>

                        {{ number_format(
                            (float) $award->awarded_amount,
                            2
                        ) }}

                        {{ $award->currency }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Date
                    </small>

                    <strong>
                        {{
                            $award->award_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Negotiation --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Approved Negotiation
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Negotiation Number
                    </small>

                    <strong>
                        {{ $award->negotiation?->negotiation_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Final Amount
                    </small>

                    <strong>
                        {{
                            $award->negotiation
                                ? number_format(
                                    (float) $award->negotiation->final_amount,
                                    2
                                )
                                : '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong>
                        {{ $award->negotiation?->currency ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Approval & LOA --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Approval & LOA
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div class="mt-2">
                        <span class="badge {{ $statusClass }}">
                            {{ $award->status }}
                        </span>
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Submitted At
                    </small>

                    <strong>
                        {{
                            $award->submitted_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Approval Date
                    </small>

                    <strong>
                        {{
                            $award->approval_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Approved By
                    </small>

                    <strong>
                        {{ $award->approved_by ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        LOA Number
                    </small>

                    <strong>
                        {{ $award->loa_number ?? 'Not Issued' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        LOA Date
                    </small>

                    <strong>
                        {{
                            $award->loa_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Acceptance Deadline
                    </small>

                    <strong>
                        {{
                            $award->acceptance_deadline
                                ?->format('d-m-Y')
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        LOA Issued At
                    </small>

                    <strong>
                        {{
                            $award->loa_issued_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}
    @if($award->description)

        <div class="card mb-4">

            <div class="card-header">
                <strong>
                    Description
                </strong>
            </div>

            <div class="card-body">

                {!! nl2br(e($award->description)) !!}

            </div>

        </div>

    @endif


    {{-- Terms --}}
    @if($award->terms_and_conditions)

        <div class="card mb-4">

            <div class="card-header">
                <strong>
                    Terms & Conditions
                </strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($award->terms_and_conditions)
                ) !!}

            </div>

        </div>

    @endif


    {{-- Remarks --}}
    @if($award->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>
                    Remarks
                </strong>
            </div>

            <div class="card-body">

                {!! nl2br(e($award->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- =========================================================
         APPROVE MODAL
    ========================================================== --}}

    @if($award->status === 'Under Review')

        <div
            class="modal fade"
            id="approveAwardModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement.tenders.awards.approve',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'award' => $award,
                        ]
                    ) }}"
                >

                    @csrf

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Approve Award
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <p>
                                Are you sure you want to approve
                                <strong>
                                    {{ $award->award_number }}
                                </strong>?
                            </p>


                            <label class="form-label">
                                Approval Remarks
                            </label>

                            <textarea
                                name="approval_remarks"
                                rows="4"
                                class="form-control"
                            ></textarea>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                <i class="ri-check-line me-1"></i>
                                Approve Award
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- =========================================================
         ISSUE LOA MODAL
    ========================================================== --}}

    @if($award->status === 'Approved')

        <div
            class="modal fade"
            id="issueLoaModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement.tenders.awards.issue-loa',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'award' => $award,
                        ]
                    ) }}"
                >

                    @csrf

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Issue Letter of Award
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <div class="alert alert-info">

                                LOA number will be generated
                                automatically.

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    LOA Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="loa_date"
                                    class="form-control"
                                    value="{{ old(
                                        'loa_date',
                                        now()->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Acceptance Deadline
                                </label>

                                <input
                                    type="date"
                                    name="acceptance_deadline"
                                    class="form-control"
                                >

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-warning"
                            >
                                <i class="ri-mail-send-line me-1"></i>
                                Issue LOA
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection