@extends('layouts.app')

@section('title', 'Handover Certificate')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handover Certificate
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}

                @if($project->project_name)
                    - {{ $project->project_name }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.handover.index',
                $project
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back to Handover

            </a>

            @if(!$certificate && $finalCompletionApproved)

                <form method="POST"
                      action="{{ route(
                          'admin.projects.handover.certificates.store',
                          $project
                      ) }}">

                    @csrf

                    <input type="hidden"
                           name="certificate_type"
                           value="Handover Certificate">

                    <input type="hidden"
                           name="title"
                           value="Handover Certificate">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ri-add-line me-1"></i>
                        Create Certificate

                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- Alerts --}}
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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Dependency Warning --}}
    @if(!$finalCompletionApproved)

        <div class="alert alert-warning border-warning mb-4">

            <div class="d-flex align-items-start">

                <i class="ri-lock-line fs-3 me-3"></i>

                <div>

                    <h6 class="mb-1">
                        Handover Certificate is Locked
                    </h6>

                    <div>
                        Final Completion must be
                        <strong>Approved</strong>
                        before the Handover Certificate can be created.
                    </div>

                    <div class="mt-2">

                        @if($finalCompletion)

                            Current Final Completion Status:

                            <span class="badge bg-secondary ms-1">
                                {{ $finalCompletion->status }}
                            </span>

                        @else

                            Final Completion record has not been created.

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Certificates
                            </div>

                            <h4 class="mb-0 mt-2">
                                {{ $totalCertificates }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-primary-subtle rounded">

                            <div class="avatar-title text-primary fs-4">

                                <i class="ri-file-text-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Approved
                            </div>

                            <h4 class="mb-0 mt-2 text-success">
                                {{ $approvedCertificates }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-success-subtle rounded">

                            <div class="avatar-title text-success fs-4">

                                <i class="ri-checkbox-circle-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Under Review --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Under Review
                            </div>

                            <h4 class="mb-0 mt-2 text-info">
                                {{ $submittedCertificates }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-info-subtle rounded">

                            <div class="avatar-title text-info fs-4">

                                <i class="ri-search-eye-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Rejected
                            </div>

                            <h4 class="mb-0 mt-2 text-danger">
                                {{ $rejectedCertificates }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-danger-subtle rounded">

                            <div class="avatar-title text-danger fs-4">

                                <i class="ri-close-circle-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Main --}}
        <div class="col-xl-8">


            {{-- Certificate --}}
            @if($certificate)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="card-title mb-1">
                                    {{ $certificate->title }}
                                </h5>

                                <div class="text-muted small">

                                    {{ $certificate->certificate_no }}

                                </div>

                            </div>


                            @php

                                $statusClass = match(
                                    $certificate->status
                                ) {

                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Under Review' =>
                                        'bg-info',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    'Cancelled' =>
                                        'bg-dark',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <span class="badge {{ $statusClass }}">

                                {{ $certificate->status }}

                            </span>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Certificate No --}}
                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Certificate No.
                                </label>

                                <div class="fw-semibold">
                                    {{ $certificate->certificate_no }}
                                </div>

                            </div>


                            {{-- Type --}}
                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Certificate Type
                                </label>

                                <div class="fw-semibold">
                                    {{ $certificate->certificate_type }}
                                </div>

                            </div>


                            {{-- Issue Date --}}
                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Issue Date
                                </label>

                                <div>

                                    {{ $certificate->issue_date
                                        ? $certificate->issue_date->format('d M Y')
                                        : '-' }}

                                </div>

                            </div>


                            {{-- Effective Date --}}
                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Effective Date
                                </label>

                                <div>

                                    {{ $certificate->effective_date
                                        ? $certificate->effective_date->format('d M Y')
                                        : '-' }}

                                </div>

                            </div>


                            {{-- Description --}}
                            @if($certificate->description)

                                <div class="col-12">

                                    <label class="text-muted small">
                                        Description
                                    </label>

                                    <div>
                                        {!! nl2br(
                                            e($certificate->description)
                                        ) !!}
                                    </div>

                                </div>

                            @endif


                            {{-- Statement --}}
                            @if($certificate->certificate_statement)

                                <div class="col-12">

                                    <label class="text-muted small">
                                        Certificate Statement
                                    </label>

                                    <div class="border rounded p-3 bg-light">

                                        {!! nl2br(
                                            e(
                                                $certificate
                                                    ->certificate_statement
                                            )
                                        ) !!}

                                    </div>

                                </div>

                            @endif


                            {{-- Remarks --}}
                            @if($certificate->remarks)

                                <div class="col-12">

                                    <label class="text-muted small">
                                        Remarks
                                    </label>

                                    <div>

                                        {!! nl2br(
                                            e($certificate->remarks)
                                        ) !!}

                                    </div>

                                </div>

                            @endif


                            {{-- Rejection --}}
                            @if($certificate->rejection_reason)

                                <div class="col-12">

                                    <div class="alert alert-danger mb-0">

                                        <strong>
                                            Rejection Reason
                                        </strong>

                                        <div class="mt-1">

                                            {!! nl2br(
                                                e(
                                                    $certificate
                                                        ->rejection_reason
                                                )
                                            ) !!}

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- Document --}}
                            @if($certificate->document_path)

                                <div class="col-12">

                                    <a href="{{ asset(
                                        'storage/' .
                                        $certificate->document_path
                                    ) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary">

                                        <i class="ri-file-text-line me-1"></i>
                                        View Certificate Document

                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Edit --}}
                @if(in_array(
                    $certificate->status,
                    ['Draft', 'Rejected']
                ))

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="card-title mb-0">
                                Certificate Details
                            </h5>

                        </div>


                        <div class="card-body">

                            <form method="POST"
                                  enctype="multipart/form-data"
                                  action="{{ route(
                                      'admin.projects.handover.certificates.update',
                                      [$project, $certificate]
                                  ) }}">

                                @csrf
                                @method('PUT')


                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Certificate Type
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="certificate_type"
                                                class="form-select"
                                                required>

                                            @foreach([
                                                'Practical Completion',
                                                'Final Completion',
                                                'Handover Certificate',
                                                'Taking Over Certificate',
                                                'Other'
                                            ] as $type)

                                                <option value="{{ $type }}"
                                                    @selected(
                                                        $certificate->certificate_type
                                                        === $type
                                                    )>

                                                    {{ $type }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Title
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="title"
                                               class="form-control"
                                               value="{{ old(
                                                   'title',
                                                   $certificate->title
                                               ) }}"
                                               required>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Issue Date
                                        </label>

                                        <input type="date"
                                               name="issue_date"
                                               class="form-control"
                                               value="{{ old(
                                                   'issue_date',
                                                   $certificate->issue_date?->format('Y-m-d')
                                               ) }}">

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Effective Date
                                        </label>

                                        <input type="date"
                                               name="effective_date"
                                               class="form-control"
                                               value="{{ old(
                                                   'effective_date',
                                                   $certificate->effective_date?->format('Y-m-d')
                                               ) }}">

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Description
                                        </label>

                                        <textarea name="description"
                                                  rows="3"
                                                  class="form-control">{{ old(
                                                    'description',
                                                    $certificate->description
                                                  ) }}</textarea>

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Certificate Statement
                                        </label>

                                        <textarea name="certificate_statement"
                                                  rows="5"
                                                  class="form-control"
                                                  placeholder="Enter formal handover certificate statement...">{{ old(
                                                    'certificate_statement',
                                                    $certificate->certificate_statement
                                                  ) }}</textarea>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Certificate Document
                                        </label>

                                        <input type="file"
                                               name="document"
                                               class="form-control">

                                        <div class="form-text">
                                            Maximum file size: 50 MB.
                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Remarks
                                        </label>

                                        <textarea name="remarks"
                                                  rows="3"
                                                  class="form-control">{{ old(
                                                    'remarks',
                                                    $certificate->remarks
                                                  ) }}</textarea>

                                    </div>


                                    <div class="col-12 text-end">

                                        <button type="submit"
                                                class="btn btn-primary">

                                            <i class="ri-save-line me-1"></i>
                                            Save Certificate

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- Workflow --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="card-title mb-0">
                            Workflow Actions
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex flex-wrap gap-2">


                            {{-- Submit --}}
                            @if($certificate->status === 'Draft')

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.certificates.submit',
                                          [$project, $certificate]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-primary">

                                        <i class="ri-send-plane-line me-1"></i>
                                        Submit for Review

                                    </button>

                                </form>

                            @endif


                            {{-- Review --}}
                            @if($certificate->status === 'Submitted')

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.certificates.review',
                                          [$project, $certificate]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-info">

                                        <i class="ri-search-eye-line me-1"></i>
                                        Start Review

                                    </button>

                                </form>

                            @endif


                            {{-- Approve / Reject --}}
                            @if($certificate->status === 'Under Review')

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.certificates.approve',
                                          [$project, $certificate]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-success"
                                            onclick="return confirm(
                                                'Approve this Handover Certificate?'
                                            );">

                                        <i class="ri-checkbox-circle-line me-1"></i>
                                        Approve Certificate

                                    </button>

                                </form>


                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectCertificateModal">

                                    <i class="ri-close-circle-line me-1"></i>
                                    Reject

                                </button>

                            @endif


                            {{-- Cancel --}}
                            @if(in_array(
                                $certificate->status,
                                ['Draft', 'Rejected']
                            ))

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.certificates.cancel',
                                          [$project, $certificate]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-outline-danger"
                                            onclick="return confirm(
                                                'Cancel this certificate?'
                                            );">

                                        <i class="ri-forbid-line me-1"></i>
                                        Cancel

                                    </button>

                                </form>

                            @endif


                            @if($certificate->status === 'Approved')

                                <span class="badge bg-success-subtle text-success p-2">

                                    <i class="ri-checkbox-circle-line me-1"></i>

                                    Certificate Approved

                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Timeline --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="card-title mb-0">
                            Certificate Timeline
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="timeline">


                            {{-- Created --}}
                            <div class="d-flex mb-4">

                                <div class="me-3">

                                    <span class="badge bg-secondary rounded-circle p-2">

                                        <i class="ri-add-line"></i>

                                    </span>

                                </div>

                                <div>

                                    <h6 class="mb-1">
                                        Certificate Created
                                    </h6>

                                    <small class="text-muted">

                                        {{ $certificate->created_at
                                            ? $certificate->created_at->format(
                                                'd M Y, h:i A'
                                            )
                                            : '-' }}

                                    </small>

                                </div>

                            </div>


                            {{-- Submitted --}}
                            @if($certificate->submitted_at)

                                <div class="d-flex mb-4">

                                    <div class="me-3">

                                        <span class="badge bg-primary rounded-circle p-2">

                                            <i class="ri-send-plane-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Submitted
                                        </h6>

                                        <small class="text-muted">

                                            {{ $certificate->submitted_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $certificate->submittedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- Review --}}
                            @if($certificate->reviewed_at)

                                <div class="d-flex mb-4">

                                    <div class="me-3">

                                        <span class="badge bg-info rounded-circle p-2">

                                            <i class="ri-search-eye-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Under Review
                                        </h6>

                                        <small class="text-muted">

                                            {{ $certificate->reviewed_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $certificate->reviewedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- Approved --}}
                            @if($certificate->approved_at)

                                <div class="d-flex">

                                    <div class="me-3">

                                        <span class="badge bg-success rounded-circle p-2">

                                            <i class="ri-checkbox-circle-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Certificate Approved
                                        </h6>

                                        <small class="text-muted">

                                            {{ $certificate->approved_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $certificate->approvedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                        </div>

                    </div>

                </div>

            @else

                {{-- No Certificate --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="ri-file-shield-2-line text-muted"
                           style="font-size: 64px;">
                        </i>

                        <h5 class="mt-3">
                            Handover Certificate Not Created
                        </h5>

                        <p class="text-muted mb-4">

                            @if($finalCompletionApproved)

                                Final Completion is approved.
                                You can now create the Handover Certificate.

                            @else

                                The certificate will become available
                                after Final Completion is approved.

                            @endif

                        </p>


                        @if($finalCompletionApproved)

                            <form method="POST"
                                  action="{{ route(
                                      'admin.projects.handover.certificates.store',
                                      $project
                                  ) }}">

                                @csrf

                                <input type="hidden"
                                       name="certificate_type"
                                       value="Handover Certificate">

                                <input type="hidden"
                                       name="title"
                                       value="Handover Certificate">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ri-add-line me-1"></i>
                                    Create Handover Certificate

                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @endif

        </div>


        {{-- Sidebar --}}
        <div class="col-xl-4">


            {{-- Final Completion --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Final Completion
                    </h5>

                </div>


                <div class="card-body">

                    @if($finalCompletion)

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Completion No.
                            </span>

                            <strong>
                                {{ $finalCompletion->completion_no }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Status
                            </span>

                            <span class="badge
                                {{ $finalCompletion->status === 'Approved'
                                    ? 'bg-success'
                                    : 'bg-secondary' }}">

                                {{ $finalCompletion->status }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Approved Date
                            </span>

                            <strong>

                                {{ $finalCompletion->approved_date
                                    ? $finalCompletion->approved_date->format(
                                        'd M Y'
                                    )
                                    : '-' }}

                            </strong>

                        </div>


                    @else

                        <div class="text-muted text-center py-3">

                            Final Completion not available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Handover Status --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Handover Status
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex align-items-center mb-3">

                        @if($finalCompletionApproved)

                            <i class="ri-checkbox-circle-fill text-success fs-4 me-2"></i>

                            <div>

                                <strong>
                                    Final Completion
                                </strong>

                                <div class="small text-muted">
                                    Approved
                                </div>

                            </div>

                        @else

                            <i class="ri-time-line text-warning fs-4 me-2"></i>

                            <div>

                                <strong>
                                    Final Completion
                                </strong>

                                <div class="small text-muted">
                                    Pending Approval
                                </div>

                            </div>

                        @endif

                    </div>


                    <div class="d-flex align-items-center">

                        @if($certificateApproved)

                            <i class="ri-checkbox-circle-fill text-success fs-4 me-2"></i>

                            <div>

                                <strong>
                                    Handover Certificate
                                </strong>

                                <div class="small text-muted">
                                    Approved
                                </div>

                            </div>

                        @else

                            <i class="ri-time-line text-warning fs-4 me-2"></i>

                            <div>

                                <strong>
                                    Handover Certificate
                                </strong>

                                <div class="small text-muted">
                                    Pending
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Navigation --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Handover Modules
                    </h5>

                </div>


                <div class="card-body p-2">

                    <a href="{{ route(
                        'admin.projects.handover.final-completion.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-flag-line me-2"></i>
                        Final Completion

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.final-account.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-file-list-3-line me-2"></i>
                        Final Account

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.final-payment.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-money-rupee-circle-line me-2"></i>
                        Final Payment

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.documents.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-folder-3-line me-2"></i>
                        Handover Documents

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start">

                        <i class="ri-dashboard-line me-2"></i>
                        Handover Dashboard

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Reject Modal --}}
@if($certificate && $certificate->status === 'Under Review')

<div class="modal fade"
     id="rejectCertificateModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <form method="POST"
              action="{{ route(
                  'admin.projects.handover.certificates.reject',
                  [$project, $certificate]
              ) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Handover Certificate
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        Provide a clear reason for rejection.
                        The certificate can then be corrected
                        and resubmitted.

                    </div>


                    <label class="form-label">

                        Rejection Reason

                        <span class="text-danger">*</span>

                    </label>

                    <textarea name="rejection_reason"
                              class="form-control"
                              rows="5"
                              required></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="ri-close-circle-line me-1"></i>

                        Reject Certificate

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endif

@endsection