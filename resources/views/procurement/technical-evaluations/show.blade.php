@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}"
                    class="text-decoration-none"
                >

                    Tender:
                    {{ $procurementTender->tender_number }}

                </a>

            </div>


            <h4 class="mb-1">

                {{ $evaluation->evaluation_number }}

            </h4>


            <div class="text-muted">

                {{
                    $evaluation
                        ->submission
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back to Tender
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.technical-evaluations.edit',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'evaluation' =>
                            $evaluation,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            @if(
                $evaluation->result === 'Qualified'
                && !$evaluation->commercialEvaluation
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.commercial-evaluations.create',
                        $procurementTender
                    ) }}"
                    class="btn btn-success"
                >
                    + Commercial Evaluation
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.procurement.tenders.technical-evaluations.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Success --}}
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


    {{-- Evaluation Summary --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Technical Evaluation Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Evaluation Number --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluation Number
                    </div>

                    <div class="fw-semibold">

                        {{ $evaluation->evaluation_number }}

                    </div>

                </div>


                {{-- Evaluation Date --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluation Date
                    </div>

                    <div>

                        {{
                            $evaluation->evaluation_date
                                ? $evaluation
                                    ->evaluation_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Evaluator --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluator
                    </div>

                    <div>

                        {{ $evaluation->evaluator_name ?: '—' }}

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>


                    @php

                        $statusClass = match(
                            $evaluation->status
                        ) {

                            'Completed'
                                => 'bg-primary',

                            'Approved'
                                => 'bg-success',

                            'Rejected'
                                => 'bg-danger',

                            'Under Evaluation'
                                => 'bg-warning text-dark',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $evaluation->status }}

                    </span>

                </div>


                {{-- Bidder --}}
                <div class="col-md-5">

                    <div class="text-muted small">
                        Bidder
                    </div>

                    <div class="fw-semibold">

                        {{
                            $evaluation
                                ->submission
                                ->tenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </div>

                </div>


                {{-- Submission --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission
                    </div>

                    <div>

                        {{
                            $evaluation
                                ->submission
                                ->submission_number
                        }}

                    </div>

                </div>


                {{-- Quoted Amount --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Bid Amount
                    </div>

                    <div class="fw-semibold">

                        {{
                            number_format(
                                $evaluation
                                    ->submission
                                    ->quoted_amount,
                                2
                            )
                        }}

                        {{
                            $evaluation
                                ->submission
                                ->currency
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Score Card --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Technical Score
            </strong>

        </div>


        <div class="card-body">

            <div class="row text-center">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Score
                    </div>

                    <h2 class="mb-0">

                        {{
                            number_format(
                                $evaluation->technical_score,
                                2
                            )
                        }}

                    </h2>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Maximum Score
                    </div>

                    <h2 class="mb-0">

                        {{
                            number_format(
                                $evaluation->maximum_score,
                                2
                            )
                        }}

                    </h2>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Passing Score
                    </div>

                    <h2 class="mb-0">

                        {{
                            number_format(
                                $evaluation->passing_score,
                                2
                            )
                        }}

                    </h2>

                </div>

            </div>


            <hr>


            @php

                $percentage = $evaluation->maximum_score > 0
                    ? (
                        $evaluation->technical_score
                        /
                        $evaluation->maximum_score
                    ) * 100
                    : 0;

            @endphp


            <div class="mb-2 d-flex justify-content-between">

                <span>
                    Score Percentage
                </span>

                <strong>
                    {{ number_format($percentage, 2) }}%
                </strong>

            </div>


            <div class="progress" style="height: 20px;">

                <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: {{ min($percentage, 100) }}%;"
                >

                    {{ number_format($percentage, 2) }}%

                </div>

            </div>

        </div>

    </div>


    {{-- Compliance + Result --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Technical Compliance
                    </strong>

                </div>


                <div class="card-body text-center">

                    @php

                        $complianceClass = match(
                            $evaluation
                                ->technical_compliance
                        ) {

                            'Compliant'
                                => 'bg-success',

                            'Partially Compliant'
                                => 'bg-warning text-dark',

                            'Non-Compliant'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span
                        class="badge {{ $complianceClass }} fs-6"
                    >

                        {{
                            $evaluation
                                ->technical_compliance
                        }}

                    </span>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Technical Result
                    </strong>

                </div>


                <div class="card-body text-center">

                    @php

                        $resultClass = match(
                            $evaluation->result
                        ) {

                            'Qualified'
                                => 'bg-success',

                            'Not Qualified'
                                => 'bg-danger',

                            default
                                => 'bg-warning text-dark',

                        };

                    @endphp


                    <span
                        class="badge {{ $resultClass }} fs-6"
                    >

                        {{ $evaluation->result }}

                    </span>


                    @if($evaluation->result === 'Qualified')

                        <div class="text-success mt-2">
                            Eligible for commercial evaluation
                        </div>

                    @elseif(
                        $evaluation->result === 'Not Qualified'
                    )

                        <div class="text-danger mt-2">
                            Not eligible for commercial evaluation
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Strengths --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Strengths
            </strong>

        </div>


        <div class="card-body">

            @if($evaluation->strengths)

                {!! nl2br(
                    e($evaluation->strengths)
                ) !!}

            @else

                <span class="text-muted">
                    No strengths recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- Weaknesses --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Weaknesses
            </strong>

        </div>


        <div class="card-body">

            @if($evaluation->weaknesses)

                {!! nl2br(
                    e($evaluation->weaknesses)
                ) !!}

            @else

                <span class="text-muted">
                    No weaknesses recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- Summary --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Evaluation Summary
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $evaluation->evaluation_summary
                    ?: 'No evaluation summary provided.'
                )
            ) !!}

        </div>

    </div>


    {{-- Remarks --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $evaluation->remarks ?: '—'
                )
            ) !!}

        </div>

    </div>


    {{-- Delete --}}
    <div class="card border-danger mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Technical Evaluation
                    </strong>

                    <div class="small text-muted">
                        This evaluation will be permanently deleted.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.technical-evaluations.destroy',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'evaluation' =>
                                $evaluation,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Are you sure you want to delete this technical evaluation?'
                        )"
                    >
                        Delete Evaluation
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection