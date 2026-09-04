@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Tender:
                {{ $procurementTender->tender_number }}

            </div>


            <h4>
                {{ $negotiation->negotiation_number }}
            </h4>


            <div class="text-muted">

                {{ $negotiation->negotiation_title }}

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- =====================================================
                BACK TO TENDER
            ====================================================== --}}

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


            {{-- =====================================================
                BACK TO NEGOTIATIONS
            ====================================================== --}}

            <a
                href="{{ route(
                    'admin.procurement.tenders.negotiations.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Negotiations
            </a>


            {{-- =====================================================
                EDIT
            ====================================================== --}}

            @if(
                in_array(
                    $negotiation->status,
                    [
                        'Draft',
                        'Under Review',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.negotiations.edit',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'negotiation' =>
                                $negotiation,
                        ]
                    ) }}"
                    class="btn btn-warning"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endif


            {{-- =====================================================
                ADD NEXT ROUND
            ====================================================== --}}

            @if(
                in_array(
                    $negotiation->status,
                    [
                        'Draft',
                        'Under Review',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.procurement.tenders.negotiations.rounds.create',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'negotiation' =>
                                $negotiation,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-add-line me-1"></i>
                    Add Next Round
                </a>

            @endif


            {{-- =====================================================
                FINALIZE
            ====================================================== --}}

            @if(
                in_array(
                    $negotiation->status,
                    [
                        'Draft',
                        'Under Review',
                    ],
                    true
                )
            )

                @php

                    $latestRound =
                        $negotiation
                            ->items
                            ->sortByDesc('round_number')
                            ->first();

                @endphp


                @if(
                    $latestRound &&
                    $latestRound->negotiation_status === 'Agreed'
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.procurement.tenders.negotiations.finalize',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'negotiation' =>
                                    $negotiation,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to finalize this negotiation?'
                        );"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Finalize Negotiation
                        </button>

                    </form>

                @endif

            @endif


            {{-- =====================================================
                APPROVE
            ====================================================== --}}

            @if(
                $negotiation->status === 'Completed'
            )

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.negotiations.approve',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'negotiation' =>
                                $negotiation,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to approve this negotiation?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="ri-check-double-line me-1"></i>
                        Approve Negotiation
                    </button>

                </form>

            @endif

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Bidder
                    </small>

                    <h6 class="mt-2">
                        {{ $negotiation->bidder_name }}
                    </h6>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Original Amount
                    </small>

                    <h5 class="mt-2">

                        {{
                            number_format(
                                (float)
                                $negotiation->original_amount,
                                2
                            )
                        }}

                        {{ $negotiation->currency }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Final Negotiated Amount
                    </small>

                    <h5 class="mt-2">

                        {{
                            number_format(
                                (float)
                                $negotiation->final_amount,
                                2
                            )
                        }}

                        {{ $negotiation->currency }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="mt-2">

                        <span class="badge bg-secondary">
                            {{ $negotiation->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Negotiation Status
                    </small>

                    @php

                        $statusClass = match(
                            $negotiation->status
                        ) {

                            'Approved' =>
                                'bg-success',

                            'Completed' =>
                                'bg-primary',

                            'Under Review' =>
                                'bg-warning text-dark',

                            'Rejected' =>
                                'bg-danger',

                            default =>
                                'bg-secondary',
                        };

                    @endphp


                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $negotiation->status }}
                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Outcome
                    </small>

                    <strong>
                        {{ $negotiation->outcome ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Final Amount
                    </small>

                    <strong class="text-success">

                        {{
                            number_format(
                                (float)
                                $negotiation->final_amount,
                                2
                            )
                        }}

                        {{ $negotiation->currency }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Approval Date
                    </small>

                    <strong>

                        {{
                            $negotiation->approval_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Negotiation Rounds
                </strong>

                <span class="badge bg-primary">

                    {{ $negotiation->items->count() }}
                    Rounds

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($negotiation->items->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                Round
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Bidder Amount
                            </th>

                            <th>
                                Negotiated Amount
                            </th>

                            <th>
                                Discount
                            </th>

                            <th>
                                Final Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $negotiation->items
                                ->sortBy('round_number')
                            as $item
                        )

                            <tr>

                                <td>

                                    <span class="badge bg-dark">
                                        Round {{ $item->round_number }}
                                    </span>

                                </td>


                                <td>

                                    {{
                                        $item->round_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            (float)
                                            $item->bidder_amount,
                                            2
                                        )
                                    }}

                                    {{ $item->currency }}

                                </td>


                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $item->negotiated_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $item->currency }}

                                </td>


                                <td>

                                    {{
                                        number_format(
                                            (float)
                                            $item->discount_amount,
                                            2
                                        )
                                    }}

                                    {{ $item->currency }}

                                </td>


                                <td>

                                    <strong class="text-success">

                                        {{
                                            number_format(
                                                (float)
                                                $item->final_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $item->currency }}

                                </td>


                                <td>

                                    @php

                                        $roundClass =
                                            match(
                                                $item->negotiation_status
                                            ) {

                                                'Agreed' =>
                                                    'bg-success',

                                                'Rejected' =>
                                                    'bg-danger',

                                                'In Progress' =>
                                                    'bg-warning text-dark',

                                                default =>
                                                    'bg-secondary',
                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $roundClass }}"
                                    >
                                        {{ $item->negotiation_status }}
                                    </span>

                                </td>
                                <td class="text-end">

                                @if(
                                    in_array(
                                        $negotiation->status,
                                        [
                                            'Draft',
                                            'Under Review',
                                        ],
                                        true
                                    )
                                )

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.negotiations.rounds.edit',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'negotiation' =>
                                                    $negotiation,

                                                'item' =>
                                                    $item,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-warning"
                                    >

                                        <i class="ri-edit-line me-1"></i>

                                        Edit

                                    </a>

                                @else

                                    <span class="text-muted small">
                                        Locked
                                    </span>

                                @endif

                            </td>

                            </tr>


                            @if(
                                $item->bidder_comments ||
                                $item->evaluator_comments ||
                                $item->remarks
                            )

                                <tr>

                                    <td
                                        colspan="8"
                                        class="bg-light"
                                    >

                                        @if($item->bidder_comments)

                                            <div class="mb-1">

                                                <strong>
                                                    Bidder:
                                                </strong>

                                                {{ $item->bidder_comments }}

                                            </div>

                                        @endif


                                        @if($item->evaluator_comments)

                                            <div class="mb-1">

                                                <strong>
                                                    Evaluator:
                                                </strong>

                                                {{ $item->evaluator_comments }}

                                            </div>

                                        @endif


                                        @if($item->remarks)

                                            <div>

                                                <strong>
                                                    Remarks:
                                                </strong>

                                                {{ $item->remarks }}

                                            </div>

                                        @endif

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-5">

                    No negotiation rounds recorded.

                </div>

            @endif

        </div>

    </div>


    <div class="card mb-4">

        <div class="card-header">
            <strong>Summary</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $negotiation->summary
                    ?? 'No summary provided.'
                )
            ) !!}

        </div>

    </div>


    <div class="card">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $negotiation->remarks
                    ?? 'No remarks provided.'
                )
            ) !!}

        </div>

    </div>

</div>

@endsection