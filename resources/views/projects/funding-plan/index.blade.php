@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan
            </div>

            <h3 class="mb-1">
                Funding Plans
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.show',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Project
            </a>

            <a
                href="{{ route(
                    'admin.projects.funding-plan.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                + Create Funding Plan
            </a>

        </div>

    </div>


    {{-- Messages --}}
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


    {{-- Plans --}}
    <div class="card">

        <div class="card-header">
            <strong>Funding Plan Versions</strong>
        </div>

        <div class="card-body p-0">

            @if($fundingPlans->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Plan No.
                                </th>

                                <th>
                                    Version
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Basis Budget
                                </th>

                                <th>
                                    Requirement
                                </th>

                                <th>
                                    Planned
                                </th>

                                <th>
                                    Committed
                                </th>

                                <th>
                                    Funding Gap
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($fundingPlans as $plan)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $plan->funding_plan_number }}
                                        </strong>
                                    </td>


                                    <td>
                                        V{{ $plan->version_number }}
                                    </td>


                                    <td>
                                        {{ $plan->title }}
                                    </td>


                                    <td>

                                        @if($plan->basisBudget)

                                            {{ $plan->basisBudget->budget_number }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $plan->currency }}
                                        {{ number_format(
                                            $plan->total_funding_requirement,
                                            2
                                        ) }}
                                    </td>


                                    <td>
                                        {{ $plan->currency }}
                                        {{ number_format(
                                            $plan->total_planned_funding,
                                            2
                                        ) }}
                                    </td>


                                    <td>
                                        {{ $plan->currency }}
                                        {{ number_format(
                                            $plan->total_committed_funding,
                                            2
                                        ) }}
                                    </td>


                                    <td>

                                        @if(
                                            $plan->funding_gap > 0
                                        )

                                            <span class="text-danger fw-semibold">
                                                {{ $plan->currency }}
                                                {{ number_format(
                                                    $plan->funding_gap,
                                                    2
                                                ) }}
                                            </span>

                                        @else

                                            <span class="text-success fw-semibold">
                                                No Gap
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @php

                                            $badgeClass = match(
                                                $plan->status
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
                                            class="badge {{ $badgeClass }}"
                                        >
                                            {{ $plan->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.funding-plan.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'fundingPlan' =>
                                                        $plan->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                        @if(
                                            $plan->status !== 'Approved'
                                        )

                                            <a
                                                href="{{ route(
                                                    'admin.projects.funding-plan.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'fundingPlan' =>
                                                            $plan->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-5 text-center">

                    <h5>
                        No Funding Plan
                    </h5>

                    <p class="text-muted mb-3">
                        Create the first funding plan for this project.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.funding-plan.create',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create Funding Plan
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection