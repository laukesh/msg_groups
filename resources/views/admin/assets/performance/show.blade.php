@extends('layouts.app')

@section('title', 'Asset Performance')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <div class="text-muted small">
                ASSET PERFORMANCE
            </div>

            <h3 class="fw-bold mb-1">

                {{ $asset->asset_code
                    ?? 'Asset #'.$asset->id }}

            </h3>

            @if(!empty($asset->name))

                <div class="text-muted">
                    {{ $asset->name }}
                </div>

            @endif

        </div>

        <div>

            <a href="{{ route(
                'admin.assets.performance.index'
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        TOTAL INCOME
                    </div>

                    <h3 class="fw-bold text-success mt-2">

                        ₹{{ number_format(
                            $income,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        TOTAL EXPENSE
                    </div>

                    <h3 class="fw-bold text-danger mt-2">

                        ₹{{ number_format(
                            $expense,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        NOI
                    </div>

                    <h3 class="fw-bold text-primary mt-2">

                        ₹{{ number_format(
                            $noi,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        ROI
                    </div>

                    <h3 class="fw-bold text-warning mt-2">

                        {{ number_format(
                            $roi,
                            2
                        ) }}%

                    </h3>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4 mt-1">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <strong>
                        Investment
                    </strong>

                </div>

                <div class="card-body">

                    <h4 class="fw-bold">

                        ₹{{ number_format(
                            $investment,
                            2
                        ) }}

                    </h4>

                    <small class="text-muted">
                        Recorded asset investment
                    </small>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <strong>
                        Profit Margin
                    </strong>

                </div>

                <div class="card-body">

                    <h4 class="fw-bold">

                        {{ number_format(
                            $profitMargin,
                            2
                        ) }}%

                    </h4>

                    <small class="text-muted">
                        NOI as percentage of income
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection