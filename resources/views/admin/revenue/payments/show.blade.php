@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Payment Details</h4>
            <p class="text-muted mb-0">
                View rent payment information
            </p>
        </div>

        <div>
            <a href="{{ url()->previous() }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>
            @if($payment->payment_status === 'Confirmed')

    <a
        href="{{ route(
            'admin.revenue.payments.receipt',
            $payment->id
        ) }}"
        target="_blank"
        class="btn btn-outline-primary"
    >

        <i class="bi bi-receipt"></i>

        Payment Receipt

    </a>

@endif
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">


        {{-- =========================================================
             PAYMENT INFORMATION
        ========================================================== --}}

        <div class="col-md-6">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Payment Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Payment No
                        </div>

                        <div class="col-sm-7 fw-semibold">
                            {{ $payment->payment_no }}
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Payment Date
                        </div>

                        <div class="col-sm-7">
                            {{ $payment->payment_date
                                ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y')
                                : '-' }}
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Payment Amount
                        </div>

                        <div class="col-sm-7 fw-bold text-success">

                            ${{ number_format(
                                $payment->payment_amount,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Payment Mode
                        </div>

                        <div class="col-sm-7">

                            <span class="badge bg-secondary">

                                {{ $payment->payment_mode }}

                            </span>

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Payment Status
                        </div>

                        <div class="col-sm-7">

                            @if($payment->payment_status === 'Pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @elseif($payment->payment_status === 'Confirmed')

                                <span class="badge bg-success">
                                    Confirmed
                                </span>

                            @elseif($payment->payment_status === 'Failed')

                                <span class="badge bg-danger">
                                    Failed
                                </span>

                            @elseif($payment->payment_status === 'Cancelled')

                                <span class="badge bg-dark">
                                    Cancelled
                                </span>

                            @elseif($payment->payment_status === 'Reversed')

                                <span class="badge bg-danger">
                                    Reversed
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $payment->payment_status ?? '-' }}
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Reconciliation
                        </div>

                        <div class="col-sm-7">

                            @if($payment->reconciliation_status === 'Reconciled')

                                <span class="badge bg-success">
                                    Reconciled
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>


                    @if($payment->bank_name)

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Bank Name
                            </div>

                            <div class="col-sm-7">
                                {{ $payment->bank_name }}
                            </div>

                        </div>

                    @endif


                    @if($payment->cheque_no)

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Cheque No
                            </div>

                            <div class="col-sm-7">
                                {{ $payment->cheque_no }}
                            </div>

                        </div>

                    @endif


                    @if($payment->transaction_reference)

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Transaction Reference
                            </div>

                            <div class="col-sm-7">
                                {{ $payment->transaction_reference }}
                            </div>

                        </div>

                    @endif


                    @if($payment->received_by)

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Received By
                            </div>

                            <div class="col-sm-7">
                                {{ $payment->received_by }}
                            </div>

                        </div>

                    @endif


                    @if($payment->remarks)

                        <div class="row">

                            <div class="col-sm-5 text-muted">
                                Remarks
                            </div>

                            <div class="col-sm-7">
                                {{ $payment->remarks }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- =========================================================
             INVOICE INFORMATION
        ========================================================== --}}

        <div class="col-md-6">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Invoice Information
                    </h5>

                </div>


                <div class="card-body">

                    @if($payment->invoice)

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Invoice No
                            </div>

                            <div class="col-sm-7 fw-semibold">

                                <a href="{{ route(
                                    'admin.revenue.invoices.show',
                                    $payment->invoice->id
                                ) }}">

                                    {{ $payment->invoice->invoice_no }}

                                </a>

                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Invoice Type
                            </div>

                            <div class="col-sm-7">

                                {{ $payment->invoice->invoice_type }}

                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Invoice Date
                            </div>

                            <div class="col-sm-7">

                                {{ $payment->invoice->invoice_date
                                    ? \Carbon\Carbon::parse(
                                        $payment->invoice->invoice_date
                                    )->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Due Date
                            </div>

                            <div class="col-sm-7">

                                {{ $payment->invoice->due_date
                                    ? \Carbon\Carbon::parse(
                                        $payment->invoice->due_date
                                    )->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        <hr>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Invoice Total
                            </div>

                            <div class="col-sm-7 fw-bold">

                                ${{ number_format(
                                    $payment->invoice->total_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Paid Amount
                            </div>

                            <div class="col-sm-7 text-success fw-bold">

                                ${{ number_format(
                                    $payment->invoice->paid_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-sm-5 text-muted">
                                Balance Amount
                            </div>

                            <div class="col-sm-7 text-danger fw-bold">

                                ${{ number_format(
                                    $payment->invoice->balance_amount,
                                    2
                                ) }}

                            </div>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            This payment is not linked to any invoice.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         TENANT INFORMATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Tenant Information
            </h5>

        </div>


        <div class="card-body">

            @if($payment->tenant)

                <div class="row">

                    <div class="col-md-3">

                        <small class="text-muted">
                            Tenant Code
                        </small>

                        <div class="fw-semibold">
                            {{ $payment->tenant->tenant_code }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Company Name
                        </small>

                        <div class="fw-semibold">
                            {{ $payment->tenant->company_name }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Brand Name
                        </small>

                        <div>
                            {{ $payment->tenant->brand_name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Phone
                        </small>

                        <div>
                            {{ $payment->tenant->phone ?? '-' }}
                        </div>

                    </div>

                </div>

            @else

                <div class="text-muted">
                    Tenant information not available.
                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
     PAYMENT ACTIONS
    ========================================================== --}}

        @if($payment->payment_status === 'Pending')

            {{-- Confirm Payment --}}
            <div class="card shadow-sm border-warning mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1">
                                Payment Pending
                            </h5>

                            <p class="text-muted mb-0">
                                This payment has not been confirmed yet.
                                Confirming it will allocate the payment
                                against the invoice.
                            </p>

                        </div>

                        <div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.revenue.payments.confirm',
                                    $payment->id
                                ) }}"
                                class="d-inline"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm(
                                        'Are you sure you want to confirm this payment?'
                                    )"
                                >

                                    <i class="bi bi-check-circle"></i>

                                    Confirm Payment

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


        @elseif($payment->payment_status === 'Confirmed')

            {{-- Reverse Payment --}}
            <div class="card shadow-sm border-danger mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1 text-danger">
                                Reverse Payment
                            </h5>

                            <p class="text-muted mb-0">
                                Reversing this payment will reverse its
                                allocation and restore the invoice balance.
                            </p>

                        </div>

                        <div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.revenue.payments.reverse',
                                    $payment->id
                                ) }}"
                                class="d-inline"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm(
                                        'Are you sure you want to reverse this payment?'
                                    )"
                                >

                                    <i class="bi bi-arrow-counterclockwise"></i>

                                    Reverse Payment

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


        @elseif($payment->payment_status === 'Reversed')

            <div class="alert alert-danger">

                <i class="bi bi-arrow-counterclockwise"></i>

                This payment has been reversed.

            </div>


        @elseif($payment->payment_status === 'Failed')

            <div class="alert alert-danger">

                <i class="bi bi-x-circle"></i>

                This payment has failed.

            </div>


        @elseif($payment->payment_status === 'Cancelled')

            <div class="alert alert-dark">

                <i class="bi bi-slash-circle"></i>

                This payment has been cancelled.

            </div>

        @endif



    {{-- =========================================================
         PAYMENT ALLOCATIONS
    ========================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Payment Allocations
            </h5>

        </div>


        <div class="card-body p-0">

            @if(
                isset($payment->allocations) &&
                $payment->allocations->count() > 0
            )

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Allocation Date
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $payment->allocations as $allocation
                            )

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        {{ $allocation->allocation_date
                                            ? \Carbon\Carbon::parse(
                                                $allocation->allocation_date
                                            )->format('d M Y')
                                            : '-' }}

                                    </td>

                                    <td>

                                        @if($allocation->invoice)

                                            {{ $allocation->invoice->invoice_no }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td class="text-end fw-semibold">

                                        ${{ number_format(
                                            $allocation->allocated_amount,
                                            2
                                        ) }}

                                    </td>

                                    <td>

                                        @if(
                                            $allocation->allocation_status
                                            === 'Allocated'
                                        )

                                            <span class="badge bg-success">
                                                Allocated
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Reversed
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $allocation->remarks ?? '-' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center text-muted">

                    No payment allocation has been created yet.

                </div>

            @endif

        </div>

    </div>
    @if(
        $payment->payment_status === 'Confirmed' &&
        $payment->reconciliation_status === 'Pending'
    )

        <div class="card shadow-sm border-primary mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1 text-primary">
                            Reconcile Payment
                        </h5>

                        <p class="text-muted mb-0">
                            Confirm that this payment has been
                            verified against the bank or payment records.
                        </p>

                    </div>

                    <div>

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.revenue.payments.reconcile',
                                $payment->id
                            ) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Are you sure you want to reconcile this payment?'
                                )"
                            >

                                <i class="bi bi-check2-circle"></i>

                                Reconcile Payment

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @endif
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Payment Status
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <label class="text-muted">
                        Payment Status
                    </label>

                    <div class="mt-1">

                        @if($payment->payment_status === 'Confirmed')

                            <span class="badge bg-success">
                                Confirmed
                            </span>

                        @elseif($payment->payment_status === 'Pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @elseif($payment->payment_status === 'Reversed')

                            <span class="badge bg-danger">
                                Reversed
                            </span>

                        @elseif($payment->payment_status === 'Failed')

                            <span class="badge bg-danger">
                                Failed
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $payment->payment_status }}
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="text-muted">
                        Reconciliation Status
                    </label>

                    <div class="mt-1">

                        @if($payment->reconciliation_status === 'Reconciled')

                            <span class="badge bg-success">
                                Reconciled
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

    </div>

</div>

@endsection