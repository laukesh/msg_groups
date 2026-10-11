@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Management
            </div>

            <h3 class="mb-1">
                {{ $project->project_name }}
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? $project->project_number }}
            </div>

        </div>


        <div>

            <a
                href="{{ route('admin.procurement.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        TOP SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Procurement Plans --}}

        <div class="col-md-3">

            <div class="procurement-summary-card">

                <div class="summary-icon summary-icon-primary">
                    <i class="ri-file-list-3-line"></i>
                </div>

                <div class="summary-content">

                    <div class="summary-label">
                        Procurement Plans
                    </div>

                    <div class="summary-value">
                        {{ $procurementPlanCount }}
                    </div>

                    <div class="summary-subtitle">
                        Plans
                    </div>

                </div>

            </div>

        </div>


        {{-- Procurement Packages --}}

        <div class="col-md-3">

            <div class="procurement-summary-card">

                <div class="summary-icon summary-icon-success">
                    <i class="ri-archive-stack-line"></i>
                </div>

                <div class="summary-content">

                    <div class="summary-label">
                        Packages
                    </div>

                    <div class="summary-value">
                        {{ $procurementPackageCount }}
                    </div>

                    <div class="summary-subtitle">
                        Packages
                    </div>

                </div>

            </div>

        </div>


        {{-- Tenders --}}

        <div class="col-md-3">

            <div class="procurement-summary-card">

                <div class="summary-icon summary-icon-purple">
                    <i class="ri-auction-line"></i>
                </div>

                <div class="summary-content">

                    <div class="summary-label">
                        Tenders
                    </div>

                    <div class="summary-value">
                        {{ $tenderCount }}
                    </div>

                    <div class="summary-subtitle">
                        Tenders
                    </div>

                </div>

            </div>

        </div>


        {{-- Contracts --}}

        <div class="col-md-3">

            <div class="procurement-summary-card">

                <div class="summary-icon summary-icon-warning">
                    <i class="ri-file-shield-2-line"></i>
                </div>

                <div class="summary-content">

                    <div class="summary-label">
                        Contracts
                    </div>

                    <div class="summary-value">
                        {{ $contractCount }}
                    </div>

                    <div class="summary-subtitle">
                        Contracts
                    </div>

                </div>

            </div>

        </div>


    </div>


    {{-- =========================================================
        PROCUREMENT MANAGEMENT
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Management
            </strong>

            <div class="small text-muted mt-1">
                Manage procurement activities and the complete procurement lifecycle.
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- =================================================
                    PROCUREMENT PLANS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Procurement Plans
                            </h5>

                            <span class="count-badge">
                                {{ $procurementPlanCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Procurement planning and strategy management.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.plans.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PROCUREMENT PACKAGES
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Procurement Packages
                            </h5>

                            <span class="count-badge success">
                                {{ $procurementPackageCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Define and manage procurement packages.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.packages.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TENDERS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Tenders
                            </h5>

                            <span class="count-badge purple">
                                {{ $tenderCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Manage tendering and bidding activities.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BIDDERS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Bidders
                            </h5>

                            <span class="count-badge danger">
                                {{ $bidderCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Manage participating bidders and vendors.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route('admin.procurement.bidders.index') }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BID EVALUATION
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Bid Evaluation
                            </h5>

                            <div class="dual-badges">

                                <span class="count-badge warning">
                                    T: {{ $technicalEvaluationCount }}
                                </span>

                                <span class="count-badge success">
                                    C: {{ $commercialEvaluationCount }}
                                </span>

                            </div>

                        </div>

                        <p class="module-description">
                            Technical and commercial evaluation.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BID COMPARISON
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Bid Comparison
                            </h5>

                            <span class="count-badge">
                                {{ $bidComparisonCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Compare qualified commercial bids.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    NEGOTIATIONS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Negotiations
                            </h5>

                            <span class="count-badge danger">
                                {{ $negotiationCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Manage bidder negotiations and revisions.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    AWARDS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Awards
                            </h5>

                            <span class="count-badge warning">
                                {{ $awardCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Manage approved procurement awards.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CONTRACTS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Contracts
                            </h5>

                            <span class="count-badge">
                                {{ $contractCount }}
                            </span>

                        </div>

                        <p class="module-description">
                            Manage procurement contracts.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    INVOICES & PAYMENTS
                ================================================== --}}

                <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Invoices & Payments
                            </h5>

                            <div class="dual-badges">

                                <span class="count-badge purple">
                                    I: {{ $invoiceCount }}
                                </span>

                                <span class="count-badge success">
                                    P: {{ $paymentCount }}
                                </span>

                            </div>

                        </div>

                        <p class="module-description">
                            Track invoices and contract payments.
                        </p>

                        <div class="module-action">

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.index',
                                    ['project' => $project]
                                ) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    COST CONTROL
                ================================================== --}}

                <!-- <div class="col-md-6 col-lg-3">

                    <div class="procurement-module-card">

                        <div class="module-header">

                            <h5 class="module-title">
                                Cost Control
                            </h5>

                        </div>

                        <p class="module-description">
                            Monitor procurement commitments, invoices,
                            payments and budget.
                        </p>

                        <div class="module-action">

                            <span class="btn btn-outline-primary btn-sm">
                                Open Module
                                <i class="ri-arrow-right-line ms-1"></i>
                            </span>

                        </div>

                    </div>

                </div> -->


            </div>

        </div>

    </div>


    {{-- =========================================================
        PROCUREMENT EXECUTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Execution
            </strong>

            <div class="small text-muted mt-1">
                Downstream execution from contract to material delivery.
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Purchase Orders --}}

                <div class="col-md-4">

                    <div class="procurement-execution-card">

                        <div class="execution-icon">
                            <i class="ri-shopping-cart-2-line"></i>
                        </div>

                        <div class="execution-content">

                            <div class="execution-title">
                                Purchase Orders
                            </div>

                            <div class="execution-description">
                                Manage purchase orders and commitments.
                            </div>

                        </div>

                        <span class="count-badge">
                            {{ $purchaseOrderCount }}
                        </span>

                    </div>

                </div>


                {{-- Deliveries --}}

                <div class="col-md-4">

                    <div class="procurement-execution-card">

                        <div class="execution-icon success">
                            <i class="ri-truck-line"></i>
                        </div>

                        <div class="execution-content">

                            <div class="execution-title">
                                Deliveries
                            </div>

                            <div class="execution-description">
                                Track purchase order deliveries.
                            </div>

                        </div>

                        <span class="count-badge success">
                            {{ $deliveryCount }}
                        </span>

                    </div>

                </div>


                {{-- Material Tracking --}}

                <div class="col-md-4">

                    <div class="procurement-execution-card">

                        <div class="execution-icon purple">
                            <i class="ri-box-3-line"></i>
                        </div>

                        <div class="execution-content">

                            <div class="execution-title">
                                Material Tracking
                            </div>

                            <div class="execution-description">
                                Track received, issued and consumed materials.
                            </div>

                        </div>

                        <span class="count-badge purple">
                            {{ $materialTrackingCount }}
                        </span>

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- =========================================================
        PROCUREMENT FINANCIAL SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Financial Summary
            </strong>

            <div class="small text-muted mt-1">
                Overview of procurement commitments, invoices and payments.
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Contract Value --}}

                <div class="col-md-6 col-lg">

                    <div class="financial-card">

                        <div class="financial-icon">
                            <i class="ri-file-shield-2-line"></i>
                        </div>

                        <div>

                            <div class="financial-label">
                                Contract Value
                            </div>

                            <div class="financial-value">

                                {{ $project->currency ?? 'USD' }}

                                {{ number_format(
                                    $contractValue,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PO Value --}}

                <div class="col-md-6 col-lg">

                    <div class="financial-card">

                        <div class="financial-icon success">
                            <i class="ri-shopping-cart-line"></i>
                        </div>

                        <div>

                            <div class="financial-label">
                                PO Value
                            </div>

                            <div class="financial-value">

                                {{ $project->currency ?? 'USD' }}

                                {{ number_format(
                                    $purchaseOrderValue,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Total Invoiced --}}

                <div class="col-md-6 col-lg">

                    <div class="financial-card">

                        <div class="financial-icon purple">
                            <i class="ri-file-text-line"></i>
                        </div>

                        <div>

                            <div class="financial-label">
                                Total Invoiced
                            </div>

                            <div class="financial-value">

                                {{ $project->currency ?? 'USD' }}

                                {{ number_format(
                                    $totalInvoiceValue,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Total Paid --}}

                <div class="col-md-6 col-lg">

                    <div class="financial-card">

                        <div class="financial-icon danger">
                            <i class="ri-bank-card-line"></i>
                        </div>

                        <div>

                            <div class="financial-label">
                                Total Paid
                            </div>

                            <div class="financial-value">

                                {{ $project->currency ?? 'USD' }}

                                {{ number_format(
                                    $paidAmount,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Remaining --}}

                <div class="col-md-6 col-lg">

                    <div class="financial-card">

                        <div class="financial-icon warning">
                            <i class="ri-time-line"></i>
                        </div>

                        <div>

                            <div class="financial-label">
                                Remaining to Pay
                            </div>

                            <div class="financial-value">

                                {{ $project->currency ?? 'USD' }}

                                {{ number_format(
                                    $remainingToPay,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


            </div>


            {{-- =================================================
                PAYMENT PROGRESS
            ================================================== --}}

            @php

                $paymentPercentage =
                    $totalInvoiceValue > 0
                        ? min(
                            100,
                            (
                                $paidAmount /
                                $totalInvoiceValue
                            ) * 100
                        )
                        : 0;

            @endphp


            <div class="mt-4">

                <div class="d-flex justify-content-between mb-1">

                    <span class="small text-muted">
                        Invoice Payment Progress
                    </span>

                    <span class="small fw-semibold">
                        {{ number_format(
                            $paymentPercentage,
                            1
                        ) }}%
                    </span>

                </div>


                <div
                    class="progress"
                    style="height: 8px;"
                >

                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: {{ $paymentPercentage }}%;"
                        aria-valuenow="{{ $paymentPercentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>

                </div>

            </div>


        </div>

    </div>


</div>


{{-- =============================================================
    STYLES
============================================================= --}}

<style>

    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .procurement-summary-card {

        display: flex;

        align-items: center;

        gap: 15px;

        min-height: 105px;

        padding: 18px;

        background: #fff;

        border: 1px solid #dee2e6;

        border-radius: 8px;

        box-shadow: 0 1px 3px rgba(0, 0, 0, .03);

        transition: all .15s ease;

    }


    .procurement-summary-card:hover {

        border-color: #c9d0ff;

        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);

        transform: translateY(-1px);

    }


    .summary-icon {

        width: 48px;

        height: 48px;

        min-width: 48px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 10px;

        font-size: 22px;

    }


    .summary-icon-primary {

        background: #eef2ff;

        color: #4353ff;

    }


    .summary-icon-success {

        background: #eaf8f0;

        color: #198754;

    }


    .summary-icon-purple {

        background: #f2edff;

        color: #6f42c1;

    }


    .summary-icon-warning {

        background: #fff5df;

        color: #b77900;

    }


    .summary-content {

        min-width: 0;

    }


    .summary-label {

        font-size: 12px;

        color: #8a94a6;

        margin-bottom: 3px;

    }


    .summary-value {

        font-size: 27px;

        line-height: 1.1;

        font-weight: 600;

        color: #1f2937;

    }


    .summary-subtitle {

        font-size: 11px;

        color: #9aa3b2;

        margin-top: 3px;

    }


    /* =========================================================
       MODULE CARDS
    ========================================================= */

    .procurement-module-card {

        position: relative;

        height: 100%;

        min-height: 155px;

        padding: 17px;

        background: #fff;

        border: 1px solid #dee2e6;

        border-radius: 7px;

        transition:
            border-color .15s ease,
            box-shadow .15s ease,
            transform .15s ease;

    }


    .procurement-module-card:hover {

        border-color: #aeb8ff;

        box-shadow: 0 5px 14px rgba(0, 0, 0, .06);

        transform: translateY(-1px);

    }


    .module-header {

        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 10px;

    }


    .module-title {

        margin: 0;

        font-size: 15px;

        line-height: 1.3;

        font-weight: 600;

        color: #202632;

    }


    .module-description {

        min-height: 42px;

        margin-top: 9px;

        margin-bottom: 0;

        font-size: 11px;

        line-height: 1.55;

        color: #8a94a6;

    }


    .module-action {

        margin-top: 12px;

    }


    /* =========================================================
       COUNT BADGES
    ========================================================= */

    .count-badge {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 30px;

        height: 24px;

        padding: 0 8px;

        border-radius: 12px;

        background: #eef2ff;

        color: #4353ff;

        font-size: 11px;

        line-height: 1;

        font-weight: 600;

        white-space: nowrap;

    }


    .count-badge.success {

        background: #e8f7ef;

        color: #198754;

    }


    .count-badge.warning {

        background: #fff3d9;

        color: #a66a00;

    }


    .count-badge.danger {

        background: #fdebed;

        color: #d6334c;

    }


    .count-badge.purple {

        background: #f0ebff;

        color: #6f42c1;

    }


    .dual-badges {

        display: flex;

        flex-direction: column;

        align-items: flex-end;

        gap: 4px;

    }


    /* =========================================================
       EXECUTION CARDS
    ========================================================= */

    .procurement-execution-card {

        display: flex;

        align-items: center;

        gap: 12px;

        min-height: 90px;

        padding: 15px;

        border: 1px solid #dee2e6;

        border-radius: 7px;

        background: #fff;

    }


    .execution-icon {

        width: 42px;

        height: 42px;

        min-width: 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;

        background: #eef2ff;

        color: #4353ff;

        font-size: 19px;

    }


    .execution-icon.success {

        background: #e8f7ef;

        color: #198754;

    }


    .execution-icon.purple {

        background: #f0ebff;

        color: #6f42c1;

    }


    .execution-content {

        flex: 1;

        min-width: 0;

    }


    .execution-title {

        font-size: 14px;

        font-weight: 600;

        color: #202632;

    }


    .execution-description {

        font-size: 11px;

        line-height: 1.45;

        color: #8a94a6;

        margin-top: 3px;

    }


    /* =========================================================
       FINANCIAL CARDS
    ========================================================= */

    .financial-card {

        display: flex;

        align-items: center;

        gap: 12px;

        min-height: 82px;

        padding: 13px;

        border: 1px solid #dee2e6;

        border-radius: 7px;

        background: #fff;

    }


    .financial-icon {

        width: 40px;

        height: 40px;

        min-width: 40px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 8px;

        background: #eef2ff;

        color: #4353ff;

        font-size: 18px;

    }


    .financial-icon.success {

        background: #e8f7ef;

        color: #198754;

    }


    .financial-icon.purple {

        background: #f0ebff;

        color: #6f42c1;

    }


    .financial-icon.danger {

        background: #fdebed;

        color: #d6334f;

    }


    .financial-icon.warning {

        background: #fff3d9;

        color: #a66a00;

    }


    .financial-label {

        font-size: 10px;

        color: #8a94a6;

        margin-bottom: 3px;

    }


    .financial-value {

        font-size: 14px;

        font-weight: 600;

        color: #202632;

        white-space: nowrap;

    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .procurement-summary-card {

            min-height: 90px;

        }


        .procurement-module-card {

            min-height: 140px;

        }


        .financial-value {

            font-size: 13px;

        }

    }

</style>


@endsection