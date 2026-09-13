@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php
        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        |
        | Once LOA is Issued, Negotiation becomes View Only.
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
                Negotiations
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


            {{-- Create Negotiation --}}
            @if(!$tenderAwarded)

                <a
                    href="{{ route(
                        'admin.procurement.tenders.negotiations.create',
                        $procurementTender
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Negotiation
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
        LOA LOCK MESSAGE
    ========================================================== --}}

    @if($tenderAwarded)

        <div class="alert alert-warning d-flex align-items-start">

            <div class="me-2">

                <i class="ri-lock-line"></i>

            </div>

            <div>

                <strong>
                    Negotiations are View Only
                </strong>

                <div class="small mt-1">

                    The Letter of Award (LOA) has been issued
                    for this Tender.

                    New negotiations cannot be created and
                    existing negotiations cannot be modified.

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        NEGOTIATION REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Negotiation Register
            </strong>

        </div>


        <div class="card-body p-0">

            @if($negotiations->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Negotiation No.
                            </th>

                            <th>
                                Bidder
                            </th>

                            <th>
                                Original Amount
                            </th>

                            <th>
                                Final Amount
                            </th>

                            <th>
                                Outcome
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
                            $negotiations as $negotiation
                        )

                            <tr>

                                {{-- =================================================
                                    #
                                ================================================== --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =================================================
                                    NEGOTIATION NUMBER
                                ================================================== --}}

                                <td>

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
                                        class="fw-semibold"
                                    >

                                        {{
                                            $negotiation
                                                ->negotiation_number
                                        }}

                                    </a>

                                </td>


                                {{-- =================================================
                                    BIDDER
                                ================================================== --}}

                                <td>

                                    {{
                                        $negotiation->bidder_name
                                    }}

                                </td>


                                {{-- =================================================
                                    ORIGINAL AMOUNT
                                ================================================== --}}

                                <td>

                                    {{
                                        number_format(
                                            (float)
                                            $negotiation
                                                ->original_amount,
                                            2
                                        )
                                    }}

                                    {{ $negotiation->currency }}

                                </td>


                                {{-- =================================================
                                    FINAL AMOUNT
                                ================================================== --}}

                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $negotiation
                                                    ->final_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $negotiation->currency }}

                                </td>


                                {{-- =================================================
                                    OUTCOME
                                ================================================== --}}

                                <td>

                                    {{ $negotiation->outcome }}

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

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

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">

                                    {{-- View remains available --}}
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
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        View

                                    </a>


                                    {{-- Show lock indicator after LOA --}}
                                    @if($tenderAwarded)

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

                {{-- =================================================
                    NO NEGOTIATIONS
                ================================================== --}}

                <div class="text-center py-5">

                    @if($tenderAwarded)

                        <div class="text-muted mb-2">

                            No Negotiations found.

                        </div>

                        <div class="small text-muted">

                            The Tender LOA has already been issued.
                            New negotiations cannot be created.

                        </div>

                    @else

                        <div class="text-muted mb-3">

                            No Negotiations found.

                        </div>


                        <a
                            href="{{ route(
                                'admin.procurement.tenders.negotiations.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-primary"
                        >

                            + Create Negotiation

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection