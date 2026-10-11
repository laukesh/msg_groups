@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Practical Completion')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Practical Completion
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
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
        STATUS / READINESS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Completion No --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Completion No.
                    </div>

                    <h5 class="mb-0">
                        {{ $completion->completion_no }}
                    </h5>

                </div>
            </div>
        </div>


        {{-- Status --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm">
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
            <div class="card h-100 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">
                            Readiness
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


        {{-- Mandatory Requirements --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Mandatory Requirements
                    </div>

                    <h5 class="mb-0">
                        {{ $completedRequirements }}
                        /
                        {{ $totalRequirements }}
                    </h5>

                    <small class="text-muted">
                        {{ $pendingRequirements }}
                        pending
                    </small>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        READINESS BANNER
    ========================================================== --}}

    @if($completion->status === 'Approved')

        <div class="alert alert-success d-flex align-items-center mb-4">

            <i class="ri-checkbox-circle-fill fs-4 me-3"></i>

            <div>
                <strong>Practical Completion Approved</strong>

                <div class="small mt-1">
                    The project has successfully achieved Practical Completion.
                </div>
            </div>

        </div>

    @elseif($completion->status === 'Under Review')

        <div class="alert alert-warning d-flex align-items-center mb-4">

            <i class="ri-time-line fs-4 me-3"></i>

            <div>
                <strong>Practical Completion Under Review</strong>

                <div class="small mt-1">
                    The completion submission is currently awaiting review.
                </div>
            </div>

        </div>

    @elseif($completion->status === 'Ready for Submission')

        <div class="alert alert-primary d-flex align-items-center mb-4">

            <i class="ri-send-plane-line fs-4 me-3"></i>

            <div>
                <strong>Ready for Submission</strong>

                <div class="small mt-1">
                    All mandatory handover requirements are completed or waived.
                    Practical Completion can now be submitted for review.
                </div>
            </div>

        </div>

    @elseif($completion->status === 'Rejected')

        <div class="alert alert-danger mb-4">

            <div class="d-flex">

                <i class="ri-close-circle-line fs-4 me-3"></i>

                <div>

                    <strong>
                        Practical Completion Rejected
                    </strong>

                    @if($completion->rejection_reason)
                        <div class="mt-2">
                            <strong>Reason:</strong>
                            {{ $completion->rejection_reason }}
                        </div>
                    @endif

                    <div class="small mt-2">
                        Update the completion details and resubmit
                        after addressing the review comments.
                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="alert alert-secondary d-flex align-items-center mb-4">

            <i class="ri-information-line fs-4 me-3"></i>

            <div>
                <strong>Practical Completion Not Ready</strong>

                <div class="small mt-1">
                    Complete all mandatory Handover & Closeout requirements
                    before submitting Practical Completion.
                </div>
            </div>

        </div>

    @endif


    {{-- =========================================================
        COMPLETION DETAILS
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-file-list-3-line me-1"></i>
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

                    <div class="border rounded p-3 bg-light">

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
                            {!! nl2br(e($completion->remarks)) !!}
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
        READINESS CHECKLIST
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="ri-list-check-2 me-1"></i>
                    Readiness Checklist
                </h5>

                <span class="badge bg-light text-dark">
                    {{ $completedRequirements }}
                    /
                    {{ $totalRequirements }}
                    Complete
                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th style="width: 130px;">
                                Code
                            </th>

                            <th>
                                Requirement
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Mandatory
                            </th>

                            <th>
                                Remarks
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($requirements as $requirement)

                        @php

                            $requirementStatusClass = match(
                                $requirement->status
                            ) {
                                'Completed' => 'success',
                                'Waived' => 'secondary',
                                'Rejected' => 'danger',
                                'Under Review' => 'warning',
                                'Submitted' => 'info',
                                'In Progress' => 'primary',
                                default => 'light',
                            };

                            $priorityClass = match(
                                $requirement->priority
                            ) {
                                'Critical' => 'danger',
                                'High' => 'warning',
                                'Medium' => 'info',
                                default => 'secondary',
                            };

                        @endphp

                        <tr>

                            <td>
                                <span class="fw-semibold">
                                    {{ $requirement->requirement_code }}
                                </span>
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $requirement->title }}
                                </div>

                                @if($requirement->description)
                                    <div class="small text-muted">
                                        {{ Str::limit(
                                            $requirement->description,
                                            100
                                        ) }}
                                    </div>
                                @endif

                            </td>

                            <td>
                                {{ $requirement->requirement_type }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $priorityClass }}">
                                    {{ $requirement->priority }}
                                </span>
                            </td>

                            <td>

                                <span class="badge
                                    @if($requirement->status === 'Completed')
                                        bg-success
                                    @elseif($requirement->status === 'Rejected')
                                        bg-danger
                                    @elseif($requirement->status === 'In Progress')
                                        bg-primary
                                    @elseif($requirement->status === 'Under Review')
                                        bg-warning text-dark
                                    @elseif($requirement->status === 'Submitted')
                                        bg-info
                                    @elseif($requirement->status === 'Waived')
                                        bg-secondary
                                    @else
                                        bg-light text-dark
                                    @endif
                                ">
                                    {{ $requirement->status }}
                                </span>

                            </td>

                            <td>

                                @if($requirement->is_mandatory)

                                    <span class="badge bg-danger">
                                        Mandatory
                                    </span>

                                @else

                                    <span class="badge bg-light text-dark">
                                        Optional
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($requirement->remarks)

                                    <span title="{{ $requirement->remarks }}">
                                        {{ Str::limit(
                                            $requirement->remarks,
                                            70
                                        ) }}
                                    </span>

                                @else
                                    <span class="text-muted">
                                        —
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-5">

                                <i class="ri-inbox-line fs-2 d-block mb-2"></i>

                                No handover requirements found.

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

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
                              'admin.projects.handover.practical-completion.submit',
                              [$project, $completion]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Submit Practical Completion for review?'
                                )">

                            <i class="ri-send-plane-line me-1"></i>
                            Submit for Review

                        </button>

                    </form>

                @endif


                {{-- Start Review --}}
                @if($completion->status === 'Submitted')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.practical-completion.review',
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


                {{-- Approve --}}
                @if($completion->status === 'Under Review')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.practical-completion.approve',
                              [$project, $completion]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Approve Practical Completion?'
                                )">

                            <i class="ri-checkbox-circle-line me-1"></i>
                            Approve

                        </button>

                    </form>


                    {{-- Reject --}}

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
                Completion Workflow
            </h5>

        </div>

        <div class="card-body">

            <div class="row text-center">

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
                                      style="width:40px;height:40px;">

                                    <i class="ri-check-line"></i>

                                </span>

                            @elseif($index <= $currentIndex)

                                <span class="rounded-circle
                                             bg-primary
                                             text-white
                                             d-inline-flex
                                             align-items-center
                                             justify-content-center"
                                      style="width:40px;height:40px;">

                                    <i class="ri-check-line"></i>

                                </span>

                            @else

                                <span class="rounded-circle
                                             bg-light
                                             text-muted
                                             d-inline-flex
                                             align-items-center
                                             justify-content-center"
                                      style="width:40px;height:40px;">

                                    {{ $index + 1 }}

                                </span>

                            @endif

                        </div>

                        <div class="small fw-semibold">
                            {{ $step }}
                        </div>

                        @if($index < count($workflow) - 1)

                            <div class="border-top mt-3"></div>

                        @endif

                    </div>

                @endforeach

            </div>

            @if($completion->status === 'Rejected')

                <div class="alert alert-danger mt-4 mb-0">

                    <strong>
                        Rejected
                    </strong>

                    <div class="small mt-1">
                        The completion record must be rectified
                        and resubmitted.
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
                      'admin.projects.handover.practical-completion.update',
                      [$project, $completion]
                  ) }}">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="ri-edit-line me-1"></i>
                        Edit Practical Completion
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
                                      rows="5"
                                      class="form-control"
                                      placeholder="Enter Practical Completion statement...">{{ old(
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
                      'admin.projects.handover.practical-completion.reject',
                      [$project, $completion]
                  ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title text-danger">

                        <i class="ri-close-circle-line me-1"></i>
                        Reject Practical Completion

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">

                        Please provide the reason for rejection.
                        The record will be returned for rectification.

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
                        Reject

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection