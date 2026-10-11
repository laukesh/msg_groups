@extends('layouts.app')

@section('content')

<style>

    /* =========================================================
       FIT-OUT REQUEST DETAIL
    ========================================================= */

    .fitout-page {
        color: #2d2723;
    }

    .fitout-header {
        background: #fbf8f5;
        border: 1px solid #e7ddd6;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .fitout-request-no {
        font-size: 23px;
        font-weight: 700;
        color: #2b211c;
        letter-spacing: -.3px;
    }

    .fitout-subtitle {
        color: #8b7d74;
        font-size: 13px;
        margin-top: 4px;
    }

    .fitout-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        justify-content: flex-end;
    }

    .fitout-actions .btn {
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .fitout-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .fitout-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-secondary {
        background: #eeeae7;
        color: #70645d;
    }

    .status-info {
        background: #e0f0f5;
        color: #12637a;
    }

    .status-warning {
        background: #fff0cf;
        color: #946000;
    }

    .status-success {
        background: #dff3e9;
        color: #087455;
    }

    .status-danger {
        background: #fde2df;
        color: #a43126;
    }

    .status-primary {
        background: #e5edff;
        color: #315db3;
    }

    .status-dark {
        background: #e7e4e2;
        color: #342e2a;
    }


    /* =========================================================
       LIFECYCLE
    ========================================================= */

    .lifecycle-card {
        background: #fff;
        border: 1px solid #e7ddd6;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .8px;
        font-weight: 700;
        color: #d45118;
        margin-bottom: 17px;
    }

    .lifecycle {
        display: flex;
        align-items: center;
        width: 100%;
        overflow-x: auto;
        padding: 5px 0;
    }

    .lifecycle-step {
        min-width: 105px;
        text-align: center;
        position: relative;
    }

    .lifecycle-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        border: 2px solid #ddd5cf;
        background: #fff;
        color: #958981;
    }

    .lifecycle-step.completed .lifecycle-dot {
        background: #16815f;
        border-color: #16815f;
        color: #fff;
    }

    .lifecycle-step.current .lifecycle-dot {
        background: #d94f14;
        border-color: #d94f14;
        color: #fff;
        box-shadow: 0 0 0 5px rgba(217, 79, 20, .10);
    }

    .lifecycle-name {
        font-size: 10px;
        margin-top: 7px;
        font-weight: 600;
        color: #766a62;
        white-space: nowrap;
    }

    .lifecycle-step.completed .lifecycle-name,
    .lifecycle-step.current .lifecycle-name {
        color: #342a25;
    }

    .lifecycle-line {
        height: 2px;
        background: #e3dcd7;
        flex: 1;
        min-width: 25px;
        margin-bottom: 20px;
    }

    .lifecycle-line.completed {
        background: #16815f;
    }


    /* =========================================================
       CARDS
    ========================================================= */

    .fitout-card {
        background: #fff;
        border: 1px solid #e7ddd6;
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .fitout-card-header {
        min-height: 52px;
        padding: 14px 18px;
        background: #fbf8f5;
        border-bottom: 1px solid #e7ddd6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fitout-card-header strong {
        font-size: 13px;
        color: #302722;
    }

    .fitout-card-body {
        padding: 18px;
    }


    /* =========================================================
       INFORMATION
    ========================================================= */

    .info-box {
        padding: 4px 0;
    }

    .info-label {
        display: block;
        font-size: 10px;
        color: #92857d;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 600;
        color: #302722;
        word-break: break-word;
    }

    .info-value.muted {
        color: #968a82;
        font-weight: 500;
    }


    /* =========================================================
       TABLES
    ========================================================= */

    .fitout-table {
        margin: 0;
        font-size: 12px;
    }

    .fitout-table thead th {
        background: #fbf8f5;
        color: #786b63;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        border-bottom: 1px solid #e4dbd5;
        white-space: nowrap;
        padding: 11px 12px;
    }

    .fitout-table tbody td {
        padding: 11px 12px;
        vertical-align: middle;
        color: #3c332e;
        border-color: #eee7e2;
    }

    .fitout-table tbody tr:hover {
        background: #fcfaf8;
    }


    /* =========================================================
       PROGRESS
    ========================================================= */

    .stage-progress {
        min-width: 130px;
    }

    .stage-progress .progress {
        height: 17px;
        background: #eee9e5;
        border-radius: 20px;
    }

    .stage-progress .progress-bar {
        font-size: 9px;
        font-weight: 700;
    }


    /* =========================================================
       SMALL BADGES
    ========================================================= */

    .count-badge {
        min-width: 25px;
        height: 23px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #eee8e3;
        color: #665a53;
        font-size: 10px;
        font-weight: 700;
    }

    .priority-critical {
        background: #fde1dd;
        color: #a52d22;
    }

    .priority-high {
        background: #fff0d1;
        color: #996000;
    }

    .priority-medium {
        background: #e9f0ff;
        color: #4261a0;
    }

    .priority-low {
        background: #e8f3ed;
        color: #267353;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .empty-state {
        text-align: center;
        padding: 30px 15px;
        color: #948880;
        font-size: 12px;
    }

    .empty-state i {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
        opacity: .55;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .fitout-actions {
            justify-content: flex-start;
            margin-top: 15px;
        }

        .lifecycle-step {
            min-width: 90px;
        }

    }

</style>


<div class="container-fluid fitout-page">


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="fitout-header">

        <div class="row align-items-center g-3">

            <div class="col-xl-5 col-lg-5">

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div class="fitout-request-no">

                        {{ $fitoutRequest->request_no }}

                    </div>


                    @php

                        $statusClass = match (
                            $fitoutRequest->fitout_status
                        ) {

                            'Draft' => 'status-secondary',

                            'Submitted' => 'status-info',

                            'Under Review' => 'status-warning',

                            'Approved' => 'status-success',

                            'Rejected' => 'status-danger',

                            'In Progress' => 'status-primary',

                            'Completed' => 'status-success',

                            'Closed' => 'status-dark',

                            default => 'status-secondary',

                        };

                    @endphp


                    <span class="fitout-status {{ $statusClass }}">

                        {{ $fitoutRequest->fitout_status }}

                    </span>

                </div>


                <div class="fitout-subtitle">

                    Fit-Out Request Details

                </div>

            </div>


            <div class="col-xl-7 col-lg-7">

                <div class="fitout-actions">


                    {{-- SUBMIT --}}

                    @if(
                        $fitoutRequest->fitout_status === 'Draft'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.submit',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-send me-1"></i>

                                Submit

                            </button>

                        </form>

                    @endif


                    {{-- START REVIEW --}}

                    @if(
                        $fitoutRequest->fitout_status === 'Submitted'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.startReview',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-warning"
                            >

                                <i class="bi bi-search me-1"></i>

                                Start Review

                            </button>

                        </form>

                    @endif


                    {{-- APPROVE / REJECT --}}

                    @if(
                        $fitoutRequest->fitout_status === 'Under Review'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.approve',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-check-lg me-1"></i>

                                Approve

                            </button>

                        </form>


                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectFitoutModal"
                        >

                            <i class="bi bi-x-lg me-1"></i>

                            Reject

                        </button>

                    @endif


                    {{-- START FIT-OUT --}}

                    @if(
                        $fitoutRequest->fitout_status === 'Approved'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.start',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-play-fill me-1"></i>

                                Start Fit-Out

                            </button>

                        </form>

                    @endif


                    {{-- COMPLETE --}}

                    @if(
                        $fitoutRequest->fitout_status === 'In Progress'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.complete',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Mark Completed

                            </button>

                        </form>

                    @endif


                    {{-- CLOSE --}}

                    @if(
                        $fitoutRequest->fitout_status === 'Completed'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.fitout.requests.close',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="d-inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-dark"
                            >

                                <i class="bi bi-lock me-1"></i>

                                Close

                            </button>

                        </form>

                    @endif


                    {{-- STAGES --}}

                    @if(
                        $fitoutRequest->stages->count() > 0
                    )

                        <a
                            href="{{
                                route(
                                    'admin.fitout.stages.index',
                                    $fitoutRequest->id
                                )
                            }}"
                            class="btn btn-outline-primary"
                        >

                            <i class="bi bi-list-check me-1"></i>

                            Stages

                        </a>

                    @endif


                    {{-- GENERATE APPROVAL --}}

                    @if(
                        !$fitoutRequest->approvals->count()
                        &&
                        in_array(
                            $fitoutRequest->fitout_status,
                            [
                                'Submitted',
                                'Under Review'
                            ]
                        )
                    )

                        <form
                            action="{{
                                route(
                                    'admin.fitout.requests.generate-approval',
                                    $fitoutRequest->id
                                )
                            }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="
                                return confirm(
                                    'Generate the approval workflow for this Fit-Out Request?'
                                );
                            "
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-diagram-3 me-1"></i>

                                Generate Approval Workflow

                            </button>

                        </form>

                    @endif


                    {{-- BACK --}}

                    <a
                        href="{{
                            route(
                                'admin.fitout.requests.index'
                            )
                        }}"
                        class="btn btn-secondary"
                    >

                        <i class="bi bi-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- LIFECYCLE --}}
    {{-- ========================================================= --}}

    @php

        $lifecycle = [
            'Draft',
            'Submitted',
            'Under Review',
            'Approved',
            'In Progress',
            'Completed',
            'Closed',
        ];

        $currentIndex = array_search(
            $fitoutRequest->fitout_status,
            $lifecycle
        );

    @endphp


    <div class="lifecycle-card">

        <div class="section-title">

            Fit-Out Lifecycle

        </div>


        <div class="lifecycle">

            @foreach(
                $lifecycle
                as $index => $status
            )

                @php

                    $isCompleted =
                        $currentIndex !== false
                        &&
                        $index < $currentIndex;

                    $isCurrent =
                        $currentIndex !== false
                        &&
                        $index === $currentIndex;

                @endphp


                <div
                    class="
                        lifecycle-step
                        {{ $isCompleted ? 'completed' : '' }}
                        {{ $isCurrent ? 'current' : '' }}
                    "
                >

                    <div class="lifecycle-dot">

                        @if($isCompleted)

                            <i class="bi bi-check"></i>

                        @elseif($isCurrent)

                            {{ $index + 1 }}

                        @else

                            {{ $index + 1 }}

                        @endif

                    </div>


                    <div class="lifecycle-name">

                        {{ $status }}

                    </div>

                </div>


                @if(!$loop->last)

                    <div
                        class="
                            lifecycle-line
                            {{
                                $currentIndex !== false
                                && $index < $currentIndex
                                    ? 'completed'
                                    : ''
                            }}
                        "
                    ></div>

                @endif

            @endforeach

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- REQUEST INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-file-earmark-text me-2"></i>

                Request Information

            </strong>

        </div>


        <div class="fitout-card-body">

            <div class="row g-4">


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Request No.
                        </span>

                        <div class="info-value">
                            {{ $fitoutRequest->request_no }}
                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Fit-Out Type
                        </span>

                        <div class="info-value">
                            {{ $fitoutRequest->fitout_type ?? '-' }}
                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Proposed Start
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest->proposed_start_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Proposed End
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest->proposed_end_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Actual Start
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest->actual_start_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Actual End
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest->actual_end_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Estimated Cost
                        </span>

                        <div class="info-value">

                            $ {{ number_format(
                                (float) $fitoutRequest->estimated_cost,
                                2
                            ) }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-4">

                    <div class="info-box">

                        <span class="info-label">
                            Work Permit No.
                        </span>

                        <div class="info-value">

                            {{ $fitoutRequest->work_permit_no ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- LEASE / TENANT / UNIT --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-building me-2"></i>

                Lease & Tenant Information

            </strong>

        </div>


        <div class="fitout-card-body">

            <div class="row g-4">


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Lease Agreement
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest
                                    ->leaseAgreement
                                    ->agreement_no
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Tenant
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest
                                    ->tenant
                                    ->company_name
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Tenant Code
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest
                                    ->tenant
                                    ->tenant_code
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Unit
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest
                                    ->unit
                                    ->unit_no
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Lease Start
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest
                                        ->leaseAgreement
                                        ?->lease_start_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-4 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Lease End
                        </span>

                        <div class="info-value">

                            {{
                                optional(
                                    $fitoutRequest
                                        ->leaseAgreement
                                        ?->lease_end_date
                                )->format('d M Y')
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- CONTRACTOR --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-person-badge me-2"></i>

                Contractor Information

            </strong>

        </div>


        <div class="fitout-card-body">

            <div class="row g-4">


                <div class="col-xl-3 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Contractor
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest->contractor
                                    ->contractor_name
                                ??
                                $fitoutRequest->contractor_name
                                ??
                                '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Contractor Code
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest->contractor
                                    ->contractor_code
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Contact
                        </span>

                        <div class="info-value">

                            {{
                                $fitoutRequest->contractor
                                    ->mobile
                                ??
                                $fitoutRequest->contractor_contact
                                ??
                                '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Insurance
                        </span>

                        <div class="info-value">

                            @if(
                                $fitoutRequest
                                    ->insurance_verified
                                === 'Yes'
                            )

                                <span
                                    class="fitout-status status-success"
                                >
                                    Verified
                                </span>

                            @else

                                <span
                                    class="fitout-status status-warning"
                                >
                                    Not Verified
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="info-box">

                        <span class="info-label">
                            Safety Induction
                        </span>

                        <div class="info-value">

                            @if(
                                $fitoutRequest
                                    ->safety_induction_completed
                                === 'Yes'
                            )

                                <span
                                    class="fitout-status status-success"
                                >
                                    Completed
                                </span>

                            @else

                                <span
                                    class="fitout-status status-warning"
                                >
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- WORK DETAILS --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-tools me-2"></i>

                Work Details

            </strong>

        </div>


        <div class="fitout-card-body">

            <div class="mb-4">

                <span class="info-label">
                    Work Description
                </span>

                <div class="info-value">

                    {!! nl2br(
                        e(
                            $fitoutRequest->work_description
                            ?? '-'
                        )
                    ) !!}

                </div>

            </div>


            <div>

                <span class="info-label">
                    Remarks
                </span>

                <div class="info-value">

                    {!! nl2br(
                        e(
                            $fitoutRequest->remarks
                            ?? '-'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FIT-OUT STAGES --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-list-check me-2"></i>

                Fit-Out Stages

            </strong>


            <div class="d-flex align-items-center gap-2">

                <span class="count-badge">

                    {{ $fitoutRequest->stages->count() }}

                </span>


                @if(
                    $fitoutRequest->stages->count() > 0
                )

                    <a
                        href="{{
                            route(
                                'admin.fitout.stages.index',
                                $fitoutRequest->id
                            )
                        }}"
                        class="btn btn-sm btn-outline-primary"
                    >

                        Manage Stages

                    </a>

                @endif

            </div>

        </div>


        <div class="p-0">

            @if(
                $fitoutRequest->stages->count()
            )

                <div class="table-responsive">

                    <table
                        class="table fitout-table table-hover mb-0"
                    >

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Stage</th>

                                <th>Contractor</th>

                                <th>Planned Start</th>

                                <th>Planned End</th>

                                <th>Actual Start</th>

                                <th>Actual End</th>

                                <th>Progress</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $fitoutRequest->stages
                                as $stage
                            )

                                <tr>

                                    <td>

                                        {{ $stage->stage_sequence }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{ $stage->stage_name }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $stage
                                                ->contractor
                                                ?->contractor_name
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $stage
                                                ->planned_start_date
                                                ?->format('d-m-Y')
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $stage
                                                ->planned_end_date
                                                ?->format('d-m-Y')
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $stage
                                                ->actual_start_date
                                                ?->format('d-m-Y')
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $stage
                                                ->actual_end_date
                                                ?->format('d-m-Y')
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        <div
                                            class="stage-progress"
                                        >

                                            <div
                                                class="progress"
                                            >

                                                <div
                                                    class="progress-bar"
                                                    role="progressbar"
                                                    style="
                                                        width:
                                                        {{
                                                            min(
                                                                100,
                                                                max(
                                                                    0,
                                                                    $stage
                                                                        ->completion_percentage
                                                                )
                                                            )
                                                        }}%;
                                                    "
                                                >

                                                    {{
                                                        number_format(
                                                            $stage
                                                                ->completion_percentage,
                                                            0
                                                        )
                                                    }}%

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        @php

                                            $stageStatusClass =
                                                match(
                                                    $stage->stage_status
                                                ) {

                                                    'Completed'
                                                        => 'status-success',

                                                    'In Progress'
                                                        => 'status-primary',

                                                    'On Hold'
                                                        => 'status-warning',

                                                    'Cancelled'
                                                        => 'status-danger',

                                                    default
                                                        => 'status-secondary',

                                                };

                                        @endphp


                                        <span
                                            class="
                                                fitout-status
                                                {{ $stageStatusClass }}
                                            "
                                        >

                                            {{ $stage->stage_status }}

                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{
                                                route(
                                                    'admin.fitout.stages.show',
                                                    $stage->id
                                                )
                                            }}"
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

                <div class="empty-state">

                    <i class="bi bi-list-check"></i>

                    No stages generated for this fit-out request.

                </div>

            @endif

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- DOCUMENTS + APPROVALS --}}
    {{-- ========================================================= --}}

    <div class="row g-4">


        {{-- DOCUMENTS --}}

        <div class="col-xl-6">

            <div class="fitout-card h-100">

                <div class="fitout-card-header">

                    <strong>

                        <i class="bi bi-folder2-open me-2"></i>

                        Documents

                    </strong>


                    <span class="count-badge">

                        {{ $fitoutRequest->documents->count() }}

                    </span>

                </div>


                <div class="p-0">

                    @if(
                        $fitoutRequest->documents->count()
                    )

                        <div class="table-responsive">

                            <table
                                class="table fitout-table mb-0"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            Document
                                        </th>

                                        <th>
                                            Document No.
                                        </th>

                                        <th>
                                            Version
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $fitoutRequest->documents
                                        as $document
                                    )

                                        <tr>

                                            <td>

                                                <strong>

                                                    {{
                                                        $document
                                                            ->document_title
                                                    }}

                                                </strong>

                                            </td>

                                            <td>

                                                {{
                                                    $document
                                                        ->document_number
                                                    ?? '-'
                                                }}

                                            </td>

                                            <td>

                                                {{
                                                    $document
                                                        ->version_no
                                                    ?? '-'
                                                }}

                                            </td>

                                            <td>

                                                {{
                                                    $document
                                                        ->approval_status
                                                }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-folder-x"></i>

                            No documents uploaded.

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- APPROVALS --}}

        <div class="col-xl-6">

            <div class="fitout-card h-100">

                <div class="fitout-card-header">

                    <strong>

                        <i class="bi bi-diagram-3 me-2"></i>

                        Approvals

                    </strong>


                    <span class="count-badge">

                        {{ $fitoutRequest->approvals->count() }}

                    </span>

                </div>


                <div class="p-0">

                    @if(
                        $fitoutRequest->approvals->count()
                    )

                        <div class="table-responsive">

                            <table
                                class="table fitout-table mb-0"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            Level
                                        </th>

                                        <th>
                                            Approval Type
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Action Date
                                        </th>

                                        <th>
                                            Comments
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $fitoutRequest->approvals
                                        as $approval
                                    )

                                        <tr>

                                            <td>

                                                {{
                                                    $approval
                                                        ->approval_level
                                                }}

                                            </td>


                                            <td>

                                                <strong>

                                                    {{
                                                        $approval
                                                            ->approval_type
                                                    }}

                                                </strong>

                                            </td>


                                            <td>

                                                @php

                                                    $approvalStatus =
                                                        $approval->status
                                                        ??
                                                        $approval
                                                            ->approval_status
                                                        ??
                                                        'Pending';

                                                    $approvalClass =
                                                        match(
                                                            $approvalStatus
                                                        ) {

                                                            'Approved'
                                                                => 'status-success',

                                                            'Rejected'
                                                                => 'status-danger',

                                                            default
                                                                => 'status-warning',

                                                        };

                                                @endphp


                                                <span
                                                    class="
                                                        fitout-status
                                                        {{
                                                            $approvalClass
                                                        }}
                                                    "
                                                >

                                                    {{
                                                        $approvalStatus
                                                    }}

                                                </span>

                                            </td>


                                            <td>

                                                {{
                                                    optional(
                                                        $approval->action_at
                                                    )->format(
                                                        'd M Y H:i'
                                                    )
                                                    ?? '-'
                                                }}

                                            </td>


                                            <td>

                                                {{
                                                    $approval->comments
                                                    ?? '-'
                                                }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-diagram-3"></i>

                            No approvals created.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- INSPECTIONS + SNAGS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mt-0">


        {{-- INSPECTIONS --}}

        <div class="col-xl-6">

            <div class="fitout-card h-100">

                <div class="fitout-card-header">

                    <strong>

                        <i class="bi bi-clipboard-check me-2"></i>

                        Inspections

                    </strong>


                    <span class="count-badge">

                        {{ $fitoutRequest->inspections->count() }}

                    </span>

                </div>


                <div class="p-0">

                    @if(
                        $fitoutRequest->inspections->count()
                    )

                        <div class="table-responsive">

                            <table
                                class="table fitout-table mb-0"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            Inspection No.
                                        </th>

                                        <th>
                                            Type
                                        </th>

                                        <th>
                                            Scheduled
                                        </th>

                                        <th>
                                            Result
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $fitoutRequest->inspections
                                        as $inspection
                                    )

                                        <tr>

                                            <td>

                                                <strong>

                                                    {{
                                                        $inspection
                                                            ->inspection_number
                                                    }}

                                                </strong>

                                            </td>


                                            <td>

                                                {{
                                                    $inspection
                                                        ->inspection_type
                                                }}

                                            </td>


                                            <td>

                                                {{
                                                    optional(
                                                        $inspection
                                                            ->scheduled_date
                                                    )->format(
                                                        'd M Y'
                                                    )
                                                    ?? '-'
                                                }}

                                            </td>


                                            <td>

                                                {{
                                                    $inspection->result
                                                    ?? '-'
                                                }}

                                            </td>


                                            <td>

                                                {{
                                                    $inspection->status
                                                    ?? '-'
                                                }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-clipboard-x"></i>

                            No inspections created.

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- SNAGS --}}

        <div class="col-xl-6">

            <div class="fitout-card h-100">

                <div class="fitout-card-header">

                    <strong>

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Snags

                    </strong>


                    <span class="count-badge">

                        {{ $fitoutRequest->snags->count() }}

                    </span>

                </div>


                <div class="p-0">

                    @if(
                        $fitoutRequest->snags->count()
                    )

                        <div class="table-responsive">

                            <table
                                class="table fitout-table mb-0"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            Snag No.
                                        </th>

                                        <th>
                                            Title
                                        </th>

                                        <th>
                                            Priority
                                        </th>

                                        <th>
                                            Due Date
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $fitoutRequest->snags
                                        as $snag
                                    )

                                        <tr>

                                            <td>

                                                <strong>

                                                    {{
                                                        $snag->snag_number
                                                    }}

                                                </strong>

                                            </td>


                                            <td>

                                                {{
                                                    $snag->title
                                                }}

                                            </td>


                                            <td>

                                                @php

                                                    $priorityClass =
                                                        match(
                                                            $snag->priority
                                                        ) {

                                                            'Critical'
                                                                => 'priority-critical',

                                                            'High'
                                                                => 'priority-high',

                                                            'Medium'
                                                                => 'priority-medium',

                                                            default
                                                                => 'priority-low',

                                                        };

                                                @endphp


                                                <span
                                                    class="
                                                        fitout-status
                                                        {{
                                                            $priorityClass
                                                        }}
                                                    "
                                                >

                                                    {{
                                                        $snag->priority
                                                    }}

                                                </span>

                                            </td>


                                            <td>

                                                {{
                                                    optional(
                                                        $snag->due_date
                                                    )->format(
                                                        'd M Y'
                                                    )
                                                    ?? '-'
                                                }}

                                            </td>


                                            <td>

                                                {{
                                                    $snag->status
                                                    ?? '-'
                                                }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-check-circle"></i>

                            No snags recorded.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- HANDOVERS --}}
    {{-- ========================================================= --}}

    <div class="fitout-card">

        <div class="fitout-card-header">

            <strong>

                <i class="bi bi-key me-2"></i>

                Handovers

            </strong>


            <span class="count-badge">

                {{ $fitoutRequest->handovers->count() }}

            </span>

        </div>


        <div class="p-0">

            @if(
                $fitoutRequest->handovers->count()
            )

                <div class="table-responsive">

                    <table
                        class="table fitout-table mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    Handover No.
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Handover Date
                                </th>

                                <th>
                                    Condition
                                </th>

                                <th>
                                    Keys
                                </th>

                                <th>
                                    Access Cards
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $fitoutRequest->handovers
                                as $handover
                            )

                                <tr>

                                    <td>

                                        <strong>

                                            {{
                                                $handover
                                                    ->handover_number
                                            }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{
                                            $handover
                                                ->handover_type
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            optional(
                                                $handover
                                                    ->handover_date
                                            )->format(
                                                'd M Y'
                                            )
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $handover
                                                ->unit_condition
                                            ?? '-'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $handover
                                                ->key_count
                                            ?? 0
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $handover
                                                ->access_card_count
                                            ?? 0
                                        }}

                                    </td>


                                    <td>

                                        @php

                                            $handoverClass =
                                                match(
                                                    $handover->status
                                                ) {

                                                    'Completed',
                                                    'Accepted'
                                                        => 'status-success',

                                                    'Rejected',
                                                    'Cancelled'
                                                        => 'status-danger',

                                                    'In Progress'
                                                        => 'status-primary',

                                                    default
                                                        => 'status-warning',

                                                };

                                        @endphp


                                        <span
                                            class="
                                                fitout-status
                                                {{
                                                    $handoverClass
                                                }}
                                            "
                                        >

                                            {{
                                                $handover->status
                                            }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-key"></i>

                    No handovers recorded.

                </div>

            @endif

        </div>

    </div>


</div>



{{-- ============================================================= --}}
{{-- REJECT MODAL --}}
{{-- ============================================================= --}}

@if(
    $fitoutRequest->fitout_status === 'Under Review'
)

    <div
        class="modal fade"
        id="rejectFitoutModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    method="POST"
                    action="{{
                        route(
                            'admin.fitout.requests.reject',
                            $fitoutRequest->id
                        )
                    }}"
                >

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">

                            Reject Fit-Out Request

                        </h5>


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <label class="form-label">

                            Rejection Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <textarea
                            name="rejection_reason"
                            class="form-control"
                            rows="4"
                            required
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
                            class="btn btn-danger"
                        >

                            <i class="bi bi-x-lg me-1"></i>

                            Reject Request

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

@endsection