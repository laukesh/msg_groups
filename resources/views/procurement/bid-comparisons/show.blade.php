@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @php

        $tenderAwarded =
            $procurementTender
                ->awards()
                ->whereIn('status', [
                    'LOA Issued',
                ])
                ->exists();

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


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">

                Procurement
                <span class="mx-1">/</span>
                Bid Comparison
                <span class="mx-1">/</span>
                {{ $comparison->comparison_number }}

            </div>


            <h4 class="mb-1">

                {{ $comparison->comparison_title }}

            </h4>


            <div class="text-muted">

                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

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


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.bid-comparisons.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-list-ul"></i>
                Comparisons
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
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
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

                <strong>Bid Comparison Locked</strong>

                <div class="small">
                    The Tender LOA has been issued.
                    This Bid Comparison is now view-only.
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }}"
                        >
                            {{ $comparison->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Qualified Bidders
                    </div>

                    <div class="fs-3 fw-semibold">

                        {{ $comparison->qualified_bidders }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Lowest Evaluated Amount
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $comparison->currency ?? '' }}

                        {{ number_format(
                            (float)
                            $comparison->lowest_evaluated_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Recommended Bidder
                    </div>

                    <div class="fw-semibold mt-2">

                        {{ $recommendedBidder }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMPARISON INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <div class="fw-semibold">

                <i class="bi bi-info-circle me-2"></i>

                Comparison Information

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Comparison Number
                    </div>

                    <div class="fw-semibold">
                        {{ $comparison->comparison_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Comparison Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $comparison->comparison_date
                                ?->format('d M Y')
                            ?? '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Evaluation Basis
                    </div>

                    <div class="fw-semibold">

                        {{ $comparison->evaluation_basis }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Total Bidders
                    </div>

                    <div class="fw-semibold">

                        {{ $comparison->total_bidders }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Qualified Bidders
                    </div>

                    <div class="fw-semibold">

                        {{ $comparison->qualified_bidders }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fw-semibold">

                        {{ $comparison->currency ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BID COMPARISON TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <div class="fw-semibold">

                <i class="bi bi-bar-chart-line me-2"></i>

                Bid Comparison

            </div>

        </div>


        <div class="card-body p-0">

            @if($comparison->items->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Rank
                                </th>

                                <th>
                                    Bidder
                                </th>

                                <th>
                                    Quoted Amount
                                </th>

                                <th>
                                    Evaluated Amount
                                </th>

                                <th>
                                    Tax
                                </th>

                                <th>
                                    Discount
                                </th>

                                <th>
                                    Final Evaluated
                                </th>

                                <th>
                                    Technical
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Overall
                                </th>

                                <th>
                                    Result
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $comparison->items
                                as $item
                            )

                                <tr
                                    @if($item->is_recommended)
                                        class="table-success"
                                    @endif
                                >

                                    <td>

                                        @if($item->is_recommended)

                                            <span
                                                class="badge bg-success"
                                            >
                                                #{{ $item->bidder_rank }}
                                            </span>

                                        @else

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                #{{ $item->bidder_rank }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->bidder_name }}

                                        </div>

                                        @if($item->is_recommended)

                                            <div class="small text-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Recommended

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $item->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $item->quoted_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $item->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $item->evaluated_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $item->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $item->tax_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $item->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $item->discount_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td class="fw-semibold">

                                        {{ $item->currency ?? '' }}

                                        {{ number_format(
                                            (float)
                                            $item->final_evaluated_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{
                                            $item->technical_score !== null
                                                ? number_format(
                                                    (float)
                                                    $item->technical_score,
                                                    2
                                                )
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $item->price_score !== null
                                                ? number_format(
                                                    (float)
                                                    $item->price_score,
                                                    2
                                                )
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        <span class="fw-semibold">

                                            {{
                                                $item->overall_score !== null
                                                    ? number_format(
                                                        (float)
                                                        $item->overall_score,
                                                        2
                                                    )
                                                    : '—'
                                            }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="badge bg-success">
                                            {{ $item->comparison_result }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-2">

                        <i class="bi bi-bar-chart-line fs-1"></i>

                    </div>

                    <div class="fw-semibold">
                        No comparison items found.
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        RECOMMENDATION
    ========================================================== --}}

    @if($recommendedBidder !== '—')

        <div class="card border-success shadow-sm mb-4">

            <div class="card-header bg-success-subtle">

                <div class="fw-semibold text-success">

                    <i class="bi bi-trophy me-2"></i>

                    Recommended Bidder

                </div>

            </div>


            <div class="card-body">

                <h5 class="mb-1">
                    {{ $recommendedBidder }}
                </h5>

                <div class="text-muted">

                    Recommended based on:

                    <strong>
                        {{ $comparison->evaluation_basis }}
                    </strong>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Summary
            </strong>

        </div>

        <div class="card-body">

            @if($comparison->summary)

                {!! nl2br(e($comparison->summary)) !!}

            @else

                <span class="text-muted">
                    No summary provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Remarks
            </strong>

        </div>

        <div class="card-body">

            @if($comparison->remarks)

                {!! nl2br(e($comparison->remarks)) !!}

            @else

                <span class="text-muted">
                    No remarks provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    @if(!$tenderAwarded)

        <div class="card border-danger shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong class="text-danger">

                            Delete Bid Comparison

                        </strong>

                        <div class="small text-muted">

                            Comparison items will also be deleted.

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.procurement.tenders.bid-comparisons.destroy',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                                'comparison' => $comparison,
                            ]
                        ) }}"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger"
                            onclick="return confirm(
                                'Are you sure you want to delete this Bid Comparison?'
                            )"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection