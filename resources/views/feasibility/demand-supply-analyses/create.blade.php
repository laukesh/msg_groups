@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Demand & Supply Analysis
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


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


    {{-- Form --}}
    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.demand-supply-analyses.store',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf


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
                            value="{{ old('title') }}"
                            placeholder="Enter analysis title"
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
                                {{ old('market_type') === 'Retail'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Retail
                            </option>

                            <option
                                value="Commercial"
                                {{ old('market_type') === 'Commercial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Commercial
                            </option>

                            <option
                                value="Residential"
                                {{ old('market_type') === 'Residential'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Residential
                            </option>

                            <option
                                value="Office"
                                {{ old('market_type') === 'Office'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Office
                            </option>

                            <option
                                value="Industrial"
                                {{ old('market_type') === 'Industrial'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Industrial
                            </option>

                            <option
                                value="Hospitality"
                                {{ old('market_type') === 'Hospitality'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Hospitality
                            </option>

                            <option
                                value="Mixed Use"
                                {{ old('market_type') === 'Mixed Use'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Mixed Use
                            </option>

                            <option
                                value="Other"
                                {{ old('market_type') === 'Other'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Other
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
                            value="{{ old('current_demand') }}"
                            step="0.01"
                            min="0"
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
                            value="{{ old('projected_demand') }}"
                            step="0.01"
                            min="0"
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
                            value="{{ old('demand_growth_rate') }}"
                            step="0.01"
                            min="0"
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
                            placeholder="Describe current and future market demand..."
                        >{{ old('demand_assessment') }}</textarea>

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
                            placeholder="Describe demand from target customer segments..."
                        >{{ old('target_customer_demand') }}</textarea>

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
                            value="{{ old('current_supply') }}"
                            step="0.01"
                            min="0"
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
                            value="{{ old('future_supply') }}"
                            step="0.01"
                            min="0"
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
                            value="{{ old('market_capacity') }}"
                            step="0.01"
                            min="0"
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
                            placeholder="Describe upcoming projects and future supply..."
                        >{{ old('supply_pipeline') }}</textarea>

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
                            placeholder="Describe competitor capacity and available supply..."
                        >{{ old('competitor_supply') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Demand-Supply Gap --}}
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
                            value="{{ old('demand_supply_gap') }}"
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
                            value="{{ old('occupancy_rate') }}"
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
                            value="{{ old('utilization_rate') }}"
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
                            value="{{ old('forecast_period') }}"
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
                            value="{{ old('forecast_demand') }}"
                            step="0.01"
                            min="0"
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
                            placeholder="Factors driving demand..."
                        >{{ old('key_drivers') }}</textarea>

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
                            placeholder="Factors limiting demand or affecting supply..."
                        >{{ old('key_constraints') }}</textarea>

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
                            placeholder="Summarize the major findings..."
                        >{{ old('key_findings') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Score & Recommendation --}}
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
                                    'overall_demand_supply_score'
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
                            placeholder="Provide your demand and supply recommendation..."
                        >{{ old('recommendation') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

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
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Analysis
            </button>

        </div>

    </form>

</div>

@endsection