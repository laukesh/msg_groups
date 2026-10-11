@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a href="{{ route(
                    'admin.projects.procurement.packages.show',
                    [
                        'project' => $project,
                        'procurementPackage' => $procurementTender->procurementPackage,
                    ]
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
                'admin.projects.procurement.tenders.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                Back to Tender

            </a>


            <a href="{{ route(
                'admin.projects.procurement.tenders.prequalifications.create',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
               class="btn btn-primary">

                + Create Prequalification

            </a>

        </div>

    </div>


    {{-- =========================================================
        MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('info'))

        <div class="alert alert-info alert-dismissible fade show">

            {{ session('info') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Prequalifications
                    </div>

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="col-md-3">

            <div class="card h-100">

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


        {{-- Under Evaluation --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Evaluation
                    </div>

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->where('status', 'Under Evaluation')
                                ->count()
                        }}

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

                    <h4 class="mb-0">

                        {{
                            $procurementTender
                                ->prequalifications
                                ->where('status', 'Qualified')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PREQUALIFICATION TABLE
    ========================================================== --}}

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

                            <th>
                                Prequalification No.
                            </th>

                            <th>
                                Bidder
                            </th>

                            <th>
                                Submission Date
                            </th>

                            <th>
                                Evaluation Date
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
                            $procurementTender->prequalifications
                            as $prequalification
                        )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Prequalification Number --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.projects.procurement.tenders.prequalifications.show',
                                        [
                                            'project' => $project,
                                            'procurementTender' => $procurementTender,
                                            'prequalification' => $prequalification,
                                        ]
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{ $prequalification->prequalification_no }}

                                    </a>

                                </td>


                                {{-- Bidder --}}
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


                                {{-- Submission Date --}}
                                <td>

                                    {{
                                        $prequalification->submission_date
                                            ? $prequalification
                                                ->submission_date
                                                ->format('d-m-Y')
                                            : '—'
                                    }}

                                </td>


                                {{-- Evaluation Date --}}
                                <td>

                                    {{
                                        $prequalification->evaluation_date
                                            ? $prequalification
                                                ->evaluation_date
                                                ->format('d-m-Y')
                                            : '—'
                                    }}

                                </td>


                                {{-- Status --}}
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


                                {{-- Actions --}}
                                <td class="text-end">

                                    <a href="{{ route(
                                        'admin.projects.procurement.tenders.prequalifications.show',
                                        [
                                            'project' => $project,
                                            'procurementTender' => $procurementTender,
                                            'prequalification' => $prequalification,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>


                                    <a href="{{ route(
                                        'admin.projects.procurement.tenders.prequalifications.edit',
                                        [
                                            'project' => $project,
                                            'procurementTender' => $procurementTender,
                                            'prequalification' => $prequalification,
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
                        'admin.projects.procurement.tenders.prequalifications.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
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