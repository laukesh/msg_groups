@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Approval Matrix
            </div>

            <h3 class="mb-1">
                Approval Matrix
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
                    'admin.projects.governance.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Governance
            </a>


            <a
                href="{{ route(
                    'admin.projects.approval-matrix.create',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Add Approval Rule
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $totalRules =
            $approvalMatrices->count();

        $activeRules =
            $approvalMatrices
                ->where('status', 'Active')
                ->count();

        $draftRules =
            $approvalMatrices
                ->where('status', 'Draft')
                ->count();

        $inactiveRules =
            $approvalMatrices
                ->where('status', 'Inactive')
                ->count();

        $mandatoryRules =
            $approvalMatrices
                ->where('is_mandatory', true)
                ->count();

        $multipleApprovalRules =
            $approvalMatrices
                ->where(
                    'requires_multiple_approvals',
                    true
                )
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Rules
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $totalRules }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}

        <div class="col-md-3">

            <div class="card h-100 border-success">

                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <div class="fs-3 fw-semibold text-success">
                        {{ $activeRules }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Mandatory --}}

        <div class="col-md-3">

            <div class="card h-100 border-primary">

                <div class="card-body">

                    <div class="text-muted small">
                        Mandatory
                    </div>

                    <div class="fs-3 fw-semibold text-primary">
                        {{ $mandatoryRules }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Multiple Approvals --}}

        <div class="col-md-3">

            <div class="card h-100 border-warning">

                <div class="card-body">

                    <div class="text-muted small">
                        Multiple Approvals
                    </div>

                    <div class="fs-3 fw-semibold text-warning">
                        {{ $multipleApprovalRules }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVAL MATRIX --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Approval Rules
                    </strong>

                    <div class="text-muted small mt-1">
                        Define approval authority, amount limits
                        and approval sequence.
                    </div>

                </div>


                <span class="text-muted small">

                    {{ $totalRules }}

                    {{ $totalRules === 1
                        ? 'rule'
                        : 'rules'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($approvalMatrices->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Sequence
                                </th>

                                <th>
                                    Approval Code
                                </th>

                                <th>
                                    Approval Type
                                </th>

                                <th>
                                    Authority
                                </th>

                                <th>
                                    Amount Range
                                </th>

                                <th>
                                    Governance
                                </th>

                                <th>
                                    Mandatory
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
                                $approvalMatrices
                                as $approvalMatrix
                            )

                                @php

                                    $statusClass =
                                        match(
                                            $approvalMatrix->status
                                        ) {

                                            'Active'
                                                => 'bg-success',

                                            'Draft'
                                                => 'bg-warning text-dark',

                                            'Inactive'
                                                => 'bg-secondary',

                                            default
                                                => 'bg-secondary',

                                        };

                                @endphp


                                <tr>

                                    {{-- Sequence --}}

                                    <td>

                                        <span
                                            class="badge bg-light text-dark border"
                                        >
                                            {{ $approvalMatrix->approval_sequence }}
                                        </span>

                                    </td>


                                    {{-- Code --}}

                                    <td>

                                        <strong>
                                            {{ $approvalMatrix->approval_code }}
                                        </strong>

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $approvalMatrix->approval_type }}
                                        </div>


                                        @if($approvalMatrix->description)

                                            <div class="text-muted small">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $approvalMatrix->description,
                                                    80
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Authority --}}

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $approvalMatrix->authority_role }}
                                        </div>


                                        @if($approvalMatrix->authorityUser)

                                            <div class="text-muted small">

                                                {{ $approvalMatrix->authorityUser->name }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Amount Range --}}

                                    <td>

                                        @if(
                                            $approvalMatrix->minimum_amount !== null ||
                                            $approvalMatrix->maximum_amount !== null
                                        )

                                            @if(
                                                $approvalMatrix->minimum_amount !== null
                                            )

                                                {{ $approvalMatrix->currency }}

                                                {{ number_format(
                                                    $approvalMatrix->minimum_amount,
                                                    2
                                                ) }}

                                            @else

                                                0.00

                                            @endif


                                            <span class="text-muted">
                                                –
                                            </span>


                                            @if(
                                                $approvalMatrix->maximum_amount !== null
                                            )

                                                {{ $approvalMatrix->currency }}

                                                {{ number_format(
                                                    $approvalMatrix->maximum_amount,
                                                    2
                                                ) }}

                                            @else

                                                No Limit

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                No amount limit
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Governance --}}

                                    <td>

                                        @if($approvalMatrix->governance)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'governance' =>
                                                            $approvalMatrix
                                                                ->governance
                                                                ->id,
                                                    ]
                                                ) }}"
                                            >

                                                {{ $approvalMatrix->governance->governance_number }}

                                            </a>

                                        @else

                                            <span class="text-muted">
                                                Not Linked
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Mandatory --}}

                                    <td>

                                        @if($approvalMatrix->is_mandatory)

                                            <span class="badge bg-danger">
                                                Yes
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark border">
                                                No
                                            </span>

                                        @endif


                                        @if(
                                            $approvalMatrix
                                                ->requires_multiple_approvals
                                        )

                                            <div class="mt-1">

                                                <span
                                                    class="badge bg-warning text-dark"
                                                >
                                                    Multiple

                                                </span>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $approvalMatrix->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <div
                                            class="d-flex justify-content-end gap-1"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.approval-matrix.show',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'approvalMatrix' =>
                                                            $approvalMatrix->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.approval-matrix.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'approvalMatrix' =>
                                                            $approvalMatrix->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <h5>
                        No Approval Rules
                    </h5>

                    <div class="text-muted mb-3">

                        No approval authority rules have been
                        configured for this project yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.approval-matrix.create',
                            [
                                'project' => $project->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First Approval Rule
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- APPROVAL LOGIC EXPLANATION --}}
    {{-- ========================================================= --}}

    <div class="card mt-4 mb-5">

        <div class="card-header">

            <strong>
                How the Approval Matrix Works
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        1. Transaction
                    </div>

                    <div class="text-muted small">
                        A project transaction or decision
                        requires approval.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        2. Amount
                    </div>

                    <div class="text-muted small">
                        The transaction amount is compared
                        against configured authority limits.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        3. Authority
                    </div>

                    <div class="text-muted small">
                        The appropriate role or designated
                        user is identified.
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="fw-semibold mb-1">
                        4. Approval
                    </div>

                    <div class="text-muted small">
                        The required approval sequence is
                        executed and recorded.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection