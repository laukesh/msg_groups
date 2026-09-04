@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php
        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        |
        | Bid Comparison becomes view-only once the LOA is issued.
        |
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();
    @endphp


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Tender:
                {{ $procurementTender->tender_number }}
            </div>

            <h4 class="mb-1">
                Bid Comparisons
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
                class="btn btn-outline-secondary"
            >
                Tender
            </a>


            {{-- Create Comparison --}}
            @if(!$tenderAwarded)

                <a
                    href="{{ route(
                        'admin.procurement.tenders.bid-comparisons.create',
                        $procurementTender
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Comparison
                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        AWARD LOCK MESSAGE
    ========================================================== --}}

    @if($tenderAwarded)

        <div class="alert alert-warning d-flex align-items-center">

            <div class="me-2">

                <i class="ri-lock-line"></i>

            </div>

            <div>

                <strong>
                    Bid Comparison is View Only
                </strong>

                <div class="small">

                    The Letter of Award (LOA) has been issued
                    for this Tender.

                    New comparisons cannot be created and
                    existing comparisons cannot be edited or deleted.

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        COMPARISON REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Bid Comparison Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($comparisons->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Comparison No.
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Bidders
                            </th>

                            <th>
                                Lowest Evaluated Amount
                            </th>

                            <th>
                                Recommended Bidder
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
                            $comparisons as $comparison
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- =================================================
                                    COMPARISON NUMBER
                                ================================================== --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.bid-comparisons.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'comparison' =>
                                                    $comparison,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >

                                        {{
                                            $comparison
                                                ->comparison_number
                                        }}

                                    </a>

                                </td>


                                {{-- =================================================
                                    TITLE
                                ================================================== --}}

                                <td>

                                    {{ $comparison->comparison_title }}

                                </td>


                                {{-- =================================================
                                    BIDDERS
                                ================================================== --}}

                                <td>

                                    <span class="badge bg-info">

                                        {{ $comparison->qualified_bidders }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    LOWEST EVALUATED AMOUNT
                                ================================================== --}}

                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $comparison
                                                    ->lowest_evaluated_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $comparison->currency }}

                                </td>


                                {{-- =================================================
                                    RECOMMENDED BIDDER
                                ================================================== --}}

                                <td>

                                    {{
                                        $comparison
                                            ->recommendedSubmission
                                            ?->tenderBidder
                                            ?->bidder
                                            ?->company_name
                                        ?? '—'
                                    }}

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @php

                                        $statusClass = match(
                                            $comparison->status
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
                                        {{ $comparison->status }}
                                    </span>

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">

                                    {{-- View is ALWAYS available --}}
                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.bid-comparisons.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'comparison' =>
                                                    $comparison,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    {{-- Edit only before LOA Issued --}}
                                    @if(!$tenderAwarded)

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
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>

                                    @else

                                        <span
                                            class="badge bg-secondary ms-1"
                                            title="Tender LOA has been issued"
                                        >
                                            <i class="ri-lock-line"></i>
                                            Locked
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No Bid Comparisons found.

                    </div>


                    {{-- Create First Comparison only before LOA --}}
                    @if(!$tenderAwarded)

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.bid-comparisons.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Create First Comparison
                        </a>

                    @else

                        <div class="text-muted">

                            The Tender LOA has already been issued.
                            New Bid Comparisons cannot be created.

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection