@extends('layouts.app')

@section('title', 'Project Dashboard')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Project Dashboard
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_number)
                    <span class="ms-2">
                        ({{ $project->project_number }})
                    </span>
                @endif
            </div>
        </div>

        <div>
            <a href="{{ url()->previous() }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

    </div>


    {{-- ============================================================
        PROJECT INFORMATION
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Project Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Number
                    </div>

                    <strong>
                        {{ $project->project_number ?: '-' }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Code
                    </div>

                    <strong>
                        {{ $project->project_code ?: '-' }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Type
                    </div>

                    <strong>
                        {{ $project->project_type ?: '-' }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Priority
                    </div>

                    <strong>
                        {{ $project->project_priority ?: '-' }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Stage
                    </div>

                    <span class="badge bg-info">
                        {{ $project->project_stage }}
                    </span>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Status
                    </div>

                    <span class="badge bg-primary">
                        {{ $project->project_status }}
                    </span>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Project Start Date
                    </div>

                    <strong>
                        {{ $project->project_start_date
                            ? \Carbon\Carbon::parse($project->project_start_date)->format('d M Y')
                            : '-' }}
                    </strong>
                </div>


                <div class="col-md-3">
                    <div class="text-muted small">
                        Planned Completion
                    </div>

                    <strong>
                        {{ $project->planned_completion_date
                            ? \Carbon\Carbon::parse($project->planned_completion_date)->format('d M Y')
                            : '-' }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        KPI CARDS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        {{-- Budget --}}
        <div class="col-xl-3 col-md-6">

            <div class="card h-100 border-start border-primary border-4">

                <div class="card-body">

                    <div class="text-muted small">
                        Current Approved Budget
                    </div>

                    <h4 class="mt-2 mb-0">
                        {{ $budget
                            ? number_format($budget->total_budget, 2)
                            : '0.00' }}
                    </h4>

                    <small class="text-muted">
                        {{ $budget?->currency ?? 'USD' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Contracts --}}
        <div class="col-xl-3 col-md-6">

            <div class="card h-100 border-start border-success border-4">

                <div class="card-body">

                    <div class="text-muted small">
                        Contracts
                    </div>

                    <h4 class="mt-2 mb-0">
                        {{ $procurementSummary['contracts'] }}
                    </h4>

                    <small class="text-muted">
                        Contracted:
                        {{ number_format($financialSummary['contracted'], 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Purchase Orders --}}
        <div class="col-xl-3 col-md-6">

            <div class="card h-100 border-start border-warning border-4">

                <div class="card-body">

                    <div class="text-muted small">
                        Purchase Orders
                    </div>

                    <h4 class="mt-2 mb-0">
                        {{ $procurementSummary['purchase_orders'] }}
                    </h4>

                    <small class="text-muted">
                        Value:
                        {{ number_format($financialSummary['purchase_orders'], 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card h-100 border-start border-info border-4">

                <div class="card-body">

                    <div class="text-muted small">
                        Paid Amount
                    </div>

                    <h4 class="mt-2 mb-0">
                        {{ number_format($financialSummary['paid'], 2) }}
                    </h4>

                    <small class="text-muted">
                        Outstanding:
                        {{ number_format($financialSummary['outstanding'], 2) }}
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FINANCIAL SUMMARY
    ============================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-lg-8">

            <div class="card h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Financial Summary
                    </h5>
                </div>

                <div class="card-body">

                    @php
                        $budgetAmount = (float) $financialSummary['budget'];
                        $contractAmount = (float) $financialSummary['contracted'];
                        $invoiceAmount = (float) $financialSummary['invoiced'];
                        $paidAmount = (float) $financialSummary['paid'];

                        $contractPercent = $budgetAmount > 0
                            ? min(100, ($contractAmount / $budgetAmount) * 100)
                            : 0;

                        $invoicePercent = $budgetAmount > 0
                            ? min(100, ($invoiceAmount / $budgetAmount) * 100)
                            : 0;

                        $paidPercent = $budgetAmount > 0
                            ? min(100, ($paidAmount / $budgetAmount) * 100)
                            : 0;
                    @endphp


                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Contracted
                            </span>

                            <strong>
                                {{ number_format($contractAmount, 2) }}
                            </strong>

                        </div>

                        <div class="progress" style="height: 10px;">

                            <div class="progress-bar"
                                 style="width: {{ $contractPercent }}%">
                            </div>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Invoiced
                            </span>

                            <strong>
                                {{ number_format($invoiceAmount, 2) }}
                            </strong>

                        </div>

                        <div class="progress" style="height: 10px;">

                            <div class="progress-bar bg-warning"
                                 style="width: {{ $invoicePercent }}%">
                            </div>

                        </div>

                    </div>


                    <div>

                        <div class="d-flex justify-content-between mb-1">

                            <span>
                                Paid
                            </span>

                            <strong>
                                {{ number_format($paidAmount, 2) }}
                            </strong>

                        </div>

                        <div class="progress" style="height: 10px;">

                            <div class="progress-bar bg-success"
                                 style="width: {{ $paidPercent }}%">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Milestone Progress --}}

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Milestone Progress
                    </h5>
                </div>

                <div class="card-body text-center">

                    <div class="display-5 fw-bold">
                        {{ number_format($milestoneSummary['progress'], 2) }}%
                    </div>

                    <div class="text-muted mb-3">
                        Overall Progress
                    </div>

                    <div class="progress mb-4"
                         style="height: 14px;">

                        <div class="progress-bar bg-success"
                             style="width: {{ min(100, $milestoneSummary['progress']) }}%">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-4">

                            <strong class="d-block">
                                {{ $milestoneSummary['completed'] }}
                            </strong>

                            <small class="text-muted">
                                Completed
                            </small>

                        </div>


                        <div class="col-4">

                            <strong class="d-block">
                                {{ $milestoneSummary['in_progress'] }}
                            </strong>

                            <small class="text-muted">
                                In Progress
                            </small>

                        </div>


                        <div class="col-4">

                            <strong class="d-block">
                                {{ $milestoneSummary['pending'] }}
                            </strong>

                            <small class="text-muted">
                                Pending
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PROCUREMENT SUMMARY
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Procurement Summary
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-2">

                    <div class="text-muted small">
                        Plans
                    </div>

                    <h4>
                        {{ $procurementSummary['plans'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Tenders
                    </div>

                    <h4>
                        {{ $procurementSummary['tenders'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Awards
                    </div>

                    <h4>
                        {{ $procurementSummary['awards'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Contracts
                    </div>

                    <h4>
                        {{ $procurementSummary['contracts'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Purchase Orders
                    </div>

                    <h4>
                        {{ $procurementSummary['purchase_orders'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Deliveries
                    </div>

                    <h4>
                        {{ $procurementSummary['deliveries'] }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RISKS
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Risk Summary
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-2">

                    <div class="text-muted small">
                        Total Risks
                    </div>

                    <h4>
                        {{ $riskSummary['total'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Open
                    </div>

                    <h4>
                        {{ $riskSummary['open'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Monitoring
                    </div>

                    <h4>
                        {{ $riskSummary['monitoring'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        High
                    </div>

                    <h4 class="text-warning">
                        {{ $riskSummary['high'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Critical
                    </div>

                    <h4 class="text-danger">
                        {{ $riskSummary['critical'] }}
                    </h4>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <h4 class="text-danger">
                        {{ $riskSummary['overdue'] }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RECENT PURCHASE ORDERS
    ============================================================ --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">

            <h5 class="mb-0">
                Recent Purchase Orders
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>PO Number</th>

                            <th>Title</th>

                            <th>PO Date</th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentPurchaseOrders as $po)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $po->po_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $po->po_title }}
                                </td>

                                <td>
                                    {{ $po->po_date
                                        ? $po->po_date->format('d M Y')
                                        : '-' }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($po->total_amount, 2) }}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $po->status }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    No purchase orders found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RECENT INVOICES + PAYMENTS
    ============================================================ --}}

    <div class="row g-3 mb-4">

        {{-- Invoices --}}

        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recent Invoices
                    </h5>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Invoice</th>

                                    <th>Date</th>

                                    <th class="text-end">
                                        Amount
                                    </th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($recentInvoices as $invoice)

                                    <tr>

                                        <td>
                                            {{ $invoice->invoice_number }}
                                        </td>

                                        <td>
                                            {{ $invoice->invoice_date
                                                ? $invoice->invoice_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($invoice->net_amount, 2) }}
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $invoice->status }}
                                            </span>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4"
                                            class="text-center text-muted py-4">

                                            No invoices found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payments --}}

        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recent Payments
                    </h5>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Payment</th>

                                    <th>Date</th>

                                    <th class="text-end">
                                        Amount
                                    </th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($recentPayments as $payment)

                                    <tr>

                                        <td>
                                            {{ $payment->payment_number }}
                                        </td>

                                        <td>
                                            {{ $payment->payment_date
                                                ? $payment->payment_date->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($payment->amount, 2) }}
                                        </td>

                                        <td>

                                            <span class="badge bg-secondary">
                                                {{ $payment->status }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4"
                                            class="text-center text-muted py-4">

                                            No payments found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection