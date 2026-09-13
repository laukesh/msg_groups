@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Demand & Supply Analysis
            </h3>

            <p class="text-muted mb-0">

                {{ $analysis->analysis_number }}
                -
                {{ $analysis->title }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.demand-supply-analyses.show',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                    'demandSupplyAnalysis' =>
                        $analysis->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Update Form --}}
    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.demand-supply-analyses.update',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'demandSupplyAnalysis' =>
                    $analysis->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Basic Information --}}
        {{-- ===================================================== --}}

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

                        <label class="form-label">
                            Analysis Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $analysis->analysis_number }}"
                            readonly
                        >

                    </div>


                    {{-- Title --}}
                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Analysis Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old(
                                'title',
                                $analysis->title
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Market Type --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Market Type
                        </label>

                        <select
                            name="market_type"
                            class="form-select"
                        >

                            <option value="">
                                Select Market Type
                            </option>

                            <option
                                value="Retail"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Retail'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Retail
                            </option>

                            <option
                                value="Commercial"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Commercial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Commercial
                            </option>

                            <option
                                value="Residential"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Residential'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Residential
                            </option>

                            <option
                                value="Office"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Office'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Office
                            </option>

                            <option
                                value="Industrial"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Industrial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Industrial
                            </option>

                            <option
                                value="Hospitality"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Hospitality'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Hospitality
                            </option>

                            <option
                                value="Mixed Use"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Mixed Use'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Mixed Use
                            </option>

                            <option
                                value="Other"
                                {{ old(
                                    'market_type',
                                    $analysis->market_type
                                ) === 'Other'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Status

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Draft"
                                {{ old(
                                    'status',
                                    $analysis->status
                                ) === 'Draft'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Draft
                            </option>

                            <option
                                value="Submitted"
                                {{ old(
                                    'status',
                                    $analysis->status
                                ) === 'Submitted'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Submitted
                            </option>

                            <option
                                value="Approved"
                                {{ old(
                                    'status',
                                    $analysis->status
                                ) === 'Approved'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Approved
                            </option>

                            <option
                                value="Rejected"
                                {{ old(
                                    'status',
                                    $analysis->status
                                ) === 'Rejected'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Rejected
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Demand Analysis --}}
        {{-- ===================================================== --}}

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

                        <label class="form-label">
                            Current Demand
                        </label>

                        <input
                            type="number"
                            name="current_demand"
                            class="form-control"
                            value="{{ old(
                                'current_demand',
                                $analysis->current_demand
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Projected Demand --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Projected Demand
                        </label>

                        <input
                            type="number"
                            name="projected_demand"
                            class="form-control"
                            value="{{ old(
                                'projected_demand',
                                $analysis->projected_demand
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Growth Rate --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Demand Growth Rate (%)
                        </label>

                        <input
                            type="number"
                            name="demand_growth_rate"
                            class="form-control"
                            value="{{ old(
                                'demand_growth_rate',
                                $analysis->demand_growth_rate
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Demand Assessment --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Demand Assessment
                        </label>

                        <textarea
                            name="demand_assessment"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'demand_assessment',
                            $analysis->demand_assessment
                        ) }}</textarea>

                    </div>


                    {{-- Target Customer Demand --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Target Customer Demand
                        </label>

                        <textarea
                            name="target_customer_demand"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'target_customer_demand',
                            $analysis->target_customer_demand
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Supply Analysis --}}
        {{-- ===================================================== --}}

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

                        <label class="form-label">
                            Current Supply
                        </label>

                        <input
                            type="number"
                            name="current_supply"
                            class="form-control"
                            value="{{ old(
                                'current_supply',
                                $analysis->current_supply
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Future Supply --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Future Supply
                        </label>

                        <input
                            type="number"
                            name="future_supply"
                            class="form-control"
                            value="{{ old(
                                'future_supply',
                                $analysis->future_supply
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Market Capacity --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Market Capacity
                        </label>

                        <input
                            type="number"
                            name="market_capacity"
                            class="form-control"
                            value="{{ old(
                                'market_capacity',
                                $analysis->market_capacity
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    {{-- Supply Pipeline --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Supply Pipeline
                        </label>

                        <textarea
                            name="supply_pipeline"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'supply_pipeline',
                            $analysis->supply_pipeline
                        ) }}</textarea>

                    </div>


                    {{-- Competitor Supply --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Competitor Supply
                        </label>

                        <textarea
                            name="competitor_supply"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'competitor_supply',
                            $analysis->competitor_supply
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Demand Supply Gap --}}
        {{-- ===================================================== --}}

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

                        <label class="form-label">
                            Demand-Supply Gap
                        </label>

                        <input
                            type="number"
                            name="demand_supply_gap"
                            class="form-control"
                            value="{{ old(
                                'demand_supply_gap',
                                $analysis->demand_supply_gap
                            ) }}"
                            step="0.01"
                        >

                        <div class="form-text">
                            Positive value indicates unmet demand.
                        </div>

                    </div>


                    {{-- Occupancy --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Occupancy Rate (%)
                        </label>

                        <input
                            type="number"
                            name="occupancy_rate"
                            class="form-control"
                            value="{{ old(
                                'occupancy_rate',
                                $analysis->occupancy_rate
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                    </div>


                    {{-- Utilization --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Utilization Rate (%)
                        </label>

                        <input
                            type="number"
                            name="utilization_rate"
                            class="form-control"
                            value="{{ old(
                                'utilization_rate',
                                $analysis->utilization_rate
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Forecast --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Demand Forecast
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Forecast Period --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Forecast Period
                        </label>

                        <input
                            type="text"
                            name="forecast_period"
                            class="form-control"
                            value="{{ old(
                                'forecast_period',
                                $analysis->forecast_period
                            ) }}"
                            placeholder="e.g. 2027-2032"
                        >

                    </div>


                    {{-- Forecast Demand --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Forecast Demand
                        </label>

                        <input
                            type="number"
                            name="forecast_demand"
                            class="form-control"
                            value="{{ old(
                                'forecast_demand',
                                $analysis->forecast_demand
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Key Findings --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Key Findings
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Key Drivers --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Drivers
                        </label>

                        <textarea
                            name="key_drivers"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'key_drivers',
                            $analysis->key_drivers
                        ) }}</textarea>

                    </div>


                    {{-- Key Constraints --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Constraints
                        </label>

                        <textarea
                            name="key_constraints"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'key_constraints',
                            $analysis->key_constraints
                        ) }}</textarea>

                    </div>


                    {{-- Key Findings --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Key Findings
                        </label>

                        <textarea
                            name="key_findings"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'key_findings',
                            $analysis->key_findings
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Assessment & Recommendation --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Assessment & Recommendation
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Score --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Demand & Supply Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_demand_supply_score"
                                class="form-control"
                                value="{{ old(
                                    'overall_demand_supply_score',
                                    $analysis
                                        ->overall_demand_supply_score
                                ) }}"
                                min="0"
                                max="100"
                                step="0.01"
                            >

                            <span class="input-group-text">
                                / 100
                            </span>

                        </div>

                    </div>


                    {{-- Recommendation --}}
                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'recommendation',
                            $analysis->recommendation
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-between mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.show',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'demandSupplyAnalysis' =>
                            $analysis->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Analysis
            </button>

        </div>

    </form>

</div>

@endsection