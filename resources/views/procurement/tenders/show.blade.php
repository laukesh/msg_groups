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
    */

    $canStartNegotiation =
        $latestBidComparison
        &&
        in_array(
            $latestBidComparison->status,
            [
                'Completed',
                'Approved',
            ],
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


    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    $statusClass = match(
        $procurementTender->status
    ) {

        'Published'
            => 'bg-primary',

        'Open'
            => 'bg-success',

        'Under Evaluation'
            => 'bg-warning text-dark',

        'Awarded'
            => 'bg-info',

        'Cancelled'
            => 'bg-danger',

        'Closed'
            => 'bg-secondary',

        default
            => 'bg-secondary',
    };

@endphp


<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Management
            </div>

            <h3 class="mb-1">
                {{ $procurementTender->tender_number }}
            </h3>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

            <div class="small text-muted mt-2">

                Project:

                <strong>
                    {{ $project->project_name }}
                </strong>

                @if($project->project_code)

                    <span class="ms-1">
                        ({{ $project->project_code }})
                    </span>

                @endif

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            @if($procurementTender->status === 'Draft')

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.edit',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit Tender
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to Tenders
            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
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
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Tender Type --}}

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Tender Type
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_type ?: '—' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Procurement Method --}}

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Procurement Method
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->procurement_method ?: '—' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Estimated Value --}}

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Estimated Value
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float)
                                $procurementTender->estimated_value,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Status --}}

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $procurementTender->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PROCUREMENT HIERARCHY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Hierarchy
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Project --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                    @if($project->project_code)

                        <div class="small text-muted">
                            {{ $project->project_code }}
                        </div>

                    @endif

                </div>


                {{-- Procurement Plan --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Procurement Plan
                    </div>

                    @if(
                        $procurementTender->package &&
                        $procurementTender->package->procurementPlan
                    )

                        <div class="fw-semibold">

                            {{
                                $procurementTender
                                    ->package
                                    ->procurementPlan
                                    ->plan_number
                                    ?? '—'
                            }}

                        </div>

                    @else

                        —

                    @endif

                </div>


                {{-- Package --}}

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Procurement Package
                    </div>

                    @if($procurementTender->package)

                        <div class="fw-semibold">

                            {{
                                $procurementTender
                                    ->package
                                    ->package_number
                            }}

                        </div>

                        @if(
                            $procurementTender
                                ->package
                                ->package_title
                        )

                            <div class="small text-muted">

                                {{
                                    $procurementTender
                                        ->package
                                        ->package_title
                                }}

                            </div>

                        @endif

                    @else

                        —

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TENDER DETAILS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Tender Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

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
                        Currency
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->currency }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Estimated Value
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float)
                                $procurementTender->estimated_value,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Fee
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float)
                                $procurementTender->tender_fee,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        EMD Amount
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->currency }}

                        {{
                            number_format(
                                (float)
                                $procurementTender->emd_amount,
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Responsible User
                    </div>

                    <div class="fw-semibold">

                        {{
                            optional(
                                $procurementTender->responsibleUser
                            )->name
                            ?: $procurementTender->responsible_name
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Prequalification
                    </div>

                    <div>

                        @if($procurementTender->prequalification_required)

                            <span class="badge bg-primary">
                                Required
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Not Required
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Created
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->created_at
                                ? $procurementTender
                                    ->created_at
                                    ->format('d-m-Y H:i')
                                : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TENDER TIMELINE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Tender Timeline
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Issue Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->issue_date
                                ? $procurementTender
                                    ->issue_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission Start
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->submission_start_date
                                ? $procurementTender
                                    ->submission_start_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Submission Deadline
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->submission_deadline
                                ? $procurementTender
                                    ->submission_deadline
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Opening Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->opening_date
                                ? $procurementTender
                                    ->opening_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Technical Evaluation
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->technical_evaluation_date
                                ? $procurementTender
                                    ->technical_evaluation_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Commercial Evaluation
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->commercial_evaluation_date
                                ? $procurementTender
                                    ->commercial_evaluation_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned Award
                    </div>

                    <div class="fw-semibold">

                        {{
                            $procurementTender->planned_award_date
                                ? $procurementTender
                                    ->planned_award_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    DOWNSTREAM PROCUREMENT MODULES
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Tender Process
            </strong>

            <div class="small text-muted mt-1">
                Manage the downstream procurement workflow for this Tender.
            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- =================================================
                    BIDDERS
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Bidders
                        </h6>

                        <p class="small text-muted">
                            Manage participating bidders and bidder information.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.bidders.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Bidders
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    PREQUALIFICATION
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Prequalification
                        </h6>

                        <p class="small text-muted">
                            Manage bidder prequalification and qualification criteria.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.prequalifications.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Prequalification
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    SUBMISSIONS
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Tender Submissions
                        </h6>

                        <p class="small text-muted">
                            Manage bidder submissions and tender responses.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.submissions.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View Submissions
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    TECHNICAL EVALUATION
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Technical Evaluation
                        </h6>

                        <p class="small text-muted">
                            Evaluate technical compliance and qualification.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.technical-evaluations.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Technical Evaluation
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    COMMERCIAL EVALUATION
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Commercial Evaluation
                        </h6>

                        <p class="small text-muted">
                            Review financial and commercial submissions.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.commercial-evaluations.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Commercial Evaluation
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    BID COMPARISON
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Bid Comparison
                        </h6>

                        <p class="small text-muted">
                            Compare evaluated bids and identify the recommended submission.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.bid-comparisons.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Bid Comparison
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    NEGOTIATION
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Negotiation
                        </h6>

                        <p class="small text-muted">
                            Manage post-evaluation commercial negotiations.
                        </p>


                        @if($latestNegotiation)

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.negotiations.show',
                                    [
                                        'project' => $project,
                                        'procurementTender' => $procurementTender,
                                        'negotiation' => $latestNegotiation,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View Negotiation
                            </a>


                        @elseif($canStartNegotiation)

                            <a
                                href="{{ route(
                                    'admin.projects.procurement.tenders.negotiations.create',
                                    [
                                        'project' => $project,
                                        'procurementTender' => $procurementTender,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                Start Negotiation
                            </a>


                        @else

                            <span class="text-muted small">
                                Available after completed/approved
                                bid comparison with a recommended submission.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    AWARDS
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Award
                        </h6>

                        <p class="small text-muted">
                            Manage tender award and Letter of Award workflow.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.awards.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Award
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    CONTRACT
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Contract
                        </h6>

                        <p class="small text-muted">
                            Manage contracts generated from this Tender.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Contracts
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    TENDER DOCUMENTS
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Tender Documents
                        </h6>

                        <p class="small text-muted">
                            Manage drawings, specifications, BOQs, addendums and other tender documents.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.documents.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Documents
                        </a>

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- =========================================================
    PROCUREMENT EXECUTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Execution
            </strong>

            <div class="small text-muted mt-1">
                Manage purchase orders and downstream material execution for this Tender.
            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- =================================================
                    PURCHASE ORDERS
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Purchase Orders
                        </h6>

                        <p class="small text-muted">
                            Create and manage purchase orders generated from this Tender.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.purchase-orders.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Manage Purchase Orders
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    DELIVERIES
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Deliveries
                        </h6>

                        <p class="small text-muted">
                            Select a Purchase Order to manage and track its deliveries.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.purchase-orders.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-success"
                        >
                            Select Purchase Order
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    MATERIAL TRACKING
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <h6>
                            Material Tracking
                        </h6>

                        <p class="small text-muted">
                            Select a Purchase Order to track received, accepted, issued and consumed materials.
                        </p>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.purchase-orders.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-sm btn-outline-success"
                        >
                            Select Purchase Order
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    TENDER COMMERCIAL SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Tender Commercial Summary
            </strong>

            <div class="small text-muted mt-1">
                Tender, award and contract values for this procurement.
            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- =================================================
                    TENDER ESTIMATED VALUE
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Tender Estimated Value
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{
                                number_format(
                                    (float) $procurementTender->estimated_value,
                                    2
                                )
                            }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    AWARDED VALUE
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Awarded Value
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($awardValue, 2) }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CONTRACT VALUE
                ================================================== --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Contract Value
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($contractValue, 2) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    PROCUREMENT COMMITMENT
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Commitment
            </strong>

            <div class="small text-muted mt-1">
                Purchase orders currently issued against the Tender.
            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- =================================================
                    PURCHASE ORDER COUNT
                ================================================== --}}

                <div class="col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Purchase Orders
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $purchaseOrderCount }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PURCHASE ORDER COMMITMENT
                ================================================== --}}

                <div class="col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Purchase Order Commitment
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($purchaseOrderValue, 2) }}

                        </div>

                        @if($contractValue > 0)

                            <div class="small text-muted mt-1">

                                {{
                                    number_format(
                                        min(
                                            100,
                                            ($purchaseOrderValue / $contractValue) * 100
                                        ),
                                        1
                                    )
                                }}%
                                of Contract Value committed

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    PROCUREMENT EXECUTION SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Procurement Execution Summary
            </strong>

            <div class="small text-muted mt-1">
                Overview of downstream procurement activity for this Tender.
            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Purchase Orders --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Purchase Orders
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $purchaseOrderCount }}
                        </div>

                    </div>

                </div>


                {{-- Deliveries --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Deliveries
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $deliveryCount }}
                        </div>

                    </div>

                </div>


                {{-- Material Tracking --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Material Tracking
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $materialTrackingCount }}
                        </div>

                    </div>

                </div>


                {{-- Contracts --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Contracts
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $contractCount }}
                        </div>

                    </div>

                </div>


                {{-- Invoices --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Invoices
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $invoiceCount }}
                        </div>

                    </div>

                </div>


                {{-- Payments --}}

                <div class="col-md-4 col-lg-2">

                    <div class="border rounded p-3 text-center h-100">

                        <div class="text-muted small">
                            Payments
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $paymentCount }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    INVOICE & PAYMENT SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Invoice & Payment Summary
            </strong>

            <div class="small text-muted mt-1">
                Financial position based on invoices and recorded payments.
            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- =================================================
                    TOTAL INVOICES
                ================================================== --}}

                <div class="col-md-4 col-lg-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Total Invoices
                        </div>

                        <div class="fs-3 fw-semibold">
                            {{ $invoiceCount }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TOTAL INVOICED VALUE
                ================================================== --}}

                <div class="col-md-4 col-lg-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Total Invoiced Value
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($totalInvoiceValue, 2) }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TOTAL PAID
                ================================================== --}}

                <div class="col-md-4 col-lg-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Total Paid
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($paidAmount, 2) }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    REMAINING TO PAY
                ================================================== --}}

                <div class="col-md-4 col-lg-3">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small mb-1">
                            Remaining to Pay
                        </div>

                        <div class="fw-semibold fs-5">

                            {{ $procurementTender->currency }}

                            {{ number_format($remainingToPay, 2) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                PAYMENT PROGRESS
            ================================================== --}}

            <div class="mt-4">

                <div class="d-flex justify-content-between mb-1">

                    <span class="small text-muted">
                        Payment Progress
                    </span>

                    <span class="small fw-semibold">
                        {{ number_format($paymentPercentage, 1) }}%
                    </span>

                </div>

                <div
                    class="progress"
                    role="progressbar"
                    aria-valuenow="{{ $paymentPercentage }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    style="height: 8px;"
                >

                    <div
                        class="progress-bar"
                        style="width: {{ $paymentPercentage }}%;"
                    ></div>

                </div>

            </div>


            {{-- =================================================
                PAYMENT CALCULATION
            ================================================== --}}

            <div class="border rounded p-3 mt-4 bg-light">

                <div class="small fw-semibold mb-2">
                    Payment Calculation
                </div>

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Total Invoiced
                        </div>

                        <div class="fw-semibold">

                            {{ $procurementTender->currency }}

                            {{ number_format($totalInvoiceValue, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Total Paid
                        </div>

                        <div class="fw-semibold">

                            {{ $procurementTender->currency }}

                            {{ number_format($paidAmount, 2) }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Remaining to Pay
                        </div>

                        <div class="fw-semibold">

                            {{ $procurementTender->currency }}

                            {{ number_format($remainingToPay, 2) }}

                        </div>

                    </div>

                </div>

                <div class="small text-muted mt-3">

                    Remaining to Pay =
                    Total Invoiced Value − Total Paid

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BID COMPARISON
    ========================================================== --}}

    @if($latestBidComparison)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Latest Bid Comparison
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Status
                        </div>

                        <div class="fw-semibold">
                            {{ $latestBidComparison->status }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Recommended Bidder
                        </div>

                        <div class="fw-semibold">

                            {{
                                optional(
                                    optional(
                                        $latestBidComparison->recommendedSubmission
                                    )->tenderBidder
                                )->bidder?->company_name
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Negotiation
                        </div>

                        <div>

                            @if($canStartNegotiation)

                                <span class="badge bg-success">
                                    Available
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Not Available
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        DESCRIPTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                Description & Scope
            </strong>

        </div>


        <div class="card-body">

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Description
                </div>

                @if($procurementTender->description)

                    <div>

                        {!! nl2br(
                            e(
                                $procurementTender->description
                            )
                        ) !!}

                    </div>

                @else

                    <span class="text-muted">
                        No description provided.
                    </span>

                @endif

            </div>


            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Scope of Work
                </div>

                @if($procurementTender->scope_of_work)

                    <div>

                        {!! nl2br(
                            e(
                                $procurementTender->scope_of_work
                            )
                        ) !!}

                    </div>

                @else

                    <span class="text-muted">
                        No scope of work provided.
                    </span>

                @endif

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Terms & Conditions
                </div>

                @if($procurementTender->terms_and_conditions)

                    <div>

                        {!! nl2br(
                            e(
                                $procurementTender
                                    ->terms_and_conditions
                            )
                        ) !!}

                    </div>

                @else

                    <span class="text-muted">
                        No terms and conditions provided.
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($procurementTender->remarks)

        <div class="card border-0 shadow-sm mb-5">

            <div class="card-header bg-white">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                {!! nl2br(
                    e(
                        $procurementTender->remarks
                    )
                ) !!}

            </div>

        </div>

    @endif

</div>

@endsection