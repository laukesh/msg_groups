@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Revenue Dashboard
            </h4>

            <p class="text-muted mb-0">
                Overview of leasing revenue, collections and outstanding dues.
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.revenue.payments.index') }}"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-wallet2"></i>

                Payments

            </a>

        </div>

    </div>


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Invoiced --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Invoiced
                            </p>

                            <h4 class="mb-0">
                                ${{ number_format($totalInvoiced, 2) }}
                            </h4>

                        </div>

                        <div class="text-primary fs-3">
                            <i class="bi bi-receipt"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Collected --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Collected
                            </p>

                            <h4 class="mb-0 text-success">
                                ${{ number_format($totalCollected, 2) }}
                            </h4>

                        </div>

                        <div class="text-success fs-3">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Outstanding
                            </p>

                            <h4 class="mb-0 text-warning">
                                ${{ number_format($outstandingAmount, 2) }}
                            </h4>

                        </div>

                        <div class="text-warning fs-3">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Overdue
                            </p>

                            <h4 class="mb-0 text-danger">
                                ${{ number_format($overdueAmount, 2) }}
                            </h4>

                        </div>

                        <div class="text-danger fs-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CURRENT MONTH
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Current Month Invoiced
                    </p>

                    <h5>
                        ${{ number_format($currentMonthInvoiced, 2) }}
                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Current Month Collected
                    </p>

                    <h5 class="text-success">
                        ${{ number_format($currentMonthCollected, 2) }}
                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Collection Rate
                    </p>

                    <h5>
                        {{ number_format($collectionRate, 2) }}%
                    </h5>

                    <div class="progress mt-2">

                        <div
                            class="progress-bar bg-success"
                            style="width: {{ min($collectionRate, 100) }}%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CHART + PAYMENT STATUS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Revenue Chart --}}
        <div class="col-lg-8">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Monthly Collection
                    </h5>

                </div>

                <div class="card-body">

                    <div style="height: 280px;">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Summary --}}
        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Payment Summary
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Pending Payments
                        </span>

                        <span class="badge bg-warning text-dark">
                            {{ $pendingPayments }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Confirmed Payments
                        </span>

                        <span class="badge bg-success">
                            {{ $confirmedPayments }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span>
                            Reconciled Payments
                        </span>

                        <span class="badge bg-primary">
                            {{ $reconciledPayments }}
                        </span>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <span>
                            Pending Reconciliation
                        </span>

                        <strong class="text-warning">
                            ${{ number_format(
                                $pendingReconciliation,
                                2
                            ) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         INVOICE SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total Invoices
                    </small>

                    <h4>
                        {{ $totalInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Paid Invoices
                    </small>

                    <h4 class="text-success">
                        {{ $paidInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Partially Paid
                    </small>

                    <h4 class="text-warning">
                        {{ $partialInvoices }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Overdue Invoices
                    </small>

                    <h4 class="text-danger">
                        {{ $overdueInvoices }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RECENT PAYMENTS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Recent Payments
                </h5>

                <a
                    href="{{ route('admin.revenue.payments.index') }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Payment No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentPayments as $payment)

                            <tr>

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


                                <td>

                                    {{ $payment->tenant?->company_name ?? '-' }}

                                </td>


                                <td>

                                    {{ $payment->invoice?->invoice_no ?? '-' }}

                                </td>


                                <td>

                                    {{ $payment->payment_date
                                        ? \Carbon\Carbon::parse(
                                            $payment->payment_date
                                        )->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    ${{ number_format(
                                        $payment->payment_amount,
                                        2
                                    ) }}

                                </td>


                                <td>

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

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $payment->payment_status }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4 text-muted"
                                >
                                    No payments found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- =========================================================
     REVENUE BY CHARGE TYPE
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                Revenue Breakdown by Charge Type
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Charge Type</th>
                            <th>Code</th>
                            <th class="text-end">Invoiced</th>
                            <th class="text-end">Collected</th>
                            <th class="text-end">Outstanding</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($chargeTypeRevenue as $charge)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $charge->charge_name }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $charge->charge_code }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    ${{ number_format(
                                        (float) $charge->invoiced_amount,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end text-success fw-semibold">
                                    ${{ number_format(
                                        (float) $charge->collected_amount,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end text-danger">
                                    ${{ number_format(
                                        (float) $charge->outstanding_amount,
                                        2
                                    ) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >
                                    No revenue data found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         OUTSTANDING INVOICES
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Outstanding Invoices
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

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

                            <th>
                                Total
                            </th>

                            <th>
                                Paid
                            </th>

                            <th>
                                Balance
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($outstandingInvoices as $invoice)

                            <tr>

                                <td>
                                    {{ $invoice->invoice_no }}
                                </td>


                                <td>
                                    {{ $invoice->tenant?->company_name ?? '-' }}
                                </td>


                                <td>

                                    {{ $invoice->due_date
                                        ? \Carbon\Carbon::parse(
                                            $invoice->due_date
                                        )->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>
                                    ${{ number_format(
                                        $invoice->total_amount,
                                        2
                                    ) }}
                                </td>


                                <td>
                                    ${{ number_format(
                                        $invoice->paid_amount,
                                        2
                                    ) }}
                                </td>


                                <td class="fw-semibold text-danger">

                                    ${{ number_format(
                                        $invoice->balance_amount,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    @if($invoice->invoice_status === 'Partially Paid')

                                        <span class="badge bg-warning text-dark">
                                            Partially Paid
                                        </span>

                                    @elseif(
                                        $invoice->invoice_status === 'Overdue'
                                    )

                                        <span class="badge bg-danger">
                                            Overdue
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $invoice->invoice_status }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-4 text-muted"
                                >

                                    No outstanding invoices.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     CHART.JS
============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const chartLabels = @json($chartLabels);

    const chartValues = @json($chartValues);

    const ctx = document
        .getElementById('revenueChart')
        .getContext('2d');

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: chartLabels,

            datasets: [{

                label: 'Collection',

                data: chartValues,

                backgroundColor: '#198754',

                borderRadius: 6,

                borderSkipped: false

            }]

        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        callback: function(value) {
                            return '$' +
                                Number(value).toLocaleString('en-IN');
                        }
                    }
                }
            }
        }

    });

</script>

@endsection