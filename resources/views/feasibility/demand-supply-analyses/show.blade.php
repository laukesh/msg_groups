@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Demand & Supply Analysis
            </h3>

            <p class="text-muted mb-0">

                {{ $analysis->analysis_number }}
                -
                {{ $analysis->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.index',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'demandSupplyAnalysis' =>
                            $analysis->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Basic Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Basic Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Analysis Number --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Analysis Number
                    </label>

                    <div class="fw-semibold">
                        {{ $analysis->analysis_number }}
                    </div>

                </div>


                {{-- Title --}}
                <div class="col-md-5 mb-3">

                    <label class="form-label text-muted">
                        Title
                    </label>

                    <div class="fw-semibold">
                        {{ $analysis->title }}
                    </div>

                </div>


                {{-- Market Type --}}
                <div class="col-md-3 mb-3">

                    <label class="form-label text-muted">
                        Market Type
                    </label>

                    <div>
                        {{ $analysis->market_type ?? '-' }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Status
                    </label>

                    <div>

                        @if($analysis->status === 'Draft')

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif($analysis->status === 'Submitted')

                            <span class="badge bg-warning text-dark">
                                Submitted
                            </span>

                        @elseif($analysis->status === 'Approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($analysis->status === 'Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $analysis->status ?? 'N/A' }}
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Score --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Overall Demand & Supply Score
                    </label>

                    <div class="fw-semibold">

                        @if(
                            $analysis->overall_demand_supply_score !== null
                        )

                            {{ number_format(
                                $analysis->overall_demand_supply_score,
                                2
                            ) }}

                            / 100

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Created At --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Created At
                    </label>

                    <div>

                        {{ $analysis->created_at
                            ? $analysis->created_at->format(
                                'd-m-Y H:i'
                            )
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Demand Analysis --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Demand Analysis
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Current Demand --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Current Demand
                    </label>

                    <div class="fs-5">

                        {{ $analysis->current_demand !== null
                            ? number_format(
                                $analysis->current_demand,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                {{-- Projected Demand --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Projected Demand
                    </label>

                    <div class="fs-5">

                        {{ $analysis->projected_demand !== null
                            ? number_format(
                                $analysis->projected_demand,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                {{-- Growth --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Demand Growth Rate
                    </label>

                    <div class="fs-5">

                        @if($analysis->demand_growth_rate !== null)

                            {{ number_format(
                                $analysis->demand_growth_rate,
                                2
                            ) }}%

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Demand Assessment --}}
                <div class="col-md-12 mb-4">

                    <label class="form-label text-muted">
                        Demand Assessment
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->demand_assessment
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Target Customer Demand --}}
                <div class="col-md-12">

                    <label class="form-label text-muted">
                        Target Customer Demand
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->target_customer_demand
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Supply Analysis --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Supply Analysis
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Current Supply --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Current Supply
                    </label>

                    <div class="fs-5">

                        {{ $analysis->current_supply !== null
                            ? number_format(
                                $analysis->current_supply,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                {{-- Future Supply --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Future Supply
                    </label>

                    <div class="fs-5">

                        {{ $analysis->future_supply !== null
                            ? number_format(
                                $analysis->future_supply,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                {{-- Market Capacity --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Market Capacity
                    </label>

                    <div class="fs-5">

                        {{ $analysis->market_capacity !== null
                            ? number_format(
                                $analysis->market_capacity,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                {{-- Supply Pipeline --}}
                <div class="col-md-12 mb-4">

                    <label class="form-label text-muted">
                        Supply Pipeline
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->supply_pipeline
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Competitor Supply --}}
                <div class="col-md-12">

                    <label class="form-label text-muted">
                        Competitor Supply
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->competitor_supply
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Gap Analysis --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Demand & Supply Gap
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Gap --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Demand-Supply Gap
                    </label>

                    <div class="fs-4 fw-semibold">

                        @if($analysis->demand_supply_gap !== null)

                            {{ number_format(
                                $analysis->demand_supply_gap,
                                2
                            ) }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Occupancy --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Occupancy Rate
                    </label>

                    <div class="fs-4 fw-semibold">

                        @if($analysis->occupancy_rate !== null)

                            {{ number_format(
                                $analysis->occupancy_rate,
                                2
                            ) }}%

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Utilization --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Utilization Rate
                    </label>

                    <div class="fs-4 fw-semibold">

                        @if($analysis->utilization_rate !== null)

                            {{ number_format(
                                $analysis->utilization_rate,
                                2
                            ) }}%

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Forecast --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Demand Forecast
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Forecast Period
                    </label>

                    <div>
                        {{ $analysis->forecast_period ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Forecast Demand
                    </label>

                    <div class="fs-5">

                        {{ $analysis->forecast_demand !== null
                            ? number_format(
                                $analysis->forecast_demand,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Key Findings --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Key Findings
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Drivers --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Key Drivers
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->key_drivers
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Constraints --}}
                <div class="col-md-6 mb-4">

                    <label class="form-label text-muted">
                        Key Constraints
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->key_constraints
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Findings --}}
                <div class="col-md-12">

                    <label class="form-label text-muted">
                        Key Findings
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->key_findings
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Recommendation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Assessment & Recommendation
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Overall Score
                    </label>

                    <div class="display-6">

                        @if(
                            $analysis->overall_demand_supply_score
                            !== null
                        )

                            {{ number_format(
                                $analysis
                                    ->overall_demand_supply_score,
                                2
                            ) }}

                            <small class="fs-6 text-muted">
                                / 100
                            </small>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-8">

                    <label class="form-label text-muted">
                        Recommendation
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $analysis->recommendation
                                ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Audit --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Audit Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created At
                    </label>

                    <div>

                        {{ $analysis->created_at
                            ? $analysis->created_at->format(
                                'd-m-Y H:i'
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Updated At
                    </label>

                    <div>

                        {{ $analysis->updated_at
                            ? $analysis->updated_at->format(
                                'd-m-Y H:i'
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created By
                    </label>

                    <div>
                        {{ $analysis->created_by ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Bottom Actions --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between mb-5">

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.show',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Feasibility Assessment
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.edit',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'demandSupplyAnalysis' =>
                            $analysis->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.destroy',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'demandSupplyAnalysis' =>
                            $analysis->id,
                    ]
                ) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this Demand & Supply Analysis?');"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Delete
                </button>

            </form>

        </div>

    </div>

</div>

@endsection