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
                Tender Submissions
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to Tender
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.submissions.create',
                    $procurementTender
                ) }}"
                class="btn btn-primary"
            >
                + Add Submission
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


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Submissions
                    </div>

                    <h4 class="mb-0">
                        {{ $submissions->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <h4 class="mb-0">

                        {{
                            $submissions
                                ->where(
                                    'submission_status',
                                    'Submitted'
                                )
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <h4 class="mb-0">

                        {{
                            $submissions
                                ->where(
                                    'submission_status',
                                    'Under Review'
                                )
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Accepted
                    </div>

                    <h4 class="mb-0">

                        {{
                            $submissions
                                ->where(
                                    'submission_status',
                                    'Accepted'
                                )
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Submission Register --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Tender Submission Register
            </strong>

            <span class="badge bg-primary">
                {{ $submissions->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($submissions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Submission No.</th>

                            <th>Bidder</th>

                            <th>Submission Date</th>

                            <th>Quoted Amount</th>

                            <th>Validity</th>

                            <th>Status</th>

                            <th>Complete</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($submissions as $submission)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.submissions.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'submission' =>
                                                    $submission,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $submission->submission_number }}

                                    </a>

                                </td>


                                <td>

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

                                </td>


                                <td>

                                    {{
                                        $submission->submission_date
                                            ? $submission
                                                ->submission_date
                                                ->format('d-m-Y H:i')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    <strong>

                                        {{
                                            number_format(
                                                $submission
                                                    ->quoted_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    <span class="text-muted">

                                        {{ $submission->currency }}

                                    </span>

                                </td>


                                <td>

                                    @if($submission->bid_valid_until)

                                        {{ $submission
                                            ->bid_valid_until
                                            ->format('d-m-Y') }}

                                    @elseif(
                                        $submission->bid_validity_days
                                    )

                                        {{
                                            $submission
                                                ->bid_validity_days
                                        }}
                                        days

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $statusClass = match(
                                            $submission
                                                ->submission_status
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

                                        {{
                                            $submission
                                                ->submission_status
                                        }}

                                    </span>

                                </td>


                                <td>

                                    @if($submission->is_complete)

                                        <span class="badge bg-success">
                                            Yes
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            No
                                        </span>

                                    @endif

                                </td>


                                <td class="text-end">
                                    <div class="d-flex gap-2">
                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.submissions.show',
                                                [
                                                    'procurementTender' =>
                                                        $procurementTender,

                                                    'submission' =>
                                                        $submission,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


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
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>
                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No tender submissions have been
                        recorded yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.procurement.tenders.submissions.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-primary"
                    >

                        + Add First Submission

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection