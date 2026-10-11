@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Revenue Report
            </h4>

            <p class="text-muted mb-0">
                Detailed invoice revenue and collection report.
            </p>

        </div>

    </div>


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Invoiced
                    </div>

                    <h4 class="mb-0">

                        ${{ number_format(
                            (float) $totalInvoiced,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

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


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Outstanding
                    </div>

                    <h4 class="mb-0 text-danger">

                        ${{ number_format(
                            (float) $totalOutstanding,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Invoice Count
                    </div>

                    <h4 class="mb-0">

                        {{ number_format($invoiceCount) }}

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
                action="{{ route('admin.revenue.reports.revenue') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-3">

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


                    {{-- Status --}}

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
                                value="Generated"
                                {{ request('status') === 'Generated'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Generated
                            </option>

                            <option
                                value="Partially Paid"
                                {{ request('status') === 'Partially Paid'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Partially Paid
                            </option>

                            <option
                                value="Paid"
                                {{ request('status') === 'Paid'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Paid
                            </option>

                        </select>

                    </div>


                    {{-- Search --}}

                    <div class="col-lg-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Go
                        </button>

                    </div>


                    {{-- Reset --}}

                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.reports.revenue'
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
        REPORT TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Revenue Details
                    </h5>

                    <small class="text-muted">
                        Invoice-wise revenue summary
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
                                Date
                            </th>

                            <th class="text-end">
                                Invoiced
                            </th>

                            <th class="text-end">
                                Collected
                            </th>

                            <th class="text-end">
                                Outstanding
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

                        @forelse($invoices as $invoice)

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


                                {{-- Date --}}

                                <td>

                                    @if($invoice->invoice_date)

                                        {{ \Carbon\Carbon::parse(
                                            $invoice->invoice_date
                                        )->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Invoiced --}}

                                <td class="text-end">

                                    ${{ number_format(
                                        (float) $invoice->total_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Collected --}}

                                <td class="text-end text-success">

                                    ${{ number_format(
                                        (float) $invoice->paid_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Outstanding --}}

                                <td class="text-end text-danger">

                                    ${{ number_format(
                                        (float) $invoice->balance_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if(
                                        $invoice->invoice_status ===
                                        'Paid'
                                    )

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif(
                                        $invoice->invoice_status ===
                                        'Partially Paid'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Partially Paid
                                        </span>

                                    @elseif(
                                        $invoice->invoice_status ===
                                        'Generated'
                                    )

                                        <span class="badge bg-danger">
                                            Outstanding
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $invoice->invoice_status }}
                                        </span>

                                    @endif

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
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No revenue records found.

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

        @if($invoices->hasPages())

            <div class="card-footer bg-white">

                {{ $invoices->links() }}

            </div>

        @endif

    </div>

</div>

@endsection