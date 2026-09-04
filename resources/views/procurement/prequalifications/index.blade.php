@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a href="{{ route(
                    'admin.procurement.packages.show',
                    $procurementTender->procurementPackage
                ) }}"
                   class="text-decoration-none">

                    Package:
                    {{ $procurementTender->procurementPackage->package_number }}

                </a>

            </div>

            <h4 class="mb-1">
                Prequalifications
            </h4>

            <div class="text-muted">

                {{ $procurementTender->tender_number }}
                -
                {{ $procurementTender->tender_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.procurement.tenders.show',
                $procurementTender
            ) }}"
               class="btn btn-outline-secondary">

                Back to Tender

            </a>


            <a href="{{ route(
                'admin.procurement.tenders.prequalifications.create',
                $procurementTender
            ) }}"
               class="btn btn-primary">

                + Create Prequalification

            </a>

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Prequalifications
                    </div>

                    <h4 class="mb-0">
                        {{ $procurementTender->prequalifications->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->where('status', 'Draft')
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
                        Under Evaluation
                    </div>

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->where(
                                    'status',
                                    'Under Evaluation'
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
                        Qualified
                    </div>

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->where(
                                    'status',
                                    'Qualified'
                                )
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Prequalification Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Prequalification Assessments
            </strong>

            <span class="badge bg-primary">

                {{
                    $procurementTender
                        ->prequalifications
                        ->count()
                }}

            </span>

        </div>


        <div class="card-body p-0">

            @if(
                $procurementTender
                    ->prequalifications
                    ->count()
            )

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Prequalification No.</th>

                            <th>Bidder</th>

                            <th>Submission Date</th>

                            <th>Evaluation Date</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $procurementTender->prequalifications
                            as $prequalification
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a href="{{ route(
                                        'admin.procurement.tenders.prequalifications.show',
                                        [
                                            'procurementTender' =>
                                                $procurementTender,

                                            'prequalification' =>
                                                $prequalification,
                                        ]
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{
                                            $prequalification
                                                ->prequalification_no
                                        }}

                                    </a>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            $prequalification
                                                ->tenderBidder
                                                ->bidder
                                                ->company_name
                                        }}

                                    </div>

                                    <div class="small text-muted">

                                        {{
                                            $prequalification
                                                ->tenderBidder
                                                ->bidder
                                                ->bidder_code
                                        }}

                                    </div>

                                </td>


                                <td>

                                    {{
                                        $prequalification
                                            ->submission_date
                                            ? $prequalification
                                                ->submission_date
                                                ->format('d-m-Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $prequalification
                                            ->evaluation_date
                                            ? $prequalification
                                                ->evaluation_date
                                                ->format('d-m-Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    @php

                                        $statusClass = match(
                                            $prequalification->status
                                        ) {

                                            'Qualified'
                                                => 'bg-success',

                                            'Not Qualified',
                                            'Rejected'
                                                => 'bg-danger',

                                            'Under Evaluation'
                                                => 'bg-warning text-dark',

                                            'Submitted'
                                                => 'bg-info',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $statusClass }}">

                                        {{ $prequalification->status }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <a href="{{ route(
                                        'admin.procurement.tenders.prequalifications.show',
                                        [
                                            'procurementTender' =>
                                                $procurementTender,

                                            'prequalification' =>
                                                $prequalification,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>


                                    <a href="{{ route(
                                        'admin.procurement.tenders.prequalifications.edit',
                                        [
                                            'procurementTender' =>
                                                $procurementTender,

                                            'prequalification' =>
                                                $prequalification,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-secondary">

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

                        No prequalification assessments
                        have been created for this Tender.

                    </div>


                    <a href="{{ route(
                        'admin.procurement.tenders.prequalifications.create',
                        $procurementTender
                    ) }}"
                       class="btn btn-primary">

                        + Create First Prequalification

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection