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

                {{ $submission->submission_number }}

            </h4>


            <div class="text-muted">

                {{
                    $submission
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.submissions.edit',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'submission' =>
                            $submission,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.submissions.index',
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


    {{-- Submission Summary --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Submission Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Submission Number --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission Number
                    </div>

                    <div class="fw-semibold">

                        {{ $submission->submission_number }}

                    </div>

                </div>


                {{-- Bidder --}}
                <div class="col-md-5">

                    <div class="text-muted small">
                        Bidder
                    </div>

                    <div class="fw-semibold">

                        {{
                            $submission
                                ->tenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </div>


                    @if(
                        $submission
                            ->tenderBidder
                            ->bidder
                            ->bidder_code
                    )

                        <div class="small text-muted">

                            {{
                                $submission
                                    ->tenderBidder
                                    ->bidder
                                    ->bidder_code
                            }}

                        </div>

                    @endif

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Status
                    </div>


                    @php

                        $statusClass = match(
                            $submission->submission_status
                        ) {

                            'Submitted'
                                => 'bg-primary',

                            'Under Review'
                                => 'bg-warning text-dark',

                            'Accepted'
                                => 'bg-success',

                            'Rejected'
                                => 'bg-danger',

                            'Withdrawn'
                                => 'bg-secondary',

                            default
                                => 'bg-light text-dark',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $submission->submission_status }}

                    </span>

                </div>


                {{-- Complete --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Complete
                    </div>


                    @if($submission->is_complete)

                        <span class="badge bg-success">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            No
                        </span>

                    @endif

                </div>


                {{-- Submission Date --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission Date
                    </div>

                    <div>

                        {{
                            $submission->submission_date
                                ? $submission
                                    ->submission_date
                                    ->format('d-m-Y H:i')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Bid Validity --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Bid Validity
                    </div>

                    <div>

                        @if($submission->bid_validity_days)

                            {{ $submission->bid_validity_days }}
                            days

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Valid Until --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Valid Until
                    </div>

                    <div>

                        {{
                            $submission->bid_valid_until
                                ? $submission
                                    ->bid_valid_until
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Quoted Amount --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Quoted Amount
                    </div>

                    <div class="fw-semibold">

                        {{
                            number_format(
                                $submission->quoted_amount,
                                2
                            )
                        }}

                        {{ $submission->currency }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Technical Submission --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Technical Submission
            </strong>

        </div>


        <div class="card-body">

            @if($submission->technical_submission)

                {!! nl2br(
                    e(
                        $submission->technical_submission
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No technical submission details provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Commercial Submission --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Commercial Submission
            </strong>

        </div>


        <div class="card-body">

            @if($submission->commercial_submission)

                {!! nl2br(
                    e(
                        $submission->commercial_submission
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No commercial submission details provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Compliance --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Compliance Declaration
            </strong>

        </div>


        <div class="card-body">

            @if($submission->compliance_declaration)

                {!! nl2br(
                    e(
                        $submission->compliance_declaration
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No compliance declaration provided.
                </span>

            @endif

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
                    $submission->remarks ?: '—'
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
                        Delete Submission
                    </strong>

                    <div class="small text-muted">

                        The submission will be permanently
                        removed.

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.submissions.destroy',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'submission' =>
                                $submission,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Are you sure you want to delete this tender submission?'
                        )"
                    >

                        Delete Submission

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection