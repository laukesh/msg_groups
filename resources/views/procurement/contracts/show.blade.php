@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Tender:
                {{ $procurementTender->tender_number }}
            </div>

            <h4>
                {{ $contract->contract_number }}
            </h4>

            <div class="text-muted">
                {{ $contract->contract_title }}
            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- =========================================================
                NAVIGATION
            ========================================================== --}}

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Contracts --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Contracts
            </a>


            {{-- =========================================================
                CONTRACT LIFECYCLE ACTIONS
            ========================================================== --}}

            {{-- Draft → Under Review --}}
            @if($contract->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.contracts.submit',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Submit this Contract for approval?'
                    );"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-send me-1"></i>
                        Submit for Approval
                    </button>

                </form>

            @endif


            {{-- Under Review → Approved --}}
            @if($contract->status === 'Under Review')

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#approveContractModal"
                >
                    <i class="bi bi-check-circle me-1"></i>
                    Approve Contract
                </button>

            @endif


            {{-- Approved → Active --}}
            @if($contract->status === 'Approved')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.contracts.activate',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Activate this Contract?'
                    );"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        <i class="bi bi-play-circle me-1"></i>
                        Activate Contract
                    </button>

                </form>

            @endif


            {{-- =========================================================
                ACTIVE CONTRACT MODULES
            ========================================================== --}}

            @if(
                in_array(
                    $contract->status,
                    ['Active', 'Completed', 'Closed'],
                    true
                )
            )

                {{-- Milestones --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.index',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-list-check me-1"></i>
                    Milestones
                </a>


                {{-- Invoices --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.invoices.index',
                        [
                            'procurementTender' => $contract->procurement_tender_id,
                            'contract' => $contract->id,
                        ]
                    ) }}"
                    class="btn btn-outline-warning"
                >
                    <i class="bi bi-receipt me-1"></i>
                    Invoices
                </a>


                {{-- Payments --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.payments.index',
                        [
                            'procurementTender' => $contract->procurement_tender_id,
                            'contract' => $contract->id,
                        ]
                    ) }}"
                    class="btn btn-outline-success"
                >
                    <i class="bi bi-cash-stack me-1"></i>
                    Payments
                </a>

            @endif


            {{-- =========================================================
                COMPLETED → CLOSED
            ========================================================== --}}

            @if($contract->status === 'Completed')

                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#closeContractModal"
                >
                    <i class="bi bi-lock me-1"></i>
                    Close Contract
                </button>

            @endif

        </div>

    </div>


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

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


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- BIDDER --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Bidder
                    </small>

                    <h6 class="mt-2">
                        {{ $contract->bidder?->company_name
    ?? $contract->bidder_name
    ?? '—'
}}
                    </h6>

                </div>

            </div>

        </div>


        {{-- CONTRACT AMOUNT --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Contract Amount
                    </small>

                    <h5 class="mt-2">

                        {{
                            number_format(
                                (float)
                                $contract->contract_amount,
                                2
                            )
                        }}

                        {{ $contract->currency }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- START DATE --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Start Date
                    </small>

                    <h6 class="mt-2">

                        {{
                            $contract->contract_start_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </h6>

                </div>

            </div>

        </div>


        {{-- STATUS --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="mt-2">

                        @php

                            $statusClass = match(
                                $contract->status
                            ) {

                                'Active' =>
                                    'bg-success',

                                'Approved' =>
                                    'bg-primary',

                                'Under Review' =>
                                    'bg-warning text-dark',

                                'Completed' =>
                                    'bg-info text-dark',

                                'Closed' =>
                                    'bg-dark',

                                'Terminated' =>
                                    'bg-danger',

                                'Expired' =>
                                    'bg-secondary',

                                'Draft' =>
                                    'bg-secondary',

                                default =>
                                    'bg-secondary',

                            };

                        @endphp


                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $contract->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CONTRACT APPROVAL & ACTIVATION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Contract Approval & Activation
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- STATUS --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $contract->status }}
                    </span>

                </div>


                {{-- SUBMITTED --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Submitted At
                    </small>

                    <strong>

                        {{
                            $contract->submitted_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- APPROVAL DATE --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Approval Date
                    </small>

                    <strong>

                        {{
                            $contract->approval_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- ACTIVATED --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Activated At
                    </small>

                    <strong>

                        {{
                            $contract->activated_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- APPROVAL REMARKS --}}

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Approval Remarks
                    </small>

                    <div>
                        {{ $contract->approval_remarks ?? '—' }}
                    </div>

                </div>


                {{-- COMPLETION DATE --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Completion Date
                    </small>

                    <strong>

                        {{
                            $contract->completion_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- CLOSED AT --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Closed At
                    </small>

                    <strong>

                        {{
                            $contract->closed_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- CLOSURE REMARKS --}}

                @if($contract->closure_remarks)

                    <div class="col-md-12">

                        <small class="text-muted d-block">
                            Closure Remarks
                        </small>

                        <div>
                            {{ $contract->closure_remarks }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

    {{-- ============================================================
    PROCUREMENT CHAIN
============================================================= --}}

<div class="card mb-4">

    <div class="card-header">

        <strong>
            Procurement Chain
        </strong>

    </div>

    <div class="card-body">

        <div class="row g-3">

            {{-- PROJECT --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Project
                </small>

                <strong>
                    {{ 
                        $contract->tender
                            ?->package
                            ?->procurementPlan
                            ?->project
                            ?->project_name
                        ?? '—'
                    }}
                </strong>

            </div>


            {{-- PROCUREMENT PLAN --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Procurement Plan
                </small>

                <strong>

                    {{
                        $contract->tender
                            ?->package
                            ?->procurementPlan
                            ?->plan_number
                        ?? '—'
                    }}

                </strong>

                @if(
                    $contract->tender
                        ?->package
                        ?->procurementPlan
                )

                    <div class="small text-muted">

                        {{
                            $contract->tender
                                ->package
                                ->procurementPlan
                                ->plan_title
                        }}

                    </div>

                @endif

            </div>


            {{-- PROCUREMENT PACKAGE --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Procurement Package
                </small>

                <strong>

                    {{
                        $contract->tender
                            ?->package
                            ?->package_number
                        ?? '—'
                    }}

                </strong>

                @if(
                    $contract->tender
                        ?->package
                )

                    <div class="small text-muted">

                        {{
                            $contract->tender
                                ->package
                                ->package_title
                        }}

                    </div>

                @endif

            </div>


            {{-- PROJECT BUDGET --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Project Budget
                </small>

                <strong>

                    {{
                        $contract->tender
                            ?->package
                            ?->budget
                            ?->budget_number
                        ?? '—'
                    }}

                </strong>

                @if(
                    $contract->tender
                        ?->package
                        ?->budget
                )

                    <div class="small text-muted">

                        {{
                            $contract->tender
                                ->package
                                ->budget
                                ->title
                        }}

                    </div>

                @endif

            </div>


            {{-- TENDER --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Tender
                </small>

                <strong>

                    {{ $contract->tender?->tender_number ?? '—' }}

                </strong>

                @if($contract->tender)

                    <div class="small text-muted">

                        {{ $contract->tender->tender_title }}

                    </div>

                @endif

            </div>


            {{-- CONTRACTOR / SUPPLIER --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Contractor / Supplier
                </small>

                <strong>

                    {{
                        $contract->bidder?->company_name
                        ?? $contract->bidder_name
                        ?? '—'
                    }}

                </strong>

                @if($contract->bidder?->bidder_code)

                    <div class="small text-muted">

                        Code:
                        {{ $contract->bidder->bidder_code }}

                    </div>

                @endif

            </div>


            {{-- AWARD --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Award / LOA
                </small>

                <strong>

                    {{ $contract->award?->award_number ?? '—' }}

                </strong>

                @if($contract->loa_number)

                    <div class="small text-muted">

                        LOA:
                        {{ $contract->loa_number }}

                    </div>

                @endif

            </div>


            {{-- CONTRACT --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Contract
                </small>

                <strong>

                    {{ $contract->contract_number }}

                </strong>

            </div>


            {{-- PAYMENT --}}

            <div class="col-md-4">

                <small class="text-muted d-block">
                    Payment Status
                </small>

                @php

                    $processedAmount = $contract->payments
                        ->where('status', 'Processed')
                        ->sum(
                            fn ($payment) =>
                                (float) $payment->amount
                        );

                @endphp

                <strong>

                    {{ number_format($processedAmount, 2) }}
                    {{ $contract->currency }}

                </strong>

                <div class="small text-muted">

                    Processed Payments

                </div>

            </div>

        </div>

    </div>

</div>


    {{-- ============================================================
        CONTRACT INFORMATION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Contract Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- CONTRACT NUMBER --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract Number
                    </small>

                    <strong>
                        {{ $contract->contract_number }}
                    </strong>

                </div>


                {{-- CONTRACT TYPE --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract Type
                    </small>

                    <strong>
                        {{ $contract->contract_type ?? '—' }}
                    </strong>

                </div>


                {{-- LOA NUMBER --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        LOA Number
                    </small>

                    <strong>
                        {{ $contract->loa_number ?? '—' }}
                    </strong>

                </div>


                {{-- LOA DATE --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        LOA Date
                    </small>

                    <strong>

                        {{
                            $contract->loa_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- SIGNING DATE --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Signing Date
                    </small>

                    <strong>

                        {{
                            $contract->signing_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- DURATION --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Duration
                    </small>

                    <strong>

                        {{
                            $contract->contract_duration_days
                            ?? '—'
                        }}

                        @if($contract->contract_duration_days)
                            days
                        @endif

                    </strong>

                </div>


                {{-- START DATE --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract Start Date
                    </small>

                    <strong>

                        {{
                            $contract->contract_start_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- END DATE --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Contract End Date
                    </small>

                    <strong>

                        {{
                            $contract->contract_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FINANCIAL & SECURITY
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Financial & Security
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- PERFORMANCE SECURITY --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Performance Security
                    </small>

                    <strong>
                        {{
                            $contract->performance_security_required
                                ? 'Required'
                                : 'Not Required'
                        }}
                    </strong>

                </div>


                {{-- SECURITY AMOUNT --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Security Amount
                    </small>

                    <strong>

                        {{
                            number_format(
                                (float)
                                $contract->performance_security_amount,
                                2
                            )
                        }}

                        {{ $contract->currency }}

                    </strong>

                </div>


                {{-- RETENTION --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Retention
                    </small>

                    <strong>

                        @if($contract->retention_required)

                            {{ $contract->retention_percentage }}%

                        @else

                            Not Required

                        @endif

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        SCOPE OF WORK
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Scope of Work
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $contract->scope_of_work
                    ?? 'No scope provided.'
                )
            ) !!}

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

            {!! nl2br(
                e(
                    $contract->terms_and_conditions
                    ?? 'No terms provided.'
                )
            ) !!}

        </div>

    </div>


    {{-- ============================================================
        SPECIAL CONDITIONS / REMARKS
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Special Conditions / Remarks
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $contract->special_conditions
                    ?? 'No special conditions.'
                )
            ) !!}


            @if($contract->remarks)

                <hr>

                <strong>
                    Remarks
                </strong>

                <p class="mb-0">
                    {{ $contract->remarks }}
                </p>

            @endif

        </div>

    </div>


    {{-- ============================================================
        PAYMENT SUMMARY
    ============================================================= --}}

    @php

        $contractInvoiceCount =
            $contract->invoices->count();

        $contractInvoiceAmount =
            $contract->invoices->sum(
                fn ($invoice) =>
                    (float) $invoice->net_amount
            );

        $contractPaidAmount =
            $contract->payments
                ->where('status', 'Processed')
                ->sum(
                    fn ($payment) =>
                        (float) $payment->amount
                );

        $contractOutstandingAmount =
            max(
                0,
                $contractInvoiceAmount
                - $contractPaidAmount
            );

        $approvedInvoiceCount =
            $contract->invoices
                ->where('status', 'Approved')
                ->count();

    @endphp


    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Payment Summary
            </strong>


            @if(
                in_array(
                    $contract->status,
                    ['Active', 'Completed', 'Closed'],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.payments.index',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'contract' =>
                                $contract,
                        ]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View Payments
                </a>

            @endif

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- TOTAL INVOICES --}}

                <div class="col-md-3">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <small class="text-muted d-block">
                                Total Invoices
                            </small>

                            <h5 class="mt-2 mb-0">
                                {{ $contractInvoiceCount }}
                            </h5>

                        </div>

                    </div>

                </div>


                {{-- INVOICE VALUE --}}

                <div class="col-md-3">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <small class="text-muted d-block">
                                Invoice Value
                            </small>

                            <h5 class="mt-2 mb-0">

                                {{
                                    number_format(
                                        $contractInvoiceAmount,
                                        2
                                    )
                                }}

                                {{ $contract->currency }}

                            </h5>

                        </div>

                    </div>

                </div>


                {{-- PAID AMOUNT --}}

                <div class="col-md-3">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <small class="text-muted d-block">
                                Paid Amount
                            </small>

                            <h5 class="mt-2 mb-0 text-success">

                                {{
                                    number_format(
                                        $contractPaidAmount,
                                        2
                                    )
                                }}

                                {{ $contract->currency }}

                            </h5>

                        </div>

                    </div>

                </div>


                {{-- OUTSTANDING --}}

                <div class="col-md-3">

                    <div class="card h-100 border">

                        <div class="card-body">

                            <small class="text-muted d-block">
                                Outstanding
                            </small>

                            <h5 class="mt-2 mb-0 text-danger">

                                {{
                                    number_format(
                                        $contractOutstandingAmount,
                                        2
                                    )
                                }}

                                {{ $contract->currency }}

                            </h5>

                        </div>

                    </div>

                </div>

            </div>


            {{-- APPROVED INVOICES --}}

            @if($approvedInvoiceCount > 0)

                <div class="alert alert-warning mt-3 mb-0">

                    <strong>
                        {{ $approvedInvoiceCount }}
                        approved invoice(s)
                    </strong>

                    are ready for payment processing.

                </div>

            @endif


            {{-- NO INVOICE --}}

            @if($contractInvoiceCount === 0)

                <div class="alert alert-light border mt-3 mb-0">

                    No invoices have been created for this contract yet.

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
        APPROVE CONTRACT MODAL
    ============================================================= --}}

    @if($contract->status === 'Under Review')

        <div
            class="modal fade"
            id="approveContractModal"
            tabindex="-1"
            aria-labelledby="approveContractModalLabel"
            aria-hidden="true"
        >

            <div class="modal-dialog">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.contracts.approve',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'contract' =>
                                $contract,
                        ]
                    ) }}"
                    class="modal-content"
                >

                    @csrf


                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="approveContractModalLabel"
                        >
                            Approve Contract
                        </h5>


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-info">

                            <div>

                                Contract:

                                <strong>
                                    {{ $contract->contract_number }}
                                </strong>

                            </div>


                            <div>

                                Bidder:

                                <strong>
                                    {{ $contract->bidder_name }}
                                </strong>

                            </div>


                            <div>

                                Amount:

                                <strong>

                                    {{
                                        number_format(
                                            (float)
                                            $contract->contract_amount,
                                            2
                                        )
                                    }}

                                    {{ $contract->currency }}

                                </strong>

                            </div>

                        </div>


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
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Approve Contract
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- ============================================================
        CLOSE CONTRACT MODAL
    ============================================================= --}}

    @if($contract->status === 'Completed')

        <div
            class="modal fade"
            id="closeContractModal"
            tabindex="-1"
            aria-labelledby="closeContractModalLabel"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">


                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="closeContractModalLabel"
                        >
                            Close Contract
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
                            'admin.procurement.tenders.contracts.close',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'contract' =>
                                    $contract,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="modal-body">

                            <div class="alert alert-warning">

                                You are about to close contract:

                                <strong>
                                    {{ $contract->contract_number }}
                                </strong>

                            </div>


                            {{-- COMPLETION DATE --}}

                            <div class="mb-3">

                                <label class="form-label">

                                    Completion Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input
                                    type="date"
                                    name="completion_date"
                                    class="form-control"
                                    value="{{ old(
                                        'completion_date',
                                        $contract->completion_date
                                            ?->format('Y-m-d')
                                            ?? now()->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                            </div>


                            {{-- CLOSURE REMARKS --}}

                            <div class="mb-3">

                                <label class="form-label">

                                    Closure Remarks

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <textarea
                                    name="closure_remarks"
                                    class="form-control"
                                    rows="5"
                                    required
                                    placeholder="Enter contract closure remarks..."
                                >{{ old('closure_remarks') }}</textarea>

                            </div>


                            <div class="alert alert-info mb-0">

                                <strong>
                                    Important:
                                </strong>

                                Once this contract is closed, it will
                                move from

                                <strong>
                                    Completed
                                </strong>

                                to

                                <strong>
                                    Closed
                                </strong>.

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
                                Close Contract
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection