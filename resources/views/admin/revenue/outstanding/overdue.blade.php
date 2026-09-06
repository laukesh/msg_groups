@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Overdue Invoices
            </h4>

            <p class="text-muted mb-0">
                Invoices that have passed their due date and still have
                an outstanding balance.
            </p>

        </div>

        <a
            href="{{ route('admin.revenue.outstanding.index') }}"
            class="btn btn-light border"
        >
            ← Outstanding
        </a>

    </div>


    {{-- ============================================================
        SUMMARY
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Overdue Invoices
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ number_format($overdueInvoices) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

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

            </div>

        </div>

    </div>


    {{-- ============================================================
        SEARCH
    ============================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.revenue.outstanding.overdue') }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Invoice number, tenant name or tenant code"
                        >

                    </div>

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>

                    <div class="col-md-2">

                        <a
                            href="{{ route('admin.revenue.outstanding.overdue') }}"
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
        TABLE
    ============================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Overdue Invoices
                </h5>

                <span class="badge bg-danger">
                    {{ $invoices->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Invoice</th>

                            <th>Tenant</th>

                            <th>Invoice Date</th>

                            <th>Due Date</th>

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

                                $dueDate = \Carbon\Carbon::parse(
                                    $invoice->due_date
                                );

                                $daysOverdue = $dueDate->diffInDays(
                                    now()
                                );

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

                                    <div class="text-danger fw-semibold">

                                        {{ $dueDate->format('d M Y') }}

                                    </div>

                                    <small class="text-danger">

                                        {{ $daysOverdue }}
                                        {{ $daysOverdue == 1 ? 'day' : 'days' }}
                                        overdue

                                    </small>

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

                                    @if(
                                        $invoice->invoice_status ===
                                        'Partially Paid'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Partially Paid
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Outstanding
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

                                    <div class="text-success fs-3">
                                        ✓
                                    </div>

                                    <div class="fw-semibold">
                                        No overdue invoices
                                    </div>

                                    <small class="text-muted">
                                        There are currently no overdue
                                        outstanding invoices.
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