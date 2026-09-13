@extends('layouts.app')

@section('title', 'Economic Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">

                    <i class="fas fa-chart-line fa-lg"></i>

                </div>

                <div>

                    <h2 class="fw-bold mb-0">
                        Economic Dashboard
                    </h2>

                    <small class="text-muted">
                        Asset financial performance overview
                    </small>

                </div>

            </div>

        </div>


        {{-- ACTION BUTTONS --}}

        @if($asset)

            <div class="d-flex flex-wrap gap-2">

                {{-- Back to Asset --}}

                <a href="{{ route(
                    'admin.assets.assets.show',
                    ['asset' => $asset->id]
                ) }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-arrow-left me-1"></i>

                    Back to Asset

                </a>


                {{-- Income --}}

                <a href="{{ route(
                    'admin.assets.incomes.index',
                    ['asset' => $asset->id]
                ) }}"
                   class="btn btn-outline-success">

                    <i class="fas fa-money-bill-wave me-1"></i>

                    Income

                </a>


                {{-- Expenses --}}

                <a href="{{ route(
                    'admin.assets.expenses.index',
                    ['asset' => $asset->id]
                ) }}"
                   class="btn btn-outline-danger">

                    <i class="fas fa-receipt me-1"></i>

                    Expenses

                </a>

            </div>

        @endif

    </div>



    {{-- =========================================================
         ASSET INFORMATION
    ========================================================== --}}

    @if($asset)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- Asset Details --}}

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3">

                            <div
                                class="bg-primary bg-opacity-10
                                       text-primary rounded-circle
                                       d-flex align-items-center
                                       justify-content-center"
                                style="width:60px;height:60px;">

                                <i class="fas fa-building fa-xl"></i>

                            </div>


                            <div>

                                <h4 class="fw-bold mb-1">

                                    {{ $asset->name
                                        ?? $asset->asset_name
                                        ?? 'Asset #' . $asset->id }}

                                </h4>


                                <div class="text-muted">

                                    <i class="fas fa-hashtag me-1"></i>

                                    Asset ID:
                                    {{ $asset->id }}

                                </div>


                                {{-- Vendor --}}

                                @if($asset->vendor)

                                    <div class="text-muted mt-1">

                                        <i class="fas fa-user-tie me-1"></i>

                                        Vendor:
                                        {{ $asset->vendor->name }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Badge --}}

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <span class="badge bg-primary-subtle text-primary px-3 py-2">

                            <i class="fas fa-chart-pie me-1"></i>

                            Economic Analysis

                        </span>

                    </div>

                </div>

            </div>

        </div>

    @else

        {{-- No Asset Selected --}}

        <div class="alert alert-warning border-0 shadow-sm">

            <i class="fas fa-exclamation-triangle me-2"></i>

            No asset selected.

        </div>

    @endif



    {{-- =========================================================
         DATE FILTER
    ========================================================== --}}

    @if($asset)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex align-items-center">

                    <div class="bg-light rounded-2 p-2 me-2">

                        <i class="fas fa-filter text-primary"></i>

                    </div>

                    <div>

                        <h5 class="fw-bold mb-0">
                            Dashboard Filters
                        </h5>

                        <small class="text-muted">
                            Select a date range for financial analysis
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route(
                        'admin.assets.economic-dashboard',
                        ['asset' => $asset->id]
                    ) }}">

                    <div class="row g-3 align-items-end">


                        {{-- From Date --}}

                        <div class="col-lg-4 col-md-6">

                            <label
                                for="from_date"
                                class="form-label fw-semibold">

                                <i class="fas fa-calendar-alt text-primary me-1"></i>

                                From Date

                            </label>


                            <input
                                type="date"
                                id="from_date"
                                name="from_date"
                                value="{{ request('from_date') }}"
                                class="form-control">

                        </div>



                        {{-- To Date --}}

                        <div class="col-lg-4 col-md-6">

                            <label
                                for="to_date"
                                class="form-label fw-semibold">

                                <i class="fas fa-calendar-alt text-primary me-1"></i>

                                To Date

                            </label>


                            <input
                                type="date"
                                id="to_date"
                                name="to_date"
                                value="{{ request('to_date') }}"
                                class="form-control">

                        </div>



                        {{-- Buttons --}}

                        <div class="col-lg-4">

                            <div class="d-flex gap-2">


                                <button
                                    type="submit"
                                    class="btn btn-primary flex-grow-1">

                                    <i class="fas fa-filter me-1"></i>

                                    Apply Filter

                                </button>


                                <a
                                    href="{{ route(
                                        'admin.assets.economic-dashboard',
                                        ['asset' => $asset->id]
                                    ) }}"
                                    class="btn btn-outline-secondary"
                                    title="Reset Filter">

                                    <i class="fas fa-sync-alt"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif



    {{-- =========================================================
         ECONOMIC KPI CARDS
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
             TOTAL INCOME
        ====================================================== --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <span class="text-muted small fw-semibold">
                                TOTAL INCOME
                            </span>


                            <h3 class="fw-bold text-success mt-2 mb-0">

                                ₹{{ number_format($totalIncome ?? 0, 2) }}

                            </h3>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-success bg-opacity-10
                                   text-success
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;">

                            <i class="fas fa-arrow-up fa-lg"></i>

                        </div>

                    </div>


                    <hr class="my-3">


                    <small class="text-muted">

                        <i class="fas fa-money-bill-wave me-1"></i>

                        Income generated by this asset

                    </small>

                </div>

            </div>

        </div>



        {{-- =====================================================
             OPERATING EXPENSES
        ====================================================== --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <span class="text-muted small fw-semibold">
                                OPERATING EXPENSES
                            </span>


                            <h3 class="fw-bold text-danger mt-2 mb-0">

                                ₹{{ number_format($operatingExpenses ?? 0, 2) }}

                            </h3>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-danger bg-opacity-10
                                   text-danger
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;">

                            <i class="fas fa-arrow-down fa-lg"></i>

                        </div>

                    </div>


                    <hr class="my-3">


                    <small class="text-muted">

                        <i class="fas fa-receipt me-1"></i>

                        Operating expenses for this asset

                    </small>

                </div>

            </div>

        </div>



        {{-- =====================================================
             NOI
        ====================================================== --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <span class="text-muted small fw-semibold">
                                NET OPERATING INCOME
                            </span>


                            <h3
                                class="fw-bold mt-2 mb-0
                                {{ ($noi ?? 0) >= 0
                                    ? 'text-primary'
                                    : 'text-danger' }}">

                                ₹{{ number_format($noi ?? 0, 2) }}

                            </h3>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-primary bg-opacity-10
                                   text-primary
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;">

                            <i class="fas fa-chart-line fa-lg"></i>

                        </div>

                    </div>


                    <hr class="my-3">


                    <small class="text-muted">

                        <i class="fas fa-calculator me-1"></i>

                        Income − Operating Expenses

                    </small>

                </div>

            </div>

        </div>



        {{-- =====================================================
             ROI
        ====================================================== --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <span class="text-muted small fw-semibold">
                                RETURN ON INVESTMENT
                            </span>


                            <h3 class="fw-bold text-warning mt-2 mb-0">

                                {{ number_format($roi ?? 0, 2) }}%

                            </h3>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-warning bg-opacity-10
                                   text-warning
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;">

                            <i class="fas fa-percentage fa-lg"></i>

                        </div>

                    </div>


                    <hr class="my-3">


                    <small class="text-muted">

                        <i class="fas fa-coins me-1"></i>

                        Investment:

                        ₹{{ number_format($investment ?? 0, 2) }}

                    </small>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FINANCIAL SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex align-items-center">

                <div
                    class="bg-primary bg-opacity-10
                           text-primary rounded-2 p-2 me-2">

                    <i class="fas fa-calculator"></i>

                </div>


                <div>

                    <h5 class="fw-bold mb-0">
                        Economic Summary
                    </h5>

                    <small class="text-muted">
                        Financial performance of the selected asset
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4 text-center">


                {{-- Income --}}

                <div class="col-md-4">

                    <div class="border rounded-3 p-4 h-100">

                        <div class="text-muted small mb-2">
                            TOTAL INCOME
                        </div>


                        <div class="fs-3 fw-bold text-success">

                            ₹{{ number_format($totalIncome ?? 0, 2) }}

                        </div>


                        <small class="text-muted">
                            Asset revenue
                        </small>

                    </div>

                </div>



                {{-- Expenses --}}

                <div class="col-md-4">

                    <div class="border rounded-3 p-4 h-100">

                        <div class="text-muted small mb-2">
                            OPERATING EXPENSES
                        </div>


                        <div class="fs-3 fw-bold text-danger">

                            ₹{{ number_format($operatingExpenses ?? 0, 2) }}

                        </div>


                        <small class="text-muted">
                            Asset operating cost
                        </small>

                    </div>

                </div>



                {{-- NOI --}}

                <div class="col-md-4">

                    <div class="border rounded-3 p-4 h-100">

                        <div class="text-muted small mb-2">
                            NET OPERATING INCOME
                        </div>


                        <div
                            class="fs-3 fw-bold
                            {{ ($noi ?? 0) >= 0
                                ? 'text-primary'
                                : 'text-danger' }}">

                            ₹{{ number_format($noi ?? 0, 2) }}

                        </div>


                        <small class="text-muted">
                            Net asset income
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         ROI CALCULATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-percentage text-warning me-2"></i>

                ROI Calculation

            </h5>

        </div>


        <div class="card-body">

            <div class="row align-items-center text-center">


                {{-- NOI --}}

                <div class="col-md-4 mb-3 mb-md-0">

                    <div class="text-muted small">
                        Net Operating Income
                    </div>


                    <div class="fs-4 fw-bold text-primary">

                        ₹{{ number_format($noi ?? 0, 2) }}

                    </div>

                </div>



                {{-- Divide --}}

                <div class="col-md-1 d-none d-md-block">

                    <i class="fas fa-divide text-muted fa-lg"></i>

                </div>



                {{-- Investment --}}

                <div class="col-md-3 mb-3 mb-md-0">

                    <div class="text-muted small">
                        Asset Investment
                    </div>


                    <div class="fs-4 fw-bold">

                        ₹{{ number_format($investment ?? 0, 2) }}

                    </div>

                </div>



                {{-- Equals --}}

                <div class="col-md-1 d-none d-md-block">

                    <i class="fas fa-equals text-muted fa-lg"></i>

                </div>



                {{-- ROI --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        ROI
                    </div>


                    <div class="fs-3 fw-bold text-warning">

                        {{ number_format($roi ?? 0, 2) }}%

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FORMULA INFORMATION
    ========================================================== --}}

    <div class="alert alert-light border mt-4 mb-0">

        <div class="d-flex align-items-start">

            <i
                class="fas fa-info-circle
                       text-primary fa-lg me-3 mt-1">
            </i>


            <div>

                <strong>
                    Economic Calculation
                </strong>


                <div class="small text-muted mt-1">

                    <strong>NOI</strong>
                    = Total Income − Operating Expenses

                    <br>

                    <strong>ROI</strong>
                    = (NOI ÷ Asset Investment) × 100

                </div>

            </div>

        </div>

    </div>

</div>

@endsection