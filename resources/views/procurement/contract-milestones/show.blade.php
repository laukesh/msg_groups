@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                {{ $milestone->milestone_number }}
            </h4>

            <div class="text-muted">
                {{ $milestone->milestone_title }}
            </div>

        </div>


        {{-- ACTIONS --}}

        <div class="d-flex flex-wrap gap-2">

            {{-- BACK --}}

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.index',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>


            {{-- PROGRESS UPDATES --}}

            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.progress.index',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-info"
                >
                    Progress Updates
                </a>


                {{-- ADD PROGRESS --}}

                @if($milestone->status !== 'Completed')

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.contracts.milestones.progress.create',
                            [
                                'procurementTender' => $procurementTender,
                                'contract' => $contract,
                                'milestone' => $milestone,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Add Progress
                    </a>

                @endif

            @endif


            {{-- DELIVERABLE DOCUMENTS --}}

            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.documents.index',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-outline-info"
                >
                    Deliverable Documents
                </a>

            @endif


            {{-- START MILESTONE --}}

            @if($milestone->status === 'Pending')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.contracts.milestones.start',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Start this milestone?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Start Milestone
                    </button>

                </form>

            @endif


            {{-- COMPLETE MILESTONE --}}

            @if(
                $milestone->status === 'In Progress'
                && $contract->status === 'Active'
            )

                @php

                    $milestoneDocuments =
                        $milestone->documents
                        ?? collect();

                    $verifiedDocuments =
                        $milestoneDocuments
                            ->where('status', 'Verified')
                            ->count();

                    $unverifiedDocuments =
                        $milestoneDocuments
                            ->whereIn(
                                'status',
                                [
                                    'Submitted',
                                    'Rejected',
                                ]
                            )
                            ->count();

                @endphp


                {{-- =========================================================
                    NO DELIVERABLE REQUIRED
                ========================================================== --}}

                @if(!$milestone->deliverable_required)

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#completeMilestoneModal"
                    >
                        Complete Milestone
                    </button>


                {{-- =========================================================
                    DELIVERABLE REQUIRED
                ========================================================== --}}

                @else

                    @if(
                        $verifiedDocuments > 0
                        && $unverifiedDocuments === 0
                    )

                        {{-- ALL DOCUMENTS VERIFIED --}}

                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#completeMilestoneModal"
                        >
                            Complete Milestone
                        </button>

                    @else

                        {{-- CANNOT COMPLETE --}}

                        <button
                            type="button"
                            class="btn btn-secondary"
                            disabled
                        >
                            Complete Milestone
                        </button>

                    @endif

                @endif

            @endif

            {{-- ============================================================
                 CREATE INVOICE
            ============================================================= --}}

            @if(
                $milestone->status === 'Completed'
                && $contract->status === 'Active'
            )

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Total Invoiced
                    |--------------------------------------------------------------------------
                    */

                    $totalInvoiced = $milestone
                        ->invoices()
                        ->where('status', '!=', 'Rejected')
                        ->sum('net_amount');


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Remaining Amount
                    |--------------------------------------------------------------------------
                    */

                    $remainingToInvoice = max(
                        0,
                        (float) $milestone->milestone_amount
                            - (float) $totalInvoiced
                    );

                @endphp


                @if($remainingToInvoice > 0)

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.contracts.milestones.invoice.create',
                            [
                                'procurementTender' => $procurementTender,
                                'contract' => $contract,
                                'milestone' => $milestone,
                            ]
                        ) }}"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-receipt me-1"></i>
                        Create Invoice
                    </a>

                @else

                    <button
                        type="button"
                        class="btn btn-secondary"
                        disabled
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Fully Invoiced
                    </button>

                @endif

            @endif

        </div>

    </div>


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

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


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
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


    {{-- ============================================================
        PREPARE PROGRESS VALUE
    ============================================================= --}}

    @php

        $progress = (float) (
            $milestone->progress_percentage ?? 0
        );

        $progress = min(
            100,
            max(0, $progress)
        );


        $progressDisplay = number_format(
            $progress,
            2
        );


        /*
         * Latest progress update.
         *
         * Prefer the relationship if it has been loaded.
         * Otherwise safely use the latest collection item.
         */

        $latestProgress = null;

        if (
            isset($milestone->latestProgress)
            && $milestone->latestProgress
        ) {

            $latestProgress =
                $milestone->latestProgress;

        } elseif (
            isset($milestone->progressUpdates)
            && $milestone->progressUpdates->isNotEmpty()
        ) {

            $latestProgress =
                $milestone->progressUpdates
                    ->sortByDesc('progress_date')
                    ->sortByDesc('id')
                    ->first();

        }


        /*
         * Status badge
         */

        $statusClass = match(
            $milestone->status
        ) {

            'Completed' =>
                'bg-success',

            'In Progress' =>
                'bg-primary',

            'Delayed' =>
                'bg-danger',

            'Pending' =>
                'bg-secondary',

            default =>
                'bg-secondary',

        };

    @endphp


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- STATUS --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $milestone->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- PROGRESS --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted">
                            Current Progress
                        </small>

                        <strong>
                            {{ $progressDisplay }}%
                        </strong>

                    </div>


                    {{-- PROGRESS BAR --}}

                    <div
                        class="progress mt-3"
                        style="height: 22px;"
                    >

                        <div
                            class="progress-bar
                                @if($milestone->status === 'Completed')
                                    bg-success
                                @elseif($milestone->status === 'Delayed')
                                    bg-danger
                                @elseif($milestone->status === 'In Progress')
                                    bg-primary
                                @else
                                    bg-secondary
                                @endif
                            "
                            role="progressbar"
                            style="width: {{ $progress }}%;"
                            aria-valuenow="{{ $progress }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >

                            {{ $progressDisplay }}%

                        </div>

                    </div>


                    {{-- LAST UPDATE --}}

                    @if($latestProgress)

                        <div class="small text-muted mt-2">

                            Last update:

                            {{
                                $latestProgress->progress_date
                                    ?->format('d-m-Y')
                                ?? '—'
                            }}

                        </div>

                    @else

                        <div class="small text-muted mt-2">
                            No progress updates yet.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- MILESTONE AMOUNT --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Milestone Amount
                    </small>

                    <h5 class="mt-2 mb-0">

                        {{
                            number_format(
                                (float)
                                $milestone->milestone_amount,
                                2
                            )
                        }}

                        {{ $milestone->currency }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- DELIVERABLE --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Deliverable
                    </small>

                    <h6 class="mt-2 mb-0">

                        @if($milestone->deliverable_required)

                            <span class="badge bg-warning text-dark">
                                Required
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Not Required
                            </span>

                        @endif

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PROGRESS OVERVIEW
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Progress Overview
            </strong>

            @if($contract->status === 'Active' && $milestone->status !== 'Completed')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.progress.create',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    + Update Progress
                </a>

            @endif

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span class="text-muted">
                    Milestone Completion
                </span>

                <strong>
                    {{ $progressDisplay }}%
                </strong>

            </div>


            <div
                class="progress"
                style="height: 30px;"
            >

                <div
                    class="progress-bar
                        @if($milestone->status === 'Completed')
                            bg-success
                        @elseif($milestone->status === 'Delayed')
                            bg-danger
                        @else
                            bg-primary
                        @endif
                    "
                    role="progressbar"
                    style="width: {{ $progress }}%;"
                    aria-valuenow="{{ $progress }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >

                    <strong>
                        {{ $progressDisplay }}%
                    </strong>

                </div>

            </div>


            @if($latestProgress)

                <div class="row g-3 mt-3">

                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Previous Progress
                        </small>

                        <strong>

                            {{
                                number_format(
                                    (float)
                                    $latestProgress
                                        ->previous_progress_percentage,
                                    2
                                )
                            }}%

                        </strong>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Latest Progress
                        </small>

                        <strong>

                            {{
                                number_format(
                                    (float)
                                    $latestProgress
                                        ->progress_percentage,
                                    2
                                )
                            }}%

                        </strong>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Progress Date
                        </small>

                        <strong>

                            {{
                                $latestProgress->progress_date
                                    ?->format('d-m-Y')
                                ?? '—'
                            }}

                        </strong>

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
        SCHEDULE
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Schedule
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- PLANNED START --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Planned Start
                    </small>

                    <strong>

                        {{
                            $milestone->planned_start_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- PLANNED END --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Planned End
                    </small>

                    <strong>

                        {{
                            $milestone->planned_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- ACTUAL START --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual Start
                    </small>

                    <strong>

                        {{
                            $milestone->actual_start_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- ACTUAL END --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual End
                    </small>

                    <strong>

                        {{
                            $milestone->actual_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DESCRIPTION
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Description
            </strong>

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $milestone->description
                    ?? 'No description provided.'
                )
            ) !!}

        </div>

    </div>


    {{-- ============================================================
        DELIVERABLE
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Deliverable
            </strong>


            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.documents.index',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-sm btn-outline-info"
                >
                    Deliverable Documents
                </a>

            @endif

        </div>


        <div class="card-body">

            {!! nl2br(
                e(
                    $milestone->deliverable_description
                    ?? 'No deliverable description provided.'
                )
            ) !!}

        </div>

    </div>


    {{-- ============================================================
        REMARKS
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>


        <div class="card-body">

            {{ $milestone->remarks ?? '—' }}

        </div>

    </div>


    {{-- ============================================================
        QUICK ACTIONS
    ============================================================= --}}

    @if($contract->status === 'Active')

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Milestone Actions
                </strong>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">


                    {{-- PROGRESS HISTORY --}}

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.contracts.milestones.progress.index',
                            [
                                'procurementTender' => $procurementTender,
                                'contract' => $contract,
                                'milestone' => $milestone,
                            ]
                        ) }}"
                        class="btn btn-info"
                    >
                        Progress Updates
                    </a>


                    {{-- ADD PROGRESS --}}

                    @if($milestone->status !== 'Completed')

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.milestones.progress.create',
                                [
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'milestone' => $milestone,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Add Progress
                        </a>

                    @endif


                    {{-- DOCUMENTS --}}

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.contracts.milestones.documents.index',
                            [
                                'procurementTender' => $procurementTender,
                                'contract' => $contract,
                                'milestone' => $milestone,
                            ]
                        ) }}"
                        class="btn btn-outline-info"
                    >
                        Deliverable Documents
                    </a>


                    {{-- UPLOAD DOCUMENT --}}

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.contracts.milestones.documents.create',
                            [
                                'procurementTender' => $procurementTender,
                                'contract' => $contract,
                                'milestone' => $milestone,
                            ]
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        + Upload Deliverable
                    </a>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- ================================================================
    COMPLETE MILESTONE MODAL
================================================================= --}}

@if($milestone->status === 'In Progress')

<div
    class="modal fade"
    id="completeMilestoneModal"
    tabindex="-1"
    aria-labelledby="completeMilestoneModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.contracts.milestones.complete',
                [
                    'procurementTender' => $procurementTender,
                    'contract' => $contract,
                    'milestone' => $milestone,
                ]
            ) }}"
            class="modal-content"
        >

            @csrf


            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="completeMilestoneModalLabel"
                >
                    Complete Milestone
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="alert alert-info">

                    This will mark the milestone as
                    <strong>100% Completed</strong>.

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Current Progress
                    </label>

                    <div class="progress">

                        <div
                            class="progress-bar bg-primary"
                            style="width: {{ $progress }}%;"
                        >
                            {{ $progressDisplay }}%
                        </div>

                    </div>

                </div>


                <label class="form-label">
                    Completion Remarks
                </label>

                <textarea
                    name="remarks"
                    class="form-control"
                    rows="4"
                    placeholder="Enter completion remarks..."
                ></textarea>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Complete Milestone
                </button>

            </div>

        </form>

    </div>

</div>

@endif

@endsection