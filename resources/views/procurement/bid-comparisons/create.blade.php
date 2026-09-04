@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Tender:
                {{ $procurementTender->tender_number }}
            </div>

            <h4 class="mb-1">
                Create Bid Comparison
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                Back to Tender
            </a>

            {{-- Back --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.bid-comparisons.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.bid-comparisons.store',
            $procurementTender
        ) }}"
    >

        @csrf


        {{-- Comparison Details --}}
        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Comparison Details
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'procurement.bid-comparisons._form',
                    [
                        'comparison' => null,
                    ]
                )

            </div>

        </div>


        {{-- Eligible Bids --}}
        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        Qualified Commercial Bids
                    </strong>

                    <span class="badge bg-success">

                        {{ $eligibleCommercialEvaluations->count() }}

                        Eligible

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if(
                    $eligibleCommercialEvaluations->isNotEmpty()
                )

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th width="50">
                                    Select
                                </th>

                                <th>
                                    Rank
                                </th>

                                <th>
                                    Bidder
                                </th>

                                <th>
                                    Submission
                                </th>

                                <th>
                                    Technical Score
                                </th>

                                <th>
                                    Quoted Amount
                                </th>

                                <th>
                                    Final Evaluated Amount
                                </th>

                                <th>
                                    Price Score
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach(
                                $eligibleCommercialEvaluations
                                as $evaluation
                            )

                                <tr>

                                    <td>

                                        <input
                                            type="checkbox"
                                            name="selected_evaluations[]"
                                            value="{{ $evaluation->id }}"
                                            class="form-check-input"
                                            @checked(
                                                in_array(
                                                    $evaluation->id,
                                                    old(
                                                        'selected_evaluations',
                                                        []
                                                    )
                                                )
                                            )
                                        >

                                    </td>


                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>

                                            {{
                                                $evaluation
                                                    ->submission
                                                    ?->tenderBidder
                                                    ?->bidder
                                                    ?->company_name
                                                ?? 'Unknown Bidder'
                                            }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $evaluation
                                                ->submission
                                                ?->submission_number
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            number_format(
                                                (float) (
                                                    $evaluation
                                                        ->technicalEvaluation
                                                        ?->technical_score
                                                    ?? 0
                                                ),
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            number_format(
                                                (float)
                                                $evaluation->quoted_amount,
                                                2
                                            )
                                        }}

                                        {{ $evaluation->currency }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{
                                                number_format(
                                                    (float)
                                                    $evaluation
                                                        ->final_evaluated_amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        {{ $evaluation->currency }}

                                    </td>


                                    <td>

                                        {{
                                            number_format(
                                                (float)
                                                $evaluation->price_score,
                                                2
                                            )
                                        }}

                                        /

                                        {{
                                            number_format(
                                                (float)
                                                $evaluation
                                                    ->maximum_price_score,
                                                2
                                            )
                                        }}

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="alert alert-warning m-3 mb-0">

                        <h6 class="alert-heading">
                            No Qualified Commercial Bids
                        </h6>

                        <p class="mb-2">

                            There are currently no Commercial
                            Evaluations with result:

                            <strong>
                                Qualified
                            </strong>.

                        </p>

                        <hr>

                        <p class="mb-0 small">

                            Complete the Commercial Evaluation
                            and change its result from
                            <strong>Pending</strong>
                            to
                            <strong>Qualified</strong>
                            before creating the Bid Comparison.

                        </p>

                    </div>

                @endif

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.procurement.tenders.bid-comparisons.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
                @disabled(
                    $eligibleCommercialEvaluations->isEmpty()
                )
            >
                Create Bid Comparison
            </button>

        </div>

    </form>

</div>

@endsection