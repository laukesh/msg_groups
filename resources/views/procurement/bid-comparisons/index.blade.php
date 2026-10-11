@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement
                <span class="mx-1">/</span>
                Bid Comparison
            </div>

            <h4 class="mb-1">
                Bid Comparisons
            </h4>

            <div class="text-muted">

                {{ $procurementTender->tender_number }}

                <span class="mx-1">—</span>

                {{ $procurementTender->tender_title }}

            </div>

            @if($project)

                <div class="mt-2">

                    <span class="badge bg-light text-dark border">
                        Project:
                        {{ $project->project_code ?? $project->id }}
                    </span>

                    @if($project->project_name)

                        <span class="text-muted small ms-2">
                            {{ $project->project_name }}
                        </span>

                    @endif

                </div>

            @endif

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
                <i class="bi bi-arrow-left"></i>
                Tender
            </a>

            @php

                $tenderAwarded =
                    $procurementTender
                        ->awards()
                        ->whereIn('status', [
                            'LOA Issued',
                        ])
                        ->exists();

            @endphp


            @if(!$tenderAwarded)

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.bid-comparisons.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Comparison
                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        LOA LOCK
    ========================================================== --}}

    @if($tenderAwarded)

        <div class="alert alert-warning d-flex align-items-center">

            <i class="bi bi-lock-fill me-2"></i>

            <div>

                <strong>Tender Award Locked</strong>

                <div class="small">
                    Bid Comparisons are now view-only because
                    the Tender LOA has been issued.
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Comparisons
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $comparisons->count() }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold text-success">

                        {{
                            $comparisons
                                ->where('status', 'Completed')
                                ->count()
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-semibold text-primary">

                        {{
                            $comparisons
                                ->where('status', 'Approved')
                                ->count()
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMPARISON REGISTER
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="fw-semibold">
                <i class="bi bi-bar-chart-line me-2"></i>
                Bid Comparison Register
            </div>

        </div>


        <div class="card-body p-0">

            @if($comparisons->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Comparison
                                </th>

                                <th>
                                    Evaluation Basis
                                </th>

                                <th>
                                    Qualified Bidders
                                </th>

                                <th>
                                    Lowest Evaluated
                                </th>

                                <th>
                                    Recommended Bidder
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="120">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $comparisons as $comparison
                            )

                                @php

                                    $statusClass = match(
                                        $comparison->status
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

                                    $recommendedBidder =
                                        $comparison
                                            ->recommendedSubmission
                                            ?->tenderBidder
                                            ?->bidder
                                            ?->company_name
                                        ?: '—';

                                @endphp

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.bid-comparisons.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'comparison' => $comparison,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $comparison->comparison_number }}
                                        </a>

                                        <div class="small text-muted">

                                            {{
                                                $comparison->comparison_title
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        <span class="small">

                                            {{
                                                $comparison->evaluation_basis
                                            }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{
                                                $comparison->qualified_bidders
                                            }}

                                        </span>

                                    </td>


                                    <td class="fw-semibold">

                                        {{ $comparison->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $comparison->lowest_evaluated_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        @if($recommendedBidder !== '—')

                                            <span class="fw-semibold">
                                                {{ $recommendedBidder }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $comparison
                                                ->comparison_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $comparison->status }}
                                        </span>

                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.tenders.bid-comparisons.show',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'comparison' => $comparison,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>


                                            @if(!$tenderAwarded)

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.procurement.tenders.bid-comparisons.edit',
                                                        [
                                                            'project' => $project,
                                                            'procurementTender' => $procurementTender,
                                                            'comparison' => $comparison,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Edit"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-bar-chart-line fs-1 text-muted"
                        ></i>

                    </div>

                    <h6>
                        No Bid Comparisons Found
                    </h6>

                    <p class="text-muted mb-4">

                        Create a bid comparison after
                        qualified commercial evaluations
                        are available.

                    </p>


                    @if(!$tenderAwarded)

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.bid-comparisons.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Create Comparison
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection