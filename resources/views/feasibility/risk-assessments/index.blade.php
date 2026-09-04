@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Risk Assessments
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
                    'admin.land.lands.feasibility-assessments.risk-assessments.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Risk Assessment
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Table --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Risk Assessment Records
            </strong>

            <span class="text-muted small">

                Total:
                {{ $riskAssessments->total() }}

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
                                Overall Risk
                            </th>

                            <th>
                                Risk Score
                            </th>

                            <th>
                                Critical Risks
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 230px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $riskAssessments as $risk
                    )

                        <tr>

                            {{-- ================================================= --}}
                            {{-- Analysis Number --}}
                            {{-- ================================================= --}}

                            <td>

                                <strong>
                                    {{ $risk->analysis_number }}
                                </strong>

                            </td>


                            {{-- ================================================= --}}
                            {{-- Title --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $risk->title }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- Overall Risk --}}
                            {{-- ================================================= --}}

                            <td>

                                @php

                                    $riskRating =
                                        $risk->overall_risk_rating;

                                @endphp


                                @if($riskRating === 'Low')

                                    <span class="badge bg-success">
                                        Low
                                    </span>

                                @elseif($riskRating === 'Medium')

                                    <span class="badge bg-warning text-dark">
                                        Medium
                                    </span>

                                @elseif($riskRating === 'High')

                                    <span class="badge bg-danger">
                                        High
                                    </span>

                                @elseif($riskRating === 'Critical')

                                    <span class="badge bg-dark">
                                        Critical
                                    </span>

                                @elseif($riskRating)

                                    <span class="badge bg-secondary">
                                        {{ $riskRating }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Risk Score --}}
                            {{-- ================================================= --}}

                            <td>

                                @if(
                                    $risk->overall_risk_score !== null
                                )

                                    <strong>

                                        {{
                                            number_format(
                                                $risk->overall_risk_score,
                                                2
                                            )
                                        }}

                                    </strong>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Critical Risks --}}
                            {{-- ================================================= --}}

                            <td style="max-width: 250px;">

                                @if($risk->critical_risks)

                                    <span
                                        title="{{ $risk->critical_risks }}"
                                    >

                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $risk->critical_risks,
                                                80
                                            )
                                        }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        None specified
                                    </span>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- Status --}}
                            {{-- ================================================= --}}

                            <td>

                                @switch($risk->status)

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
                                            {{ $risk->status }}
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
                                            'admin.land.lands.feasibility-assessments.risk-assessments.show',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'riskAssessment' =>
                                                    $risk->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.risk-assessments.edit',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'riskAssessment' =>
                                                    $risk->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.risk-assessments.destroy',
                                            [
                                                'land' =>
                                                    $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'riskAssessment' =>
                                                    $risk->id,
                                            ]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this Risk Assessment?');"
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
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">

                                    No risk assessment records
                                    found.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.risk-assessments.create',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Create Risk Assessment
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

        @if($riskAssessments->hasPages())

            <div class="card-footer">

                {{ $riskAssessments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection