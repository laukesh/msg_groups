@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Investment Analysis
            </h3>

            <p class="text-muted mb-0">
                {{ $investmentAnalysis->analysis_number }}
                -
                {{ $investmentAnalysis->title }}
            </p>

        </div>


        <div class="d-flex gap-2">

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


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentAnalysis' =>
                            $investmentAnalysis->id,
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

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Investment Analysis Summary
            </strong>


            @switch($investmentAnalysis->status)

                @case('Draft')

                    <span class="badge bg-secondary">
                        Draft
                    </span>

                    @break

                @case('Submitted')

                    <span class="badge bg-warning text-dark">
                        Submitted
                    </span>

                    @break

                @case('Approved')

                    <span class="badge bg-success">
                        Approved
                    </span>

                    @break

                @case('Rejected')

                    <span class="badge bg-danger">
                        Rejected
                    </span>

                    @break

                @default

                    <span class="badge bg-secondary">
                        {{ $investmentAnalysis->status }}
                    </span>

            @endswitch

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Analysis Number
                    </label>

                    <div class="fw-bold">
                        {{ $investmentAnalysis->analysis_number }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="text-muted small">
                        Title
                    </label>

                    <div class="fw-bold">
                        {{ $investmentAnalysis->title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Investment Attractiveness
                    </label>

                    <div>

                        @php
                            $attractiveness =
                                $investmentAnalysis
                                    ->investment_attractiveness;
                        @endphp


                        @if(
                            $attractiveness === 'Very High'
                        )

                            <span class="badge bg-success">
                                Very High
                            </span>

                        @elseif(
                            $attractiveness === 'High'
                        )

                            <span class="badge bg-success">
                                High
                            </span>

                        @elseif(
                            $attractiveness === 'Moderate'
                        )

                            <span class="badge bg-warning text-dark">
                                Moderate
                            </span>

                        @elseif(
                            $attractiveness === 'Low'
                        )

                            <span class="badge bg-danger">
                                Low
                            </span>

                        @elseif($attractiveness)

                            <span class="badge bg-secondary">
                                {{ $attractiveness }}
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Investment Risk
                    </label>

                    <div>

                        @php
                            $risk =
                                $investmentAnalysis
                                    ->investment_risk_rating;
                        @endphp


                        @if($risk === 'Low')

                            <span class="badge bg-success">
                                Low
                            </span>

                        @elseif($risk === 'Medium')

                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>

                        @elseif($risk === 'High')

                            <span class="badge bg-danger">
                                High
                            </span>

                        @elseif($risk === 'Critical')

                            <span class="badge bg-dark">
                                Critical
                            </span>

                        @elseif($risk)

                            <span class="badge bg-secondary">
                                {{ $risk }}
                            </span>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Investment Horizon
                    </label>

                    <div class="fw-bold">
                        {{ $investmentAnalysis->investment_horizon ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Investor Profile
                    </label>

                    <div class="fw-bold">
                        {{ $investmentAnalysis->investor_profile ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted small">
                        Overall Score
                    </label>

                    <div class="fs-5 fw-bold">

                        @if(
                            $investmentAnalysis
                                ->overall_investment_score
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentAnalysis
                                        ->overall_investment_score,
                                    2
                                )
                            }}

                            <small class="text-muted">
                                / 100
                            </small>

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Key Investment Metrics --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Key Investment Metrics
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Total Investment
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->total_investment
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->total_investment,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Expected Revenue
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->expected_revenue
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->expected_revenue,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Expected Profit
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->expected_profit
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->expected_profit,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Expected Cash Flow
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis
                                    ->expected_cash_flow
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->expected_cash_flow,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            ROI
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->roi !== null
                            )

                                {{
                                    number_format(
                                        $investmentAnalysis->roi,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            IRR
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->irr !== null
                            )

                                {{
                                    number_format(
                                        $investmentAnalysis->irr,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            NPV
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->npv !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis->npv,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Payback Period
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis
                                    ->payback_period
                                !== null
                            )

                                {{
                                    number_format(
                                        $investmentAnalysis
                                            ->payback_period,
                                        2
                                    )
                                }}
                                Years

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Profit Margin
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis
                                    ->profit_margin
                                !== null
                            )

                                {{
                                    number_format(
                                        $investmentAnalysis
                                            ->profit_margin,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Investment Multiple
                        </div>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis
                                    ->investment_multiple
                                !== null
                            )

                                {{
                                    number_format(
                                        $investmentAnalysis
                                            ->investment_multiple,
                                        2
                                    )
                                }}x

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Structure --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Equity & Debt Structure
            </strong>

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

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="fw-bold">

                            @if(
                                $investmentAnalysis->{$field}
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->{$field},
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Valuation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project & Investment Valuation
            </strong>

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

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="fw-bold">

                            @if(
                                $investmentAnalysis->{$field}
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->{$field},
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

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

            <strong>
                Exit Strategy
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Exit Strategy
                    </label>

                    <div class="fw-bold">
                        {{ $investmentAnalysis->exit_strategy ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted small">
                        Exit Period
                    </label>

                    <div class="fw-bold">

                        @if(
                            $investmentAnalysis->exit_period
                            !== null
                        )

                            {{
                                number_format(
                                    $investmentAnalysis
                                        ->exit_period,
                                    2
                                )
                            }}
                            Years

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Exit Assumptions
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentAnalysis
                                ->exit_assumptions
                            ?? '-'
                        )) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Investment Scenarios --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investment Scenarios
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Scenario
                            </th>

                            <th>
                                Expected Return
                            </th>

                            <th>
                                IRR
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                Pessimistic Case
                            </td>

                            <td>
                                {{ $investmentAnalysis->pessimistic_case_return !== null
                                    ? number_format($investmentAnalysis->pessimistic_case_return, 2) . '%'
                                    : '-' }}
                            </td>

                            <td>
                                {{ $investmentAnalysis->pessimistic_case_irr !== null
                                    ? number_format($investmentAnalysis->pessimistic_case_irr, 2) . '%'
                                    : '-' }}
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <strong>
                                    Base Case
                                </strong>
                            </td>

                            <td>
                                {{ $investmentAnalysis->base_case_return !== null
                                    ? number_format($investmentAnalysis->base_case_return, 2) . '%'
                                    : '-' }}
                            </td>

                            <td>
                                {{ $investmentAnalysis->base_case_irr !== null
                                    ? number_format($investmentAnalysis->base_case_irr, 2) . '%'
                                    : '-' }}
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Optimistic Case
                            </td>

                            <td>
                                {{ $investmentAnalysis->optimistic_case_return !== null
                                    ? number_format($investmentAnalysis->optimistic_case_return, 2) . '%'
                                    : '-' }}
                            </td>

                            <td>
                                {{ $investmentAnalysis->optimistic_case_irr !== null
                                    ? number_format($investmentAnalysis->optimistic_case_irr, 2) . '%'
                                    : '-' }}
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

            <strong>
                Sensitivity Analysis
            </strong>

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

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $investmentAnalysis->{$field}
                                ?? '-'
                            )) !!}

                        </div>

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

            <strong>
                Investment Conditions
            </strong>

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

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="fs-5 fw-bold">

                            @if(
                                $investmentAnalysis->{$field}
                                !== null
                            )

                                ${{
                                    number_format(
                                        $investmentAnalysis
                                            ->{$field},
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

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

            <strong>
                Investment SWOT Analysis
            </strong>

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

                    <div class="col-md-6 mb-4">

                        <label class="text-muted small">
                            {{ $label }}
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(e(
                                $investmentAnalysis->{$field}
                                ?? '-'
                            )) !!}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Findings & Recommendation --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Findings & Recommendation
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">

                    <label class="text-muted small">
                        Key Investment Findings
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentAnalysis
                                ->key_investment_findings
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <label class="text-muted small">
                        Investment Risks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentAnalysis
                                ->investment_risks
                            ?? '-'
                        )) !!}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <label class="text-muted small">
                        Recommendation
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(e(
                            $investmentAnalysis
                                ->recommendation
                            ?? '-'
                        )) !!}

                    </div>

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
                'admin.land.lands.feasibility-assessments.investment-analyses.index',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-secondary"
        >
            ← Back to Investment Analyses
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentAnalysis' =>
                            $investmentAnalysis->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-analyses.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'investmentAnalysis' =>
                            $investmentAnalysis->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Investment Analysis?');"
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