@extends('layouts.app')

@section('content')

<style>
    .budget-page .metric-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .budget-page .metric-label {
        color: #64748b;
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .budget-page .metric-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
    }

    .budget-page .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: .2rem;
    }

    .budget-page .section-subtitle {
        color: #64748b;
        font-size: .8rem;
    }

    .budget-page .progress {
        height: 8px;
        border-radius: 20px;
        background: #e2e8f0;
    }

    .budget-page .progress-bar {
        border-radius: 20px;
    }

    .budget-page .explanation-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        color: #1e40af;
    }

    .budget-page .table thead th {
        white-space: nowrap;
        font-size: .75rem;
        background: #f8fafc;
        color: #334155;
    }

    .budget-page .table tbody td {
        font-size: .78rem;
        vertical-align: middle;
    }

    .budget-page .hero-card {
        border: 0;
        border-radius: 16px;
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .14);
    }

    .budget-page .hero-card .muted-white {
        color: rgba(255,255,255,.72);
    }

    .budget-page .summary-number {
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .budget-page .summary-caption {
        font-size: .75rem;
        color: #64748b;
        margin-top: .35rem;
    }

    .budget-page .status-pill {
        border-radius: 999px;
        padding: .35rem .7rem;
        font-size: .72rem;
        font-weight: 700;
    }

    .budget-page .reading-guide {
        border-left: 4px solid #2563eb;
        background: #f8fafc;
        border-radius: 8px;
    }

    .budget-page .reconciliation-table td,
    .budget-page .reconciliation-table th {
        padding: .7rem .8rem;
    }

    .budget-page .number-positive { color: #059669; font-weight: 700; }
    .budget-page .number-negative { color: #dc2626; font-weight: 700; }
    .budget-page .number-neutral { color: #0f172a; font-weight: 700; }

    .budget-page .line-item-table th {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .budget-page .line-item-table .primary-cell {
        min-width: 190px;
    }

    .budget-page .line-item-table .amount-cell {
        white-space: nowrap;
        text-align: right;
    }

</style>

<div class="container-fluid budget-page">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget
            </div>

            <h3 class="mb-1">
                {{ $projectBudget->title }}
            </h3>

            <div class="text-muted">

                {{ $projectBudget->budget_number }}

                ·

                {{ $project->project_name }}

                ·

                {{ $project->project_number }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- ========================================================= --}}
            {{-- DRAFT / REJECTED --}}
            {{-- ========================================================= --}}

            @if(
                in_array(
                    $projectBudget->status,
                    ['Draft', 'Rejected'],
                    true
                )
            )

                {{-- Edit --}}
                <a
                    href="{{ route(
                        'admin.projects.budget.edit',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit Budget
                </a>


                {{-- Submit For Review --}}
                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.budget.submit',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Submit this budget for review?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="ri-send-plane-line me-1"></i>
                        Submit for Review
                    </button>

                </form>

            @endif


            {{-- ========================================================= --}}
            {{-- UNDER REVIEW --}}
            {{-- ========================================================= --}}

            @if(
                $projectBudget->status === 'Under Review'
            )

                {{-- Approve --}}
                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.budget.approve',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Are you sure you want to approve this budget?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="ri-check-line me-1"></i>
                        Approve Budget
                    </button>

                </form>


                {{-- Reject --}}
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectBudgetModal"
                >
                    <i class="ri-close-line me-1"></i>
                    Reject Budget
                </button>

            @endif


            {{-- ========================================================= --}}
            {{-- APPROVED --}}
            {{-- ========================================================= --}}

            @if(
                $projectBudget->status === 'Approved'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.budget.revision',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Create a new budget revision from this approved budget?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        <i class="ri-file-copy-line me-1"></i>
                        Create Revision
                    </button>

                </form>

            @endif


            {{-- Back --}}
            <a
                href="{{ route(
                    'admin.projects.budget.index',
                    [
                        'project' =>
                            $project->id
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Budgets
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if(session('info'))

        <div class="alert alert-info">
            {{ session('info') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Budget Understanding Overview --}}
    {{-- ========================================================= --}}

    @php
        $budgetCurrency = $projectBudget->currency ?? '';
        $approvedBudget = (float) ($projectBudget->total_budget ?? 0);
        $lineItemAllocated = (float) $projectBudget->items->sum('estimated_amount');
        $totalCommitted = (float) $projectBudget->items->sum(
            fn ($budgetItem) => (float) ($budgetItem->total_committed_amount ?? 0)
        );
        $availableBudget = $approvedBudget - $totalCommitted;
        $allocationDifference = $approvedBudget - $lineItemAllocated;
        $overallUtilization = $approvedBudget > 0
            ? ($totalCommitted / $approvedBudget) * 100
            : 0;
        $allocationPercentage = $approvedBudget > 0
            ? ($lineItemAllocated / $approvedBudget) * 100
            : 0;
    @endphp

    <div class="card hero-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="small muted-white text-uppercase fw-semibold mb-2">Budget at a glance</div>
                    <h4 class="mb-2">{{ $projectBudget->title }}</h4>
                    <div class="muted-white small">
                        This overview explains how much is approved, allocated, committed, and still available.
                    </div>
                </div>
                <div class="text-end">
                    <div class="small muted-white">Current status</div>
                    <span class="badge bg-light text-dark mt-1">{{ $projectBudget->status }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Approved Budget</div>
                    <div class="summary-number mt-2">{{ $budgetCurrency }} {{ number_format($approvedBudget, 2) }}</div>
                    <div class="summary-caption">Maximum approved budget for this version</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Allocated to Line Items</div>
                    <div class="summary-number mt-2">{{ $budgetCurrency }} {{ number_format($lineItemAllocated, 2) }}</div>
                    <div class="summary-caption">{{ number_format($allocationPercentage, 2) }}% of approved budget</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Committed</div>
                    <div class="summary-number mt-2">{{ $budgetCurrency }} {{ number_format($totalCommitted, 2) }}</div>
                    <div class="summary-caption">{{ number_format($overallUtilization, 2) }}% of approved budget</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card metric-card h-100 border-{{ $availableBudget < 0 ? 'danger' : 'success' }}">
                <div class="card-body">
                    <div class="metric-label">Available Budget</div>
                    <div class="summary-number mt-2 {{ $availableBudget < 0 ? 'number-negative' : 'number-positive' }}">
                        {{ $budgetCurrency }} {{ number_format($availableBudget, 2) }}
                    </div>
                    <div class="summary-caption">Approved budget minus commitments</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card reading-guide mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-information-line text-primary fs-5"></i>
                <div>
                    <strong>How to read this page</strong>
                    <div class="text-muted small mt-1">
                        <strong>Approved Budget</strong> is the approved ceiling.
                        <strong>Allocated</strong> is the sum of all budget line items.
                        <strong>Committed</strong> is the amount already linked to procurement, contracts, work orders, variations, land acquisition, and other connected costs.
                        <strong>Available</strong> is the approved budget minus committed amounts.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Budget Reconciliation</strong>
                    <div class="text-muted small">Compare the approved budget with line-item allocation and actual commitments.</div>
                </div>
                @if(abs($allocationDifference) < 0.01)
                    <span class="badge bg-success status-pill">Fully Allocated</span>
                @elseif($allocationDifference > 0)
                    <span class="badge bg-warning text-dark status-pill">Partially Allocated</span>
                @else
                    <span class="badge bg-danger status-pill">Over Allocated</span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered reconciliation-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Measure</th>
                            <th class="text-end">Amount</th>
                            <th>Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Approved Budget</td>
                            <td class="text-end fw-semibold">{{ $budgetCurrency }} {{ number_format($approvedBudget, 2) }}</td>
                            <td class="text-muted">Approved financial ceiling</td>
                        </tr>
                        <tr>
                            <td>Line Item Allocation</td>
                            <td class="text-end fw-semibold">{{ $budgetCurrency }} {{ number_format($lineItemAllocated, 2) }}</td>
                            <td class="text-muted">Amount distributed across budget items</td>
                        </tr>
                        <tr>
                            <td>Unallocated Amount</td>
                            <td class="text-end {{ $allocationDifference < 0 ? 'number-negative' : 'number-positive' }}">{{ $budgetCurrency }} {{ number_format($allocationDifference, 2) }}</td>
                            <td class="text-muted">Approved budget minus line-item allocation</td>
                        </tr>
                        <tr>
                            <td>Total Commitments</td>
                            <td class="text-end fw-semibold">{{ $budgetCurrency }} {{ number_format($totalCommitted, 2) }}</td>
                            <td class="text-muted">Linked costs across project modules</td>
                        </tr>
                        <tr>
                            <td>Remaining Approved Budget</td>
                            <td class="text-end {{ $availableBudget < 0 ? 'number-negative' : 'number-positive' }}">{{ $budgetCurrency }} {{ number_format($availableBudget, 2) }}</td>
                            <td class="text-muted">Approved budget minus total commitments</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- Budget Status --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                {{-- Version --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Version
                    </div>

                    <div class="fs-5 fw-semibold mt-1">
                        V{{ $projectBudget->version_number }}
                    </div>

                </div>


                {{-- Budget Number --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget Number
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->budget_number }}
                    </div>

                </div>


                {{-- Type --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Type
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->budget_type }}
                    </div>

                </div>


                {{-- Currency --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->currency }}
                    </div>

                </div>


                {{-- Status --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-1">

                        @switch($projectBudget->status)

                            @case('Approved')

                                <span class="badge bg-success fs-6">
                                    Approved
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-info text-dark fs-6">
                                    Submitted
                                </span>

                                @break

                            @case('Under Review')

                                <span class="badge bg-warning text-dark fs-6">
                                    Under Review
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary fs-6">
                                    {{ $projectBudget->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Budget Composition --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Direct Cost
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->direct_cost,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Indirect Cost
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->indirect_cost,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contingency
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->contingency_amount,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-primary h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Budget
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->total_budget,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- Budget Utilization Overview --}}
    {{-- ========================================================= --}}

    @php
        $overviewEstimated = (float) $projectBudget->items->sum(function ($item) {
            return (float) $item->estimated_amount;
        });

        $overviewCommitted = (float) $projectBudget->items->sum(function ($item) {
            return (float) $item->total_committed_amount;
        });

        $overviewRemaining = $overviewEstimated - $overviewCommitted;
        $overviewUtilization = $overviewEstimated > 0
            ? ($overviewCommitted / $overviewEstimated) * 100
            : 0;

        $totalBudget = (float) $projectBudget->total_budget;
        $totalBudgetRemaining = $totalBudget - $overviewCommitted;
        $totalBudgetUtilization = $totalBudget > 0
            ? ($overviewCommitted / $totalBudget) * 100
            : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title">Budget Utilization Overview</div>
                            <div class="section-subtitle">How much of the budget is currently committed</div>
                        </div>
                        <span class="badge {{ $totalBudgetUtilization > 100 ? 'bg-danger' : 'bg-success' }}">
                            {{ number_format($totalBudgetUtilization, 2) }}% Used
                        </span>
                    </div>

                    <div class="progress mb-3">
                        <div
                            class="progress-bar {{ $totalBudgetUtilization > 100 ? 'bg-danger' : 'bg-success' }}"
                            style="width: {{ min(100, max(0, $totalBudgetUtilization)) }}%"
                        ></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="metric-label">Total Committed</div>
                            <div class="fw-bold text-primary">
                                {{ $projectBudget->currency }} {{ number_format($overviewCommitted, 2) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metric-label">Remaining Budget</div>
                            <div class="fw-bold {{ $totalBudgetRemaining < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $projectBudget->currency }} {{ number_format($totalBudgetRemaining, 2) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metric-label">Approved Total Budget</div>
                            <div class="fw-bold">
                                {{ $projectBudget->currency }} {{ number_format($totalBudget, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="section-title mb-3">Line Item Reconciliation</div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="metric-label">Line Item Estimate</div>
                            <div class="metric-value" style="font-size:1.05rem;">
                                {{ $projectBudget->currency }} {{ number_format($overviewEstimated, 2) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-label">Line Item Commitments</div>
                            <div class="metric-value" style="font-size:1.05rem;">
                                {{ $projectBudget->currency }} {{ number_format($overviewCommitted, 2) }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Line-item utilization</span>
                        <strong class="{{ $overviewUtilization > 100 ? 'text-danger' : 'text-primary' }}">
                            {{ number_format($overviewUtilization, 2) }}%
                        </strong>
                    </div>
                    <div class="progress mt-2">
                        <div
                            class="progress-bar {{ $overviewUtilization > 100 ? 'bg-danger' : 'bg-primary' }}"
                            style="width: {{ min(100, max(0, $overviewUtilization)) }}%"
                        ></div>
                    </div>
                    @if(abs($totalBudget - $overviewEstimated) > 0.01)
                        <div class="alert alert-warning py-2 px-3 mt-3 mb-0 small">
                            <strong>Note:</strong> Header budget and line-item total are different.
                        </div>
                    @else
                        <div class="alert alert-success py-2 px-3 mt-3 mb-0 small">
                            Header budget and line-item total are reconciled.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="explanation-box p-3 mb-4">
        <div class="fw-semibold mb-1">How to read this page</div>
        <div class="small">
            <strong>Total Budget</strong> is the approved project budget. 
            <strong>Committed Amount</strong> is the sum of linked commitments from procurement, contracts,
            work orders, approved variations, other construction costs, land acquisition, and design changes.
            <strong>Remaining Budget</strong> is the budget left after commitments.
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- Budget Period --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Budget Period & Approval</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget Start
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->budget_start_date
                                ? $projectBudget
                                    ->budget_start_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget End
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->budget_end_date
                                ? $projectBudget
                                    ->budget_end_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->approved_date
                                ? $projectBudget
                                    ->approved_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved By
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->approved_by
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Categories --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Budget Categories
                    </strong>

                    <div class="text-muted small">
                        Major cost groupings
                    </div>

                </div>

                <span class="badge bg-secondary">
                    {{ $projectBudget->categories->count() }}
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($projectBudget->status !== 'Approved')

                <div class="border rounded p-3 mb-4">

                    <h6 class="mb-3">
                        Add Budget Category
                    </h6>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.budget.categories.store',
                            [
                                'project' =>
                                    $project->id,

                                'projectBudget' =>
                                    $projectBudget->id,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="row">

                            <div class="col-md-2 mb-3">

                                <label
                                    for="category_code"
                                    class="form-label"
                                >
                                    Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="category_code"
                                    id="category_code"
                                    class="form-control"
                                    placeholder="C01"
                                    required
                                >

                            </div>


                            <div class="col-md-5 mb-3">

                                <label
                                    for="category_name"
                                    class="form-label"
                                >
                                    Category Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="category_name"
                                    id="category_name"
                                    class="form-control"
                                    placeholder="Construction"
                                    required
                                >

                            </div>


                            <div class="col-md-2 mb-3">

                                <label
                                    for="category_sequence"
                                    class="form-label"
                                >
                                    Sequence
                                </label>

                                <input
                                    type="number"
                                    name="sequence"
                                    id="category_sequence"
                                    class="form-control"
                                    value="{{
                                        ($projectBudget
                                            ->categories
                                            ->max('sequence')
                                            ?? 0) + 1
                                    }}"
                                    min="0"
                                >

                            </div>


                            <div class="col-md-3 mb-3 d-flex align-items-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    + Add Category
                                </button>

                            </div>

                        </div>


                        <div>

                            <label
                                for="category_description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="category_description"
                                rows="2"
                                class="form-control"
                            ></textarea>

                        </div>

                    </form>

                </div>

            @endif


            @if(
                $projectBudget->categories->count()
            )

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle mb-0"
                    >

                        <thead>

                            <tr>

                                <th style="width:80px;">
                                    #
                                </th>

                                <th style="width:120px;">
                                    Code
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Items
                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th style="width:160px;">
                                        Action
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $projectBudget->categories
                                as $category
                            )

                                <tr>

                                    <td>
                                        {{ $category->sequence }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->category_code }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $category->category_name }}
                                    </td>

                                    <td>
                                        {{ $category->description ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $category->items->count() }}
                                    </td>


                                    @if(
                                        $projectBudget->status !== 'Approved'
                                    )

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.projects.budget.categories.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'category' =>
                                                            $category->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.budget.categories.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'category' =>
                                                            $category->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this budget category?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </td>

                                    @endif

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4 text-muted">

                    No budget categories have been added yet.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Budget Items --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Detailed Budget Allocation & Commitments
                    </strong>

                    <div class="text-muted small">
                        Review each cost item, its linked commitments, and the amount still available
                    </div>

                </div>

                <span class="badge bg-secondary">
                    {{ $projectBudget->items->count() }}
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($projectBudget->status !== 'Approved')

                <div class="border rounded p-3 mb-4">

                    <h6 class="mb-3">
                        Add Budget Item
                    </h6>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.budget.items.store',
                            [
                                'project' =>
                                    $project->id,

                                'projectBudget' =>
                                    $projectBudget->id,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="row">

                            {{-- Code --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="item_code"
                                    class="form-label"
                                >
                                    Item Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="item_code"
                                    id="item_code"
                                    class="form-control"
                                    placeholder="C01.01"
                                    required
                                >

                            </div>


                            {{-- Name --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="item_name"
                                    class="form-label"
                                >
                                    Item Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="item_name"
                                    id="item_name"
                                    class="form-control"
                                    placeholder="Civil Works"
                                    required
                                >

                            </div>


                            {{-- Category --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="project_budget_category_id"
                                    class="form-label"
                                >
                                    Category
                                </label>

                                <select
                                    name="project_budget_category_id"
                                    id="project_budget_category_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Select Category --
                                    </option>

                                    @foreach(
                                        $projectBudget->categories
                                        as $category
                                    )

                                        <option
                                            value="{{ $category->id }}"
                                        >
                                            {{ $category->category_code }}
                                            -
                                            {{ $category->category_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Cost Type --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="cost_type"
                                    class="form-label"
                                >
                                    Cost Type
                                </label>

                                <select
                                    name="cost_type"
                                    id="cost_type"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Select --
                                    </option>

                                    <option value="Direct">
                                        Direct
                                    </option>

                                    <option value="Indirect">
                                        Indirect
                                    </option>

                                    <option value="Contingency">
                                        Contingency
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div class="row">

                            {{-- Parent --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="parent_item_id"
                                    class="form-label"
                                >
                                    Parent Item
                                </label>

                                <select
                                    name="parent_item_id"
                                    id="parent_item_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Top Level --
                                    </option>

                                    @foreach(
                                        $projectBudget->items
                                        as $parentItem
                                    )

                                        <option
                                            value="{{ $parentItem->id }}"
                                        >
                                            {{ $parentItem->item_code }}
                                            -
                                            {{ $parentItem->item_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Quantity --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="quantity"
                                    class="form-label"
                                >
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control"
                                    min="0"
                                    step="0.0001"
                                    value="0"
                                >

                            </div>


                            {{-- Unit --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="unit"
                                    class="form-label"
                                >
                                    Unit
                                </label>

                                <input
                                    type="text"
                                    name="unit"
                                    id="unit"
                                    class="form-control"
                                    placeholder="m²"
                                >

                            </div>


                            {{-- Rate --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="unit_rate"
                                    class="form-label"
                                >
                                    Unit Rate
                                </label>

                                <input
                                    type="number"
                                    name="unit_rate"
                                    id="unit_rate"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                >

                            </div>


                            {{-- Amount --}}

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    Amount
                                </label>

                                <input
                                    type="text"
                                    id="estimated_amount_preview"
                                    class="form-control bg-light"
                                    value="0.00"
                                    readonly
                                >

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-2 mb-3">

                                <label
                                    for="item_sequence"
                                    class="form-label"
                                >
                                    Sequence
                                </label>

                                <input
                                    type="number"
                                    name="sequence"
                                    id="item_sequence"
                                    class="form-control"
                                    value="{{
                                        ($projectBudget
                                            ->items
                                            ->max('sequence')
                                            ?? 0) + 1
                                    }}"
                                    min="0"
                                >

                            </div>


                            <div class="col-md-10 mb-3">

                                <label
                                    for="item_remarks"
                                    class="form-label"
                                >
                                    Remarks
                                </label>

                                <input
                                    type="text"
                                    name="remarks"
                                    id="item_remarks"
                                    class="form-control"
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            + Add Budget Item
                        </button>

                    </form>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- Items Table --}}
            {{-- ================================================= --}}

            @if(
                $projectBudget->items->count()
            )

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle mb-0 line-item-table"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Budget Item
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th>
                                    Unit Rate
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Cost Type
                                </th>

                                <th class="text-end">
                                    Committed Amount
                                </th>

                                <th class="text-end">
                                    Remaining Budget
                                </th>

                                <th style="min-width:180px;">
                                    Utilization
                                </th>

                                <th>
                                    Status
                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th>
                                        Action
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $projectBudget->items
                                as $item
                            )

                                <tr>

                                    <td>
                                        {{ $item->sequence }}
                                    </td>


                                    <td>
                                        <strong>
                                            {{ $item->item_code }}
                                        </strong>
                                    </td>


                                    <td>

                                        <div
                                            style="
                                                padding-left:
                                                {{ $item->parent_item_id
                                                    ? '20px'
                                                    : '0'
                                                }};
                                            "
                                        >

                                            @if(
                                                $item->parent_item_id
                                            )

                                                <span class="text-muted">
                                                    ↳
                                                </span>

                                            @endif

                                            {{ $item->item_name }}

                                        </div>

                                    </td>


                                    <td>

                                        @if($item->category)

                                            {{ $item->category->category_code }}
                                            -
                                            {{ $item->category->category_name }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            $item->quantity !== null
                                        )

                                            {{
                                                rtrim(
                                                    rtrim(
                                                        number_format(
                                                            $item->quantity,
                                                            4,
                                                            '.',
                                                            ''
                                                        ),
                                                        '0'
                                                    ),
                                                    '.'
                                                )
                                            }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>
                                        {{ $item->unit ?? '-' }}
                                    </td>


                                    <td>

                                        {{
                                            number_format(
                                                $item->unit_rate ?? 0,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{
                                                number_format(
                                                    $item->estimated_amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>


                                    <td>
                                        {{ $item->cost_type ?? '-' }}
                                    </td>

                                    {{-- Committed Amount --}}
                                    <td class="text-end">
                                        <strong>
                                            {{ $projectBudget->currency }}
                                            {{ number_format($item->total_committed_amount ?? 0, 2) }}
                                        </strong>
                                    </td>

                                    {{-- Remaining Budget --}}
                                    <td class="text-end">
                                        <strong class="{{ ($item->remaining_budget_amount ?? 0) < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $projectBudget->currency }}
                                            {{ number_format($item->remaining_budget_amount ?? 0, 2) }}
                                        </strong>
                                    </td>

                                    {{-- Utilization --}}
                                    <td>
                                        @php
                                            $utilization = (float) ($item->budget_utilization_percentage ?? 0);
                                            $progress = min(max($utilization, 0), 100);
                                        @endphp

                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>{{ number_format($utilization, 2) }}%</span>
                                            <span class="text-muted">
                                                {{ number_format($item->total_committed_amount ?? 0, 2) }}
                                                /
                                                {{ number_format($item->estimated_amount ?? 0, 2) }}
                                            </span>
                                        </div>

                                        <div class="progress" style="height:8px;">
                                            <div
                                                class="progress-bar
                                                    {{ $utilization > 100 ? 'bg-danger' : ($utilization >= 90 ? 'bg-warning' : ($utilization >= 75 ? 'bg-info' : 'bg-success')) }}"
                                                role="progressbar"
                                                style="width: {{ $progress }}%;"
                                                aria-valuenow="{{ $utilization }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @php
                                            $utilizationStatus = $item->utilization_status ?? 'Healthy';
                                        @endphp

                                        <span class="badge
                                            {{ $utilizationStatus === 'Over Budget'
                                                ? 'bg-danger'
                                                : ($utilizationStatus === 'Critical'
                                                    ? 'bg-warning text-dark'
                                                    : ($utilizationStatus === 'Warning'
                                                        ? 'bg-info text-dark'
                                                        : 'bg-success')) }}">
                                            {{ $utilizationStatus }}
                                        </span>
                                    </td>


                                    @if(
                                        $projectBudget->status !== 'Approved'
                                    )

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.projects.budget.items.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'item' =>
                                                            $item->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.budget.items.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'item' =>
                                                            $item->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this budget item?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </td>

                                    @endif

                                </tr>

                            @endforeach

                        </tbody>


                        {{-- ================================================= --}}
                        {{-- Items Total --}}
                        {{-- ================================================= --}}

                        <tfoot>

                            <tr>

                                <th
                                    colspan="7"
                                    class="text-end"
                                >
                                    Line Item Total
                                </th>

                                <th>
                                    {{
                                        number_format(
                                            $projectBudget
                                                ->items
                                                ->sum('estimated_amount'),
                                            2
                                        )
                                    }}
                                </th>

                                <th>
                                    {{
                                        number_format(
                                            $projectBudget
                                                ->items
                                                ->sum(fn ($budgetItem) => $budgetItem->total_committed_amount ?? 0),
                                            2
                                        )
                                    }}
                                </th>

                                <th>
                                    {{
                                        number_format(
                                            $projectBudget
                                                ->items
                                                ->sum(fn ($budgetItem) => $budgetItem->remaining_budget_amount ?? 0),
                                            2
                                        )
                                    }}
                                </th>

                                <th>
                                    @php
                                        $totalEstimated = (float) $projectBudget->items->sum('estimated_amount');
                                        $totalCommitted = (float) $projectBudget->items->sum(
                                            fn ($budgetItem) => $budgetItem->total_committed_amount ?? 0
                                        );
                                        $totalUtilization = $totalEstimated > 0
                                            ? ($totalCommitted / $totalEstimated) * 100
                                            : 0;
                                    @endphp

                                    <strong>{{ number_format($totalUtilization, 2) }}%</strong>
                                </th>

                                <th>
                                    <span class="badge
                                        {{ $totalUtilization > 100
                                            ? 'bg-danger'
                                            : ($totalUtilization >= 90
                                                ? 'bg-warning text-dark'
                                                : ($totalUtilization >= 75
                                                    ? 'bg-info text-dark'
                                                    : 'bg-success')) }}">
                                        {{ $totalUtilization > 100
                                            ? 'Over Budget'
                                            : ($totalUtilization >= 90
                                                ? 'Critical'
                                                : ($totalUtilization >= 75
                                                    ? 'Warning'
                                                    : 'Healthy')) }}
                                    </span>
                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th></th>

                                @endif

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No Budget Items
                    </h6>

                    <p class="text-muted mb-0">

                        Add detailed budget items above.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

    @if($projectBudget->remarks)

        <div class="card mb-5">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($projectBudget->remarks)
                ) !!}

            </div>

        </div>

    @endif
    {{-- ========================================================= --}}
{{-- Reject Budget Modal --}}
{{-- ========================================================= --}}

@if(
    $projectBudget->status === 'Under Review'
)

    <div
        class="modal fade"
        id="rejectBudgetModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.budget.reject',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                >

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Reject Budget
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-warning">

                            Please provide the reason for rejecting
                            this budget.

                        </div>


                        <div class="mb-3">

                            <label
                                for="rejection_remarks"
                                class="form-label"
                            >
                                Rejection Reason
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="rejection_remarks"
                                id="rejection_remarks"
                                rows="5"
                                class="form-control"
                                placeholder="Enter rejection reason..."
                                required
                            ></textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            Reject Budget
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

</div>


{{-- ============================================================= --}}
{{-- Line Item Calculation --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const quantity =
            document.getElementById('quantity');

        const unitRate =
            document.getElementById('unit_rate');

        const preview =
            document.getElementById(
                'estimated_amount_preview'
            );


        function calculateItemAmount()
        {
            const qty =
                parseFloat(quantity.value) || 0;

            const rate =
                parseFloat(unitRate.value) || 0;

            const amount =
                qty * rate;


            preview.value =
                amount.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        if (
            quantity &&
            unitRate &&
            preview
        ) {

            quantity.addEventListener(
                'input',
                calculateItemAmount
            );

            unitRate.addEventListener(
                'input',
                calculateItemAmount
            );

            calculateItemAmount();

        }

    }
);

</script>

@endsection