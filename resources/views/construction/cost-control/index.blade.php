@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Cost Control
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">           

            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.other-costs.index',
                    $project
                ) }}"
                class="btn btn-outline-primary"
            >
                Other Costs
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.report',
                    $project
                ) }}"
                class="btn btn-outline-primary"
            >
                Management Report
            </a>

        </div>

    </div>


    {{-- ================================================================
         SUCCESS / ERROR
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ================================================================
         NO APPROVED BUDGET
    ================================================================= --}}

    @if(!$budget)

        <div class="alert alert-warning">

            <div class="fw-semibold mb-1">
                No Approved Project Budget
            </div>

            <div>
                Cost Control cannot calculate the project's
                budget utilization until an approved project
                budget is available.
            </div>

        </div>

    @endif


    {{-- ================================================================
         SUMMARY CARDS
    ================================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Approved Budget --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Approved Budget
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ number_format(
                            $summary['approved_budget'],
                            2
                        ) }}

                    </div>

                    <div class="small text-muted">

                        {{ $budget?->currency ?? 'USD' }}

                        @if($budget)
                            · Version {{ $budget->version_number }}
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Contracted --}}

        <div class="col-xl-3 col-md-6">

            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.contracts',
                    $project
                ) }}"
                class="text-decoration-none text-dark"
            >

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Contracted
                        </div>

                        <div class="fs-4 fw-semibold">

                            {{
                                number_format(
                                    $summary['contracted'],
                                    2
                                )
                            }}

                        </div>

                        <div class="small text-primary mt-2">
                            View contract details →
                        </div>

                    </div>

                </div>

            </a>

        </div>

        {{-- Approved Variations --}}

        <div class="col-xl-3 col-md-6">

            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.variations',
                    $project
                ) }}"
                class="text-decoration-none text-dark"
            >

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Approved Variations
                        </div>

                        <div class="fs-4 fw-semibold">

                            {{
                                number_format(
                                    $summary['variations'],
                                    2
                                )
                            }}

                        </div>

                        <div class="small text-primary mt-2">
                            View approved variations →
                        </div>

                    </div>

                </div>

            </a>

        </div>
        <div class="col-xl-3 col-md-6">
            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.invoices',
                    $project
                ) }}"
                class="text-decoration-none text-dark"
            >

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Invoiced
                        </div>

                        <div class="fs-4 fw-semibold">

                            {{
                                number_format(
                                    $summary['invoiced'],
                                    2
                                )
                            }}

                        </div>

                        <div class="small text-primary mt-2">
                            View invoices →
                        </div>

                    </div>

                </div>

            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.payments',
                    $project
                ) }}"
                class="text-decoration-none text-dark"
            >

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            Paid
                        </div>

                        <div class="fs-4 fw-semibold text-success">

                            {{
                                number_format(
                                    $summary['paid'],
                                    2
                                )
                            }}

                        </div>

                        <div class="small text-primary mt-2">
                            View processed payments →
                        </div>

                    </div>

                </div>

            </a>
        </div>


        {{-- Other Costs --}}

        <div class="col-xl-3 col-md-6">
        <a
                href="{{ route(
                    'admin.projects.construction.cost-control.other-costs',
                    $project
                ) }}"
                class="text-decoration-none text-dark"
            >
            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Other Approved Costs
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ number_format(
                            $summary['other_costs'],
                            2
                        ) }}

                    </div>

                    <div class="small text-muted">
                        Construction Expenses
                    </div>

                </div>

            </div>
        </a>

        </div>


        {{-- Remaining --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Remaining Budget
                    </div>

                    <div class="fs-4 fw-semibold
                        {{ $summary['remaining_budget'] < 0
                            ? 'text-danger'
                            : 'text-success'
                        }}"
                    >

                        {{ number_format(
                            $summary['remaining_budget'],
                            2
                        ) }}

                    </div>

                    <div class="small text-muted">
                        Budget less committed cost
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Revised Cost Position
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Original Contract Commitment
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['contracted'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Variations
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['variations'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Other Approved Costs
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['other_costs'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Revised Commitment
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['revised_commitment'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Budget Utilization
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Committed --}}

                <div class="col-md-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Committed
                        </span>

                        <strong>
                            {{
                                number_format(
                                    $summary['budget_utilization'],
                                    1
                                )
                            }}%
                        </strong>

                    </div>


                    <div class="progress">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="
                                width:
                                {{ min(
                                    100,
                                    $summary['budget_utilization']
                                ) }}%;
                            "
                        ></div>

                    </div>


                    <div class="small text-muted mt-2">

                        {{
                            number_format(
                                $summary['revised_commitment'],
                                2
                            )
                        }}

                        committed against approved budget.

                    </div>

                </div>


                {{-- Invoiced --}}

                <div class="col-md-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Invoiced
                        </span>

                        <strong>
                            {{
                                number_format(
                                    $summary['invoice_utilization'],
                                    1
                                )
                            }}%
                        </strong>

                    </div>


                    <div class="progress">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="
                                width:
                                {{ min(
                                    100,
                                    $summary['invoice_utilization']
                                ) }}%;
                            "
                        ></div>

                    </div>


                    <div class="small text-muted mt-2">

                        {{
                            number_format(
                                $summary['invoiced'],
                                2
                            )
                        }}

                        invoiced against approved budget.

                    </div>

                </div>


                {{-- Paid --}}

                <div class="col-md-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Paid
                        </span>

                        <strong>
                            {{
                                number_format(
                                    $summary['payment_utilization'],
                                    1
                                )
                            }}%
                        </strong>

                    </div>


                    <div class="progress">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="
                                width:
                                {{ min(
                                    100,
                                    $summary['payment_utilization']
                                ) }}%;
                            "
                        ></div>

                    </div>


                    <div class="small text-muted mt-2">

                        {{
                            number_format(
                                $summary['paid'],
                                2
                            )
                        }}

                        actually paid.

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

    <div class="card-header">

        <strong>
            Budget vs Actual Cost Position
        </strong>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>
                            Cost Position
                        </th>

                        <th class="text-end">
                            Amount
                        </th>

                        <th class="text-end">
                            % of Budget
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    {{-- Approved Budget --}}

                    <tr>

                        <td>
                            <strong>
                                Approved Budget
                            </strong>
                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['approved_budget'],
                                    2
                                )
                            }}

                        </td>

                        <td class="text-end">
                            100%
                        </td>

                        <td>

                            <span class="badge bg-secondary">
                                Baseline
                            </span>

                        </td>

                    </tr>


                    {{-- Revised Commitment --}}

                    <tr>

                        <td>
                            Revised Commitment
                        </td>

                        <td class="text-end fw-semibold">

                            {{
                                number_format(
                                    $summary['revised_commitment'],
                                    2
                                )
                            }}

                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['budget_utilization'],
                                    1
                                )
                            }}%

                        </td>

                        <td>

                            @if(
                                $summary['budget_utilization'] > 100
                            )

                                <span class="badge bg-danger">
                                    Over Budget
                                </span>

                            @elseif(
                                $summary['budget_utilization'] >= 90
                            )

                                <span class="badge bg-warning text-dark">
                                    Critical
                                </span>

                            @elseif(
                                $summary['budget_utilization'] >= 75
                            )

                                <span class="badge bg-warning text-dark">
                                    Watch
                                </span>

                            @else

                                <span class="badge bg-success">
                                    Healthy
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- Invoiced --}}

                    <tr>

                        <td>
                            Invoiced
                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['invoiced'],
                                    2
                                )
                            }}

                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['invoice_utilization'],
                                    1
                                )
                            }}%

                        </td>

                        <td>

                            <span class="badge bg-info text-dark">
                                Billed
                            </span>

                        </td>

                    </tr>


                    {{-- Paid --}}

                    <tr>

                        <td>
                            Processed Payments
                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['paid'],
                                    2
                                )
                            }}

                        </td>

                        <td class="text-end">

                            {{
                                number_format(
                                    $summary['payment_utilization'],
                                    1
                                )
                            }}%

                        </td>

                        <td>

                            <span class="badge bg-success">
                                Paid
                            </span>

                        </td>

                    </tr>


                    {{-- Outstanding --}}

                    <tr class="table-light">

                        <td>
                            Outstanding Invoices
                        </td>

                        <td class="text-end fw-semibold">

                            {{
                                number_format(
                                    $summary['outstanding'],
                                    2
                                )
                            }}

                        </td>

                        <td class="text-end">

                            @if(
                                $summary['approved_budget'] > 0
                            )

                                {{
                                    number_format(
                                        (
                                            $summary['outstanding']
                                            /
                                            $summary['approved_budget']
                                        ) * 100,
                                        1
                                    )
                                }}%

                            @else

                                0%

                            @endif

                        </td>

                        <td>

                            @if(
                                $summary['outstanding'] > 0
                            )

                                <span class="badge bg-warning text-dark">
                                    Outstanding
                                </span>

                            @else

                                <span class="badge bg-success">
                                    Fully Paid
                                </span>

                            @endif

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

        @php

        $utilization =
            (float) $summary['budget_utilization'];

        if ($utilization >= 100) {

            $healthClass = 'danger';

            $healthTitle =
                'Budget Exceeded';

            $healthMessage =
                'The revised commitment has reached or exceeded the approved budget.';

        } elseif ($utilization >= 90) {

            $healthClass = 'danger';

            $healthTitle =
                'Critical';

            $healthMessage =
                'The project is approaching the approved budget limit.';

        } elseif ($utilization >= 75) {

            $healthClass = 'warning';

            $healthTitle =
                'Watch';

            $healthMessage =
                'Budget utilization is high and should be monitored closely.';

        } else {

            $healthClass = 'success';

            $healthTitle =
                'Healthy';

            $healthMessage =
                'Current commitments remain comfortably within the approved budget.';

        }

    @endphp


    <div class="alert alert-{{ $healthClass }} mb-4">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <strong>
                    Budget Health: {{ $healthTitle }}
                </strong>

                <div class="small mt-1">
                    {{ $healthMessage }}
                </div>

            </div>


            <strong class="fs-5">

                {{
                    number_format(
                        $utilization,
                        1
                    )
                }}%

            </strong>

        </div>

    </div>

    <div class="card mb-4">

    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-md-8">

                <div class="text-muted small">
                    Remaining Approved Budget
                </div>

                <div class="fs-2 fw-semibold">

                    {{
                        number_format(
                            max(
                                0,
                                $summary['remaining_budget']
                            ),
                            2
                        )
                    }}

                </div>

                <div class="text-muted small">

                    {{
                        number_format(
                            $summary[
                                'remaining_budget_percentage'
                            ],
                            1
                        )
                    }}%

                    of the approved budget remains
                    uncommitted.

                </div>

            </div>


            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                @if(
                    $summary['remaining_budget'] < 0
                )

                    <span class="badge bg-danger fs-6">
                        Budget Overrun
                    </span>

                @elseif(
                    $summary['budget_utilization'] >= 90
                )

                    <span class="badge bg-warning text-dark fs-6">
                        Near Budget Limit
                    </span>

                @else

                    <span class="badge bg-success fs-6">
                        Within Budget
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>


    {{-- ================================================================
         FINANCIAL POSITION
    ================================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Budget Position --}}

        <div class="col-lg-7">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Budget Position
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Approved Budget
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{
                                    number_format(
                                        $summary['approved_budget'],
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Total Committed
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{
                                    number_format(
                                        $summary['total_committed'],
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Remaining
                            </div>

                            <div
                                class="
                                    fs-5
                                    fw-semibold
                                    {{
                                        $summary['remaining_budget'] < 0
                                            ? 'text-danger'
                                            : 'text-success'
                                    }}
                                "
                            >

                                {{
                                    number_format(
                                        $summary['remaining_budget'],
                                        2
                                    )
                                }}

                            </div>

                        </div>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Budget Utilization
                        </span>

                        <strong>
                            {{ number_format(
                                $summary['budget_utilization'],
                                1
                            ) }}%
                        </strong>

                    </div>


                    @php

                        $budgetProgress =
                            min(
                                100,
                                max(
                                    0,
                                    $summary['budget_utilization']
                                )
                            );

                    @endphp


                    <div
                        class="progress"
                        style="height: 12px;"
                    >

                        <div
                            class="
                                progress-bar
                                {{
                                    $summary['budget_utilization'] > 100
                                        ? 'bg-danger'
                                        : (
                                            $summary['budget_utilization'] >= 80
                                                ? 'bg-warning'
                                                : 'bg-success'
                                        )
                                }}
                            "
                            role="progressbar"
                            style="width: {{ $budgetProgress }}%;"
                        ></div>

                    </div>


                    @if($summary['remaining_budget'] < 0)

                        <div class="alert alert-danger mt-3 mb-0">

                            <strong>
                                Budget Overrun
                            </strong>

                            <div class="small mt-1">
                                Committed construction cost has
                                exceeded the approved project budget.
                            </div>

                        </div>

                    @elseif($summary['budget_utilization'] >= 80)

                        <div class="alert alert-warning mt-3 mb-0">

                            <strong>
                                Budget Utilization Warning
                            </strong>

                            <div class="small mt-1">
                                More than 80% of the approved budget
                                has been committed.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Payment Position --}}

        <div class="col-lg-5">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Invoice & Payment Position
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        <div class="col-6">

                            <div class="text-muted small">
                                Invoiced
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{
                                    number_format(
                                        $summary['invoiced'],
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="text-muted small">
                                Paid
                            </div>

                            <div class="fs-5 fw-semibold text-success">

                                {{
                                    number_format(
                                        $summary['paid'],
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="text-muted small">
                                Outstanding
                            </div>

                            <div class="fs-5 fw-semibold
                                {{
                                    $summary['outstanding'] > 0
                                        ? 'text-danger'
                                        : 'text-success'
                                }}"
                            >

                                {{
                                    number_format(
                                        $summary['outstanding'],
                                        2
                                    )
                                }}

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="text-muted small">
                                Payment Utilization
                            </div>

                            <div class="fs-5 fw-semibold">

                                {{
                                    number_format(
                                        $summary['payment_utilization'],
                                        1
                                    )
                                }}%

                            </div>

                        </div>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Paid against Invoiced
                        </span>

                        <strong>
                            {{
                                number_format(
                                    $summary['payment_utilization'],
                                    1
                                )
                            }}%
                        </strong>

                    </div>


                    @php

                        $paymentProgress =
                            min(
                                100,
                                max(
                                    0,
                                    $summary['payment_utilization']
                                )
                            );

                    @endphp


                    <div
                        class="progress"
                        style="height: 10px;"
                    >

                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="
                                width:
                                {{ $paymentProgress }}%;
                            "
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         CONTRACT REGISTER
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Contract Cost Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($contractSummary->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="
                            table
                            table-hover
                            align-middle
                            mb-0
                        "
                    >

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Contractor
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Committed
                                </th>

                                <th class="text-end">
                                    Invoiced
                                </th>

                                <th class="text-end">
                                    Paid
                                </th>

                                <th class="text-end">
                                    Outstanding
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $contractSummary
                                as $item
                            )

                                @php

                                    $contract =
                                        $item['contract'];

                                    $statusClass =
                                        match(
                                            $contract->status
                                        ) {

                                            'Approved',
                                            'Active' =>
                                                'bg-success',

                                            'Completed',
                                            'Closed' =>
                                                'bg-primary',

                                            default =>
                                                'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.contracts.show',
                                                [
                                                    'procurementTender' =>
                                                        $contract->tender,

                                                    'contract' =>
                                                        $contract,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >

                                            {{
                                                $contract
                                                    ->contract_number
                                            }}

                                        </a>


                                        <div class="small text-muted">

                                            {{
                                                $contract
                                                    ->contract_title
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $contract
                                                ->bidder_name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="
                                                badge
                                                {{ $statusClass }}
                                            "
                                        >
                                            {{ $contract->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        {{
                                            number_format(
                                                $item['committed'],
                                                2
                                            )
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{
                                            number_format(
                                                $item['invoiced'],
                                                2
                                            )
                                        }}

                                    </td>


                                    <td class="text-end text-success">

                                        {{
                                            number_format(
                                                $item['paid'],
                                                2
                                            )
                                        }}

                                    </td>


                                    <td class="text-end">

                                        <span
                                            class="
                                                {{
                                                    $item['outstanding'] > 0
                                                        ? 'text-danger fw-semibold'
                                                        : 'text-success'
                                                }}
                                            "
                                        >

                                            {{
                                                number_format(
                                                    $item['outstanding'],
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.contracts.show',
                                                [
                                                    'procurementTender' =>
                                                        $contract->tender,

                                                    'contract' =>
                                                        $contract,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No procurement contracts found
                    for this project.

                </div>

            @endif

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Approved Variations
                </strong>

                <a
                    href="{{ route(
                        'admin.projects.construction.variations.index',
                        $project
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All Variations
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if($variations->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Variation
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($variations as $variation)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.variations.show',
                                                [
                                                    'project' =>
                                                        $project,

                                                    'variation' =>
                                                        $variation,
                                                ]
                                            ) }}"
                                            class="fw-semibold"
                                        >

                                            {{
                                                $variation
                                                    ->variation_number
                                            }}

                                        </a>


                                        <div class="small text-muted">

                                            {{
                                                $variation->title
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $variation
                                                ->variation_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>
                                        {{ $variation->variation_type }}
                                    </td>


                                    <td>

                                        {{
                                            $variation
                                                ->contract
                                                ?->contract_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        <strong>

                                            {{
                                                number_format(
                                                    (float)
                                                    $variation->amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        {{ $variation->currency }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="5">
                                    Total Approved Variations
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $summary['variations'],
                                            2
                                        )
                                    }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No approved variations.

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         OTHER COST BREAKDOWN
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                "
            >

                <strong>
                    Approved Other Construction Costs
                </strong>


                <a
                    href="{{ route(
                        'admin.projects.construction.other-costs.index',
                        $project
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View Register
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if($otherCostSummary->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="
                            table
                            table-hover
                            align-middle
                            mb-0
                        "
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Cost Type
                                </th>

                                <th>
                                    Entries
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $otherCostSummary
                                as $type => $item
                            )

                                <tr>

                                    <td>
                                        {{ $type }}
                                    </td>

                                    <td>
                                        {{ $item['count'] }}
                                    </td>

                                    <td class="text-end">

                                        <strong>

                                            {{
                                                number_format(
                                                    $item['amount'],
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="2">
                                    Total
                                </th>

                                <th class="text-end">

                                    {{
                                        number_format(
                                            $summary['other_costs'],
                                            2
                                        )
                                    }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No approved other construction costs.

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         FINANCIAL SUMMARY
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Financial Summary
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Budget
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['approved_budget'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Contracted
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['contracted'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Other Approved Costs
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['other_costs'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Total Committed
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['total_committed'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Cost Forecast
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved Budget
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['approved_budget'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Estimated At Completion
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{
                            number_format(
                                $summary['estimated_at_completion'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Forecast Variance
                    </div>

                    <div
                        class="
                            fs-5
                            fw-semibold
                            {{
                                $summary['forecast_variance'] < 0
                                    ? 'text-danger'
                                    : (
                                        $summary['forecast_variance'] > 0
                                            ? 'text-success'
                                            : 'text-muted'
                                    )
                            }}
                        "
                    >

                        {{
                            number_format(
                                abs(
                                    $summary['forecast_variance']
                                ),
                                2
                            )
                        }}

                    </div>

                    <div class="small">

                        @if(
                            $summary['forecast_status']
                            === 'Under Budget'
                        )

                            <span class="text-success">
                                ✓ Under Budget
                            </span>

                        @elseif(
                            $summary['forecast_status']
                            === 'Over Budget'
                        )

                            <span class="text-danger">
                                ⚠ Over Budget
                            </span>

                        @else

                            <span class="text-muted">
                                On Budget
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            @if(
                $summary['forecast_status']
                === 'Over Budget'
            )

                <div class="alert alert-danger mt-4 mb-0">

                    <strong>
                        Forecast Overrun
                    </strong>

                    <div class="small mt-1">

                        The current approved commitments indicate
                        that the project may exceed the approved
                        budget by

                        {{
                            number_format(
                                abs(
                                    $summary['forecast_variance']
                                ),
                                2
                            )
                        }}

                        {{ $budget?->currency ?? 'USD' }}.

                    </div>

                </div>

            @elseif(
                $summary['forecast_status']
                === 'Under Budget'
            )

                <div class="alert alert-success mt-4 mb-0">

                    <strong>
                        Forecast Within Budget
                    </strong>

                    <div class="small mt-1">

                        The current approved commitments remain
                        within the approved project budget.

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection