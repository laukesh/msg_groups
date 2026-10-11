@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Aging Report
            </h4>

            <p class="text-muted mb-0">
                Analyze outstanding invoices by aging period.
            </p>

        </div>

    </div>


    {{-- ============================================================
        AGING SUMMARY
    ============================================================ --}}

    <div class="row g-3 mb-4">

        {{-- Current --}}

        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Current
                    </div>

                    <h5 class="mb-1">

                        ${{ number_format(
                            (float) $aging['current']['amount'],
                            2
                        ) }}

                    </h5>

                    <small class="text-muted">

                        {{ $aging['current']['count'] }}
                        invoices

                    </small>

                </div>

            </div>

        </div>


        {{-- 1-30 --}}

        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        1–30 Days
                    </div>

                    <h5 class="mb-1 text-warning">

                        ${{ number_format(
                            (float) $aging['1_30']['amount'],
                            2
                        ) }}

                    </h5>

                    <small class="text-muted">

                        {{ $aging['1_30']['count'] }}
                        invoices

                    </small>

                </div>

            </div>

        </div>


        {{-- 31-60 --}}

        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        31–60 Days
                    </div>

                    <h5 class="mb-1 text-warning">

                        ${{ number_format(
                            (float) $aging['31_60']['amount'],
                            2
                        ) }}

                    </h5>

                    <small class="text-muted">

                        {{ $aging['31_60']['count'] }}
                        invoices

                    </small>

                </div>

            </div>

        </div>


        {{-- 61-90 --}}

        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        61–90 Days
                    </div>

                    <h5 class="mb-1 text-danger">

                        ${{ number_format(
                            (float) $aging['61_90']['amount'],
                            2
                        ) }}

                    </h5>

                    <small class="text-muted">

                        {{ $aging['61_90']['count'] }}
                        invoices

                    </small>

                </div>

            </div>

        </div>


        {{-- 90+ --}}

        <div class="col-xl col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        90+ Days
                    </div>

                    <h5 class="mb-1 text-danger">

                        ${{ number_format(
                            (float) $aging['90_plus']['amount'],
                            2
                        ) }}

                    </h5>

                    <small class="text-muted">

                        {{ $aging['90_plus']['count'] }}
                        invoices

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        TOTAL
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="text-muted small">
                        Total Outstanding
                    </div>

                    <h4 class="mb-0 text-danger">

                        ${{ number_format(
                            (float) $totalOutstanding,
                            2
                        ) }}

                    </h4>

                </div>

                <span class="badge bg-danger">
                    Outstanding
                </span>

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
                    'admin.revenue.reports.aging'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Invoice / Tenant"
                        >

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


                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>


                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reports.aging'
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
        INVOICE AGING TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Aging Details
                    </h5>

                    <small class="text-muted">
                        Outstanding invoice aging
                    </small>

                </div>

                <span class="badge bg-light text-dark">

                    {{ $invoices->total() }}
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
                                Invoice
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th class="text-center">
                                Days
                            </th>

                            <th>
                                Aging
                            </th>

                            <th class="text-end">
                                Outstanding
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoices as $invoice)

                            @php

                                $dueDate = $invoice->due_date
                                    ? \Carbon\Carbon::parse(
                                        $invoice->due_date
                                    )
                                    : null;

                                $daysOverdue = 0;

                                if ($dueDate && $dueDate->isPast()) {

                                    $daysOverdue =
                                        $dueDate->diffInDays(
                                            now()
                                        );
                                }

                                if (!$dueDate || !$dueDate->isPast()) {

                                    $agingLabel = 'Current';
                                    $agingClass = 'bg-success';

                                } elseif ($daysOverdue <= 30) {

                                    $agingLabel = '1–30 Days';
                                    $agingClass = 'bg-warning text-dark';

                                } elseif ($daysOverdue <= 60) {

                                    $agingLabel = '31–60 Days';
                                    $agingClass = 'bg-warning text-dark';

                                } elseif ($daysOverdue <= 90) {

                                    $agingLabel = '61–90 Days';
                                    $agingClass = 'bg-danger';

                                } else {

                                    $agingLabel = '90+ Days';
                                    $agingClass = 'bg-danger';
                                }

                            @endphp

                            <tr>

                                {{-- Invoice --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $invoice->invoice_no }}

                                    </div>

                                </td>


                                {{-- Tenant --}}

                                <td>

                                    @if($invoice->tenant)

                                        <div class="fw-semibold">

                                            {{ $invoice->tenant->company_name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $invoice->tenant->tenant_code }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Due Date --}}

                                <td>

                                    @if($dueDate)

                                        {{ $dueDate->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Days --}}

                                <td class="text-center">

                                    @if($daysOverdue > 0)

                                        <span class="text-danger fw-semibold">

                                            {{ $daysOverdue }}

                                        </span>

                                    @else

                                        <span class="text-success">
                                            0
                                        </span>

                                    @endif

                                </td>


                                {{-- Aging --}}

                                <td>

                                    <span class="badge {{ $agingClass }}">

                                        {{ $agingLabel }}

                                    </span>

                                </td>


                                {{-- Outstanding --}}

                                <td class="text-end">

                                    <span class="text-danger fw-semibold">

                                        ${{ number_format(
                                            (float) $invoice->balance_amount,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.revenue.invoices.show',
                                            $invoice->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-success fs-3">
                                        ✓
                                    </div>

                                    <div class="fw-semibold">
                                        No outstanding invoices
                                    </div>

                                    <small class="text-muted">
                                        There are no invoices requiring
                                        aging analysis.
                                    </small>

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

        @if($invoices->hasPages())

            <div class="card-footer bg-white">

                {{ $invoices->links() }}

            </div>

        @endif

    </div>

</div>

@endsection