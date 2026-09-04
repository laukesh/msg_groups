@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Latest Bid Comparison
    |--------------------------------------------------------------------------
    */

    $latestBidComparison = $procurementTender
        ->bidComparisons()
        ->with([
            'recommendedSubmission.tenderBidder.bidder',
        ])
        ->latest('id')
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Can Start Negotiation?
    |--------------------------------------------------------------------------
    |
    | Negotiation is allowed only when:
    | 1. Bid Comparison exists
    | 2. Bid Comparison is Completed or Approved
    | 3. Recommended submission exists
    |--------------------------------------------------------------------------
    */

    $canStartNegotiation =
        $latestBidComparison
        &&
        in_array(
            $latestBidComparison->status,
            ['Completed', 'Approved'],
            true
        )
        &&
        $latestBidComparison->recommendedSubmission;


    /*
    |--------------------------------------------------------------------------
    | Existing Negotiation
    |--------------------------------------------------------------------------
    */

    $latestNegotiation = $procurementTender
        ->negotiations()
        ->latest('id')
        ->first();
@endphp

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Tender
            </div>

            <h3 class="mb-1">
                {{ $procurementTender->tender_number }}
            </h3>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Edit --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.edit',
                    $procurementTender
                ) }}"
                class="btn btn-primary"
            >
                Edit Tender
            </a>


            {{-- Back --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.index'
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
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


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
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


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

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
        TENDER STATUS / QUICK INFORMATION
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Tender Type
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_type ?: '—' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Procurement Method
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->procurement_method ?: '—' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Estimated Value
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->currency }}

                        {{ number_format(
                            (float) $procurementTender->estimated_value,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php
                        $statusClass = match(
                            $procurementTender->status
                        ) {
                            'Draft' => 'bg-secondary',
                            'Published' => 'bg-primary',
                            'Open' => 'bg-info',
                            'Under Evaluation' => 'bg-warning text-dark',
                            'Awarded' => 'bg-success',
                            'Cancelled' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ $procurementTender->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PROCUREMENT HIERARCHY
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Procurement Hierarchy
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Procurement Package --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Procurement Package
                        </div>

                        @if($procurementTender->package)

                            <a
                                href="{{ route(
                                    'admin.procurement.packages.show',
                                    $procurementTender->package
                                ) }}"
                                class="fw-semibold text-decoration-none"
                            >

                                {{
                                    $procurementTender
                                        ->package
                                        ->package_number
                                }}

                            </a>

                            <div class="small text-muted mt-1">

                                {{
                                    $procurementTender
                                        ->package
                                        ->package_title
                                }}

                            </div>

                        @else

                            <span class="text-muted">
                                No package linked
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Procurement Plan --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Procurement Plan
                        </div>

                        @if(
                            $procurementTender->package
                            && $procurementTender->package->procurementPlan
                        )

                            <a
                                href="{{ route(
                                    'admin.procurement.plans.show',
                                    $procurementTender
                                        ->package
                                        ->procurementPlan
                                ) }}"
                                class="fw-semibold text-decoration-none"
                            >

                                {{
                                    $procurementTender
                                        ->package
                                        ->procurementPlan
                                        ->plan_number
                                }}

                            </a>

                            <div class="small text-muted mt-1">

                                {{
                                    $procurementTender
                                        ->package
                                        ->procurementPlan
                                        ->plan_title
                                }}

                            </div>

                        @else

                            <span class="text-muted">
                                No plan linked
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Tender --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Current Tender
                        </div>

                        <div class="fw-semibold">
                            {{ $procurementTender->tender_number }}
                        </div>

                        <div class="small text-muted mt-1">
                            {{ $procurementTender->tender_title }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        TENDER DETAILS
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Tender Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Number
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Title
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_title }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Type
                    </div>

                    <div>
                        {{ $procurementTender->tender_type ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Procurement Method
                    </div>

                    <div>
                        {{ $procurementTender->procurement_method ?: '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Fee
                    </div>

                    <div>

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float) $procurementTender->tender_fee,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        EMD Amount
                    </div>

                    <div>

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float) $procurementTender->emd_amount,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Issue Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->issue_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Submission Start Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->submission_start_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Submission Deadline
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender
                                ->submission_deadline
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Opening Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->opening_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Technical Evaluation Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->technical_evaluation_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Commercial Evaluation Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->commercial_evaluation_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Planned Award Date
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->planned_award_date
                                ?->format('d-m-Y')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Prequalification Required
                    </div>

                    @if($procurementTender->prequalification_required)

                        <span class="badge bg-success">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            No
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        DESCRIPTION / SCOPE / TERMS
    ========================================================== --}}
    <div class="row g-4 mb-4">


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Description</strong>
                </div>

                <div class="card-body">

                    @if($procurementTender->description)

                        {!! nl2br(
                            e(
                                $procurementTender->description
                            )
                        ) !!}

                    @else

                        <span class="text-muted">
                            No description provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    <strong>Scope of Work</strong>
                </div>

                <div class="card-body">

                    @if($procurementTender->scope_of_work)

                        {!! nl2br(
                            e(
                                $procurementTender->scope_of_work
                            )
                        ) !!}

                    @else

                        <span class="text-muted">
                            No scope of work provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <strong>
                        Terms & Conditions
                    </strong>
                </div>

                <div class="card-body">

                    @if($procurementTender->terms_and_conditions)

                        {!! nl2br(
                            e(
                                $procurementTender
                                    ->terms_and_conditions
                            )
                        ) !!}

                    @else

                        <span class="text-muted">
                            No terms and conditions provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        PROCUREMENT ACTIONS
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Procurement Evaluation
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                {{-- =================================================
                    BIDDERS
                ================================================== --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.bidders.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-dark"
                >
                    Bidders
                </a>


                {{-- =================================================
                    TENDER SUBMISSIONS
                ================================================== --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.submissions.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Tender Submissions
                </a>


                {{-- =================================================
                    TECHNICAL EVALUATIONS
                ================================================== --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.technical-evaluations.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-warning"
                >
                    Technical Evaluations
                </a>


                {{-- =================================================
                    COMMERCIAL EVALUATIONS
                ================================================== --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.commercial-evaluations.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-success"
                >
                    Commercial Evaluations
                </a>


                {{-- =================================================
                    BID COMPARISONS
                ================================================== --}}
                <a
                    href="{{ route(
                        'admin.procurement.tenders.bid-comparisons.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Bid Comparisons
                </a>


                {{-- =================================================
                    NEGOTIATIONS
                ================================================== --}}
                @if($latestNegotiation)

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.negotiations.show',
                            [
                                'procurementTender' =>
                                    $procurementTender,

                                'negotiation' =>
                                    $latestNegotiation,
                            ]
                        ) }}"
                        class="btn btn-warning"
                    >
                        Negotiation
                    </a>

                @elseif($canStartNegotiation)

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.negotiations.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-warning"
                    >
                        Start Negotiation
                    </a>

                @else

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        disabled
                        title="Complete Bid Comparison and select a recommended bidder first"
                    >
                        Start Negotiation
                    </button>

                @endif


                @if($latestNegotiation)

                    @if($latestNegotiation->status === 'Approved')

                        @php
                            $existingAward = $procurementTender
                                ->awards()
                                ->latest('id')
                                ->first();
                        @endphp

                        @if($existingAward)

                            <a
                                href="{{ route(
                                    'admin.procurement.tenders.awards.show',
                                    [
                                        'procurementTender' =>
                                            $procurementTender,

                                        'award' =>
                                            $existingAward,
                                    ]
                                ) }}"
                                class="btn btn-success"
                            >
                                View Award
                            </a>

                        @else

                            <a
                                href="{{ route(
                                    'admin.procurement.tenders.awards.create',
                                    $procurementTender
                                ) }}"
                                class="btn btn-success"
                            >
                                Create Award
                            </a>

                        @endif

                    @endif

                @endif


                @php
                    $latestAward = $procurementTender
                        ->awards()
                        ->latest('id')
                        ->first();

                    $latestContract = $procurementTender
                        ->contracts()
                        ->latest('id')
                        ->first();
                @endphp


                @if($latestAward)

                    @if($latestContract)

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.show',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'contract' =>
                                        $latestContract,
                                ]
                            ) }}"
                            class="btn btn-dark"
                        >
                            View Contract
                        </a>

                    @elseif($latestAward->status === 'LOA Issued')

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-dark"
                        >
                            Create Contract
                        </a>

                    @endif

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
    BID COMPARISON QUICK ACTION
    ========================================================== --}}
    <div class="card mb-4 border-primary">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Bid Comparison
                </strong>


                @if($latestBidComparison)
                    <a
                        href="{{ route(
                            'admin.procurement.tenders.bid-comparisons.show',
                            [
                                'procurementTender' => $procurementTender,
                                'comparison' => $latestBidComparison,
                            ]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        <i class="bi bi-bar-chart"></i>
                        View Comparison
                    </a>
                @else
                    <span class="text-muted">
                        No bid comparison available
                    </span>
                @endif

            </div>

        </div>


        <div class="card-body">

            @if($latestBidComparison)

                <div class="row g-3 align-items-center">

                    {{-- =================================================
                        COMPARISON INFORMATION
                    ================================================== --}}
                    <div class="col-md-7">

                        <div class="fw-semibold mb-1">

                            {{
                                $latestBidComparison
                                    ->comparison_number
                                ?? 'Bid Comparison'
                            }}

                        </div>


                        <div class="text-muted small mb-2">

                            {{
                                $latestBidComparison
                                    ->comparison_title
                                ?? 'Latest Bid Comparison'
                            }}

                        </div>


                        <div class="d-flex flex-wrap gap-2">

                            <span class="badge bg-secondary">

                                Status:
                                {{ $latestBidComparison->status }}

                            </span>


                            @if(
                                $latestBidComparison
                                    ->recommendedSubmission
                            )

                                <span class="badge bg-success">

                                    Recommended Bidder:
                                    {{
                                        $latestBidComparison
                                            ->recommendedSubmission
                                            ?->tenderBidder
                                            ?->bidder
                                            ?->company_name
                                        ?? '—'
                                    }}

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    No Recommended Bidder

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                        ACTIONS
                    ================================================== --}}
                    <div class="col-md-5 text-md-end">

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">


                            {{-- View Bid Comparison --}}
                            @if($latestBidComparison)
                            <a
                                href="{{ route(
                                    'admin.procurement.tenders.bid-comparisons.show',
                                    [
                                        'procurementTender' => $procurementTender,
                                        'comparison' => $latestBidComparison,
                                    ]
                                ) }}"
                                class="btn btn-outline-primary"
                            >
                                <i class="bi bi-bar-chart"></i>
                                View Comparison
                            </a>
                        @else
                            <span class="text-muted">
                                No bid comparison available
                            </span>
                        @endif


                            {{-- Start / View Negotiation --}}
                            @if($latestNegotiation)

                                <a
                                    href="{{ route(
                                        'admin.procurement.tenders.negotiations.show',
                                        [
                                            'procurementTender' =>
                                                $procurementTender,

                                            'negotiation' =>
                                                $latestNegotiation,
                                        ]
                                    ) }}"
                                    class="btn btn-warning"
                                >
                                    View Negotiation
                                </a>

                            @elseif($canStartNegotiation)

                                <a
                                    href="{{ route(
                                        'admin.procurement.tenders.negotiations.create',
                                        $procurementTender
                                    ) }}"
                                    class="btn btn-warning"
                                >
                                    Start Negotiation
                                </a>

                            @else

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    disabled
                                    title="Bid Comparison must be Completed or Approved and have a recommended bidder"
                                >
                                    Start Negotiation
                                </button>

                            @endif

                        </div>

                    </div>

                </div>


            @else

                {{-- =================================================
                    NO BID COMPARISON
                ================================================== --}}
                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="fw-semibold mb-1">

                            Compare commercially qualified bids

                        </div>


                        <div class="text-muted small">

                            Only submissions that have passed
                            Technical Evaluation and Commercial
                            Evaluation will be available for
                            Bid Comparison.

                        </div>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.bid-comparisons.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Create Bid Comparison
                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

    {{-- =========================================================
    NEGOTIATION QUICK ACTION
    ========================================================== --}}
    <div class="card mb-4 border-warning">

        <div class="card-header bg-warning">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Negotiation
                </strong>

                @if($latestNegotiation)

                    <span class="badge bg-dark">
                        {{ $latestNegotiation->status }}
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            @if($latestNegotiation)

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="fw-semibold">

                            {{ $latestNegotiation->negotiation_number }}

                        </div>


                        <div class="text-muted small">

                            {{ $latestNegotiation->negotiation_title }}

                        </div>


                        <div class="mt-2">

                            <span class="me-3">

                                <strong>
                                    Bidder:
                                </strong>

                                {{ $latestNegotiation->bidder_name }}

                            </span>


                            <span>

                                <strong>
                                    Final Amount:
                                </strong>

                                {{
                                    number_format(
                                        (float)
                                        $latestNegotiation->final_amount,
                                        2
                                    )
                                }}

                                {{ $latestNegotiation->currency }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.negotiations.show',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'negotiation' =>
                                        $latestNegotiation,
                                ]
                            ) }}"
                            class="btn btn-warning"
                        >
                            View Negotiation
                        </a>

                    </div>

                </div>


            @elseif($canStartNegotiation)

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="fw-semibold mb-1">

                            Bid Comparison is ready for negotiation

                        </div>


                        <div class="text-muted small">

                            Recommended Bidder:

                            <strong>

                                {{
                                    $latestBidComparison
                                        ->recommendedSubmission
                                        ?->tenderBidder
                                        ?->bidder
                                        ?->company_name
                                    ?? '—'
                                }}

                            </strong>

                        </div>


                        <div class="text-muted small mt-1">

                            Bid Comparison Status:

                            <strong>
                                {{ $latestBidComparison->status }}
                            </strong>

                        </div>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.negotiations.create',
                                $procurementTender
                            ) }}"
                            class="btn btn-warning"
                        >
                            Start Negotiation
                        </a>

                    </div>

                </div>


            @else

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="fw-semibold mb-1">

                            Negotiation is not available yet

                        </div>


                        <div class="text-muted small">

                            Complete the Bid Comparison and select
                            a recommended bidder before starting
                            negotiation.

                        </div>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            disabled
                        >
                            Start Negotiation
                        </button>

                    </div>

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        RESPONSIBLE USER
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Responsible Person</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Responsible User ID
                    </div>

                    <div>
                        {{ $procurementTender->responsible_user_id ?: '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Responsible Name
                    </div>

                    <div>
                        {{ $procurementTender->responsible_name ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REMARKS
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            @if($procurementTender->remarks)

                {!! nl2br(
                    e(
                        $procurementTender->remarks
                    )
                ) !!}

            @else

                <span class="text-muted">
                    No remarks provided.
                </span>

            @endif

        </div>

    </div>



    {{-- =========================================================
        AUDIT INFORMATION
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Audit Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div>
                        {{ $procurementTender->created_by ?: '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->created_at
                                ?->format('d-m-Y H:i')
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <div>
                        {{ $procurementTender->updated_by ?: '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <div>

                        {{
                            $procurementTender
                                ->updated_at
                                ?->format('d-m-Y H:i')
                            ?: '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection