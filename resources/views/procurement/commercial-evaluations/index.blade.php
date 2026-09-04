@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div>
                <a
                    href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}"
                >
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
                    'admin.procurement.tenders.commercial-evaluations.create',
                    $procurementTender
                ) }}"
                class="btn btn-primary"
            >
                + Add Commercial Evaluation
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Evaluations
                    </div>

                    <h4>
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

                    <h4 class="text-success">

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

                    <h4 class="text-danger">

                        {{
                            $evaluations
                                ->where('result', 'Not Qualified')
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

                    <h4>

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


    <div class="card">

        <div class="card-header">

            <strong>
                Commercial Evaluation Register
            </strong>

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
                            <th>Final Amount</th>
                            <th>Price Score</th>
                            <th>Compliance</th>
                            <th>Result</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>

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
                                            'admin.procurement.tenders.commercial-evaluations.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'evaluation' =>
                                                    $evaluation,
                                            ]
                                        ) }}"
                                        class="fw-semibold"
                                    >

                                        {{
                                            $evaluation
                                                ->evaluation_number
                                        }}

                                    </a>

                                </td>


                                <td>

                                    {{
                                        $evaluation
                                            ->submission
                                            ->tenderBidder
                                            ->bidder
                                            ->company_name
                                    }}

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
                                                    ->final_evaluated_amount,
                                                2
                                            )
                                        }}

                                    </strong>

                                    {{ $evaluation->currency }}

                                </td>


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


                                <td>

                                    <span class="badge
                                        @if($evaluation->commercial_compliance === 'Compliant')
                                            bg-success
                                        @elseif($evaluation->commercial_compliance === 'Non-Compliant')
                                            bg-danger
                                        @elseif($evaluation->commercial_compliance === 'Partially Compliant')
                                            bg-warning text-dark
                                        @else
                                            bg-secondary
                                        @endif
                                    ">

                                        {{
                                            $evaluation
                                                ->commercial_compliance
                                        }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge
                                        @if($evaluation->result === 'Qualified')
                                            bg-success
                                        @elseif($evaluation->result === 'Not Qualified')
                                            bg-danger
                                        @else
                                            bg-warning text-dark
                                        @endif
                                    ">

                                        {{ $evaluation->result }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $evaluation->status }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.commercial-evaluations.show',
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
                                            'admin.procurement.tenders.commercial-evaluations.edit',
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
                        No Commercial Evaluations found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.commercial-evaluations.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add First Commercial Evaluation
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection