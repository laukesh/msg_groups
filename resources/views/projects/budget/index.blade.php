@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget
            </div>

            <h3 class="mb-1">
                Project Budget
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
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
                    'admin.projects.budget.create',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                + Create Budget
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Flash Messages --}}
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
    {{-- Approved Budget --}}
    {{-- ========================================================= --}}

    @php

        $approvedBudget = $budgets
            ->firstWhere('status', 'Approved');

    @endphp


    @if($approvedBudget)

        <div class="card border-success mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        Current Approved Budget
                    </strong>

                    <span class="badge bg-success">
                        Approved
                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Budget Number --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Budget Number
                        </div>

                        <div class="fw-semibold mt-1">
                            {{ $approvedBudget->budget_number }}
                        </div>

                    </div>


                    {{-- Version --}}

                    <div class="col-md-2">

                        <div class="text-muted small">
                            Version
                        </div>

                        <div class="fw-semibold mt-1">
                            V{{ $approvedBudget->version_number }}
                        </div>

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-2">

                        <div class="text-muted small">
                            Currency
                        </div>

                        <div class="fw-semibold mt-1">
                            {{ $approvedBudget->currency }}
                        </div>

                    </div>


                    {{-- Total --}}

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Approved Budget
                        </div>

                        <div class="fs-4 fw-semibold mt-1">

                            {{ $approvedBudget->currency }}

                            {{ number_format(
                                $approvedBudget->total_budget,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- Action --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <a
                            href="{{ route(
                                'admin.projects.budget.show',
                                [
                                    'project' =>
                                        $project->id,

                                    'projectBudget' =>
                                        $approvedBudget->id,
                                ]
                            ) }}"
                            class="btn btn-outline-success w-100"
                        >
                            View
                        </a>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="alert alert-warning mb-4">

            <strong>
                No approved budget exists.
            </strong>

            Create and submit a budget for review and approval.

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Budget Version History --}}
    {{-- ========================================================= --}}

    <div class="card mb-5">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Budget Versions
                    </strong>

                    <div class="text-muted small">
                        Budget history and revision control
                    </div>

                </div>


                <span class="badge bg-secondary">

                    {{ $budgets->count() }}

                    {{ $budgets->count() === 1
                        ? 'Version'
                        : 'Versions'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($budgets->count())

                <div class="table-responsive">

                    <table
                        class="table table-hover table-bordered mb-0 align-middle"
                    >

                        <thead>

                            <tr>

                                <th style="width:80px;">
                                    Version
                                </th>

                                <th>
                                    Budget Number
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Budget Type
                                </th>

                                <th>
                                    Period
                                </th>

                                <th>
                                    Direct Cost
                                </th>

                                <th>
                                    Indirect Cost
                                </th>

                                <th>
                                    Contingency
                                </th>

                                <th>
                                    Total Budget
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="width:180px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($budgets as $budget)

                                <tr>

                                    {{-- Version --}}

                                    <td>

                                        <span class="fw-semibold">
                                            V{{ $budget->version_number }}
                                        </span>

                                        @if(
                                            $approvedBudget &&
                                            $budget->id ===
                                            $approvedBudget->id
                                        )

                                            <span class="badge bg-success ms-1">
                                                Current
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Number --}}

                                    <td>

                                        <span class="fw-semibold">
                                            {{ $budget->budget_number }}
                                        </span>

                                    </td>


                                    {{-- Title --}}

                                    <td>

                                        {{ $budget->title }}

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        {{ $budget->budget_type }}

                                    </td>


                                    {{-- Period --}}

                                    <td>

                                        @if(
                                            $budget->budget_start_date
                                        )

                                            {{
                                                $budget
                                                    ->budget_start_date
                                                    ->format('d M Y')
                                            }}

                                        @else

                                            -

                                        @endif


                                        <br>

                                        <span class="text-muted">
                                            to
                                        </span>

                                        <br>

                                        @if(
                                            $budget->budget_end_date
                                        )

                                            {{
                                                $budget
                                                    ->budget_end_date
                                                    ->format('d M Y')
                                            }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Direct Cost --}}

                                    <td>

                                        {{ $budget->currency }}

                                        <br>

                                        {{
                                            number_format(
                                                $budget->direct_cost,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Indirect Cost --}}

                                    <td>

                                        {{ $budget->currency }}

                                        <br>

                                        {{
                                            number_format(
                                                $budget->indirect_cost,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Contingency --}}

                                    <td>

                                        {{ $budget->currency }}

                                        <br>

                                        {{
                                            number_format(
                                                $budget->contingency_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Total --}}

                                    <td>

                                        <strong>

                                            {{ $budget->currency }}

                                            {{
                                                number_format(
                                                    $budget->total_budget,
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @switch($budget->status)

                                            @case('Approved')

                                                <span class="badge bg-success">
                                                    Approved
                                                </span>

                                                @break

                                            @case('Submitted')

                                                <span class="badge bg-info text-dark">
                                                    Submitted
                                                </span>

                                                @break

                                            @case('Under Review')

                                                <span class="badge bg-warning text-dark">
                                                    Under Review
                                                </span>

                                                @break

                                            @case('Rejected')

                                                <span class="badge bg-danger">
                                                    Rejected
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary">
                                                    {{ $budget->status }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Actions --}}

                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.budget.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $budget->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if(
                                                $budget->status !== 'Approved'
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.budget.edit',
                                                        [
                                                            'project' =>
                                                                $project->id,

                                                            'projectBudget' =>
                                                                $budget->id,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.budget.destroy',
                                                        [
                                                            'project' =>
                                                                $project->id,

                                                            'projectBudget' =>
                                                                $budget->id,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this budget version?');"
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

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        {{-- ================================================= --}}
                        {{-- Total --}}
                        {{-- ================================================= --}}

                        <tfoot>

                            <tr>

                                <th colspan="8" class="text-end">
                                    Latest Version Total:
                                </th>

                                <th>

                                    @if($budgets->first())

                                        {{ $budgets->first()->currency }}

                                        {{
                                            number_format(
                                                $budgets
                                                    ->first()
                                                    ->total_budget,
                                                2
                                            )
                                        }}

                                    @else

                                        -

                                    @endif

                                </th>

                                <th colspan="2"></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h5>
                        No Budget Created
                    </h5>

                    <p class="text-muted mb-4">

                        This project does not have a budget yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.budget.create',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First Budget
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Budget Lifecycle Information --}}
    {{-- ========================================================= --}}

    <div class="card mb-5">

        <div class="card-header">
            <strong>Budget Lifecycle</strong>
        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-2">

                    <div class="fw-semibold">
                        Draft
                    </div>

                    <small class="text-muted">
                        Preparation
                    </small>

                </div>


                <div class="col-md-1 d-flex align-items-center justify-content-center">
                    →
                </div>


                <div class="col-md-2">

                    <div class="fw-semibold">
                        Submitted
                    </div>

                    <small class="text-muted">
                        Submission
                    </small>

                </div>


                <div class="col-md-1 d-flex align-items-center justify-content-center">
                    →
                </div>


                <div class="col-md-2">

                    <div class="fw-semibold">
                        Under Review
                    </div>

                    <small class="text-muted">
                        Management Review
                    </small>

                </div>


                <div class="col-md-1 d-flex align-items-center justify-content-center">
                    →
                </div>


                <div class="col-md-2">

                    <div class="fw-semibold text-success">
                        Approved
                    </div>

                    <small class="text-muted">
                        Controlled Budget
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection