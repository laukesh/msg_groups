@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Invoice {{ $invoice->invoice_number }}
            </h4>

            <div class="text-muted">
                Milestone Invoice
            </div>

        </div>


        <div class="d-flex gap-2">

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


            {{-- Back to Contract --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.invoices.index',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to Invoices
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $invoice->milestone,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Milestone
            </a>

            @if($invoice->balance_amount > 0)

                <a
                    href="{{ route(
                    'admin.procurement.tenders.contracts.invoices.payment.create',
                    [
                        'procurementTender' => $procurementTender->id,
                        'contract' => $contract->id,
                        'invoice' => $invoice->id,
                    ]
                ) }}?invoice_id={{ $invoice->id }}"
                    class="btn btn-success"
                >
                    <i class="bi bi-credit-card me-1"></i>
                    Make Payment
                </a>

            @else

                <button
                    type="button"
                    class="btn btn-secondary"
                    disabled
                >
                    <i class="bi bi-check-circle me-1"></i>
                    Fully Paid
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
        STATUS
    ============================================================= --}}

    @php

        $statusClass = match($invoice->status) {

            'Approved' =>
                'bg-success',

            'Paid' =>
                'bg-success',

            'Rejected' =>
                'bg-danger',

            'Submitted' =>
                'bg-warning text-dark',

            'Draft' =>
                'bg-secondary',

            default =>
                'bg-secondary',

        };

    @endphp


    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Invoice Status
                    </div>

                    <span
                        class="badge {{ $statusClass }} fs-6 mt-1"
                    >
                        {{ $invoice->status }}
                    </span>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <div class="text-muted small">
                        Invoice Number
                    </div>

                    <strong class="fs-5">
                        {{ $invoice->invoice_number }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        INVOICE INFORMATION
    ============================================================= --}}

    <div class="row g-4">


        {{-- ========================================================
            LEFT
        ========================================================= --}}

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Invoice Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- INVOICE NUMBER --}}

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Invoice Number
                            </div>

                            <div class="fw-semibold">
                                {{ $invoice->invoice_number }}
                            </div>

                        </div>


                        {{-- DATE --}}

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Invoice Date
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $invoice->invoice_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        {{-- TYPE --}}

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Invoice Type
                            </div>

                            <div class="fw-semibold">
                                {{ $invoice->invoice_type }}
                            </div>

                        </div>


                        {{-- MILESTONE --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Milestone
                            </div>

                            @if($invoice->milestone)

                                <div class="fw-semibold">

                                    {{
                                        $invoice->milestone
                                            ->milestone_number
                                    }}

                                    -
                                    {{
                                        $invoice->milestone
                                            ->milestone_title
                                    }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- CONTRACT --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contract
                            </div>

                            <div class="fw-semibold">
                                {{ $contract->contract_number }}
                            </div>

                        </div>


                        {{-- DESCRIPTION --}}

                        <div class="col-12">

                            <div class="text-muted small">
                                Description
                            </div>

                            <div class="mt-1">

                                {{
                                    $invoice->description
                                    ?? 'No description provided.'
                                }}

                            </div>

                        </div>


                        {{-- REMARKS --}}

                        @if($invoice->remarks)

                            <div class="col-12">

                                <div class="text-muted small">
                                    Remarks
                                </div>

                                <div class="mt-1">

                                    {{ $invoice->remarks }}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ====================================================
                AMOUNT BREAKDOWN
            ===================================================== --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Amount Breakdown
                    </strong>

                </div>


                <div class="card-body p-0">

                    <table class="table mb-0">

                        <tbody>

                        <tr>

                            <td>
                                Base Amount
                            </td>

                            <td class="text-end">

                                {{
                                    number_format(
                                        (float) $invoice->amount,
                                        2
                                    )
                                }}

                                {{ $invoice->currency }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Tax Amount
                            </td>

                            <td class="text-end">

                                +
                                {{
                                    number_format(
                                        (float) $invoice->tax_amount,
                                        2
                                    )
                                }}

                                {{ $invoice->currency }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Discount
                            </td>

                            <td class="text-end text-danger">

                                -
                                {{
                                    number_format(
                                        (float) $invoice->discount_amount,
                                        2
                                    )
                                }}

                                {{ $invoice->currency }}

                            </td>

                        </tr>


                        <tr class="table-light">

                            <th>
                                Net Invoice Amount
                            </th>

                            <th class="text-end fs-5">

                                {{
                                    number_format(
                                        (float) $invoice->net_amount,
                                        2
                                    )
                                }}

                                {{ $invoice->currency }}

                            </th>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>
            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Payment Summary
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Invoice Amount
                            </div>

                            <div class="fw-semibold">
                                {{ number_format(
                                    (float) $invoice->net_amount,
                                    2
                                ) }}
                                {{ $invoice->currency }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Paid Amount
                            </div>

                            <div class="fw-semibold text-success">
                                {{ number_format(
                                    (float) $invoice->paid_amount,
                                    2
                                ) }}
                                {{ $invoice->currency }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Outstanding
                            </div>

                            <div class="fw-semibold text-danger">
                                {{ number_format(
                                    (float) $invoice->balance_amount,
                                    2
                                ) }}
                                {{ $invoice->currency }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            RIGHT - WORKFLOW
        ========================================================= --}}

        <div class="col-lg-4">

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Invoice Workflow
                    </strong>

                </div>


                <div class="card-body">


                    {{-- DRAFT --}}

                    <div class="d-flex gap-3 mb-4">

                        <div>

                            <span class="badge bg-secondary">
                                1
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Draft
                            </div>

                            <div class="small text-muted">

                                Invoice created.

                            </div>

                        </div>

                    </div>


                    {{-- SUBMITTED --}}

                    <div class="d-flex gap-3 mb-4">

                        <div>

                            <span
                                class="badge
                                {{ $invoice->submitted_at
                                    ? 'bg-warning text-dark'
                                    : 'bg-light text-dark' }}"
                            >
                                2
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Submitted
                            </div>

                            @if($invoice->submitted_at)

                                <div class="small text-muted">

                                    {{
                                        $invoice->submitted_at
                                            ->format('d-m-Y H:i')
                                    }}

                                </div>

                            @else

                                <div class="small text-muted">
                                    Pending submission
                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- APPROVED --}}

                    <div class="d-flex gap-3 mb-4">

                        <div>

                            <span
                                class="badge
                                {{ $invoice->approved_at
                                    ? 'bg-success'
                                    : 'bg-light text-dark' }}"
                            >
                                3
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Approved
                            </div>

                            @if($invoice->approved_at)

                                <div class="small text-muted">

                                    {{
                                        $invoice->approved_at
                                            ->format('d-m-Y H:i')
                                    }}

                                </div>

                            @else

                                <div class="small text-muted">
                                    Pending approval
                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- PAYMENT --}}

                    <div class="d-flex gap-3">

                        <div>

                            <span
                                class="badge
                                {{ $invoice->paid_at
                                    ? 'bg-success'
                                    : 'bg-light text-dark' }}"
                            >
                                4
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Paid
                            </div>

                            @if($invoice->paid_at)

                                <div class="small text-muted">

                                    {{
                                        $invoice->paid_at
                                            ->format('d-m-Y H:i')
                                    }}

                                </div>

                            @else

                                <div class="small text-muted">
                                    Payment pending
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                ACTIONS
            ===================================================== --}}

            <div class="card">

                <div class="card-header">

                    <strong>
                        Actions
                    </strong>

                </div>


                <div class="card-body">


                    {{-- SUBMIT --}}

                    @if($invoice->status === 'Draft')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.procurement.tenders.contracts.invoices.submit',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'contract' =>
                                        $contract,

                                    'invoice' =>
                                        $invoice,
                                ]
                            ) }}"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                onclick="return confirm(
                                    'Submit this invoice for approval?'
                                )"
                            >
                                Submit Invoice
                            </button>

                        </form>

                    @endif


                    {{-- APPROVE --}}

                    @if($invoice->status === 'Submitted')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.procurement.tenders.contracts.invoices.approve',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'contract' =>
                                        $contract,

                                    'invoice' =>
                                        $invoice,
                                ]
                            ) }}"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm(
                                    'Approve this invoice?'
                                )"
                            >
                                Approve Invoice
                            </button>

                        </form>


                        {{-- REJECT BUTTON --}}

                        <button
                            type="button"
                            class="btn btn-danger w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectInvoiceModal"
                        >
                            Reject Invoice
                        </button>

                    @endif


                    {{-- APPROVED --}}

                    @if($invoice->status === 'Approved')

                        <div class="alert alert-success">

                            <strong>
                                Invoice Approved
                            </strong>

                            <div class="small mt-1">
                                This invoice is ready for payment processing.
                            </div>

                        </div>


                        @if(!$invoice->is_fully_paid)

                            <a
                                href="{{ route(
                                    'admin.procurement.tenders.contracts.invoices.payment.create',
                                    [
                                        'procurementTender' =>
                                            $procurementTender,

                                        'contract' =>
                                            $contract,

                                        'invoice' =>
                                            $invoice,
                                    ]
                                ) }}?invoice_id={{ $invoice->id }}"
                                class="btn btn-warning w-100"
                            >
                                + Create Payment
                            </a>

                        @else

                            <div class="alert alert-success mb-0">
                                Invoice Fully Paid
                            </div>

                        @endif

                    @endif


                    {{-- REJECTED --}}

                    @if($invoice->status === 'Rejected')

                        <div class="alert alert-danger mb-0">

                            <strong>
                                Invoice Rejected
                            </strong>

                            @if($invoice->rejection_remarks)

                                <div class="small mt-2">

                                    <strong>
                                        Reason:
                                    </strong>

                                    <br>

                                    {{ $invoice->rejection_remarks }}

                                </div>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================
    REJECT MODAL
================================================================ --}}

@if($invoice->status === 'Submitted')

<div
    class="modal fade"
    id="rejectInvoiceModal"
    tabindex="-1"
    aria-labelledby="rejectInvoiceModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="rejectInvoiceModalLabel"
                >
                    Reject Invoice
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
                    'admin.procurement.tenders.contracts.invoices.reject',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,

                        'invoice' =>
                            $invoice,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-warning">

                        Invoice:

                        <strong>
                            {{ $invoice->invoice_number }}
                        </strong>

                    </div>


                    <label class="form-label">

                        Rejection Reason

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <textarea
                        name="rejection_remarks"
                        class="form-control"
                        rows="5"
                        required
                        placeholder="Enter rejection reason..."
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
                        class="btn btn-danger"
                    >
                        Reject Invoice
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection