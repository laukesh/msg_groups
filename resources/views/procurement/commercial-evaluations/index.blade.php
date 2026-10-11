@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- Header --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="text-decoration-none"
                >

                    <i class="ri-arrow-left-line me-1"></i>

                    Tender:
                    {{ $procurementTender->tender_number }}

                </a>

            </div>


            <h4 class="mb-1">
                Commercial Evaluations
            </h4>


            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back to Tender
            </a>


            {{-- Add Commercial Evaluation --}}
            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.commercial-evaluations.create',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Commercial Evaluation
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Success Message --}}
    {{-- ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- Error Message --}}
    {{-- ============================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- Summary Cards --}}
    {{-- ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Evaluations
                    </div>

                    <h4 class="mb-0">
                        {{ $evaluations->count() }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Qualified --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Qualified
                    </div>

                    <h4 class="mb-0 text-success">

                        {{
                            $evaluations
                                ->where('result', 'Qualified')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Not Qualified --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Not Qualified
                    </div>

                    <h4 class="mb-0 text-danger">

                        {{
                            $evaluations
                                ->where('result', 'Not Qualified')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending
                    </div>

                    <h4 class="mb-0 text-warning">

                        {{
                            $evaluations
                                ->where('result', 'Pending')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Commercial Evaluation Register --}}
    {{-- ============================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Commercial Evaluation Register
            </strong>

            <span class="text-muted small">
                {{ $evaluations->count() }} record(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($evaluations->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    #
                                </th>

                                <th>
                                    Evaluation No.
                                </th>

                                <th>
                                    Bidder
                                </th>

                                <th>
                                    Submission
                                </th>

                                <th>
                                    Final Amount
                                </th>

                                <th>
                                    Price Score
                                </th>

                                <th>
                                    Compliance
                                </th>

                                <th>
                                    Result
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($evaluations as $evaluation)

                                <tr>

                                    {{-- # --}}
                                    <td class="ps-3">
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Evaluation Number --}}
                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.commercial-evaluations.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'evaluation' => $evaluation,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $evaluation->evaluation_number }}

                                        </a>

                                        @if($evaluation->evaluation_date)

                                            <div class="text-muted small">

                                                {{
                                                    $evaluation
                                                        ->evaluation_date
                                                        ->format('d-m-Y')
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Bidder --}}
                                    <td>

                                        {{
                                            $evaluation
                                                ->submission
                                                ->tenderBidder
                                                ->bidder
                                                ->company_name
                                        }}

                                    </td>


                                    {{-- Submission --}}
                                    <td>

                                        <span class="fw-medium">

                                            {{
                                                $evaluation
                                                    ->submission
                                                    ->submission_number
                                            }}

                                        </span>

                                    </td>


                                    {{-- Final Amount --}}
                                    <td>

                                        <strong>

                                            {{
                                                number_format(
                                                    $evaluation
                                                        ->final_evaluated_amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                        <span class="text-muted">

                                            {{ $evaluation->currency }}

                                        </span>

                                    </td>


                                    {{-- Price Score --}}
                                    <td>

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

                                    </td>


                                    {{-- Compliance --}}
                                    <td>

                                        @if(
                                            $evaluation->commercial_compliance
                                            === 'Compliant'
                                        )

                                            <span class="badge bg-success">
                                                Compliant
                                            </span>

                                        @elseif(
                                            $evaluation->commercial_compliance
                                            === 'Non-Compliant'
                                        )

                                            <span class="badge bg-danger">
                                                Non-Compliant
                                            </span>

                                        @elseif(
                                            $evaluation->commercial_compliance
                                            === 'Partially Compliant'
                                        )

                                            <span class="badge bg-warning text-dark">
                                                Partially Compliant
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Result --}}
                                    <td>

                                        @if($evaluation->result === 'Qualified')

                                            <span class="badge bg-success">
                                                Qualified
                                            </span>

                                        @elseif(
                                            $evaluation->result === 'Not Qualified'
                                        )

                                            <span class="badge bg-danger">
                                                Not Qualified
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $statusClass = match(
                                                $evaluation->status
                                            ) {

                                                'Approved' =>
                                                    'bg-success',

                                                'Completed' =>
                                                    'bg-primary',

                                                'Rejected' =>
                                                    'bg-danger',

                                                'Under Evaluation' =>
                                                    'bg-warning text-dark',

                                                default =>
                                                    'bg-secondary',

                                            };

                                        @endphp


                                        <span class="badge {{ $statusClass }}">

                                            {{ $evaluation->status }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-3">

                                        <div
                                            class="d-flex justify-content-end gap-2"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.tenders.commercial-evaluations.show',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'evaluation' => $evaluation,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="ri-eye-line"></i>
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.tenders.commercial-evaluations.edit',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'evaluation' => $evaluation,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                <i class="ri-edit-line"></i>
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

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="ri-file-list-3-line"
                            style="font-size: 48px;"
                        ></i>

                    </div>


                    <h5>
                        No Commercial Evaluations Found
                    </h5>


                    <p class="text-muted mb-3">

                        No commercial evaluation has been
                        created for this tender yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.commercial-evaluations.create',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="ri-add-line me-1"></i>
                        Add Commercial Evaluation
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection