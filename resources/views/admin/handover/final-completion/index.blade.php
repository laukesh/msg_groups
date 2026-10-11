@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Final Completion')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Final Completion
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? 'Project' }}
                -
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.handover.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>
                Handover Dashboard

            </a>

            @if(in_array($completion->status, [
                'Not Ready',
                'Ready for Submission',
                'Rejected'
            ]))

                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editCompletionModal">

                    <i class="ri-edit-line me-1"></i>
                    Edit Details

                </button>

            @endif

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Completion Number --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Final Completion No.
                    </div>

                    <h5 class="mb-0">
                        {{ $completion->completion_no }}
                    </h5>

                </div>

            </div>

        </div>


        {{-- Status --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-2">
                        Status
                    </div>

                    @php
                        $statusClass = match($completion->status) {
                            'Approved' => 'success',
                            'Under Review' => 'warning',
                            'Submitted' => 'info',
                            'Ready for Submission' => 'primary',
                            'Rejected' => 'danger',
                            default => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $statusClass }} fs-6">

                        {{ $completion->status }}

                    </span>

                </div>

            </div>

        </div>


        {{-- Readiness --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Final Readiness
                        </span>

                        <strong>
                            {{ number_format(
                                (float) $completion->readiness_percentage,
                                2
                            ) }}%
                        </strong>

                    </div>

                    <div class="progress"
                         style="height: 8px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ min(
                                 100,
                                 (float) $completion->readiness_percentage
                             ) }}%">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Closeout Checks --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Closeout Checks
                    </div>

                    @php
                        $completedChecks = collect([
                            $completion->practical_completion_complete,
                            $completion->final_account_complete,
                            $completion->final_payment_complete,
                            $completion->defects_complete,
                            $completion->documents_complete,
                        ])->filter()->count();
                    @endphp

                    <h5 class="mb-0">
                        {{ $completedChecks }} / 5
                    </h5>

                    <small class="text-muted">
                        Final Completion readiness gates
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STATUS BANNER
    ========================================================== --}}

    @if($completion->status === 'Approved')

        <div class="alert alert-success d-flex align-items-center mb-4">

            <i class="ri-checkbox-circle-fill fs-3 me-3"></i>

            <div>

                <strong>
                    Final Completion Approved
                </strong>

                <div class="small mt-1">
                    Final Completion has been approved and
                    Handover & Closeout has been completed.
                </div>

            </div>

        </div>


    @elseif($completion->status === 'Under Review')

        <div class="alert alert-warning d-flex align-items-center mb-4">

            <i class="ri-time-line fs-3 me-3"></i>

            <div>

                <strong>
                    Final Completion Under Review
                </strong>

                <div class="small mt-1">
                    The Final Completion submission is currently
                    awaiting review.
                </div>

            </div>

        </div>


    @elseif($completion->status === 'Submitted')

        <div class="alert alert-info d-flex align-items-center mb-4">

            <i class="ri-send-plane-line fs-3 me-3"></i>

            <div>

                <strong>
                    Final Completion Submitted
                </strong>

                <div class="small mt-1">
                    The Final Completion record has been submitted
                    and is awaiting review.
                </div>

            </div>

        </div>


    @elseif($completion->status === 'Ready for Submission')

        <div class="alert alert-primary d-flex align-items-center mb-4">

            <i class="ri-checkbox-circle-line fs-3 me-3"></i>

            <div>

                <strong>
                    Final Completion Ready for Submission
                </strong>

                <div class="small mt-1">
                    All final completion gates are satisfied.
                    You can now submit Final Completion for review.
                </div>

            </div>

        </div>


    @elseif($completion->status === 'Rejected')

        <div class="alert alert-danger mb-4">

            <div class="d-flex">

                <i class="ri-close-circle-line fs-3 me-3"></i>

                <div>

                    <strong>
                        Final Completion Rejected
                    </strong>

                    @if($completion->rejection_reason)

                        <div class="mt-2">

                            <strong>
                                Rejection Reason:
                            </strong>

                            <div>
                                {!! nl2br(
                                    e($completion->rejection_reason)
                                ) !!}
                            </div>

                        </div>

                    @endif

                    <div class="small mt-2">
                        Rectify the identified issues and update
                        the Final Completion record before resubmission.
                    </div>

                </div>

            </div>

        </div>


    @else

        <div class="alert alert-secondary d-flex align-items-center mb-4">

            <i class="ri-information-line fs-3 me-3"></i>

            <div>

                <strong>
                    Final Completion Not Ready
                </strong>

                <div class="small mt-1">
                    Complete the remaining closeout activities
                    before Final Completion can be submitted.
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        CLOSEOUT READINESS CHECKLIST
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        <i class="ri-list-check-2 me-1"></i>
                        Final Completion Readiness
                    </h5>

                    <small class="text-muted">
                        System-driven closeout verification
                    </small>

                </div>

                <span class="badge bg-primary">
                    {{ number_format(
                        (float) $completion->readiness_percentage,
                        2
                    ) }}%
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Practical Completion --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Practical Completion
                                </div>

                                <small class="text-muted">
                                    Must be approved
                                </small>

                            </div>

                            @if($completion->practical_completion_complete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Final Account --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Final Account
                                </div>

                                <small class="text-muted">
                                    Financial closeout
                                </small>

                            </div>

                            @if($completion->final_account_complete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Final Payment --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Final Payment
                                </div>

                                <small class="text-muted">
                                    Payment closeout
                                </small>

                            </div>

                            @if($completion->final_payment_complete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Defects --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Defects
                                </div>

                                <small class="text-muted">
                                    All defects must be closed
                                </small>

                            </div>

                            @if($completion->defects_complete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Outstanding
                                </span>

                            @endif

                        </div>

                        <div class="small text-muted mt-2">

                            {{ $closedDefects }}
                            /
                            {{ $totalDefects }}
                            defects closed

                        </div>

                    </div>

                </div>


                {{-- Documents --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Handover Documents
                                </div>

                                <small class="text-muted">
                                    Required documents approved
                                </small>

                            </div>

                            @if($completion->documents_complete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>

                        <div class="small text-muted mt-2">

                            {{ $approvedDocuments }}
                            /
                            {{ $totalDocuments }}
                            documents approved

                        </div>

                    </div>

                </div>


                {{-- Handover Certificate --}}
                <div class="col-lg-4 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="fw-semibold">
                                    Handover Certificate
                                </div>

                                <small class="text-muted">
                                    Final handover certification
                                </small>

                            </div>

                            @if($handoverCertificateComplete)

                                <span class="badge bg-success">
                                    Complete
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @endif

                        </div>


                        @if($handoverCertificate)

                            <div class="small text-muted mt-2">

                                Certificate No.:

                                <strong class="text-dark">
                                    {{ $handoverCertificate->certificate_no ?? '—' }}
                                </strong>

                            </div>

                            <div class="small text-muted mt-1">

                                Status:

                                <strong class="text-dark">
                                    {{ $handoverCertificate->status }}
                                </strong>

                            </div>

                            @if(
                                !empty($handoverCertificate->approved_at)
                            )

                                <div class="small text-muted mt-1">

                                    Approved:

                                    <strong class="text-dark">
                                        {{ \Carbon\Carbon::parse(
                                            $handoverCertificate->approved_at
                                        )->format('d M Y') }}
                                    </strong>

                                </div>

                            @endif

                        @else

                            <div class="small text-muted mt-2">
                                Handover Certificate has not been issued yet.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    @if($handoverCertificateComplete)

        <div class="alert alert-success d-flex align-items-center mb-4">

            <i class="ri-checkbox-circle-fill fs-4 me-3"></i>

            <div>

                <strong>
                    Handover Certificate Approved
                </strong>

                <div class="small mt-1">

                    The Handover Certificate has been approved successfully.

                    @if(!empty($handoverCertificate->certificate_no))
                        Certificate No.
                        <strong>
                            {{ $handoverCertificate->certificate_no }}
                        </strong>.
                    @endif

                </div>

            </div>

        </div>

    @elseif($handoverCertificate)

        <div class="alert alert-warning d-flex align-items-center mb-4">

            <i class="ri-time-line fs-4 me-3"></i>

            <div>

                <strong>
                    Handover Certificate Pending
                </strong>

                <div class="small mt-1">

                    Certificate status:
                    <strong>
                        {{ $handoverCertificate->status }}
                    </strong>

                </div>

            </div>

        </div>

    @endif

    {{-- =========================================================
        RELATED RECORDS
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- Practical Completion --}}
        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="ri-checkbox-circle-line me-1"></i>
                        Practical Completion
                    </h6>

                </div>

                <div class="card-body">

                    @if($practicalCompletion)

                        <div class="mb-2">

                            <span class="text-muted">
                                Completion No.
                            </span>

                            <div class="fw-semibold">
                                {{ $practicalCompletion->completion_no }}
                            </div>

                        </div>

                        <div>

                            <span class="text-muted">
                                Status
                            </span>

                            <div class="mt-1">

                                <span class="badge
                                    @if($practicalCompletion->status === 'Approved')
                                        bg-success
                                    @elseif($practicalCompletion->status === 'Rejected')
                                        bg-danger
                                    @else
                                        bg-warning text-dark
                                    @endif
                                ">
                                    {{ $practicalCompletion->status }}
                                </span>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            Practical Completion record not found.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Defects --}}
        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="ri-tools-line me-1"></i>
                        Defects
                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Total Defects
                        </span>

                        <strong>
                            {{ $totalDefects }}
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Closed
                        </span>

                        <strong class="text-success">
                            {{ $closedDefects }}
                        </strong>

                    </div>

                    @if($totalDefects > 0)

                        <div class="progress mt-3"
                             style="height: 7px;">

                            <div class="progress-bar bg-success"
                                 style="width: {{ round(
                                     ($closedDefects / $totalDefects) * 100,
                                     2
                                 ) }}%">
                            </div>

                        </div>

                    @endif

                    <div class="mt-3">

                        <a href="{{ route(
                            'admin.projects.handover.defects.index',
                            $project
                        ) }}"
                           class="btn btn-sm btn-outline-primary">

                            Manage Defects

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Documents --}}
        <div class="col-lg-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="ri-file-list-3-line me-1"></i>
                        Handover Documents
                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Total Documents
                        </span>

                        <strong>
                            {{ $totalDocuments }}
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Approved
                        </span>

                        <strong class="text-success">
                            {{ $approvedDocuments }}
                        </strong>

                    </div>

                    <div class="mt-3">

                        <a href="{{ route(
                            'admin.projects.handover.documents.index',
                            $project
                        ) }}"
                           class="btn btn-sm btn-outline-primary">

                            Manage Documents

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMPLETION DETAILS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-file-text-line me-1"></i>
                Completion Details
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Planned Date
                    </div>

                    <div class="fw-semibold">
                        {{ $completion->planned_date
                            ? $completion->planned_date->format('d M Y')
                            : '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Submitted Date
                    </div>

                    <div class="fw-semibold">
                        {{ $completion->submitted_date
                            ? $completion->submitted_date->format('d M Y')
                            : '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Date
                    </div>

                    <div class="fw-semibold">
                        {{ $completion->approved_date
                            ? $completion->approved_date->format('d M Y')
                            : '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Handover Status
                    </div>

                    <div class="fw-semibold">
                        {{ $handover->status }}
                    </div>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Completion Statement
                    </div>

                    <div class="border rounded bg-light p-3">

                        @if($completion->completion_statement)

                            {!! nl2br(
                                e($completion->completion_statement)
                            ) !!}

                        @else

                            <span class="text-muted">
                                No completion statement provided.
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Remarks
                    </div>

                    <div class="border rounded p-3">

                        @if($completion->remarks)

                            {!! nl2br(
                                e($completion->remarks)
                            ) !!}

                        @else

                            <span class="text-muted">
                                No remarks.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        WORKFLOW ACTIONS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-flow-chart me-1"></i>
                Workflow Actions
            </h5>

        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- Submit --}}
                @if($completion->status === 'Ready for Submission')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-completion.submit',
                              [$project, $completion]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Submit Final Completion for review?'
                                )">

                            <i class="ri-send-plane-line me-1"></i>
                            Submit for Review

                        </button>

                    </form>

                @endif


                {{-- Review --}}
                @if($completion->status === 'Submitted')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-completion.review',
                              [$project, $completion]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-warning">

                            <i class="ri-search-eye-line me-1"></i>
                            Start Review

                        </button>

                    </form>

                @endif


                {{-- Approve / Reject --}}
                @if($completion->status === 'Under Review')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-completion.approve',
                              [$project, $completion]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Approve Final Completion? This will complete Handover & Closeout.'
                                )">

                            <i class="ri-checkbox-circle-line me-1"></i>
                            Approve

                        </button>

                    </form>


                    <button type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectCompletionModal">

                        <i class="ri-close-circle-line me-1"></i>
                        Reject

                    </button>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        WORKFLOW TIMELINE
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-history-line me-1"></i>
                Final Completion Workflow
            </h5>

        </div>

        <div class="card-body">

            @php

                $workflow = [
                    'Not Ready',
                    'Ready for Submission',
                    'Submitted',
                    'Under Review',
                    'Approved',
                ];

                $currentIndex = array_search(
                    $completion->status,
                    $workflow
                );

                if ($currentIndex === false) {
                    $currentIndex = -1;
                }

            @endphp


            <div class="row text-center">

                @foreach($workflow as $index => $step)

                    <div class="col">

                        <div class="mb-2">

                            @if(
                                $completion->status === 'Approved'
                                && $step === 'Approved'
                            )

                                <span class="rounded-circle
                                             bg-success
                                             text-white
                                             d-inline-flex
                                             align-items-center
                                             justify-content-center"
                                      style="width:42px;height:42px;">

                                    <i class="ri-check-line"></i>

                                </span>

                            @elseif($index <= $currentIndex)

                                <span class="rounded-circle
                                             bg-primary
                                             text-white
                                             d-inline-flex
                                             align-items-center
                                             justify-content-center"
                                      style="width:42px;height:42px;">

                                    <i class="ri-check-line"></i>

                                </span>

                            @else

                                <span class="rounded-circle
                                             bg-light
                                             text-muted
                                             d-inline-flex
                                             align-items-center
                                             justify-content-center"
                                      style="width:42px;height:42px;">

                                    {{ $index + 1 }}

                                </span>

                            @endif

                        </div>

                        <div class="small fw-semibold">
                            {{ $step }}
                        </div>

                    </div>

                @endforeach

            </div>


            @if($completion->status === 'Rejected')

                <div class="alert alert-danger mt-4 mb-0">

                    <strong>
                        Rectification Required
                    </strong>

                    <div class="small mt-1">
                        Address the rejection comments, update
                        the Final Completion record and resubmit.
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    EDIT MODAL
============================================================= --}}

<div class="modal fade"
     id="editCompletionModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.handover.final-completion.update',
                      [$project, $completion]
                  ) }}">

                @csrf
                @method('PUT')


                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="ri-edit-line me-1"></i>
                        Edit Final Completion

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Planned Completion Date
                            </label>

                            <input type="date"
                                   name="planned_date"
                                   class="form-control"
                                   value="{{ old(
                                       'planned_date',
                                       $completion->planned_date
                                           ? $completion->planned_date->format('Y-m-d')
                                           : ''
                                   ) }}">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Completion No.
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $completion->completion_no }}"
                                   readonly>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Completion Statement
                            </label>

                            <textarea name="completion_statement"
                                      rows="6"
                                      class="form-control"
                                      placeholder="Enter Final Completion statement...">{{ old(
                                          'completion_statement',
                                          $completion->completion_statement
                                      ) }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter remarks...">{{ old(
                                          'remarks',
                                          $completion->remarks
                                      ) }}</textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ri-save-line me-1"></i>
                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    REJECT MODAL
============================================================= --}}

<div class="modal fade"
     id="rejectCompletionModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.handover.final-completion.reject',
                      [$project, $completion]
                  ) }}">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title text-danger">

                        <i class="ri-close-circle-line me-1"></i>
                        Reject Final Completion

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        Please provide the reason for rejecting
                        Final Completion.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Rejection Reason
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="rejection_reason"
                                  rows="5"
                                  class="form-control"
                                  required
                                  placeholder="Enter rejection reason..."></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="ri-close-circle-line me-1"></i>
                        Reject Final Completion

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection