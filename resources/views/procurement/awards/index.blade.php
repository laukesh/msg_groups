@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement
                /
                Tender Awards
            </div>

            <h4 class="mb-1">
                Awards
            </h4>

            <div class="text-muted">

                {{ $procurementTender->tender_number }}

                <span class="mx-1">|</span>

                {{ $procurementTender->tender_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

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
                Tender
            </a>


            @php
                $loaIssued = $awards->contains(
                    fn ($award) => $award->status === 'LOA Issued'
                );
            @endphp


            @if(!$loaIssued)

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.awards.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-add-line me-1"></i>
                    Create Award
                </a>

            @endif

        </div>

    </div>


    {{-- Project Context --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Project & Tender Context
            </strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-muted small">
                        Project Code
                    </div>

                    <div>
                        {{ $project->project_code ?? '—' }}
                    </div>

                </div>

                <div class="col-md-3">

                    <div class="text-muted small">
                        Tender
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_number }}
                    </div>

                </div>

                <div class="col-md-3">

                    <div class="text-muted small">
                        Tender Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $procurementTender->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


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


    @if($loaIssued)

        <div class="alert alert-warning">

            <strong>
                <i class="ri-lock-line me-1"></i>
                Award Finalized
            </strong>

            <div class="small mt-1">
                An LOA has been issued for this Tender.
                Award creation is now locked.
            </div>

        </div>

    @endif


    {{-- Award Register --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Award Register
            </strong>

            <span class="badge bg-primary">
                {{ $awards->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($awards->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
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

                                <th class="text-end pe-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($awards as $award)

                                <tr>

                                    <td class="ps-3">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.awards.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'award' => $award,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $award->award_number }}
                                        </a>

                                    </td>


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


                                    <td>

                                        <strong>
                                            {{ number_format(
                                                (float) $award->awarded_amount,
                                                2
                                            ) }}
                                        </strong>

                                        <span class="text-muted">
                                            {{ $award->currency }}
                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $award->award_date
                                                ?->format('d-m-Y')
                                            ?? '—'
                                        }}

                                    </td>


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


                                    <td class="text-end pe-3">

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.awards.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'award' => $award,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i
                        class="ri-award-line text-muted"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Awards Found
                    </h6>

                    <p class="text-muted mb-3">
                        No procurement award has been created
                        for this Tender yet.
                    </p>


                    @if(!$loaIssued)

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.awards.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
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