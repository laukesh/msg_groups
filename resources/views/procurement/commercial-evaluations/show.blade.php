@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}"
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
                    'admin.procurement.tenders.commercial-evaluations.edit',
                    [
                        'procurementTender' => $procurementTender,
                        'evaluation' => $evaluation,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.commercial-evaluations.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Basic Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Commercial Evaluation Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluation Number
                    </div>

                    <strong>
                        {{ $evaluation->evaluation_number }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluation Date
                    </div>

                    {{
                        $evaluation->evaluation_date
                            ? $evaluation
                                ->evaluation_date
                                ->format('d-m-Y')
                            : '—'
                    }}

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluator
                    </div>

                    {{ $evaluation->evaluator_name ?: '—' }}

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $evaluation->status }}
                    </span>

                </div>


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


                <div class="col-md-3">

                    <div class="text-muted small">
                        Currency
                    </div>

                    {{ $evaluation->currency }}

                </div>

            </div>

        </div>

    </div>


    {{-- Technical Qualification --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Qualification</strong>
        </div>


        <div class="card-body">

            <div class="row">

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


                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Score
                    </div>

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

                </div>


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


    {{-- Financial Details --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Commercial / Financial Details</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">

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


                <div class="col-md-3">

                    <div class="text-muted small">
                        Evaluated Amount
                    </div>

                    {{
                        number_format(
                            $evaluation->evaluated_amount,
                            2
                        )
                    }}

                    {{ $evaluation->currency }}

                </div>


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

                </div>


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

                </div>


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

                </div>

            </div>

        </div>

    </div>


    {{-- Score --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Price Score</strong>
                </div>

                <div class="card-body text-center">

                    <h2>

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


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Commercial Result</strong>
                </div>

                <div class="card-body text-center">

                    <span class="badge
                        fs-5
                        @if($evaluation->result === 'Qualified')
                            bg-success
                        @elseif($evaluation->result === 'Not Qualified')
                            bg-danger
                        @else
                            bg-warning text-dark
                        @endif
                    ">

                        {{ $evaluation->result }}

                    </span>


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


    {{-- Summary --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Evaluation Summary</strong>
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


    {{-- Strengths / Weaknesses --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Strengths</strong>
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


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Weaknesses</strong>
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


    {{-- Remarks --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
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
                        'admin.procurement.tenders.commercial-evaluations.destroy',
                        [
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
                        Delete Evaluation
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection