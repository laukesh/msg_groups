@extends('layouts.app')

@section('title', 'Procurement Performance')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Procurement Performance
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
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


    {{-- =========================================================
        PROCUREMENT OVERVIEW
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Plans --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="text-muted small">
                                Procurement Plans
                            </div>

                            <h3 class="mb-1">
                                {{ $totalPlans }}
                            </h3>

                            <small class="text-muted">
                                {{ $approvedPlans }} Approved
                            </small>
                        </div>

                        <div class="fs-2 text-primary">
                            <i class="bi bi-journal-text"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Packages --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Procurement Packages
                            </div>

                            <h3 class="mb-1">
                                {{ $totalPackages }}
                            </h3>

                            <small class="text-muted">
                                Value:
                                ₹{{ number_format($packageValue, 2) }}
                            </small>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="bi bi-box-seam"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Tenders --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Tenders
                            </div>

                            <h3 class="mb-1">
                                {{ $totalTenders }}
                            </h3>

                            <small class="text-muted">
                                {{ $activeTenders }} Active
                            </small>

                        </div>

                        <div class="fs-2 text-warning">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Contracts --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Contracts
                            </div>

                            <h3 class="mb-1">
                                {{ $totalContracts }}
                            </h3>

                            <small class="text-muted">
                                {{ $activeContracts }} Active
                            </small>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINANCIAL PERFORMANCE
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Contract Value --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <h4 class="mt-2">
                        ₹{{ number_format($contractValue, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- PO Value --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Purchase Order Value
                    </div>

                    <h4 class="mt-2">
                        ₹{{ number_format($purchaseOrderValue, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Invoiced --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Invoiced Amount
                    </div>

                    <h4 class="mt-2">
                        ₹{{ number_format($invoicedAmount, 2) }}
                    </h4>

                    <small class="text-muted">
                        {{ $totalInvoices }} invoices
                    </small>

                </div>

            </div>

        </div>


        {{-- Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Paid Amount
                    </div>

                    <h4 class="mt-2">
                        ₹{{ number_format($paidAmount, 2) }}
                    </h4>

                    <small class="text-muted">
                        Outstanding:
                        ₹{{ number_format($outstandingAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE INDICATORS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Tender Award --}}
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="fw-semibold">
                            Tender Award Rate
                        </span>

                        <strong>
                            {{ $tenderAwardRate }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ min($tenderAwardRate, 100) }}%">
                        </div>

                    </div>

                    <small class="text-muted">
                        {{ $awardedTenders }}
                        awarded /
                        {{ $totalTenders }}
                        tenders
                    </small>

                </div>

            </div>

        </div>


        {{-- Contract Completion --}}
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="fw-semibold">
                            Contract Completion
                        </span>

                        <strong>
                            {{ $contractCompletionRate }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar bg-success"
                             style="width: {{ min($contractCompletionRate, 100) }}%">
                        </div>

                    </div>

                    <small class="text-muted">
                        {{ $completedContracts }}
                        completed /
                        {{ $totalContracts }}
                        contracts
                    </small>

                </div>

            </div>

        </div>


        {{-- Milestone Completion --}}
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="fw-semibold">
                            Milestone Completion
                        </span>

                        <strong>
                            {{ $milestoneCompletionRate }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar bg-info"
                             style="width: {{ min($milestoneCompletionRate, 100) }}%">
                        </div>

                    </div>

                    <small class="text-muted">
                        Average progress:
                        {{ $milestoneProgress }}%
                    </small>

                </div>

            </div>

        </div>


        {{-- Payment Rate --}}
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="fw-semibold">
                            Payment Rate
                        </span>

                        <strong>
                            {{ $paymentRate }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar bg-warning"
                             style="width: {{ min($paymentRate, 100) }}%">
                        </div>

                    </div>

                    <small class="text-muted">
                        ₹{{ number_format($paidAmount, 2) }}
                        paid /
                        ₹{{ number_format($invoicedAmount, 2) }}
                        invoiced
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PROCUREMENT PIPELINE
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        Procurement Pipeline
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalPlans }}
                            </div>

                            <small class="text-muted">
                                Plans
                            </small>

                        </div>

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalPackages }}
                            </div>

                            <small class="text-muted">
                                Packages
                            </small>

                        </div>

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalTenders }}
                            </div>

                            <small class="text-muted">
                                Tenders
                            </small>

                        </div>

                    </div>

                    <hr>

                    <div class="row text-center">

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalAwards }}
                            </div>

                            <small class="text-muted">
                                Awards
                            </small>

                        </div>

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalContracts }}
                            </div>

                            <small class="text-muted">
                                Contracts
                            </small>

                        </div>

                        <div class="col-4">

                            <div class="fs-3 fw-bold">
                                {{ $totalPurchaseOrders }}
                            </div>

                            <small class="text-muted">
                                Purchase Orders
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Execution --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        Procurement Execution
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="border rounded p-3">

                                <small class="text-muted">
                                    Milestones
                                </small>

                                <h4 class="mb-0">
                                    {{ $totalMilestones }}
                                </h4>

                                <small>
                                    {{ $completedMilestones }}
                                    completed
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded p-3">

                                <small class="text-muted">
                                    Deliveries
                                </small>

                                <h4 class="mb-0">
                                    {{ $totalDeliveries }}
                                </h4>

                                <small>
                                    recorded
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded p-3">

                                <small class="text-muted">
                                    Material Tracking
                                </small>

                                <h4 class="mb-0">
                                    {{ $totalMaterialTrackings }}
                                </h4>

                                <small>
                                    tracking records
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded p-3">

                                <small class="text-muted">
                                    Issued POs
                                </small>

                                <h4 class="mb-0">
                                    {{ $issuedPurchaseOrders }}
                                </h4>

                                <small>
                                    of {{ $totalPurchaseOrders }}
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RECENT CONTRACTS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                Recent Contracts
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Contract No.</th>

                            <th>Title</th>

                            <th>Bidder</th>

                            <th>Amount</th>

                            <th>Start Date</th>

                            <th>End Date</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentContracts as $contract)

                            <tr>

                                <td>
                                    {{ $contract->contract_number }}
                                </td>

                                <td>
                                    {{ $contract->contract_title }}
                                </td>

                                <td>
                                    {{ $contract->bidder_name ?? '-' }}
                                </td>

                                <td>
                                    ₹{{ number_format($contract->contract_amount, 2) }}
                                </td>

                                <td>
                                    {{ optional($contract->contract_start_date)->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($contract->contract_end_date)->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $contract->status }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center text-muted py-4">

                                    No contracts found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RECENT PURCHASE ORDERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                Recent Purchase Orders
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>PO Number</th>

                            <th>PO Title</th>

                            <th>Supplier</th>

                            <th>PO Date</th>

                            <th>Total Amount</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentPurchaseOrders as $po)

                            <tr>

                                <td>
                                    {{ $po->po_number }}
                                </td>

                                <td>
                                    {{ $po->po_title }}
                                </td>

                                <td>
                                    {{ $po->supplier_name }}
                                </td>

                                <td>
                                    {{ optional($po->po_date)->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    ₹{{ number_format($po->total_amount, 2) }}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $po->status }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
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

</div>

@endsection