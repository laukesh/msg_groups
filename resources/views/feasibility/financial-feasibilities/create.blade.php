@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                New Financial Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


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

    </div>


    {{-- ========================================================= --}}
    {{-- Validation --}}
    {{-- ========================================================= --}}

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


    <form
        action="{{ route(
            'admin.land.lands.feasibility-assessments.financial-feasibilities.store',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        ) }}"
        method="POST"
        id="financialFeasibilityForm"
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
                            placeholder="Enter financial feasibility title"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Draft"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Project Cost --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Project Cost
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    @php
                        $costFields = [
                            'land_cost' => 'Land Cost',
                            'construction_cost' => 'Construction Cost',
                            'development_cost' => 'Development Cost',
                            'infrastructure_cost' => 'Infrastructure Cost',
                            'professional_fee' => 'Professional Fee',
                            'approval_cost' => 'Approval Cost',
                            'marketing_cost' => 'Marketing Cost',
                            'financing_cost' => 'Financing Cost',
                            'contingency_cost' => 'Contingency Cost',
                            'other_project_cost' => 'Other Project Cost',
                        ];
                    @endphp


                    @foreach($costFields as $field => $label)

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <input
                                type="number"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                class="form-control project-cost"
                                value="{{ old($field) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    @endforeach


                    {{-- Total --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Total Project Cost
                        </label>

                        <input
                            type="number"
                            name="total_project_cost"
                            id="total_project_cost"
                            class="form-control fw-bold"
                            value="{{ old('total_project_cost') }}"
                            step="0.01"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Revenue --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Revenue
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Sales Revenue
                        </label>

                        <input
                            type="number"
                            name="sales_revenue"
                            id="sales_revenue"
                            class="form-control revenue-field"
                            value="{{ old('sales_revenue') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Rental Revenue
                        </label>

                        <input
                            type="number"
                            name="rental_revenue"
                            id="rental_revenue"
                            class="form-control revenue-field"
                            value="{{ old('rental_revenue') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Other Revenue
                        </label>

                        <input
                            type="number"
                            name="other_revenue"
                            id="other_revenue"
                            class="form-control revenue-field"
                            value="{{ old('other_revenue') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Total Revenue
                        </label>

                        <input
                            type="number"
                            name="total_revenue"
                            id="total_revenue"
                            class="form-control fw-bold"
                            value="{{ old('total_revenue') }}"
                            step="0.01"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Operating Expenses --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Operating Expenses
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Operating Expenses
                        </label>

                        <input
                            type="number"
                            name="operating_expenses"
                            class="form-control expense-field"
                            value="{{ old('operating_expenses') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Maintenance Cost
                        </label>

                        <input
                            type="number"
                            name="maintenance_cost"
                            class="form-control expense-field"
                            value="{{ old('maintenance_cost') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Administrative Cost
                        </label>

                        <input
                            type="number"
                            name="administrative_cost"
                            class="form-control expense-field"
                            value="{{ old('administrative_cost') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Other Operating Cost
                        </label>

                        <input
                            type="number"
                            name="other_operating_cost"
                            class="form-control expense-field"
                            value="{{ old('other_operating_cost') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Net Operating Income
                        </label>

                        <input
                            type="number"
                            name="net_operating_income"
                            id="net_operating_income"
                            class="form-control fw-bold"
                            value="{{ old('net_operating_income') }}"
                            step="0.01"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Profitability --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Profitability
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Gross Profit
                        </label>

                        <input
                            type="number"
                            name="gross_profit"
                            id="gross_profit"
                            class="form-control"
                            value="{{ old('gross_profit') }}"
                            step="0.01"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Net Profit
                        </label>

                        <input
                            type="number"
                            name="net_profit"
                            id="net_profit"
                            class="form-control fw-bold"
                            value="{{ old('net_profit') }}"
                            step="0.01"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Profit Margin (%)
                        </label>

                        <input
                            type="number"
                            name="profit_margin"
                            id="profit_margin"
                            class="form-control"
                            value="{{ old('profit_margin') }}"
                            step="0.01"
                            readonly
                        >

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

                        <input
                            type="number"
                            name="npv"
                            class="form-control"
                            value="{{ old('npv') }}"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Payback Period (Years)
                        </label>

                        <input
                            type="number"
                            name="payback_period"
                            class="form-control"
                            value="{{ old('payback_period') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Financing --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Financing Structure
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Equity Contribution
                        </label>

                        <input
                            type="number"
                            name="equity_contribution"
                            class="form-control"
                            value="{{ old('equity_contribution') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Debt Financing
                        </label>

                        <input
                            type="number"
                            name="debt_financing"
                            class="form-control"
                            value="{{ old('debt_financing') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Interest Rate (%)
                        </label>

                        <input
                            type="number"
                            name="interest_rate"
                            class="form-control"
                            value="{{ old('interest_rate') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Loan Tenure (Years)
                        </label>

                        <input
                            type="number"
                            name="loan_tenure"
                            class="form-control"
                            value="{{ old('loan_tenure') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            DSCR
                        </label>

                        <input
                            type="number"
                            name="dscr"
                            class="form-control"
                            value="{{ old('dscr') }}"
                            min="0"
                            step="0.01"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Financial Assumptions --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Financial Assumptions & Analysis
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Financial Assumptions
                        </label>

                        <textarea
                            name="financial_assumptions"
                            class="form-control"
                            rows="5"
                            placeholder="Enter key financial assumptions..."
                        >{{ old('financial_assumptions') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Cash Flow Summary
                        </label>

                        <textarea
                            name="cash_flow_summary"
                            class="form-control"
                            rows="6"
                            placeholder="Summarize expected cash flows..."
                        >{{ old('cash_flow_summary') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Sensitivity Analysis
                        </label>

                        <textarea
                            name="sensitivity_analysis"
                            class="form-control"
                            rows="6"
                            placeholder="Describe sensitivity to major assumptions..."
                        >{{ old('sensitivity_analysis') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Findings & Recommendation --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Findings & Recommendation
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Key Financial Findings
                        </label>

                        <textarea
                            name="key_financial_findings"
                            class="form-control"
                            rows="6"
                            placeholder="Summarize key financial findings..."
                        >{{ old('key_financial_findings') }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Financial Risks
                        </label>

                        <textarea
                            name="financial_risks"
                            class="form-control"
                            rows="6"
                            placeholder="Identify financial risks..."
                        >{{ old('financial_risks') }}</textarea>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            class="form-control"
                            rows="5"
                            placeholder="Provide financial feasibility recommendation..."
                        >{{ old('recommendation') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overall Financial Score
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="overall_financial_score"
                                class="form-control"
                                value="{{ old(
                                    'overall_financial_score'
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

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

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
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Financial Feasibility
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- Automatic Calculations --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    function numberValue(element) {

        if (!element) {
            return 0;
        }

        let value = parseFloat(element.value);

        return isNaN(value) ? 0 : value;
    }


    function calculateProjectCost() {

        let total = 0;

        document
            .querySelectorAll('.project-cost')
            .forEach(function (input) {

                total += numberValue(input);

            });


        document.getElementById(
            'total_project_cost'
        ).value = total.toFixed(2);


        calculateProfitability();
    }


    function calculateRevenue() {

        let total = 0;

        document
            .querySelectorAll('.revenue-field')
            .forEach(function (input) {

                total += numberValue(input);

            });


        document.getElementById(
            'total_revenue'
        ).value = total.toFixed(2);


        calculateProfitability();
    }


    function calculateProfitability() {

        let totalRevenue =
            numberValue(
                document.getElementById(
                    'total_revenue'
                )
            );


        let totalProjectCost =
            numberValue(
                document.getElementById(
                    'total_project_cost'
                )
            );


        let operatingExpenses = 0;

        document
            .querySelectorAll('.expense-field')
            .forEach(function (input) {

                operatingExpenses +=
                    numberValue(input);

            });


        let noi =
            totalRevenue -
            operatingExpenses;


        document.getElementById(
            'net_operating_income'
        ).value = noi.toFixed(2);


        let grossProfit =
            totalRevenue -
            totalProjectCost;


        document.getElementById(
            'gross_profit'
        ).value =
            grossProfit.toFixed(2);


        let netProfit =
            grossProfit -
            operatingExpenses;


        document.getElementById(
            'net_profit'
        ).value =
            netProfit.toFixed(2);


        let profitMargin = 0;


        if (totalRevenue > 0) {

            profitMargin =
                (
                    netProfit /
                    totalRevenue
                ) * 100;

        }


        document.getElementById(
            'profit_margin'
        ).value =
            profitMargin.toFixed(2);

    }


    document
        .querySelectorAll(
            '.project-cost'
        )
        .forEach(function (input) {

            input.addEventListener(
                'input',
                calculateProjectCost
            );

        });


    document
        .querySelectorAll(
            '.revenue-field'
        )
        .forEach(function (input) {

            input.addEventListener(
                'input',
                calculateRevenue
            );

        });


    document
        .querySelectorAll(
            '.expense-field'
        )
        .forEach(function (input) {

            input.addEventListener(
                'input',
                calculateProfitability
            );

        });


    calculateProjectCost();
    calculateRevenue();

});

</script>

@endsection