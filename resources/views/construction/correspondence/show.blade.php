@extends('layouts.app')

@section('title', 'Correspondence Details')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="mb-0">
                    {{ $correspondence->correspondence_number }}
                </h4>

                @php

                    $statusClass = match($correspondence->status) {
                        'Draft' => 'bg-secondary-subtle text-secondary',
                        'Registered' => 'bg-info-subtle text-info',
                        'Under Review' => 'bg-primary-subtle text-primary',
                        'Action Required' => 'bg-warning-subtle text-warning',
                        'Responded' => 'bg-success-subtle text-success',
                        'Closed' => 'bg-dark-subtle text-dark',
                        'Archived' => 'bg-light text-dark',
                        default => 'bg-light text-dark',
                    };

                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ $correspondence->status }}
                </span>

            </div>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.correspondence.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>


            @if(in_array($correspondence->status, ['Draft', 'Registered']))

                <a href="{{ route(
                    'admin.projects.construction.correspondence.edit',
                    [$project, $correspondence]
                ) }}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-pencil me-1"></i>
                    Edit

                </a>

            @endif

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         WORKFLOW ACTIONS
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0">
                    <i class="bi bi-diagram-3 me-1"></i>
                    Correspondence Workflow
                </h6>

                <span class="small text-muted">
                    Current Status:
                    <strong>{{ $correspondence->status }}</strong>
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- Draft --}}
                @if($correspondence->status === 'Draft')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.correspondence.register',
                              [$project, $correspondence]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-info">

                            <i class="bi bi-check2-circle me-1"></i>
                            Register

                        </button>

                    </form>

                @endif


                {{-- Registered --}}
                @if($correspondence->status === 'Registered')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.correspondence.review',
                              [$project, $correspondence]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>
                            Start Review

                        </button>

                    </form>

                @endif


                {{-- Under Review --}}
                @if($correspondence->status === 'Under Review')

                    <button type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#actionRequiredModal">

                        <i class="bi bi-exclamation-circle me-1"></i>
                        Action Required

                    </button>

                @endif


                {{-- Action Required --}}
                @if($correspondence->status === 'Action Required')

                    <button type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#respondModal">

                        <i class="bi bi-reply me-1"></i>
                        Mark Responded

                    </button>

                @endif


                {{-- Responded --}}
                @if($correspondence->status === 'Responded')

                    <button type="button"
                            class="btn btn-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#closeModal">

                        <i class="bi bi-check-circle me-1"></i>
                        Close

                    </button>

                @endif


                {{-- Archive --}}
                @if($correspondence->status === 'Closed')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.correspondence.archive',
                              [$project, $correspondence]
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-outline-dark"
                                onclick="return confirm('Archive this correspondence?');">

                            <i class="bi bi-archive me-1"></i>
                            Archive

                        </button>

                    </form>

                @endif


                {{-- Documents --}}
                <a href="{{ route(
                    'admin.projects.construction.correspondence.documents.index',
                    [$project, $correspondence]
                ) }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-paperclip me-1"></i>
                    Documents

                </a>

            </div>

        </div>

    </div>


    {{-- ============================================================
         SUBJECT / BASIC INFORMATION
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="bi bi-envelope me-1"></i>
                Correspondence Information
            </h6>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-8">

                    <div class="text-muted small mb-1">
                        Subject
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $correspondence->subject }}
                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="text-muted small mb-1">
                        Correspondence Type
                    </div>

                    <span class="badge bg-primary-subtle text-primary fs-6">
                        {{ $correspondence->correspondence_type }}
                    </span>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Reference Number
                    </div>

                    <div class="fw-semibold">
                        {{ $correspondence->reference_number ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Communication Method
                    </div>

                    <div class="fw-semibold">
                        {{ $correspondence->communication_method ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Correspondence Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->correspondence_date)->format('d M Y') ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Priority
                    </div>

                    @php

                        $priorityClass = match($correspondence->priority) {
                            'Low' => 'bg-secondary-subtle text-secondary',
                            'Medium' => 'bg-info-subtle text-info',
                            'High' => 'bg-warning-subtle text-warning',
                            'Critical' => 'bg-danger-subtle text-danger',
                            default => 'bg-light text-dark',
                        };

                    @endphp

                    <span class="badge {{ $priorityClass }}">
                        {{ $correspondence->priority }}
                    </span>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Received Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->received_date)->format('d M Y') ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Sent Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->sent_date)->format('d M Y') ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->creator)->name ?: '—' }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Created On
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->created_at)->format('d M Y H:i') ?: '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         SENDER / RECEIVER
    ============================================================ --}}
    <div class="row g-4 mb-4">

        {{-- Sender --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i>
                        Sender
                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->sender_type ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Name
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->sender_name ?: '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Organization
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->sender_organization ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Receiver --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        <i class="bi bi-box-arrow-in-down-left me-1"></i>
                        Receiver
                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Type
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->receiver_type ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Name
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->receiver_name ?: '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Organization
                            </div>

                            <div class="fw-semibold">
                                {{ $correspondence->receiver_organization ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PROJECT REFERENCES
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="bi bi-link-45deg me-1"></i>
                Project References
            </h6>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Work Order --}}
                <div class="col-lg-4 col-md-6">

                    <div class="text-muted small">
                        Construction Work Order
                    </div>

                    @if($correspondence->workOrder)

                        <div class="fw-semibold">
                            {{ $correspondence->workOrder->work_order_number }}
                        </div>

                        @if($correspondence->workOrder->work_order_title)

                            <div class="small text-muted">
                                {{ $correspondence->workOrder->work_order_title }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Not Linked
                        </div>

                    @endif

                </div>


                {{-- Contract --}}
                <div class="col-lg-4 col-md-6">

                    <div class="text-muted small">
                        Procurement Contract
                    </div>

                    @if($correspondence->procurementContract)

                        <div class="fw-semibold">
                            {{ $correspondence->procurementContract->contract_number
                                ?? 'Contract #'.$correspondence->procurementContract->id }}
                        </div>

                    @else

                        <div class="text-muted">
                            Not Linked
                        </div>

                    @endif

                </div>


                {{-- Claim --}}
                <div class="col-lg-4 col-md-6">

                    <div class="text-muted small">
                        Construction Claim
                    </div>

                    @if($correspondence->claim)

                        <div class="fw-semibold">
                            {{ $correspondence->claim->claim_number }}
                        </div>

                        @if($correspondence->claim->subject)

                            <div class="small text-muted">
                                {{ $correspondence->claim->subject }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Not Linked
                        </div>

                    @endif

                </div>


                {{-- Delay --}}
                <div class="col-lg-4 col-md-6">

                    <div class="text-muted small">
                        Construction Delay
                    </div>

                    @if($correspondence->delay)

                        <div class="fw-semibold">
                            {{ $correspondence->delay->delay_number }}
                        </div>

                        @if($correspondence->delay->delay_title)

                            <div class="small text-muted">
                                {{ $correspondence->delay->delay_title }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Not Linked
                        </div>

                    @endif

                </div>


                {{-- Risk --}}
                <div class="col-lg-4 col-md-6">

                    <div class="text-muted small">
                        Construction Risk
                    </div>

                    @if($correspondence->risk)

                        <div class="fw-semibold">
                            {{ $correspondence->risk->risk_number }}
                        </div>

                        @if($correspondence->risk->risk_title)

                            <div class="small text-muted">
                                {{ $correspondence->risk->risk_title }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Not Linked
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         ACTION & RESPONSE
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="bi bi-check2-square me-1"></i>
                Action & Response Tracking
            </h6>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Response Required --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Response Required
                    </div>

                    @if($correspondence->response_required)

                        <span class="badge bg-warning-subtle text-warning">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-secondary-subtle text-secondary">
                            No
                        </span>

                    @endif

                </div>


                {{-- Response Due --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Response Due Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->response_due_date)->format('d M Y') ?: '—' }}
                    </div>

                </div>


                {{-- Response Date --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Response Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->response_date)->format('d M Y') ?: '—' }}
                    </div>

                </div>


                {{-- Assigned --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Assigned To
                    </div>

                    <div class="fw-semibold">
                        {{ optional($correspondence->assignedTo)->name ?: '—' }}
                    </div>

                </div>


                {{-- Action Required --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Action Required
                    </div>

                    @if($correspondence->action_required)

                        <span class="badge bg-danger-subtle text-danger">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-secondary-subtle text-secondary">
                            No
                        </span>

                    @endif

                </div>


                {{-- Responsible Type --}}
                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Responsible Party
                    </div>

                    <div class="fw-semibold">

                        {{ $correspondence->responsible_party_type ?: '—' }}

                        @if($correspondence->responsible_party_name)

                            <div class="small text-muted">
                                {{ $correspondence->responsible_party_name }}
                            </div>

                        @endif

                    </div>

                </div>


                {{-- Action Description --}}
                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Action Description
                    </div>

                    <div class="border rounded p-3 bg-light">

                        @if($correspondence->action_description)

                            {!! nl2br(e($correspondence->action_description)) !!}

                        @else

                            <span class="text-muted">
                                No action description provided.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         DESCRIPTION
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="bi bi-file-text me-1"></i>
                Description & Remarks
            </h6>

        </div>


        <div class="card-body">

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Description
                </div>

                <div class="border rounded p-3">

                    @if($correspondence->description)

                        {!! nl2br(e($correspondence->description)) !!}

                    @else

                        <span class="text-muted">
                            No description provided.
                        </span>

                    @endif

                </div>

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Remarks
                </div>

                <div class="border rounded p-3">

                    @if($correspondence->remarks)

                        {!! nl2br(e($correspondence->remarks)) !!}

                    @else

                        <span class="text-muted">
                            No remarks.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         DOCUMENTS
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0">
                    <i class="bi bi-paperclip me-1"></i>
                    Documents
                </h6>

                <a href="{{ route(
                    'admin.projects.construction.correspondence.documents.index',
                    [$project, $correspondence]
                ) }}"
                   class="btn btn-sm btn-outline-primary">

                    <i class="bi bi-folder2-open me-1"></i>
                    Manage Documents

                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if($correspondence->documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    Document
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Uploaded By
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end pe-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($correspondence->documents->take(5) as $document)

                                <tr>

                                    <td class="ps-3">

                                        <div class="fw-semibold">
                                            {{ $document->document_title }}
                                        </div>

                                        @if($document->file_name)

                                            <div class="small text-muted">
                                                {{ $document->file_name }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark">
                                            {{ $document->document_type }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ optional($document->uploadedBy)->name ?: '—' }}
                                    </td>


                                    <td>
                                        {{ optional($document->created_at)->format('d M Y') ?: '—' }}
                                    </td>


                                    <td class="text-end pe-3">

                                        <a href="{{ route(
                                            'admin.projects.construction.correspondence.documents.view',
                                            [$project, $correspondence, $document]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.projects.construction.correspondence.documents.download',
                                            [$project, $correspondence, $document]
                                        ) }}"
                                           class="btn btn-sm btn-outline-secondary">

                                            <i class="fa fa-download"></i>

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                @if($correspondence->documents->count() > 5)

                    <div class="card-footer bg-white text-center">

                        <a href="{{ route(
                            'admin.projects.construction.correspondence.documents.index',
                            [$project, $correspondence]
                        ) }}">

                            View all
                            {{ $correspondence->documents->count() }}
                            documents

                        </a>

                    </div>

                @endif

            @else

                <div class="text-center py-4">

                    <i class="bi bi-paperclip fs-2 text-muted"></i>

                    <div class="text-muted mt-2">
                        No documents attached.
                    </div>

                    <a href="{{ route(
                        'admin.projects.construction.correspondence.documents.create',
                        [$project, $correspondence]
                    ) }}"
                       class="btn btn-sm btn-primary mt-3">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Document

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
         STATUS HISTORY
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="bi bi-clock-history me-1"></i>
                Status History
            </h6>

        </div>


        <div class="card-body">

            @if($correspondence->history->count())

                <div class="timeline">

                    @foreach($correspondence->history as $history)

                        <div class="d-flex mb-4">

                            <div class="me-3">

                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                     style="width: 38px; height: 38px;">

                                    <i class="bi bi-arrow-right"></i>

                                </div>

                            </div>


                            <div class="flex-grow-1">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $history->action }}
                                        </div>

                                        @if($history->old_status || $history->new_status)

                                            <div class="small text-muted">

                                                @if($history->old_status)
                                                    {{ $history->old_status }}
                                                @endif

                                                @if($history->old_status && $history->new_status)
                                                    →
                                                @endif

                                                @if($history->new_status)
                                                    {{ $history->new_status }}
                                                @endif

                                            </div>

                                        @endif

                                    </div>


                                    <div class="small text-muted">

                                        {{ optional($history->performed_at)->format('d M Y H:i') }}

                                    </div>

                                </div>


                                @if($history->remarks)

                                    <div class="mt-2 text-muted">
                                        {!! nl2br(e($history->remarks)) !!}
                                    </div>

                                @endif


                                @if($history->performedBy)

                                    <div class="small text-muted mt-1">

                                        By:
                                        {{ $history->performedBy->name }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-muted text-center py-3">
                    No workflow history available.
                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
     ACTION REQUIRED MODAL
================================================================ --}}
@if($correspondence->status === 'Under Review')

    <div class="modal fade"
         id="actionRequiredModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.correspondence.action_required',
                          [$project, $correspondence]
                      ) }}">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Mark Action Required
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Action Description
                                <span class="text-danger">*</span>
                            </label>

                            <textarea name="action_description"
                                      class="form-control"
                                      rows="4"
                                      required
                                      placeholder="Describe the action required..."></textarea>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Assigned To
                            </label>

                            <select name="assigned_to"
                                    class="form-select">

                                <option value="">
                                    Select User
                                </option>

                                @foreach(\App\Models\User::orderBy('name')->get() as $user)

                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Responsible Party Type
                            </label>

                            <select name="responsible_party_type"
                                    class="form-select">

                                <option value="">
                                    Select
                                </option>

                                @foreach([
                                    'Client',
                                    'Consultant',
                                    'Contractor',
                                    'Supplier',
                                    'Project Team',
                                    'Other'
                                ] as $type)

                                    <option value="{{ $type }}">
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Responsible Party Name
                            </label>

                            <input type="text"
                                   name="responsible_party_name"
                                   class="form-control"
                                   maxlength="255">

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Response Due Date
                            </label>

                            <input type="date"
                                   name="response_due_date"
                                   class="form-control">

                        </div>


                        <div>

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      class="form-control"
                                      rows="3"></textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="btn btn-warning">

                            <i class="bi bi-check-lg me-1"></i>
                            Mark Action Required

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
     RESPOND MODAL
================================================================ --}}
@if($correspondence->status === 'Action Required')

    <div class="modal fade"
         id="respondModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.correspondence.respond',
                          [$project, $correspondence]
                      ) }}">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Mark Correspondence Responded
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Response Date
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="response_date"
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="form-control"
                                   required>

                        </div>


                        <div>

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter response remarks..."></textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-lg me-1"></i>
                            Mark Responded

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
     CLOSE MODAL
================================================================ --}}
@if($correspondence->status === 'Responded')

    <div class="modal fade"
         id="closeModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST"
                      action="{{ route(
                          'admin.projects.construction.correspondence.close',
                          [$project, $correspondence]
                      ) }}">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Close Correspondence
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-light border">

                            <i class="bi bi-info-circle me-1"></i>

                            Closing this correspondence means that
                            all required action and response activities
                            have been completed.

                        </div>


                        <label class="form-label">
                            Closing Remarks
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="Enter closing remarks..."></textarea>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="btn btn-dark">

                            <i class="bi bi-check-circle me-1"></i>
                            Close Correspondence

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

@endsection