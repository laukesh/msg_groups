@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Rent Payments
            </h4>

            <p class="text-muted mb-0">
                Manage and track tenant rent payments.
            </p>
        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- =========================================================
     FILTERS
========================================================== --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-funnel me-1"></i>
            Filters
        </h5>

    </div>


    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.revenue.payments.index') }}"
        >

            <div class="row g-3">

                {{-- Payment No --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment No.
                    </label>

                    <input
                        type="text"
                        name="payment_no"
                        class="form-control"
                        placeholder="RP-2026-00001"
                        value="{{ request('payment_no') }}"
                    >

                </div>


                {{-- Tenant --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Tenant
                    </label>

                    <select
                        name="tenant_id"
                        class="form-select"
                    >

                        <option value="">
                            All Tenants
                        </option>

                        @foreach($tenants as $tenant)

                            <option
                                value="{{ $tenant->id }}"
                                {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}
                            >

                                {{ $tenant->company_name }}

                                @if($tenant->tenant_code)
                                    ({{ $tenant->tenant_code }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Payment Status --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment Status
                    </label>

                    <select
                        name="payment_status"
                        class="form-select"
                    >

                        <option value="">
                            All Statuses
                        </option>

                        <option
                            value="Pending"
                            {{ request('payment_status') === 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Confirmed"
                            {{ request('payment_status') === 'Confirmed' ? 'selected' : '' }}
                        >
                            Confirmed
                        </option>

                        <option
                            value="Failed"
                            {{ request('payment_status') === 'Failed' ? 'selected' : '' }}
                        >
                            Failed
                        </option>

                        <option
                            value="Cancelled"
                            {{ request('payment_status') === 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                        <option
                            value="Reversed"
                            {{ request('payment_status') === 'Reversed' ? 'selected' : '' }}
                        >
                            Reversed
                        </option>

                    </select>

                </div>


                {{-- Reconciliation --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Reconciliation
                    </label>

                    <select
                        name="reconciliation_status"
                        class="form-select"
                    >

                        <option value="">
                            All
                        </option>

                        <option
                            value="Pending"
                            {{ request('reconciliation_status') === 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Reconciled"
                            {{ request('reconciliation_status') === 'Reconciled' ? 'selected' : '' }}
                        >
                            Reconciled
                        </option>

                    </select>

                </div>


                {{-- Payment Mode --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment Mode
                    </label>

                    <select
                        name="payment_mode"
                        class="form-select"
                    >

                        <option value="">
                            All Modes
                        </option>

                        <option value="Cash"
                            {{ request('payment_mode') === 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="Cheque"
                            {{ request('payment_mode') === 'Cheque' ? 'selected' : '' }}>
                            Cheque
                        </option>

                        <option value="NEFT"
                            {{ request('payment_mode') === 'NEFT' ? 'selected' : '' }}>
                            NEFT
                        </option>

                        <option value="RTGS"
                            {{ request('payment_mode') === 'RTGS' ? 'selected' : '' }}>
                            RTGS
                        </option>

                        <option value="IMPS"
                            {{ request('payment_mode') === 'IMPS' ? 'selected' : '' }}>
                            IMPS
                        </option>

                        <option value="UPI"
                            {{ request('payment_mode') === 'UPI' ? 'selected' : '' }}>
                            UPI
                        </option>

                        <option value="Credit Card"
                            {{ request('payment_mode') === 'Credit Card' ? 'selected' : '' }}>
                            Credit Card
                        </option>

                        <option value="Debit Card"
                            {{ request('payment_mode') === 'Debit Card' ? 'selected' : '' }}>
                            Debit Card
                        </option>

                    </select>

                </div>


                {{-- Date From --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment Date From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        class="form-control"
                        value="{{ request('date_from') }}"
                    >

                </div>


                {{-- Date To --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}"
                    >

                </div>


                {{-- Buttons --}}
                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-search"></i>
                        Search

                    </button>


                    <a
                        href="{{ route('admin.revenue.payments.index') }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="bi bi-arrow-clockwise"></i>
                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


    {{-- =========================================================
         PAYMENT LIST
    ========================================================== --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Payment List
                </h5>

                <span class="text-muted">
                    Total:
                    {{ $payments->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($payments->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Payment No.</th>

                                <th>Date</th>

                                <th>Tenant</th>

                                <th>Invoice</th>

                                <th>Amount</th>

                                <th>Mode</th>

                                <th>Payment Status</th>

                                <th>Reconciliation</th>

                                <th class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($payments as $payment)

                                <tr>

                                    {{-- Serial Number --}}
                                    <td>
                                        {{ $payments->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Payment Number --}}
                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.revenue.payments.show',
                                                $payment->id
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $payment->payment_no }}
                                        </a>

                                    </td>


                                    {{-- Payment Date --}}
                                    <td>

                                        {{ $payment->payment_date
                                            ? \Carbon\Carbon::parse(
                                                $payment->payment_date
                                            )->format('d M Y')
                                            : '-' }}

                                    </td>


                                    {{-- Tenant --}}
                                    <td>

                                        @if($payment->tenant)

                                            <div class="fw-semibold">
                                                {{ $payment->tenant->company_name }}
                                            </div>

                                            @if($payment->tenant->tenant_code)

                                                <small class="text-muted">
                                                    {{ $payment->tenant->tenant_code }}
                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Invoice --}}
                                    <td>

                                        @if($payment->invoice)

                                            <a
                                                href="#"
                                                class="text-decoration-none"
                                            >
                                                {{ $payment->invoice->invoice_no }}
                                            </a>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}
                                    <td>

                                        <strong>
                                            ${{ number_format(
                                                $payment->payment_amount,
                                                2
                                            ) }}
                                        </strong>

                                    </td>


                                    {{-- Payment Mode --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $payment->payment_mode }}

                                        </span>

                                    </td>


                                    {{-- Payment Status --}}
                                    <td>

                                        @switch($payment->payment_status)

                                            @case('Pending')

                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>

                                                @break


                                            @case('Confirmed')

                                                <span class="badge bg-success">
                                                    Confirmed
                                                </span>

                                                @break


                                            @case('Reversed')

                                                <span class="badge bg-danger">
                                                    Reversed
                                                </span>

                                                @break


                                            @case('Failed')

                                                <span class="badge bg-danger">
                                                    Failed
                                                </span>

                                                @break


                                            @case('Cancelled')

                                                <span class="badge bg-secondary">
                                                    Cancelled
                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary">
                                                    {{ $payment->payment_status }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Reconciliation Status --}}
                                    <td>

                                        @if($payment->reconciliation_status === 'Reconciled')

                                            <span class="badge bg-success">
                                                Reconciled
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <a
                                            href="{{ route(
                                                'admin.revenue.payments.show',
                                                $payment->id
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View Payment"
                                        >

                                            <i class="bi bi-eye"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}
                <div class="p-3 border-top">

                    {{ $payments->links() }}

                </div>


            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-wallet2"
                            style="font-size: 3rem; color: #adb5bd;"
                        ></i>

                    </div>

                    <h5>
                        No payments found
                    </h5>

                    <p class="text-muted mb-0">
                        No rent payments have been recorded yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection