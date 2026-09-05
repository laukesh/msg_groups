@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Outstanding</h4>

            <p class="text-muted mb-0">
                Track unpaid and partially paid invoices.
            </p>
        </div>

    </div>


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        {{-- Total Outstanding --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Outstanding
                            </div>

                            <h4 class="mb-0">
                                ${{ number_format(
                                    (float) $totalOutstanding,
                                    2
                                ) }}
                            </h4>

                        </div>

                        <div class="text-danger fs-3">
                            $
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Outstanding Invoices --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-1">
                                Outstanding Invoices
                            </div>

                            <h4 class="mb-0">
                                {{ number_format($totalInvoices) }}
                            </h4>

                        </div>

                        <div class="text-warning fs-3">
                            #
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue Invoices --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-1">
                                Overdue Invoices
                            </div>

                            <h4 class="mb-0">
                                {{ number_format($overdueInvoices) }}
                            </h4>

                        </div>

                        <div class="text-danger fs-3">
                            !
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue Amount --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-1">
                                Overdue Amount
                            </div>

                            <h4 class="mb-0 text-danger">
                                ${{ number_format(
                                    (float) $overdueAmount,
                                    2
                                ) }}
                            </h4>

                        </div>

                        <div class="text-danger fs-3">
                            ⚠
                        </div>

                    </div>

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
                action="{{ route('admin.revenue.outstanding.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Invoice number, tenant name or tenant code"
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Outstanding
                            </option>

                            <option
                                value="Generated"
                                {{ request('status') === 'Generated' ? 'selected' : '' }}
                            >
                                Generated
                            </option>

                            <option
                                value="Partially Paid"
                                {{ request('status') === 'Partially Paid' ? 'selected' : '' }}
                            >
                                Partially Paid
                            </option>

                        </select>

                    </div>


                    {{-- Search Button --}}

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
                            href="{{ route('admin.revenue.outstanding.index') }}"
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
        OUTSTANDING INVOICES TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Outstanding Invoices
                    </h5>

                    <small class="text-muted">
                        Showing unpaid and partially paid invoices
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $invoices->total() }} Records
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
                                Invoice Date
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th class="text-end">
                                Paid
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

                            @php

                                $isOverdue =
                                    !empty($invoice->due_date) &&
                                    \Carbon\Carbon::parse(
                                        $invoice->due_date
                                    )->isPast();

                            @endphp

                            <tr>

                                {{-- Invoice --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $invoice->invoice_no }}

                                    </div>

                                    @if($invoice->invoice_type ?? false)

                                        <small class="text-muted">
                                            {{ $invoice->invoice_type }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Tenant --}}

                                <td>

                                    @if($invoice->tenant)

                                        <div class="fw-semibold">

                                            {{ $invoice->tenant->company_name }}

                                        </div>

                                        @if($invoice->tenant->tenant_code)

                                            <small class="text-muted">

                                                {{ $invoice->tenant->tenant_code }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Invoice Date --}}

                                <td>

                                    @if($invoice->invoice_date)

                                        {{ \Carbon\Carbon::parse(
                                            $invoice->invoice_date
                                        )->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Due Date --}}

                                <td>

                                    @if($invoice->due_date)

                                        <div class="
                                            {{ $isOverdue
                                                ? 'text-danger fw-semibold'
                                                : ''
                                            }}
                                        ">

                                            {{ \Carbon\Carbon::parse(
                                                $invoice->due_date
                                            )->format('d M Y') }}

                                        </div>

                                        @if($isOverdue)

                                            <small class="text-danger">
                                                Overdue
                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Total --}}

                                <td class="text-end">

                                    ${{ number_format(
                                        (float) $invoice->total_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Paid --}}

                                <td class="text-end text-success">

                                    ${{ number_format(
                                        (float) $invoice->paid_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Outstanding --}}

                                <td class="text-end">

                                    <span class="fw-semibold text-danger">

                                        ${{ number_format(
                                            (float) $invoice->balance_amount,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if($invoice->invoice_status === 'Partially Paid')

                                        <span class="badge bg-warning text-dark">
                                            Partially Paid
                                        </span>

                                    @elseif($invoice->invoice_status === 'Generated')

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
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <div class="fs-3 mb-2">
                                            ✓
                                        </div>

                                        <div class="fw-semibold">
                                            No outstanding invoices
                                        </div>

                                        <small>
                                            All invoices are currently paid.
                                        </small>

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

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        Showing
                        {{ $invoices->firstItem() ?? 0 }}
                        to
                        {{ $invoices->lastItem() ?? 0 }}
                        of
                        {{ $invoices->total() }}
                        records

                    </div>

                    <div>

                        {{ $invoices->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection