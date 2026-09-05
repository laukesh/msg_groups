@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Investment Analyses
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
                    'admin.land.lands.feasibility-assessments.investment-analyses.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Investment Analysis
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
                Investment Analysis Records
            </strong>

            <span class="text-muted small">

                Total:
                {{ $investmentAnalyses->total() }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Analysis Number
                            </th>

                            <th>
                                Title
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
                                Investment Risk
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 240px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $investmentAnalyses as $analysis
                    )

                        <tr>

                            {{-- ================================================= --}}
                            {{-- Analysis Number --}}
                            {{-- ================================================= --}}

                            <td>

                                <strong>
                                    {{ $analysis->analysis_number }}
                                </strong>

                            </td>


                            {{-- ================================================= --}}
                            {{-- Title --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $analysis->title }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- Total Investment --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $analysis->total_investment !== null
                                )

                                    $
                                    {{
                                        number_format(
                                            $analysis->total_investment,
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
                                    $analysis->roi !== null
                                )

                                    {{
                                        number_format(
                                            $analysis->roi,
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
                                    $analysis->irr !== null
                                )

                                    {{
                                        number_format(
                                            $analysis->irr,
                                            2
                                        )
                                    }}%

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Investment Risk --}}
                            {{-- ================================================= --}}

                            <td>

                                @php

                                    $risk =
                                        $analysis
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

                            </td>


                            {{-- ================================================= --}}
                            {{-- Investment Score --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $analysis
                                        ->overall_investment_score
                                    !== null
                                )

                                    <strong>

                                        {{
                                            number_format(
                                                $analysis
                                                    ->overall_investment_score,
                                                2
                                            )
                                        }}

                                    </strong>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Status --}}
                            {{-- ================================================= --}}

                            <td>

                                @switch($analysis->status)

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
                                            {{ $analysis->status }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- ================================================= --}}
                            {{-- Actions --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="d-flex gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-analyses.show',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentAnalysis' =>
                                                    $analysis->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-analyses.edit',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentAnalysis' =>
                                                    $analysis->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.investment-analyses.destroy',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'investmentAnalysis' =>
                                                    $analysis->id,
                                            ]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Investment Analysis?');"
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
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">

                                    No investment analysis
                                    records found.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-analyses.create',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Create Investment Analysis
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

        @if($investmentAnalyses->hasPages())

            <div class="card-footer">

                {{ $investmentAnalyses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection