@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
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


        {{-- ========================================================
            HEADER ACTIONS
        ========================================================= --}}
        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Tender
            </a>


            {{-- Back to Awards --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.awards.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Awards
            </a>


            {{-- Edit Award --}}
            @if(
                in_array(
                    $award->status,
                    [
                        'Draft',
                        'Under Review',
                    ]
                )
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.awards.edit',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'award' =>
                                $award,
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
                    'admin.procurement.tenders.purchase-orders.index',
                    [
                        'procurementTender' =>
                            $procurementTender,
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
                        'admin.procurement.tenders.purchase-orders.create',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'procurementAward' =>
                                $award,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-add-line me-1"></i>
                    Create Purchase Order
                </a>

            @endif


            {{-- ====================================================
                SUBMIT AWARD
            ===================================================== --}}
            @if($award->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.awards.submit',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'award' =>
                                $award,
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
                        Submit for Approval
                    </button>

                </form>

            @endif


            {{-- ====================================================
                APPROVE AWARD
            ===================================================== --}}
            @if($award->status === 'Under Review')

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#approveAwardModal"
                >
                    <i class="ri-check-line me-1"></i>
                    Approve Award
                </button>

            @endif


            {{-- ====================================================
                ISSUE LOA
            ===================================================== --}}
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


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

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


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================= --}}
    <div class="row g-3 mb-4">

        {{-- Bidder --}}
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


        {{-- Amount --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Award Amount
                    </small>

                    <h5 class="mt-2 mb-0">

                        {{
                            number_format(
                                (float)
                                $award->awarded_amount,
                                2
                            )
                        }}

                        <small class="text-muted">
                            {{ $award->currency }}
                        </small>

                    </h5>

                </div>

            </div>

        </div>


        {{-- Award Date --}}
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


        {{-- Status --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div class="mt-2">

                        @php

                            $statusClass = match(
                                $award->status
                            ) {

                                'Draft' =>
                                    'bg-secondary',

                                'Under Review' =>
                                    'bg-primary',

                                'Approved' =>
                                    'bg-success',

                                'LOA Issued' =>
                                    'bg-warning text-dark',

                                'Rejected' =>
                                    'bg-danger',

                                default =>
                                    'bg-secondary',

                            };

                        @endphp

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $award->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        AWARD INFORMATION
    ============================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Award Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Award Number --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Number
                    </small>

                    <strong>
                        {{ $award->award_number }}
                    </strong>

                </div>


                {{-- Award Title --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Title
                    </small>

                    <strong>
                        {{ $award->award_title }}
                    </strong>

                </div>


                {{-- Award Type --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Award Type
                    </small>

                    <strong>
                        {{ $award->award_type ?? '—' }}
                    </strong>

                </div>


                {{-- Bidder --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Awarded Bidder
                    </small>

                    <strong>
                        {{ $award->bidder_name }}
                    </strong>

                </div>


                {{-- Amount --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Awarded Amount
                    </small>

                    <strong>

                        {{
                            number_format(
                                (float)
                                $award->awarded_amount,
                                2
                            )
                        }}

                        {{ $award->currency }}

                    </strong>

                </div>


                {{-- Award Date --}}
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


                {{-- Contract Required --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract Required
                    </small>

                    <strong>

                        {{
                            $award->contract_required
                                ? 'Yes'
                                : 'No'
                        }}

                    </strong>

                </div>


                {{-- Procurement Tender --}}
                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Tender
                    </small>

                    <strong>
                        {{ $procurementTender->tender_number }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        APPROVAL & LOA
    ============================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approval & LOA
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Status --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $award->status }}
                        </span>

                    </div>

                </div>


                {{-- Submitted At --}}
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


                {{-- Approval Date --}}
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


                {{-- Approval By --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Approved By
                    </small>

                    <strong>

                        {{ $award->approved_by ?? '—' }}

                    </strong>

                </div>


                {{-- LOA Number --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        LOA Number
                    </small>

                    <strong>

                        {{ $award->loa_number ?? 'Not Issued' }}

                    </strong>

                </div>


                {{-- LOA Date --}}
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


                {{-- Acceptance Deadline --}}
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


                {{-- LOA Issued At --}}
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


                {{-- Approval Remarks --}}
                <div class="col-12">

                    <small class="text-muted d-block mb-1">
                        Approval Remarks
                    </small>

                    <div>

                        {{ $award->approval_remarks ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DESCRIPTION
    ============================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Description
            </strong>

        </div>

        <div class="card-body">

            @if($award->description)

                {!! nl2br(
                    e(
                        $award->description
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No description provided.
                </span>

            @endif

        </div>

    </div>


    {{-- ============================================================
        TERMS & CONDITIONS
    ============================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Terms & Conditions
            </strong>

        </div>

        <div class="card-body">

            @if($award->terms_and_conditions)

                {!! nl2br(
                    e(
                        $award->terms_and_conditions
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No terms and conditions provided.
                </span>

            @endif

        </div>

    </div>


    {{-- ============================================================
        REMARKS
    ============================================================= --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>

        <div class="card-body">

            @if($award->remarks)

                {!! nl2br(
                    e(
                        $award->remarks
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No remarks provided.
                </span>

            @endif

        </div>

    </div>


    {{-- ============================================================
        APPROVE AWARD MODAL
    ============================================================= --}}
    @if($award->status === 'Under Review')

        <div
            class="modal fade"
            id="approveAwardModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.awards.approve',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'award' =>
                                $award,
                        ]
                    ) }}"
                    class="modal-content"
                >

                    @csrf


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

                        <div class="alert alert-info">

                            Award Amount:

                            <strong>

                                {{
                                    number_format(
                                        (float)
                                        $award->awarded_amount,
                                        2
                                    )
                                }}

                                {{ $award->currency }}

                            </strong>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Approval Remarks
                            </label>

                            <textarea
                                name="approval_remarks"
                                rows="4"
                                class="form-control"
                                placeholder="Enter approval remarks"
                            ></textarea>

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
                            class="btn btn-success"
                        >
                            <i class="ri-check-line me-1"></i>
                            Approve Award
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- ============================================================
        ISSUE LOA MODAL
    ============================================================= --}}
    @if($award->status === 'Approved')

        <div
            class="modal fade"
            id="issueLoaModal"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.awards.issue-loa',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'award' =>
                                $award,
                        ]
                    ) }}"
                    class="modal-content"
                >

                    @csrf


                    {{-- Header --}}
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


                    {{-- Body --}}
                    <div class="modal-body">

                        {{-- Award Summary --}}
                        <div class="alert alert-success">

                            <div>

                                Award approved for:

                                <strong>
                                    {{ $award->bidder_name }}
                                </strong>

                            </div>


                            <div class="mt-1">

                                Amount:

                                <strong>

                                    {{
                                        number_format(
                                            (float)
                                            $award->awarded_amount,
                                            2
                                        )
                                    }}

                                    {{ $award->currency }}

                                </strong>

                            </div>

                        </div>


                        {{-- Auto Generated LOA Number --}}
                        <div class="mb-3">

                            <label class="form-label">
                                LOA Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Will be generated automatically"
                                readonly
                            >

                            <div class="form-text">

                                The system will generate the LOA
                                number automatically when the LOA
                                is issued.

                            </div>

                        </div>


                        {{-- LOA Date --}}
                        <div class="mb-3">

                            <label class="form-label">

                                LOA Date

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input
                                type="date"
                                name="loa_date"
                                class="form-control @error('loa_date') is-invalid @enderror"
                                value="{{ old(
                                    'loa_date',
                                    now()->format('Y-m-d')
                                ) }}"
                                required
                            >

                            @error('loa_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Acceptance Deadline --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Acceptance Deadline
                            </label>

                            <input
                                type="date"
                                name="acceptance_deadline"
                                class="form-control @error('acceptance_deadline') is-invalid @enderror"
                                value="{{ old(
                                    'acceptance_deadline'
                                ) }}"
                            >

                            <div class="form-text">

                                Last date for the bidder
                                to accept the Award.

                            </div>

                            @error('acceptance_deadline')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Process Information --}}
                        <div class="alert alert-light border mb-0">

                            <small class="text-muted">

                                After issuing the LOA, the Award
                                status will become

                                <strong>
                                    LOA Issued
                                </strong>

                                and the system will generate a
                                unique LOA number.

                            </small>

                        </div>

                    </div>


                    {{-- Footer --}}
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

                </form>

            </div>

        </div>

    @endif

</div>

@endsection