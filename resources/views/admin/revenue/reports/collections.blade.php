@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Collection Report
            </h4>

            <p class="text-muted mb-0">
                Detailed report of confirmed rent payments.
            </p>

        </div>

    </div>


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Collected
                    </div>

                    <h4 class="mb-0 text-success">

                        ${{ number_format(
                            (float) $totalCollected,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Confirmed Payments
                    </div>

                    <h4 class="mb-0">

                        {{ number_format($paymentCount) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FILTERS
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.revenue.reports.collections'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Payment / Invoice / Tenant"
                        >

                    </div>


                    {{-- From Date --}}

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


                    {{-- To Date --}}

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


                    {{-- Search --}}

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>


                    {{-- Reset --}}

                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reports.collections'
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


    {{-- ============================================================
        COLLECTION TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Collection Details
                    </h5>

                    <small class="text-muted">
                        Confirmed payment transactions
                    </small>

                </div>

                <span class="badge bg-light text-dark">

                    {{ $payments->total() }}
                    Records

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Payment No.
                            </th>

                            <th>
                                Payment Date
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Payment Mode
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th>
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

                                {{-- Payment No --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $payment->payment_no }}

                                    </div>

                                </td>


                                {{-- Payment Date --}}

                                <td>

                                    @if($payment->payment_date)

                                        {{ \Carbon\Carbon::parse(
                                            $payment->payment_date
                                        )->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Tenant --}}

                                <td>

                                    @if($payment->tenant)

                                        <div class="fw-semibold">

                                            {{ $payment->tenant->company_name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $payment->tenant->tenant_code }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Invoice --}}

                                <td>

                                    @if($payment->invoice)

                                        <a
                                            href="{{ route(
                                                'admin.revenue.invoices.show',
                                                $payment->invoice->id
                                            ) }}"
                                            class="text-decoration-none fw-semibold"
                                        >
                                            {{ $payment->invoice->invoice_no }}
                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Payment Mode --}}

                                <td>

                                    {{ $payment->payment_mode ?? '—' }}

                                </td>


                                {{-- Amount --}}

                                <td class="text-end text-success fw-semibold">

                                    ${{ number_format(
                                        (float) $payment->payment_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Status --}}

                                <td>

                                    <span class="badge bg-success">
                                        Confirmed
                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    @if(
                                        Route::has(
                                            'admin.revenue.payments.show'
                                        )
                                    )

                                        <a
                                            href="{{ route(
                                                'admin.revenue.payments.show',
                                                $payment->id
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
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


        {{-- ========================================================
            PAGINATION
        ========================================================= --}}

        @if($payments->hasPages())

            <div class="card-footer bg-white">

                {{ $payments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection