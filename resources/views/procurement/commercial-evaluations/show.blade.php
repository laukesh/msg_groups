@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- Header --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="text-decoration-none"
                >

                    <i class="ri-arrow-left-line me-1"></i>

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
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back to Tender
            </a>


            {{-- Edit --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.commercial-evaluations.edit',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'evaluation' => $evaluation,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>


            {{-- Back to Register --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.commercial-evaluations.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Success Message --}}
    {{-- ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- Commercial Evaluation Information --}}
    {{-- ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Commercial Evaluation Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Evaluation Number --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluation Number
                    </div>

                    <strong>
                        {{ $evaluation->evaluation_number }}
                    </strong>

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

                            'Approved' =>
                                'bg-success',

                            'Completed' =>
                                'bg-primary',

                            'Rejected' =>
                                'bg-danger',

                            'Under Evaluation' =>
                                'bg-warning text-dark',

                            default =>
                                'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $evaluation->status }}

                    </span>

                </div>


                {{-- Bidder --}}
                <div class="col-md-6">

                    <div class="text-muted small">
                        Bidder
                    </div>

                    <strong>

                        {{
                            $evaluation
                                ->submission
                                ->tenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </strong>

                </div>


                {{-- Submission --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission
                    </div>

                    {{
                        $evaluation
                            ->submission
                            ->submission_number
                    }}

                </div>


                {{-- Currency --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Currency
                    </div>

                    {{ $evaluation->currency }}

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Technical Qualification --}}
    {{-- ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Technical Qualification
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                {{-- Technical Evaluation --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Evaluation
                    </div>

                    {{
                        $evaluation
                            ->technicalEvaluation
                            ->evaluation_number
                    }}

                </div>


                {{-- Technical Score --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Score
                    </div>

                    <strong>

                        {{
                            number_format(
                                $evaluation
                                    ->technicalEvaluation
                                    ->technical_score,
                                2
                            )
                        }}

                        /

                        {{
                            number_format(
                                $evaluation
                                    ->technicalEvaluation
                                    ->maximum_score,
                                2
                            )
                        }}

                    </strong>

                </div>


                {{-- Technical Result --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Result
                    </div>

                    <span class="badge bg-success">

                        {{
                            $evaluation
                                ->technicalEvaluation
                                ->result
                        }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Financial Details --}}
    {{-- ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Commercial / Financial Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Original Quoted Amount --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Original Quoted Amount
                    </div>

                    <strong>

                        {{
                            number_format(
                                $evaluation->quoted_amount,
                                2
                            )
                        }}

                    </strong>

                    {{ $evaluation->currency }}

                </div>


                {{-- Evaluated Amount --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluated Amount
                    </div>

                    <strong>

                        {{
                            number_format(
                                $evaluation->evaluated_amount,
                                2
                            )
                        }}

                    </strong>

                    {{ $evaluation->currency }}

                </div>


                {{-- Tax --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Tax
                    </div>

                    {{
                        number_format(
                            $evaluation->tax_amount,
                            2
                        )
                    }}

                    {{ $evaluation->currency }}

                </div>


                {{-- Discount --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Discount
                    </div>

                    {{
                        number_format(
                            $evaluation->discount_amount,
                            2
                        )
                    }}

                    {{ $evaluation->currency }}

                </div>


                {{-- Final Amount --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Final Amount
                    </div>

                    <strong class="fs-5">

                        {{
                            number_format(
                                $evaluation
                                    ->final_evaluated_amount,
                                2
                            )
                        }}

                    </strong>

                    {{ $evaluation->currency }}

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Score / Result --}}
    {{-- ============================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Price Score --}}
        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Price Score
                    </strong>

                </div>


                <div class="card-body text-center">

                    <h2 class="mb-0">

                        {{
                            number_format(
                                $evaluation->price_score,
                                2
                            )
                        }}

                        /

                        {{
                            number_format(
                                $evaluation
                                    ->maximum_price_score,
                                2
                            )
                        }}

                    </h2>

                </div>

            </div>

        </div>


        {{-- Commercial Result --}}
        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Commercial Result
                    </strong>

                </div>


                <div class="card-body text-center">


                    @if($evaluation->result === 'Qualified')

                        <span class="badge bg-success fs-5">
                            Qualified
                        </span>

                    @elseif($evaluation->result === 'Not Qualified')

                        <span class="badge bg-danger fs-5">
                            Not Qualified
                        </span>

                    @else

                        <span class="badge bg-warning text-dark fs-5">
                            Pending
                        </span>

                    @endif


                    <div class="mt-3">

                        Compliance:

                        <strong>

                            {{ $evaluation->commercial_compliance }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Evaluation Summary --}}
    {{-- ============================================================= --}}

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


    {{-- ============================================================= --}}
    {{-- Strengths / Weaknesses --}}
    {{-- ============================================================= --}}

    <div class="row g-4 mb-4">


        {{-- Strengths --}}
        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Strengths
                    </strong>

                </div>


                <div class="card-body">

                    {!! nl2br(
                        e(
                            $evaluation->strengths ?: '—'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        {{-- Weaknesses --}}
        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">

                    <strong>
                        Weaknesses
                    </strong>

                </div>


                <div class="card-body">

                    {!! nl2br(
                        e(
                            $evaluation->weaknesses ?: '—'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Remarks --}}
    {{-- ============================================================= --}}

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


    {{-- ============================================================= --}}
    {{-- Delete --}}
    {{-- ============================================================= --}}

    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Commercial Evaluation
                    </strong>

                    <div class="small text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.procurement.tenders.commercial-evaluations.destroy',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'evaluation' => $evaluation,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Are you sure you want to delete this commercial evaluation?'
                        )"
                    >
                        <i class="ri-delete-bin-line me-1"></i>
                        Delete Evaluation
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection