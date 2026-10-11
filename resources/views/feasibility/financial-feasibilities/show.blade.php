@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Financial Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $financialFeasibility->analysis_number }}
                -
                {{ $financialFeasibility->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.financial-feasibilities.index',
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
                    'admin.land.lands.feasibility-assessments.financial-feasibilities.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                        'financialFeasibility' =>
                            $financialFeasibility->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- Success --}}
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
            <strong>Basic Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted">
                        Analysis Number
                    </label>

                    <div class="fw-semibold">
                        {{ $financialFeasibility->analysis_number }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="text-muted">
                        Title
                    </label>

                    <div class="fw-semibold">
                        {{ $financialFeasibility->title }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Status
                    </label>

                    <div>

                        @if(
                            $financialFeasibility->status === 'Draft'
                        )

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif(
                            $financialFeasibility->status === 'Submitted'
                        )

                            <span class="badge bg-warning text-dark">
                                Submitted
                            </span>

                        @elseif(
                            $financialFeasibility->status === 'Approved'
                        )

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif(
                            $financialFeasibility->status === 'Rejected'
                        )

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $financialFeasibility->status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Financial Summary --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Financial Summary</strong>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Project Cost --}}
                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Total Project Cost
                        </div>

                        <div class="fs-5 fw-bold">

                            {{ number_format(
                                $financialFeasibility
                                    ->total_project_cost ?? 0,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                {{-- Revenue --}}
                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Total Revenue
                        </div>

                        <div class="fs-5 fw-bold">

                            {{ number_format(
                                $financialFeasibility
                                    ->total_revenue ?? 0,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                {{-- Net Profit --}}
                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Net Profit
                        </div>

                        <div class="fs-5 fw-bold
                            {{
                                $financialFeasibility->net_profit >= 0
                                    ? 'text-success'
                                    : 'text-danger'
                            }}"
                        >

                            {{ number_format(
                                $financialFeasibility
                                    ->net_profit ?? 0,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                {{-- Score --}}
                <div class="col-md-3 mb-3">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Financial Score
                        </div>

                        <div class="fs-5 fw-bold">

                            {{ $financialFeasibility
                                ->overall_financial_score !== null
                                    ? number_format(
                                        $financialFeasibility
                                            ->overall_financial_score,
                                        2
                                    ) . ' / 100'
                                    : '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Project Cost --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Project Cost</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <tbody>

                    @php
                        $costRows = [
                            'land_cost' =>
                                'Land Cost',

                            'construction_cost' =>
                                'Construction Cost',

                            'development_cost' =>
                                'Development Cost',

                            'infrastructure_cost' =>
                                'Infrastructure Cost',

                            'professional_fee' =>
                                'Professional Fee',

                            'approval_cost' =>
                                'Approval Cost',

                            'marketing_cost' =>
                                'Marketing Cost',

                            'financing_cost' =>
                                'Financing Cost',

                            'contingency_cost' =>
                                'Contingency Cost',

                            'other_project_cost' =>
                                'Other Project Cost',
                        ];
                    @endphp


                    @foreach($costRows as $field => $label)

                        <tr>

                            <th style="width: 60%;">
                                {{ $label }}
                            </th>

                            <td>

                                {{ number_format(
                                    $financialFeasibility->$field ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>

                    @endforeach


                    <tr class="table-light">

                        <th>
                            Total Project Cost
                        </th>

                        <th>

                            {{ number_format(
                                $financialFeasibility
                                    ->total_project_cost ?? 0,
                                2
                            ) }}

                        </th>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Revenue --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Revenue</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <tr>

                        <th>
                            Sales Revenue
                        </th>

                        <td>
                            {{ number_format(
                                $financialFeasibility
                                    ->sales_revenue ?? 0,
                                2
                            ) }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Rental Revenue
                        </th>

                        <td>
                            {{ number_format(
                                $financialFeasibility
                                    ->rental_revenue ?? 0,
                                2
                            ) }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Other Revenue
                        </th>

                        <td>
                            {{ number_format(
                                $financialFeasibility
                                    ->other_revenue ?? 0,
                                2
                            ) }}
                        </td>

                    </tr>


                    <tr class="table-light">

                        <th>
                            Total Revenue
                        </th>

                        <th>
                            {{ number_format(
                                $financialFeasibility
                                    ->total_revenue ?? 0,
                                2
                            ) }}
                        </th>

                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Profitability --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Profitability</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        NOI
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ number_format(
                            $financialFeasibility
                                ->net_operating_income ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Gross Profit
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ number_format(
                            $financialFeasibility
                                ->gross_profit ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Net Profit
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ number_format(
                            $financialFeasibility
                                ->net_profit ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Profit Margin
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ number_format(
                            $financialFeasibility
                                ->profit_margin ?? 0,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Investment Metrics --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Investment Metrics</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        ROI
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ $financialFeasibility->roi !== null
                            ? number_format(
                                $financialFeasibility->roi,
                                2
                            ) . '%'
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        IRR
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ $financialFeasibility->irr !== null
                            ? number_format(
                                $financialFeasibility->irr,
                                2
                            ) . '%'
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        NPV
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ $financialFeasibility->npv !== null
                            ? number_format(
                                $financialFeasibility->npv,
                                2
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Payback Period
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ $financialFeasibility
                            ->payback_period !== null
                                ? number_format(
                                    $financialFeasibility
                                        ->payback_period,
                                    2
                                ) . ' Years'
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        DSCR
                    </label>

                    <div class="fs-5 fw-bold">

                        {{ $financialFeasibility->dscr !== null
                            ? number_format(
                                $financialFeasibility->dscr,
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
    {{-- Financing --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Financing Structure</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Equity Contribution
                    </label>

                    <div class="fw-semibold">

                        {{ number_format(
                            $financialFeasibility
                                ->equity_contribution ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Debt Financing
                    </label>

                    <div class="fw-semibold">

                        {{ number_format(
                            $financialFeasibility
                                ->debt_financing ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Interest Rate
                    </label>

                    <div class="fw-semibold">

                        {{ $financialFeasibility
                            ->interest_rate !== null
                                ? number_format(
                                    $financialFeasibility
                                        ->interest_rate,
                                    2
                                ) . '%'
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted">
                        Loan Tenure
                    </label>

                    <div class="fw-semibold">

                        {{ $financialFeasibility
                            ->loan_tenure !== null
                                ? number_format(
                                    $financialFeasibility
                                        ->loan_tenure,
                                    2
                                ) . ' Years'
                                : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Assumptions --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Financial Assumptions & Analysis</strong>
        </div>

        <div class="card-body">

            <div class="mb-4">

                <label class="text-muted">
                    Financial Assumptions
                </label>

                <div class="border rounded p-3 bg-light">

                    {!! nl2br(
                        e(
                            $financialFeasibility
                                ->financial_assumptions
                                ?? '-'
                        )
                    ) !!}

                </div>

            </div>


            <div class="row">

                <div class="col-md-6">

                    <label class="text-muted">
                        Cash Flow Summary
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $financialFeasibility
                                    ->cash_flow_summary
                                    ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="text-muted">
                        Sensitivity Analysis
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $financialFeasibility
                                    ->sensitivity_analysis
                                    ?? '-'
                            )
                        ) !!}

                    </div>

                </div>

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

                <div class="col-md-6 mb-4">

                    <label class="text-muted">
                        Key Financial Findings
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $financialFeasibility
                                    ->key_financial_findings
                                    ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <label class="text-muted">
                        Financial Risks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $financialFeasibility
                                    ->financial_risks
                                    ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-8">

                    <label class="text-muted">
                        Recommendation
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {!! nl2br(
                            e(
                                $financialFeasibility
                                    ->recommendation
                                    ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted">
                        Overall Financial Score
                    </label>

                    <div class="display-6">

                        {{ $financialFeasibility
                            ->overall_financial_score !== null
                                ? number_format(
                                    $financialFeasibility
                                        ->overall_financial_score,
                                    2
                                ) . ' / 100'
                                : '-'
                        }}

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
                    'admin.land.lands.feasibility-assessments.financial-feasibilities.edit',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'financialFeasibility' =>
                            $financialFeasibility->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.financial-feasibilities.destroy',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,

                        'financialFeasibility' =>
                            $financialFeasibility->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this Financial Feasibility?');"
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