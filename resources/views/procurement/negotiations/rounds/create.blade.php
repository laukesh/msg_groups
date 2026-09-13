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
                Add Negotiation Round {{ $nextRound }}
            </h4>

            <div class="text-muted">

                {{ $negotiation->negotiation_number }}
                -
                {{ $negotiation->negotiation_title }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.procurement.tenders.negotiations.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'negotiation' =>
                        $negotiation,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Bidder
                    </small>

                    <h6 class="mt-2 mb-0">

                        {{
                            $negotiation
                                ->bidder_name
                        }}

                    </h6>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Previous Final Amount
                    </small>

                    <h5 class="mt-2 mb-0">

                        {{
                            number_format(
                                (float)
                                $previousAmount,
                                2
                            )
                        }}

                        {{ $negotiation->currency }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Round
                    </small>

                    <h5 class="mt-2 mb-0">

                        Round {{ $nextRound }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.negotiations.rounds.store',
            [
                'procurementTender' =>
                    $procurementTender,

                'negotiation' =>
                    $negotiation,
            ]
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Round {{ $nextRound }} Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Round Date
                        </label>

                        <input
                            type="date"
                            name="round_date"
                            class="form-control"
                            value="{{ old(
                                'round_date',
                                now()->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Bidder Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="bidder_amount"
                            class="form-control"
                            value="{{ old(
                                'bidder_amount',
                                $previousAmount
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Negotiated Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="negotiated_amount"
                            class="form-control"
                            value="{{ old(
                                'negotiated_amount',
                                $previousAmount
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Discount Amount
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="discount_amount"
                            class="form-control"
                            value="{{ old(
                                'discount_amount',
                                0
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Round Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="negotiation_status"
                            class="form-select"
                            required
                        >

                            <option value="Open">
                                Open
                            </option>

                            <option value="In Progress">
                                In Progress
                            </option>

                            <option value="Agreed">
                                Agreed
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Bidder Comments
                        </label>

                        <textarea
                            name="bidder_comments"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'bidder_comments'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Evaluator Comments
                        </label>

                        <textarea
                            name="evaluator_comments"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'evaluator_comments'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'remarks'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Round {{ $nextRound }}
            </button>

        </div>

    </form>

</div>

@endsection