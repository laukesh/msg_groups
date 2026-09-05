@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>Create Investment Analysis</h3>

            <p class="text-muted mb-0">
                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}
            </p>

        </div>

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.investment-analyses.index',
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


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.feasibility-assessments.investment-analyses.store',
            [
                'land' => $land->id,
                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- Basic Information --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Analysis Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            readonly
                        >

                    </div>


                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Investment Risk Rating
                        </label>

                        <select
                            name="investment_risk_rating"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'investment_risk_rating'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Attractiveness
                        </label>

                        <select
                            name="investment_attractiveness"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Low',
                                'Moderate',
                                'High',
                                'Very High'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'investment_attractiveness'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Horizon
                        </label>

                        <input
                            type="text"
                            name="investment_horizon"
                            class="form-control"
                            placeholder="e.g. 5 Years"
                            value="{{ old(
                                'investment_horizon'
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investor Profile
                        </label>

                        <input
                            type="text"
                            name="investor_profile"
                            class="form-control"
                            placeholder="e.g. Institutional Investor"
                            value="{{ old(
                                'investor_profile'
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Investment Requirement --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Requirement</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $investmentFields = [

                            [
                                'name' => 'total_investment',
                                'label' => 'Total Investment',
                            ],

                            [
                                'name' => 'initial_investment',
                                'label' => 'Initial Investment',
                            ],

                            [
                                'name' => 'working_capital',
                                'label' => 'Working Capital',
                            ],

                            [
                                'name' => 'reserve_requirement',
                                'label' => 'Reserve Requirement',
                            ],

                            [
                                'name' => 'contingency_reserve',
                                'label' => 'Contingency Reserve',
                            ],

                        ];

                    @endphp


                    @foreach(
                        $investmentFields as $field
                    )

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $field['label'] }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field['name'] }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field['name']
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Equity & Debt --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Equity & Debt Structure</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $fundingFields = [

                            [
                                'name' => 'equity_investment',
                                'label' => 'Equity Investment',
                            ],

                            [
                                'name' => 'debt_investment',
                                'label' => 'Debt Investment',
                            ],

                            [
                                'name' => 'promoter_contribution',
                                'label' => 'Promoter Contribution',
                            ],

                            [
                                'name' => 'external_investment',
                                'label' => 'External Investment',
                            ],

                        ];

                    @endphp


                    @foreach(
                        $fundingFields as $field
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $field['label'] }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field['name'] }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field['name']
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Returns --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Expected Investment Returns</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected Revenue
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="expected_revenue"
                                class="form-control"
                                value="{{ old(
                                    'expected_revenue'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected Profit
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="expected_profit"
                                class="form-control"
                                value="{{ old(
                                    'expected_profit'
                                ) }}"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expected Cash Flow
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="expected_cash_flow"
                                class="form-control"
                                value="{{ old(
                                    'expected_cash_flow'
                                ) }}"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            ROI (%)
                        </label>

                        <input
                            type="number"
                            name="roi"
                            class="form-control"
                            value="{{ old('roi') }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            IRR (%)
                        </label>

                        <input
                            type="number"
                            name="irr"
                            class="form-control"
                            value="{{ old('irr') }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            NPV
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="npv"
                                class="form-control"
                                value="{{ old('npv') }}"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Payback Period (Years)
                        </label>

                        <input
                            type="number"
                            name="payback_period"
                            class="form-control"
                            value="{{ old(
                                'payback_period'
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Profit Margin (%)
                        </label>

                        <input
                            type="number"
                            name="profit_margin"
                            class="form-control"
                            value="{{ old(
                                'profit_margin'
                            ) }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Multiple
                        </label>

                        <input
                            type="number"
                            name="investment_multiple"
                            class="form-control"
                            value="{{ old(
                                'investment_multiple'
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Valuation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Project & Investment Valuation</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $valuationFields = [

                            [
                                'name' => 'project_valuation',
                                'label' => 'Project Valuation',
                            ],

                            [
                                'name' => 'investment_valuation',
                                'label' => 'Investment Valuation',
                            ],

                            [
                                'name' => 'exit_valuation',
                                'label' => 'Exit Valuation',
                            ],

                            [
                                'name' => 'expected_exit_value',
                                'label' => 'Expected Exit Value',
                            ],

                        ];

                    @endphp


                    @foreach(
                        $valuationFields as $field
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $field['label'] }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field['name'] }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field['name']
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Exit Strategy --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Exit Strategy</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Exit Strategy
                        </label>

                        <select
                            name="exit_strategy"
                            class="form-select"
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Sale',
                                'Strategic Sale',
                                'Refinancing',
                                'IPO',
                                'Buyout',
                                'Hold',
                                'Other'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'exit_strategy'
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Exit Period (Years)
                        </label>

                        <input
                            type="number"
                            name="exit_period"
                            class="form-control"
                            value="{{ old(
                                'exit_period'
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Exit Assumptions
                        </label>

                        <textarea
                            name="exit_assumptions"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'exit_assumptions'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Investment Scenarios --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Scenarios</strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Scenario
                                </th>

                                <th>
                                    Expected Return (%)
                                </th>

                                <th>
                                    IRR (%)
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td>
                                    Pessimistic Case
                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="pessimistic_case_return"
                                        class="form-control"
                                        value="{{ old(
                                            'pessimistic_case_return'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="pessimistic_case_irr"
                                        class="form-control"
                                        value="{{ old(
                                            'pessimistic_case_irr'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Base Case
                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="base_case_return"
                                        class="form-control"
                                        value="{{ old(
                                            'base_case_return'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="base_case_irr"
                                        class="form-control"
                                        value="{{ old(
                                            'base_case_irr'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <td>
                                    Optimistic Case
                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="optimistic_case_return"
                                        class="form-control"
                                        value="{{ old(
                                            'optimistic_case_return'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="optimistic_case_irr"
                                        class="form-control"
                                        value="{{ old(
                                            'optimistic_case_irr'
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Sensitivity Analysis --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Sensitivity Analysis</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Revenue Sensitivity
                        </label>

                        <textarea
                            name="revenue_sensitivity"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'revenue_sensitivity'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Cost Sensitivity
                        </label>

                        <textarea
                            name="cost_sensitivity"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'cost_sensitivity'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Price Sensitivity
                        </label>

                        <textarea
                            name="price_sensitivity"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'price_sensitivity'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Interest Rate Sensitivity
                        </label>

                        <textarea
                            name="interest_rate_sensitivity"
                            class="form-control"
                            rows="4"
                        >{{ old(
                            'interest_rate_sensitivity'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Investment Conditions --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Conditions</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Minimum Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="minimum_investment"
                                class="form-control"
                                value="{{ old(
                                    'minimum_investment'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Recommended Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="recommended_investment"
                                class="form-control"
                                value="{{ old(
                                    'recommended_investment'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Maximum Investment
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                type="number"
                                name="maximum_investment"
                                class="form-control"
                                value="{{ old(
                                    'maximum_investment'
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Investment SWOT --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Assessment</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Investment Strengths
                        </label>

                        <textarea
                            name="investment_strengths"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'investment_strengths'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Investment Weaknesses
                        </label>

                        <textarea
                            name="investment_weaknesses"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'investment_weaknesses'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Investment Opportunities
                        </label>

                        <textarea
                            name="investment_opportunities"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'investment_opportunities'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Investment Threats
                        </label>

                        <textarea
                            name="investment_threats"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'investment_threats'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Findings & Recommendation --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Findings & Recommendation</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Key Investment Findings
                        </label>

                        <textarea
                            name="key_investment_findings"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'key_investment_findings'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Investment Risks
                        </label>

                        <textarea
                            name="investment_risks"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'investment_risks'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="6"
                        >{{ old(
                            'recommendation'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Investment Score
                        </label>

                        <input
                            type="number"
                            name="overall_investment_score"
                            class="form-control"
                            value="{{ old(
                                'overall_investment_score'
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                        <small class="text-muted">
                            Score from 0 to 100
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Submit --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.index',
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
                Create Investment Analysis
            </button>

        </div>

    </form>

</div>

@endsection