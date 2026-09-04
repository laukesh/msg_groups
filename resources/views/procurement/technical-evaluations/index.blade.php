@extends('layouts.app')

@section('content')

<div class="container-fluid">

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
                Technical Evaluations
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
                    'admin.procurement.tenders.technical-evaluations.create',
                    $procurementTender
                ) }}"
                class="btn btn-primary"
            >
                + Add Technical Evaluation
            </a>

        </div>

    </div>


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


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

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


        <div class="col-md-3">

            <div class="card">

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


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Not Qualified
                    </div>

                    <h4 class="mb-0 text-danger">

                        {{
                            $evaluations
                                ->where(
                                    'result',
                                    'Not Qualified'
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
                        Pending
                    </div>

                    <h4 class="mb-0">

                        {{
                            $evaluations
                                ->where(
                                    'result',
                                    'Pending'
                                )
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Evaluation Register --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Technical Evaluation Register
            </strong>

            <span class="badge bg-primary">
                {{ $evaluations->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($evaluations->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Evaluation No.</th>

                            <th>Bidder</th>

                            <th>Submission</th>

                            <th>Score</th>

                            <th>Compliance</th>

                            <th>Result</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($evaluations as $evaluation)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.technical-evaluations.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'evaluation' =>
                                                    $evaluation,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $evaluation->evaluation_number }}

                                    </a>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            $evaluation
                                                ->submission
                                                ->tenderBidder
                                                ->bidder
                                                ->company_name
                                        }}

                                    </div>

                                    @if(
                                        $evaluation
                                            ->submission
                                            ->tenderBidder
                                            ->bidder
                                            ->bidder_code
                                    )

                                        <div class="small text-muted">

                                            {{
                                                $evaluation
                                                    ->submission
                                                    ->tenderBidder
                                                    ->bidder
                                                    ->bidder_code
                                            }}

                                        </div>

                                    @endif

                                </td>


                                <td>

                                    {{
                                        $evaluation
                                            ->submission
                                            ->submission_number
                                    }}

                                </td>


                                <td>

                                    <strong>
                                        {{
                                            number_format(
                                                $evaluation
                                                    ->technical_score,
                                                2
                                            )
                                        }}
                                    </strong>

                                    /
                                    {{
                                        number_format(
                                            $evaluation
                                                ->maximum_score,
                                            2
                                        )
                                    }}

                                    <div class="small text-muted">

                                        Pass:
                                        {{
                                            number_format(
                                                $evaluation
                                                    ->passing_score,
                                                2
                                            )
                                        }}

                                    </div>

                                </td>


                                <td>

                                    @php

                                        $complianceClass = match(
                                            $evaluation
                                                ->technical_compliance
                                        ) {

                                            'Compliant'
                                                => 'bg-success',

                                            'Partially Compliant'
                                                => 'bg-warning text-dark',

                                            'Non-Compliant'
                                                => 'bg-danger',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $complianceClass }}"
                                    >

                                        {{
                                            $evaluation
                                                ->technical_compliance
                                        }}

                                    </span>

                                </td>


                                <td>

                                    @php

                                        $resultClass = match(
                                            $evaluation->result
                                        ) {

                                            'Qualified'
                                                => 'bg-success',

                                            'Not Qualified'
                                                => 'bg-danger',

                                            default
                                                => 'bg-warning text-dark',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $resultClass }}"
                                    >

                                        {{ $evaluation->result }}

                                    </span>

                                </td>


                                <td>

                                    @php

                                        $statusClass = match(
                                            $evaluation->status
                                        ) {

                                            'Completed'
                                                => 'bg-primary',

                                            'Approved'
                                                => 'bg-success',

                                            'Rejected'
                                                => 'bg-danger',

                                            'Under Evaluation'
                                                => 'bg-warning text-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $evaluation->status }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.technical-evaluations.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'evaluation' =>
                                                    $evaluation,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.technical-evaluations.edit',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'evaluation' =>
                                                    $evaluation,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No technical evaluations have been
                        recorded for this Tender.

                    </div>


                    <a
                        href="{{ route(
                            'admin.procurement.tenders.technical-evaluations.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-primary"
                    >

                        + Add First Evaluation

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection