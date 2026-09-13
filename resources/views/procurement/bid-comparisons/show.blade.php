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
                {{ $comparison->comparison_number }}
            </h4>

            <div class="text-muted">
                {{ $comparison->comparison_title }}
            </div>

        </div>


        <!-- <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.bid-comparisons.edit',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'comparison' =>
                            $comparison,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.bid-comparisons.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div> -->

        <div class="d-flex flex-wrap gap-2">

                {{-- Back to Tender --}}

                <a
                    href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Tender
                </a>


                {{-- Negotiation --}}

                @if($latestNegotiation)

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.negotiations.show',
                            [
                                'procurementTender' => $procurementTender,
                                'negotiation' => $latestNegotiation,
                            ]
                        ) }}"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-chat-square-text"></i>
                        View Negotiation
                    </a>

                @elseif($canStartNegotiation)

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.negotiations.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-chat-square-text"></i>
                        Start Negotiation
                    </a>

                @endif

            </div>


    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Qualified Bidders
                    </div>

                    <h3>
                        {{ $comparison->qualified_bidders }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Lowest Evaluated Amount
                    </div>

                    <h5>

                        {{
                            number_format(
                                (float)
                                $comparison
                                    ->lowest_evaluated_amount,
                                2
                            )
                        }}

                        {{ $comparison->currency }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Evaluation Basis
                    </div>

                    <strong>
                        {{ $comparison->evaluation_basis }}
                    </strong>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <span class="badge bg-secondary fs-6">
                        {{ $comparison->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Recommended Bidder --}}
    @if($comparison->recommendedSubmission)

        <div class="alert alert-success mb-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Recommended Bidder
                    </strong>

                    <div class="mt-1">

                        {{
                            $comparison
                                ->recommendedSubmission
                                ->tenderBidder
                                ?->bidder
                                ?->company_name
                            ?? 'Unknown Bidder'
                        }}

                    </div>

                </div>


                <div class="text-end">

                    <div class="small">
                        Recommended Submission
                    </div>

                    <strong>

                        {{
                            $comparison
                                ->recommendedSubmission
                                ->submission_number
                            ?? '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    @endif


    {{-- Comparison --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Bid Comparison
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

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
                            Quoted Amount
                        </th>

                        <th>
                            Evaluated Amount
                        </th>

                        <th>
                            Tax
                        </th>

                        <th>
                            Discount
                        </th>

                        <th>
                            Final Amount
                        </th>

                        <th>
                            Technical
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Overall
                        </th>

                        <th>
                            Result
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    @foreach(
                        $comparison->items
                            ->sortBy('bidder_rank')
                        as $item
                    )

                        <tr
                            @if($item->is_recommended)
                                class="table-success"
                            @endif
                        >

                            <td>

                                <span
                                    class="badge
                                    {{
                                        $item->bidder_rank == 1
                                            ? 'bg-success'
                                            : 'bg-secondary'
                                    }}"
                                >
                                    #{{ $item->bidder_rank }}
                                </span>

                            </td>


                            <td>

                                <strong>
                                    {{ $item->bidder_name }}
                                </strong>

                                @if($item->is_recommended)

                                    <div>
                                        <span class="badge bg-success">
                                            Recommended
                                        </span>
                                    </div>

                                @endif

                            </td>


                            <td>

                                {{
                                    $item
                                        ->submission
                                        ?->submission_number
                                    ?? '—'
                                }}

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->quoted_amount,
                                        2
                                    )
                                }}

                                {{ $item->currency }}

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->evaluated_amount,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->tax_amount,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->discount_amount,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                <strong>

                                    {{
                                        number_format(
                                            (float)
                                            $item
                                                ->final_evaluated_amount,
                                            2
                                        )
                                    }}

                                </strong>

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->technical_score,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                {{
                                    number_format(
                                        (float)
                                        $item->price_score,
                                        2
                                    )
                                }}

                            </td>


                            <td>

                                <strong>

                                    {{
                                        number_format(
                                            (float)
                                            $item->overall_score,
                                            2
                                        )
                                    }}

                                </strong>

                            </td>


                            <td>

                                <span class="badge bg-success">
                                    {{ $item->comparison_result }}
                                </span>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Summary --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Summary</strong>
        </div>

        <div class="card-body">

            @if($comparison->summary)

                {!! nl2br(e($comparison->summary)) !!}

            @else

                <span class="text-muted">
                    No summary provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Remarks --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            @if($comparison->remarks)

                {!! nl2br(e($comparison->remarks)) !!}

            @else

                <span class="text-muted">
                    No remarks provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Delete --}}
    <div class="card border-danger">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Bid Comparison
                    </strong>

                    <div class="small text-muted">
                        Comparison items will also be deleted.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.bid-comparisons.destroy',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'comparison' =>
                                $comparison,
                        ]
                    ) }}"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Are you sure you want to delete this Bid Comparison?'
                        )"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection