@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Payment Reconciliation
            </h4>

            <p class="text-muted mb-0">
                Reconcile confirmed rent payments against bank records.
            </p>

        </div>

    </div>


    {{-- SUMMARY --}}

    <div class="row g-3 mb-4">

        {{-- Pending --}}

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending Reconciliation
                    </div>

                    <h4 class="text-warning mb-1">

                        ${{ number_format(
                            (float) $pendingAmount,
                            2
                        ) }}

                    </h4>

                    <small class="text-muted">

                        {{ $pendingCount }}
                        payments

                    </small>

                </div>

            </div>

        </div>


        {{-- Reconciled --}}

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Reconciled
                    </div>

                    <h4 class="text-success mb-1">

                        ${{ number_format(
                            (float) $reconciledAmount,
                            2
                        ) }}

                    </h4>

                    <small class="text-muted">

                        {{ $reconciledCount }}
                        payments

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTERS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.revenue.reconciliation.index'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Payment / Tenant"
                        >

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="Pending"
                                {{ request('status') === 'Pending'
                                    ? 'selected'
                                    : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="Reconciled"
                                {{ request('status') === 'Reconciled'
                                    ? 'selected'
                                    : '' }}
                            >
                                Reconciled
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-lg-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Go
                        </button>

                    </div>


                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reconciliation.index'
                            ) }}"
                            class="btn btn-light border w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- PAYMENT TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Confirmed Payments
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Payment
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Payment Date
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $payment->payment_no }}

                                    </div>

                                </td>


                                <td>

                                    @if($payment->tenant)

                                        {{ $payment->tenant->company_name }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($payment->invoice)

                                        {{ $payment->invoice->invoice_no }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{ $payment->payment_date
                                        ? \Carbon\Carbon::parse(
                                            $payment->payment_date
                                        )->format('d M Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="text-end fw-semibold">

                                    ${{ number_format(
                                        (float) $payment->payment_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-center">

                                    @if(
                                        $payment->reconciliation_status
                                        === 'Reconciled'
                                    )

                                        <span class="badge bg-success">
                                            Reconciled
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    @if(
                                        $payment->reconciliation_status
                                        !== 'Reconciled'
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.revenue.reconciliation.reconcile',
                                                $payment->id
                                            ) }}"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                'Mark this payment as reconciled?'
                                            );"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                            >
                                                Reconcile
                                            </button>

                                        </form>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">
                                        No confirmed payments found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($payments->hasPages())

            <div class="card-footer bg-white">

                {{ $payments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection