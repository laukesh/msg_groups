@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Payment {{ $payment->payment_number }}
            </h4>

            <div class="text-muted">

                Invoice:

                @if($payment->invoice)
                    {{ $payment->invoice->invoice_number }}
                @else
                    —
                @endif

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Contract --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>


            {{-- Invoice --}}
            @if($payment->invoice)

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.contracts.invoices.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'invoice' => $payment->invoice,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-receipt me-1"></i>
                    Back to Invoice
                </a>

            @endif


            {{-- Payments --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.payments.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                Back
            </a>

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

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        STATUS
    ============================================================= --}}

    @php

        $statusClass = match($payment->status) {

            'Processed' =>
                'bg-success',

            'Approved' =>
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
                        Payment Status
                    </div>

                    <span class="badge {{ $statusClass }} fs-6 mt-1">
                        {{ $payment->status }}
                    </span>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <div class="text-muted small">
                        Payment Number
                    </div>

                    <strong class="fs-5">
                        {{ $payment->payment_number }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">


        {{-- ========================================================
            PAYMENT DETAILS
        ========================================================= --}}

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>
                        Payment Details
                    </strong>
                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Payment Number
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->payment_number }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Payment Date
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $payment->payment_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Payment Type
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->payment_type ?? '—' }}
                            </div>

                        </div>


                        {{-- Invoice --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Invoice
                            </div>

                            @if($payment->invoice)

                                <div class="fw-semibold">
                                    {{ $payment->invoice->invoice_number }}
                                </div>

                            @else
                                —
                            @endif

                        </div>


                        {{-- Milestone --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Milestone
                            </div>

                            @if($payment->milestone)

                                <div class="fw-semibold">
                                    {{ $payment->milestone->milestone_number }}
                                </div>

                                <div class="small text-muted">
                                    {{ $payment->milestone->milestone_title }}
                                </div>

                            @else
                                —
                            @endif

                        </div>


                        {{-- Payment Method --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Payment Method
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->payment_method ?? '—' }}
                            </div>

                        </div>


                        {{-- Transaction Reference --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Transaction Reference
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->transaction_reference ?? '—' }}
                            </div>

                        </div>


                        {{-- Bank --}}
                        <div class="col-md-4">

                            <div class="text-muted small">
                                Bank
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->bank_name ?? '—' }}
                            </div>

                        </div>


                        {{-- Account Reference --}}
                        <div class="col-12">

                            <div class="text-muted small">
                                Account Reference
                            </div>

                            <div class="fw-semibold">
                                {{ $payment->account_reference ?? '—' }}
                            </div>

                        </div>


                        {{-- Description --}}
                        @if($payment->description)

                            <div class="col-12">

                                <div class="text-muted small">
                                    Description
                                </div>

                                <div class="mt-1">
                                    {{ $payment->description }}
                                </div>

                            </div>

                        @endif


                        {{-- Remarks --}}
                        @if($payment->remarks)

                            <div class="col-12">

                                <div class="text-muted small">
                                    Remarks
                                </div>

                                <div class="mt-1">
                                    {{ $payment->remarks }}
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ====================================================
                PAYMENT AMOUNT
            ===================================================== --}}

            <div class="card">

                <div class="card-header">

                    <strong>
                        Payment Amount
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Payment Amount
                            </div>

                            <div class="fs-3 fw-bold">

                                {{ number_format(
                                    (float) $payment->amount,
                                    2
                                ) }}

                                {{ $payment->currency }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Invoice Amount
                            </div>

                            <div class="fs-5 fw-semibold">

                                @if($payment->invoice)

                                    {{ number_format(
                                        (float) $payment->invoice->net_amount,
                                        2
                                    ) }}

                                    {{ $payment->currency }}

                                @else
                                    —
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            WORKFLOW + ACTIONS
        ========================================================= --}}

        <div class="col-lg-4">


            {{-- WORKFLOW --}}
            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Payment Workflow
                    </strong>

                </div>


                <div class="card-body">


                    {{-- DRAFT --}}
                    <div class="d-flex gap-3 mb-4">

                        <span class="badge bg-secondary">
                            1
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Draft
                            </div>

                            <div class="small text-muted">
                                Payment created.
                            </div>

                        </div>

                    </div>


                    {{-- SUBMITTED --}}
                    <div class="d-flex gap-3 mb-4">

                        <span
                            class="badge
                            {{
                                $payment->submitted_at
                                    ? 'bg-warning text-dark'
                                    : 'bg-light text-dark'
                            }}"
                        >
                            2
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Submitted
                            </div>

                            <div class="small text-muted">

                                @if($payment->submitted_at)

                                    {{
                                        $payment->submitted_at
                                            ->format('d-m-Y H:i')
                                    }}

                                @else

                                    Pending submission

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- APPROVED --}}
                    <div class="d-flex gap-3 mb-4">

                        <span
                            class="badge
                            {{
                                $payment->approved_at
                                    ? 'bg-success'
                                    : 'bg-light text-dark'
                            }}"
                        >
                            3
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Approved
                            </div>

                            <div class="small text-muted">

                                @if($payment->approved_at)

                                    {{
                                        $payment->approved_at
                                            ->format('d-m-Y H:i')
                                    }}

                                @else

                                    Pending approval

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- PROCESSED --}}
                    <div class="d-flex gap-3">

                        <span
                            class="badge
                            {{
                                $payment->processed_at
                                    ? 'bg-success'
                                    : 'bg-light text-dark'
                            }}"
                        >
                            4
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Processed
                            </div>

                            <div class="small text-muted">

                                @if($payment->processed_at)

                                    {{
                                        $payment->processed_at
                                            ->format('d-m-Y H:i')
                                    }}

                                @else

                                    Payment pending processing

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="card">

                <div class="card-header">

                    <strong>
                        Actions
                    </strong>

                </div>


                <div class="card-body">


                    {{-- SUBMIT --}}
                    @if($payment->status === 'Draft')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.procurement.tenders.contracts.payments.submit',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'payment' => $payment,
                                ]
                            ) }}"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                onclick="return confirm(
                                    'Submit this payment for approval?'
                                )"
                            >
                                Submit Payment
                            </button>

                        </form>

                    @endif


                    {{-- APPROVE --}}
                    @if($payment->status === 'Submitted')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.procurement.tenders.contracts.payments.approve',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'payment' => $payment,
                                ]
                            ) }}"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm(
                                    'Approve this payment?'
                                )"
                            >
                                Approve Payment
                            </button>

                        </form>


                        {{-- Reject goes to dedicated page --}}
                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.payments.reject',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'payment' => $payment,
                                ]
                            ) }}"
                            class="btn btn-danger w-100"
                        >
                            Reject Payment
                        </a>

                    @endif


                    {{-- PROCESS --}}
                    @if($payment->status === 'Approved')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.procurement.tenders.contracts.payments.process',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'payment' => $payment,
                                ]
                            ) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm(
                                    'Process this payment? This will update the invoice payment balance.'
                                )"
                            >
                                Process Payment
                            </button>

                        </form>

                    @endif


                    {{-- PROCESSED --}}
                    @if($payment->status === 'Processed')

                        <div class="alert alert-success mb-0">

                            <strong>
                                Payment Processed
                            </strong>

                            <div class="small mt-1">
                                This payment has been processed successfully.
                            </div>

                        </div>

                    @endif


                    {{-- REJECTED --}}
                    @if($payment->status === 'Rejected')

                        <div class="alert alert-danger mb-0">

                            <strong>
                                Payment Rejected
                            </strong>

                            @if($payment->rejection_remarks)

                                <div class="small mt-2">

                                    <strong>
                                        Reason:
                                    </strong>

                                    <br>

                                    {{ $payment->rejection_remarks }}

                                </div>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection