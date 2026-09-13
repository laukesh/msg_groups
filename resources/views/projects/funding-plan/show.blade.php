@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan
            </div>

            <h3 class="mb-1">
                {{ $fundingPlan->title }}
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

            </div>

            <div class="mt-2">

                <span class="badge bg-secondary">
                    {{ $fundingPlan->funding_plan_number }}
                </span>

                <span class="badge bg-info text-dark">
                    V{{ $fundingPlan->version_number }}
                </span>

                @php

                    $badgeClass = match($fundingPlan->status) {

                        'Approved'
                            => 'bg-success',

                        'Submitted',
                        'Under Review'
                            => 'bg-warning text-dark',

                        'Rejected'
                            => 'bg-danger',

                        default
                            => 'bg-secondary',

                    };

                @endphp

                <span class="badge {{ $badgeClass }}">
                    {{ $fundingPlan->status }}
                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Header Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.funding-plan.index',
                    [
                        'project' => $project->id
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Funding Plans
            </a>


            {{-- Draft --}}

            @if($fundingPlan->status === 'Draft')

                <a
                    href="{{ route(
                        'admin.projects.funding-plan.edit',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.funding-plan.submit',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Submit this Funding Plan for review?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Submit for Review
                    </button>

                </form>

            @endif


            {{-- Under Review --}}

            @if($fundingPlan->status === 'Under Review')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.funding-plan.approve',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Approve this Funding Plan?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Approve
                    </button>

                </form>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.funding-plan.reject',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Reject this Funding Plan?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject
                    </button>

                </form>

            @endif


            {{-- Rejected --}}

            @if($fundingPlan->status === 'Rejected')

                <a
                    href="{{ route(
                        'admin.projects.funding-plan.edit',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit & Resubmit
                </a>

            @endif


            {{-- Approved --}}

            @if($fundingPlan->status === 'Approved')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.funding-plan.revision',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="d-inline"
                    onsubmit="return confirm(
                        'Create a new revision from this approved Funding Plan?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Create Revision
                    </button>

                </form>

            @endif

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


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Funding Summary --}}
    {{-- ========================================================= --}}

    <div class="row mb-4">

        {{-- Requirement --}}

        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Funding Requirement
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $fundingPlan->total_funding_requirement,
                                2
                            )
                        }}

                    </div>

                    @if($fundingPlan->basisBudget)

                        <div class="text-muted small mt-2">

                            Based on
                            {{ $fundingPlan->basisBudget->budget_number }}

                            V{{ $fundingPlan->basisBudget->version_number }}

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Planned --}}

        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Planned Funding
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $fundingPlan->total_planned_funding,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Committed --}}

        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Committed Funding
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $fundingPlan->total_committed_funding,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Gap --}}

        <div class="col-md-3 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Funding Gap
                    </div>

                    @if($fundingPlan->funding_gap > 0)

                        <div class="fs-4 fw-semibold text-danger mt-1">

                            {{ $fundingPlan->currency }}

                            {{
                                number_format(
                                    $fundingPlan->funding_gap,
                                    2
                                )
                            }}

                        </div>

                    @else

                        <div class="fs-4 fw-semibold text-success mt-1">
                            No Gap
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Progress --}}
    {{-- ========================================================= --}}

    @php

        $requirement =
            (float) $fundingPlan->total_funding_requirement;

        $committed =
            (float) $fundingPlan->total_committed_funding;

        $percentage =
            $requirement > 0
                ? min(
                    100,
                    ($committed / $requirement) * 100
                )
                : 0;

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Funding Coverage
            </strong>

        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span>
                    Committed Funding
                </span>

                <strong>
                    {{ number_format($percentage, 2) }}%
                </strong>

            </div>


            <div
                class="progress"
                style="height: 20px;"
            >

                <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: {{ $percentage }}%;"
                    aria-valuenow="{{ $percentage }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    {{ number_format($percentage, 1) }}%
                </div>

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Funding Gap Analysis --}}
    {{-- ========================================================= --}}

    @php

        $requirement = (float)
            $fundingPlan->total_funding_requirement;

        $planned = (float)
            $fundingPlan->total_planned_funding;

        $committed = (float)
            $fundingPlan->total_committed_funding;

        $plannedGap = max(
            $requirement - $planned,
            0
        );

        $committedGap = max(
            $requirement - $committed,
            0
        );

        $excessPlanned = max(
            $planned - $requirement,
            0
        );

        $excessCommitted = max(
            $committed - $requirement,
            0
        );

    @endphp


    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Funding Gap Analysis
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Requirement --}}

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Total Requirement
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($requirement, 2) }}

                    </div>

                </div>


                {{-- Planned --}}

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Planned Funding
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($planned, 2) }}

                    </div>

                </div>


                {{-- Committed --}}

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Committed Funding
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($committed, 2) }}

                    </div>

                </div>


                {{-- Gap --}}

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Current Funding Gap
                    </div>

                    @if($committedGap > 0)

                        <div class="fs-5 fw-semibold text-danger">

                            {{ $fundingPlan->currency }}

                            {{ number_format($committedGap, 2) }}

                        </div>

                    @else

                        <div class="fs-5 fw-semibold text-success">

                            Fully Covered

                        </div>

                    @endif

                </div>

            </div>


            <hr>


            {{-- Planned Coverage --}}

            <div class="mb-4">

                <div class="d-flex justify-content-between mb-1">

                    <span class="small">
                        Planned Funding Coverage
                    </span>

                    <span class="small fw-semibold">

                        {{
                            $requirement > 0
                                ? number_format(
                                    min(
                                        ($planned / $requirement) * 100,
                                        100
                                    ),
                                    2
                                )
                                : 0
                        }}%

                    </span>

                </div>


                <div
                    class="progress"
                    style="height: 18px;"
                >

                    <div
                        class="progress-bar"
                        style="
                            width:
                            {{
                                $requirement > 0
                                    ? min(
                                        ($planned / $requirement) * 100,
                                        100
                                    )
                                    : 0
                            }}%;
                        "
                    ></div>

                </div>

            </div>


            {{-- Committed Coverage --}}

            <div class="mb-3">

                <div class="d-flex justify-content-between mb-1">

                    <span class="small">
                        Committed Funding Coverage
                    </span>

                    <span class="small fw-semibold">

                        {{
                            $requirement > 0
                                ? number_format(
                                    min(
                                        ($committed / $requirement) * 100,
                                        100
                                    ),
                                    2
                                )
                                : 0
                        }}%

                    </span>

                </div>


                <div
                    class="progress"
                    style="height: 18px;"
                >

                    <div
                        class="progress-bar bg-success"
                        style="
                            width:
                            {{
                                $requirement > 0
                                    ? min(
                                        ($committed / $requirement) * 100,
                                        100
                                    )
                                    : 0
                            }}%;
                        "
                    ></div>

                </div>

            </div>


            {{-- Gap message --}}

            @if($committedGap > 0)

                <div class="alert alert-danger mb-0">

                    <strong>
                        Funding Gap Exists
                    </strong>

                    <div class="mt-1">

                        Additional funding of

                        <strong>

                            {{ $fundingPlan->currency }}

                            {{ number_format(
                                $committedGap,
                                2
                            ) }}

                        </strong>

                        is required to fully cover the funding requirement.

                    </div>

                </div>

            @elseif($excessCommitted > 0)

                <div class="alert alert-warning mb-0">

                    Committed funding exceeds the funding requirement by

                    <strong>

                        {{ $fundingPlan->currency }}

                        {{ number_format(
                            $excessCommitted,
                            2
                        ) }}

                    </strong>.

                </div>

            @else

                <div class="alert alert-success mb-0">

                    <strong>
                        Funding Fully Covered
                    </strong>

                    <div class="mt-1">
                        The committed funding covers the complete
                        funding requirement.
                    </div>

                </div>

            @endif

        </div>

    </div>

    @php

        $requirement =
            (float) $fundingPlan->total_funding_requirement;

        $planned =
            $fundingPlan->calculated_planned_funding;

        $committed =
            $fundingPlan->calculated_committed_funding;

        $actual =
            $fundingPlan->calculated_actual_funding;

        $plannedGap =
            $fundingPlan->planned_funding_gap;

        $committedGap =
            $fundingPlan->committed_funding_gap;

        $actualGap =
            $fundingPlan->actual_funding_gap;

    @endphp

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-warning h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Planned Funding Gap
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($plannedGap, 2) }}

                    </div>

                    <div class="small text-muted mt-1">
                        Requirement minus planned funding
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-danger h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Commitment Gap
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($committedGap, 2) }}

                    </div>

                    <div class="small text-muted mt-1">
                        Requirement minus committed funding
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-success h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Actual Funding Gap
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{ number_format($actualGap, 2) }}

                    </div>

                    <div class="small text-muted mt-1">
                        Requirement minus actual received funding
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Basis Budget --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Budget Basis</strong>
        </div>

        <div class="card-body">

            @if($fundingPlan->basisBudget)

                <div class="row">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Budget Number
                        </div>

                        <div class="fw-semibold">
                            {{ $fundingPlan->basisBudget->budget_number }}
                        </div>

                    </div>


                    <div class="col-md-2">

                        <div class="text-muted small">
                            Version
                        </div>

                        <div class="fw-semibold">
                            V{{ $fundingPlan->basisBudget->version_number }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Budget Total
                        </div>

                        <div class="fw-semibold">

                            {{ $fundingPlan->basisBudget->currency }}

                            {{
                                number_format(
                                    $fundingPlan->basisBudget->total_budget,
                                    2
                                )
                            }}

                        </div>

                    </div>


                    <div class="col-md-2">

                        <div class="text-muted small">
                            Budget Status
                        </div>

                        <div>

                            <span class="badge bg-success">
                                {{ $fundingPlan->basisBudget->status }}
                            </span>

                        </div>

                    </div>


                    <div class="col-md-2 text-end">

                        <a
                            href="{{ route(
                                'admin.projects.budget.show',
                                [
                                    'project' => $project->id,
                                    'projectBudget' =>
                                        $fundingPlan->basisBudget->id,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View Budget
                        </a>

                    </div>

                </div>

            @else

                <div class="text-muted">
                    No basis budget linked.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Sources --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Funding Sources
            </strong>

            @if($fundingPlan->status !== 'Approved')

                <a
                    href="{{ route(
                        'admin.projects.funding-plan.sources.create',
                        [
                            'project' => $project->id,
                            'fundingPlan' => $fundingPlan->id,
                        ]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    + Add Funding Source
                </a>

            @endif

        </div>


        <div class="card-body p-0">

            @if($fundingPlan->sources->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Source
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Provider
                                </th>

                                <th class="text-end">
                                    Planned
                                </th>

                                <th class="text-end">
                                    Committed
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $fundingPlan->sources
                                as $source
                            )

                                <tr>

                                    <td>
                                        {{ $source->source_code }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $source->source_name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $source->source_type }}
                                    </td>

                                    <td>
                                        {{ $source->provider_name ?? '—' }}
                                    </td>

                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $source->planned_amount,
                                                2
                                            )
                                        }}

                                    </td>

                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $source->committed_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        @if($fundingPlan->status !== 'Approved')

                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.commitments.create',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' => $source->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                + Commitment
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.sources.edit',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' => $source->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.funding-plan.sources.destroy',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' => $source->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Delete this funding source?'
                                                );"
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

                                        @else

                                            <span class="text-muted small">
                                                Read Only
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center">

                    <div class="text-muted mb-2">
                        No funding sources have been added.
                    </div>

                    @if($fundingPlan->status !== 'Approved')

                        <div class="small text-muted">
                            Add equity, debt, investor,
                            promoter or other funding sources.
                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Commitments --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Funding Commitments
            </strong>

            <span class="text-muted small">
                {{ $fundingPlan->commitments->count() }}
                Commitment(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($fundingPlan->commitments->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Commitment No.
                                </th>

                                <th>
                                    Source
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end">
                                    Committed
                                </th>

                                <th class="text-end">
                                    Approved
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Tranches
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $fundingPlan->commitments
                                as $commitment
                            )

                                @php

                                    $trancheCount =
                                        $commitment->tranches->count();

                                    $tranchePlanned =
                                        $commitment
                                            ->tranches
                                            ->where(
                                                'status',
                                                '!=',
                                                'Cancelled'
                                            )
                                            ->sum(
                                                'planned_amount'
                                            );

                                    $trancheActual =
                                        $commitment
                                            ->tranches
                                            ->sum(
                                                'actual_amount'
                                            );

                                @endphp


                                <tr>

                                    <td>

                                        <strong>
                                            {{ $commitment->commitment_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        @if($commitment->source)

                                            {{ $commitment->source->source_name }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $commitment->commitment_date
                                                ? $commitment
                                                    ->commitment_date
                                                    ->format('d M Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $commitment->committed_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $commitment->approved_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        @php

                                            $commitmentBadge =
                                                match(
                                                    $commitment->status
                                                ) {

                                                    'Approved'
                                                        => 'bg-success',

                                                    'Submitted'
                                                        => 'bg-warning text-dark',

                                                    'Rejected',
                                                    'Cancelled'
                                                        => 'bg-danger',

                                                    default
                                                        => 'bg-secondary',

                                                };

                                        @endphp

                                        <span
                                            class="badge {{ $commitmentBadge }}"
                                        >
                                            {{ $commitment->status }}
                                        </span>

                                    </td>


                                    {{-- Tranche summary --}}

                                    <td class="text-center">

                                        @if($trancheCount > 0)

                                            <span class="badge bg-info text-dark">
                                                {{ $trancheCount }}
                                            </span>

                                            <div class="small text-muted mt-1">

                                                Planned:
                                                {{ $fundingPlan->currency }}

                                                {{
                                                    number_format(
                                                        $tranchePlanned,
                                                        2
                                                    )
                                                }}

                                            </div>

                                            @if($trancheActual > 0)

                                                <div class="small text-success">

                                                    Received:
                                                    {{ $fundingPlan->currency }}

                                                    {{
                                                        number_format(
                                                            $trancheActual,
                                                            2
                                                        )
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted small">
                                                No Tranches
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        @if($fundingPlan->status !== 'Approved')

                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.tranches.create',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' =>
                                                            $commitment->project_funding_source_id,
                                                        'fundingCommitment' =>
                                                            $commitment->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                + Tranche
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.commitments.edit',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' =>
                                                            $commitment->project_funding_source_id,
                                                        'fundingCommitment' =>
                                                            $commitment->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.funding-plan.commitments.destroy',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' =>
                                                            $commitment->project_funding_source_id,
                                                        'fundingCommitment' =>
                                                            $commitment->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Delete this commitment?'
                                                );"
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

                                        @else

                                            <span class="text-muted small">
                                                Read Only
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center text-muted">
                    No funding commitments recorded.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Tranches --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Funding Tranches
            </strong>

            <span class="text-muted small">
                {{ $fundingPlan->tranches->count() }}
                Tranche(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($fundingPlan->tranches->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Source
                                </th>

                                <th>
                                    Commitment
                                </th>

                                <th>
                                    Planned Date
                                </th>

                                <th class="text-end">
                                    Planned Amount
                                </th>

                                <th>
                                    Expected Date
                                </th>

                                <th class="text-end">
                                    Actual Amount
                                </th>

                                <th>
                                    Actual Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $fundingPlan->tranches
                                as $tranche
                            )

                                <tr>

                                    <td>
                                        {{ $tranche->tranche_number }}
                                    </td>


                                    <td>

                                        @if($tranche->source)

                                            {{ $tranche->source->source_name }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($tranche->commitment)

                                            {{ $tranche->commitment->commitment_number }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $tranche->planned_date
                                                ? $tranche
                                                    ->planned_date
                                                    ->format('d M Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $tranche->planned_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $tranche->expected_date
                                                ? $tranche
                                                    ->expected_date
                                                    ->format('d M Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{ $fundingPlan->currency }}

                                        {{
                                            number_format(
                                                $tranche->actual_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $tranche->actual_date
                                                ? $tranche
                                                    ->actual_date
                                                    ->format('d M Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        @php

                                            $trancheBadge =
                                                match(
                                                    $tranche->status
                                                ) {

                                                    'Received'
                                                        => 'bg-success',

                                                    'Expected'
                                                        => 'bg-info text-dark',

                                                    'Delayed'
                                                        => 'bg-warning text-dark',

                                                    'Cancelled'
                                                        => 'bg-danger',

                                                    default
                                                        => 'bg-secondary',

                                                };

                                        @endphp

                                        <span
                                            class="badge {{ $trancheBadge }}"
                                        >
                                            {{ $tranche->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        @if($fundingPlan->status !== 'Approved')

                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.tranches.edit',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' =>
                                                            $tranche->project_funding_source_id,
                                                        'fundingCommitment' =>
                                                            $tranche->project_funding_commitment_id,
                                                        'fundingTranche' =>
                                                            $tranche->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.funding-plan.tranches.destroy',
                                                    [
                                                        'project' => $project->id,
                                                        'fundingPlan' => $fundingPlan->id,
                                                        'fundingSource' =>
                                                            $tranche->project_funding_source_id,
                                                        'fundingCommitment' =>
                                                            $tranche->project_funding_commitment_id,
                                                        'fundingTranche' =>
                                                            $tranche->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Delete this funding tranche?'
                                                );"
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

                                        @else

                                            <span class="text-muted small">
                                                Read Only
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center text-muted">

                    No funding tranches planned.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Funding Totals by Tranche --}}
    {{-- ========================================================= --}}

    @php

        $totalTranchePlanned =
            $fundingPlan
                ->tranches
                ->where(
                    'status',
                    '!=',
                    'Cancelled'
                )
                ->sum('planned_amount');

        $totalTrancheActual =
            $fundingPlan
                ->tranches
                ->sum('actual_amount');

        $remainingTrancheFunding =
            max(
                (float) $fundingPlan->total_funding_requirement
                -
                (float) $totalTranchePlanned,
                0
            );

    @endphp


    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Tranche Planned
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $totalTranchePlanned,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Actual Funding Received
                    </div>

                    <div class="fs-5 fw-semibold text-success">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $totalTrancheActual,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Requirement Not Yet Planned
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $fundingPlan->currency }}

                        {{
                            number_format(
                                $remainingTrancheFunding,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

    @if($fundingPlan->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">
                {{ $fundingPlan->remarks }}
            </div>

        </div>

    @endif

    {{-- ========================================================= --}}
    {{-- Revision History --}}
    {{-- ========================================================= --}}

    @php

        $revisions = \App\Models\ProjectFundingPlan::where(
            'project_id',
            $project->id
        )
        ->where(
            'basis_budget_id',
            $fundingPlan->basis_budget_id
        )
        ->orderByDesc('version_number')
        ->get();

    @endphp


    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Funding Plan Revision History
            </strong>

            <span class="text-muted small">
                {{ $revisions->count() }} Version(s)
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Version
                            </th>

                            <th>
                                Funding Plan No.
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Effective Date
                            </th>

                            <th>
                                Approved Date
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($revisions as $revision)

                            <tr
                                class="{{
                                    $revision->id === $fundingPlan->id
                                        ? 'table-primary'
                                        : ''
                                }}"
                            >

                                <td>

                                    <strong>
                                        V{{ $revision->version_number }}
                                    </strong>

                                    @if(
                                        $revision->id ===
                                        $fundingPlan->id
                                    )

                                        <span class="badge bg-primary ms-1">
                                            Current
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $revision->funding_plan_number }}
                                </td>


                                <td>
                                    {{ $revision->title }}
                                </td>


                                <td>

                                    @php

                                        $revisionBadge =
                                            match(
                                                $revision->status
                                            ) {

                                                'Approved'
                                                    => 'bg-success',

                                                'Submitted',
                                                'Under Review'
                                                    => 'bg-warning text-dark',

                                                'Rejected'
                                                    => 'bg-danger',

                                                default
                                                    => 'bg-secondary',

                                            };

                                    @endphp

                                    <span
                                        class="badge {{ $revisionBadge }}"
                                    >
                                        {{ $revision->status }}
                                    </span>

                                </td>


                                <td>

                                    {{
                                        $revision->effective_date
                                            ? $revision
                                                ->effective_date
                                                ->format('d M Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $revision->approved_date
                                            ? $revision
                                                ->approved_date
                                                ->format('d M Y')
                                            : '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.funding-plan.show',
                                            [
                                                'project' =>
                                                    $project->id,

                                                'fundingPlan' =>
                                                    $revision->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted p-4"
                                >
                                    No revisions found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Approval & Activity History
            </strong>
        </div>

        <div class="card-body p-0">

            @if($fundingPlan->histories->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Remarks</th>
                                <th>User</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach(
                                $fundingPlan->histories
                                as $history
                            )

                                <tr>

                                    <td>
                                        {{
                                            $history->performed_at
                                                ? $history
                                                    ->performed_at
                                                    ->format(
                                                        'd M Y H:i'
                                                    )
                                                : '—'
                                        }}
                                    </td>

                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ $history->action }}
                                        </span>

                                    </td>

                                    <td>
                                        {{ $history->old_status ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $history->new_status ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $history->remarks ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $history->performed_by ?? '—' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-muted text-center">
                    No activity history recorded.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Revision / Read Only Notice --}}
    {{-- ========================================================= --}}

    @if($fundingPlan->status === 'Approved')

        <div class="alert alert-info">

            <strong>
                Approved Funding Plan
            </strong>

            <div class="mt-1">

                This Funding Plan is approved and is read-only.

                To make changes, create a new revision.

            </div>

        </div>

    @endif


</div>

@endsection