@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Investment Decisions
            </h3>

            <p class="text-muted mb-0">
                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
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
                    'admin.land.lands.feasibility-assessments.investment-decisions.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Investment Decision
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success Message --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Main Card --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Investment Decision Records
            </strong>

            <span class="text-muted small">

                Total:
                {{ $investmentDecisions->total() }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Decision Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Decision
                            </th>

                            <th>
                                Investment
                            </th>

                            <th>
                                ROI
                            </th>

                            <th>
                                IRR
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 250px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $investmentDecisions as $decision
                    )

                        <tr>

                            {{-- ================================================= --}}
                            {{-- Decision Number --}}
                            {{-- ================================================= --}}

                            <td>

                                <strong>
                                    {{ $decision->decision_number }}
                                </strong>

                            </td>


                            {{-- ================================================= --}}
                            {{-- Title --}}
                            {{-- ================================================= --}}

                            <td>
                                {{ $decision->title }}
                            </td>


                            {{-- ================================================= --}}
                            {{-- Decision --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->decision === 'Go'
                                )

                                    <span class="badge bg-success">
                                        Go
                                    </span>

                                @elseif(
                                    $decision->decision === 'No-Go'
                                )

                                    <span class="badge bg-danger">
                                        No-Go
                                    </span>

                                @elseif(
                                    $decision->decision ===
                                    'Conditional Go'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Conditional Go
                                    </span>

                                @elseif(
                                    $decision->decision
                                )

                                    <span class="badge bg-secondary">
                                        {{ $decision->decision }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Investment --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->approved_investment
                                    !== null
                                )

                                    <span class="text-success fw-bold">
                                        ${{
                                            number_format(
                                                $decision
                                                    ->approved_investment,
                                                2
                                            )
                                        }}
                                    </span>

                                @elseif(
                                    $decision->recommended_investment
                                    !== null
                                )

                                    ${{
                                        number_format(
                                            $decision
                                                ->recommended_investment,
                                            2
                                        )
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- ROI --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->expected_roi
                                    !== null
                                )

                                    {{
                                        number_format(
                                            $decision->expected_roi,
                                            2
                                        )
                                    }}%

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- IRR --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->expected_irr
                                    !== null
                                )

                                    {{
                                        number_format(
                                            $decision->expected_irr,
                                            2
                                        )
                                    }}%

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Overall Score --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->overall_score
                                    !== null
                                )

                                    <strong>
                                        {{
                                            number_format(
                                                $decision->overall_score,
                                                2
                                            )
                                        }}
                                    </strong>

                                    <small class="text-muted">
                                        /100
                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Priority --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $decision->investment_priority
                                    === 'High'
                                )

                                    <span class="badge bg-danger">
                                        High
                                    </span>

                                @elseif(
                                    $decision->investment_priority
                                    === 'Medium'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Medium
                                    </span>

                                @elseif(
                                    $decision->investment_priority
                                    === 'Low'
                                )

                                    <span class="badge bg-success">
                                        Low
                                    </span>

                                @elseif(
                                    $decision->investment_priority
                                )

                                    <span class="badge bg-secondary">
                                        {{ $decision->investment_priority }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Status --}}
                            {{-- ================================================= --}}

                            <td>

                                @switch($decision->status)

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
                                            {{ $decision->status }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- ================================================= --}}
                            {{-- Actions --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-decisions.show',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentDecision' =>
                                                    $decision->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-decisions.edit',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentDecision' =>
                                                    $decision->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-decisions.destroy',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentDecision' =>
                                                    $decision->id,
                                            ]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Investment Decision?');"
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

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">

                                    No investment decisions
                                    found.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-decisions.create',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Create Investment Decision
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Pagination --}}
        {{-- ========================================================= --}}

        @if($investmentDecisions->hasPages())

            <div class="card-footer">

                {{ $investmentDecisions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection