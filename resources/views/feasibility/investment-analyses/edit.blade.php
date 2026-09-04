@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>Edit Investment Analysis</h3>

            <p class="text-muted mb-0">
                {{ $investmentAnalysis->analysis_number }}
                -
                {{ $investmentAnalysis->title }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentAnalysis' =>
                            $investmentAnalysis->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.feasibility-assessments.investment-analyses.update',
            [
                'land' => $land->id,
                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
                'investmentAnalysis' =>
                    $investmentAnalysis->id,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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
                            value="{{ $investmentAnalysis->analysis_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old(
                                'title',
                                $investmentAnalysis->title
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Rejected'
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'status',
                                            $investmentAnalysis->status
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
                                            'investment_risk_rating',
                                            $investmentAnalysis
                                                ->investment_risk_rating
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
                                            'investment_attractiveness',
                                            $investmentAnalysis
                                                ->investment_attractiveness
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
                            value="{{ old(
                                'investment_horizon',
                                $investmentAnalysis->investment_horizon
                            ) }}"
                            placeholder="e.g. 5 Years"
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
                            value="{{ old(
                                'investor_profile',
                                $investmentAnalysis->investor_profile
                            ) }}"
                            placeholder="e.g. Institutional Investor"
                        >

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
                                'overall_investment_score',
                                $investmentAnalysis
                                    ->overall_investment_score
                            ) }}"
                            min="0"
                            max="100"
                            step="0.01"
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

                            'total_investment' =>
                                'Total Investment',

                            'initial_investment' =>
                                'Initial Investment',

                            'working_capital' =>
                                'Working Capital',

                            'reserve_requirement' =>
                                'Reserve Requirement',

                            'contingency_reserve' =>
                                'Contingency Reserve',

                        ];

                    @endphp


                    @foreach(
                        $investmentFields as $field => $label
                    )

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field,
                                        $investmentAnalysis->{$field}
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

                            'equity_investment' =>
                                'Equity Investment',

                            'debt_investment' =>
                                'Debt Investment',

                            'promoter_contribution' =>
                                'Promoter Contribution',

                            'external_investment' =>
                                'External Investment',

                        ];

                    @endphp


                    @foreach(
                        $fundingFields as $field => $label
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field,
                                        $investmentAnalysis->{$field}
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
        {{-- Expected Returns --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Expected Investment Returns</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $returnMoneyFields = [

                            'expected_revenue' =>
                                'Expected Revenue',

                            'expected_profit' =>
                                'Expected Profit',

                            'expected_cash_flow' =>
                                'Expected Cash Flow',

                        ];

                    @endphp


                    @foreach(
                        $returnMoneyFields as $field => $label
                    )

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field,
                                        $investmentAnalysis->{$field}
                                    ) }}"
                                    step="0.01"
                                >

                            </div>

                        </div>

                    @endforeach


                    @php

                        $returnPercentFields = [

                            'roi' =>
                                'ROI (%)',

                            'irr' =>
                                'IRR (%)',

                            'profit_margin' =>
                                'Profit Margin (%)',

                        ];

                    @endphp


                    @foreach(
                        $returnPercentFields as $field => $label
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <input
                                type="number"
                                name="{{ $field }}"
                                class="form-control"
                                value="{{ old(
                                    $field,
                                    $investmentAnalysis->{$field}
                                ) }}"
                                step="0.01"
                            >

                        </div>

                    @endforeach


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            NPV
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="npv"
                                class="form-control"
                                value="{{ old(
                                    'npv',
                                    $investmentAnalysis->npv
                                ) }}"
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
                                'payback_period',
                                $investmentAnalysis->payback_period
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Investment Multiple
                        </label>

                        <input
                            type="number"
                            name="investment_multiple"
                            class="form-control"
                            value="{{ old(
                                'investment_multiple',
                                $investmentAnalysis
                                    ->investment_multiple
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

                            'project_valuation' =>
                                'Project Valuation',

                            'investment_valuation' =>
                                'Investment Valuation',

                            'exit_valuation' =>
                                'Exit Valuation',

                            'expected_exit_value' =>
                                'Expected Exit Value',

                        ];

                    @endphp


                    @foreach(
                        $valuationFields as $field => $label
                    )

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field,
                                        $investmentAnalysis->{$field}
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
                                            'exit_strategy',
                                            $investmentAnalysis
                                                ->exit_strategy
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
                                'exit_period',
                                $investmentAnalysis->exit_period
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
                            'exit_assumptions',
                            $investmentAnalysis->exit_assumptions
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
                                            'pessimistic_case_return',
                                            $investmentAnalysis
                                                ->pessimistic_case_return
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
                                            'pessimistic_case_irr',
                                            $investmentAnalysis
                                                ->pessimistic_case_irr
                                        ) }}"
                                        step="0.01"
                                    >

                                </td>

                            </tr>


                            <tr>

                                <td>
                                    <strong>
                                        Base Case
                                    </strong>
                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="base_case_return"
                                        class="form-control"
                                        value="{{ old(
                                            'base_case_return',
                                            $investmentAnalysis
                                                ->base_case_return
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
                                            'base_case_irr',
                                            $investmentAnalysis
                                                ->base_case_irr
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
                                            'optimistic_case_return',
                                            $investmentAnalysis
                                                ->optimistic_case_return
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
                                            'optimistic_case_irr',
                                            $investmentAnalysis
                                                ->optimistic_case_irr
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
        {{-- Sensitivity --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Sensitivity Analysis</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $sensitivityFields = [

                            'revenue_sensitivity' =>
                                'Revenue Sensitivity',

                            'cost_sensitivity' =>
                                'Cost Sensitivity',

                            'price_sensitivity' =>
                                'Price Sensitivity',

                            'interest_rate_sensitivity' =>
                                'Interest Rate Sensitivity',

                        ];

                    @endphp


                    @foreach(
                        $sensitivityFields as $field => $label
                    )

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <textarea
                                name="{{ $field }}"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                $field,
                                $investmentAnalysis->{$field}
                            ) }}</textarea>

                        </div>

                    @endforeach

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

                    @php

                        $conditionFields = [

                            'minimum_investment' =>
                                'Minimum Investment',

                            'recommended_investment' =>
                                'Recommended Investment',

                            'maximum_investment' =>
                                'Maximum Investment',

                        ];

                    @endphp


                    @foreach(
                        $conditionFields as $field => $label
                    )

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="{{ $field }}"
                                    class="form-control"
                                    value="{{ old(
                                        $field,
                                        $investmentAnalysis->{$field}
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
        {{-- SWOT --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Investment Assessment</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @php

                        $swotFields = [

                            'investment_strengths' =>
                                'Investment Strengths',

                            'investment_weaknesses' =>
                                'Investment Weaknesses',

                            'investment_opportunities' =>
                                'Investment Opportunities',

                            'investment_threats' =>
                                'Investment Threats',

                        ];

                    @endphp


                    @foreach(
                        $swotFields as $field => $label
                    )

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <textarea
                                name="{{ $field }}"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                $field,
                                $investmentAnalysis->{$field}
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Findings --}}
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
                            'key_investment_findings',
                            $investmentAnalysis
                                ->key_investment_findings
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
                            'investment_risks',
                            $investmentAnalysis
                                ->investment_risks
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
                            'recommendation',
                            $investmentAnalysis
                                ->recommendation
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between mb-5">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentAnalysis' =>
                            $investmentAnalysis->id,
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
                Update Investment Analysis
            </button>

        </div>

    </form>

</div>

@endsection