@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php
        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        |
        | Once LOA is issued, the Award becomes view-only.
        |
        */

        $loaIssued = $procurementTender
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

                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

            </div>


            <h4 class="mb-1">
                Awards
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


            {{-- Create Award --}}
            @if(!$loaIssued)

                <a
                    href="{{ route(
                        'admin.procurement.tenders.awards.create',
                        $procurementTender
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="ri-add-line me-1"></i>

                    Create Award

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        LOA LOCK MESSAGE
    ========================================================== --}}

    @if($loaIssued)

        <div class="alert alert-warning d-flex align-items-start">

            <div class="me-2">

                <i class="ri-lock-line"></i>

            </div>


            <div>

                <strong>
                    Award is Final
                </strong>


                <div class="small mt-1">

                    The Letter of Award (LOA) has been issued
                    for this Tender.

                    The Award is now view-only and no new Award
                    can be created.

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        AWARD REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Award Register
            </strong>


            <span class="badge bg-primary ms-2">

                {{ $awards->count() }}

            </span>

        </div>


        <div class="card-body p-0">

            @if($awards->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Award Number
                            </th>

                            <th>
                                Bidder
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Award Date
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

                        @foreach($awards as $award)

                            <tr>

                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =================================================
                                    AWARD NUMBER
                                ================================================== --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.awards.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'award' =>
                                                    $award,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $award->award_number }}

                                    </a>

                                </td>


                                {{-- =================================================
                                    BIDDER
                                ================================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $award->bidder_name }}

                                    </div>


                                    @if($award->award_type)

                                        <small class="text-muted">

                                            {{ $award->award_type }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    AMOUNT
                                ================================================== --}}

                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                (float)
                                                $award->awarded_amount,
                                                2
                                            )
                                        }}

                                    </strong>


                                    <span class="text-muted">

                                        {{ $award->currency }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    AWARD DATE
                                ================================================== --}}

                                <td>

                                    {{
                                        $award->award_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @php

                                        $statusClass =
                                            match($award->status) {

                                                'Draft' =>
                                                    'bg-secondary',

                                                'Under Review' =>
                                                    'bg-warning text-dark',

                                                'Approved' =>
                                                    'bg-success',

                                                'LOA Issued' =>
                                                    'bg-primary',

                                                'Rejected' =>
                                                    'bg-danger',

                                                default =>
                                                    'bg-secondary',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $award->status }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">
                                    <div class="d-flex gap-2">
                                        {{-- View is always available --}}
                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.awards.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'award' =>
                                                        $award,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >

                                            View

                                        </a>


                                        {{-- Locked indicator --}}
                                        @if($loaIssued)

                                            <span
                                                class="badge bg-secondary ms-1"
                                                title="Tender LOA has been issued"
                                            >

                                                <i class="ri-lock-line"></i>

                                                Locked

                                            </span>

                                        @endif
                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =================================================
                    NO AWARDS
                ================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="ri-award-line"
                            style="font-size: 40px;"
                        ></i>

                    </div>


                    <h6>
                        No Awards Found
                    </h6>


                    @if($loaIssued)

                        <p class="text-muted mb-0">

                            The Tender LOA has already been issued.
                            A new Award cannot be created.

                        </p>

                    @else

                        <p class="text-muted mb-3">

                            No procurement award has been created
                            for this Tender yet.

                        </p>


                        <a
                            href="{{ route(
                                'admin.procurement.tenders.awards.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="ri-add-line me-1"></i>

                            Create Award

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection