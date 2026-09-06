@extends('layouts.app')

@section('title', 'Claim Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $claim->claim_number }}
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.claims.documents.index',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fa fa-folder"></i>
                Documents

            </a>

            @if(in_array($claim->status, ['Draft', 'Rejected']))

                <a href="{{ route('admin.projects.construction.claims.edit', [$project, $claim]) }}"
                   class="btn btn-primary">

                    <i class="fa fa-edit"></i>
                    Edit

                </a>

            @endif


            <a href="{{ route('admin.projects.construction.claims.index', $project) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Status --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h5 class="mb-1">
                        {{ $claim->subject }}
                    </h5>

                    <div class="text-muted">
                        Claim Type: {{ $claim->claim_type }}
                    </div>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    @php

                        $statusClass = match($claim->status) {

                            'Draft' => 'bg-secondary',
                            'Submitted' => 'bg-info',
                            'Under Review' => 'bg-warning text-dark',
                            'Under Assessment' => 'bg-primary',
                            'Approved' => 'bg-success',
                            'Partially Approved' => 'bg-success',
                            'Rejected' => 'bg-danger',
                            'Withdrawn' => 'bg-dark',
                            'Closed' => 'bg-dark',

                            default => 'bg-secondary'

                        };

                    @endphp

                    <span class="badge {{ $statusClass }} fs-6 px-3 py-2">
                        {{ $claim->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Claim Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Amount
                    </div>

                    <h4 class="mb-0">
                        ${{ number_format($claim->claimed_amount ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Days
                    </div>

                    <h4 class="mb-0">
                        {{ $claim->claimed_days ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <h4 class="mb-0">
                        ${{ number_format($claim->approved_amount ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Days
                    </div>

                    <h4 class="mb-0">
                        {{ $claim->approved_days ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Claim Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Claim Number
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->claim_number }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Claim Date
                    </div>

                    <div>
                        {{ $claim->claim_date?->format('d-m-Y') ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Event Date
                    </div>

                    <div>
                        {{ $claim->event_date?->format('d-m-Y') ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Claim Type
                    </div>

                    <div>
                        {{ $claim->claim_type }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div>
                        {{ $claim->priority }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Claimant Type
                    </div>

                    <div>
                        {{ $claim->claimant_type }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Claimant Name
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->claimant_name }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Subject
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->subject }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Contract Reference --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract & Work Order Reference
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Procurement Contract
                    </div>

                    @if($claim->procurementContract)

                        <div class="fw-semibold">
                            {{ $claim->procurementContract->contract_number }}
                        </div>

                        @if($claim->procurementContract->bidder)

                            <div class="text-muted">
                                {{ $claim->procurementContract->bidder->bidder_name }}
                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Construction Work Order
                    </div>

                    @if($claim->workOrder)

                        <div class="fw-semibold">
                            {{ $claim->workOrder->work_order_number }}
                        </div>

                        @if($claim->workOrder->work_order_title)

                            <div class="text-muted">
                                {{ $claim->workOrder->work_order_title }}
                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Claim Description
            </h5>

        </div>

        <div class="card-body">

            <h6>
                Description
            </h6>

            <div class="mb-4">
                {!! nl2br(e($claim->description)) !!}
            </div>


            @if($claim->justification)

                <h6>
                    Justification
                </h6>

                <div>
                    {!! nl2br(e($claim->justification)) !!}
                </div>

            @endif

        </div>

    </div>


    {{-- Assessment --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Assessment & Approval
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Claimed Amount
                    </div>

                    <div class="fw-semibold">
                        ${{ number_format($claim->claimed_amount ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assessed Amount
                    </div>

                    <div class="fw-semibold">
                        ${{ number_format($claim->assessed_amount ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <div class="fw-semibold">
                        ${{ number_format($claim->approved_amount ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Claimed Days
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->claimed_days ?? 0 }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Assessed Days
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->assessed_days ?? 0 }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Days
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->approved_days ?? 0 }}
                    </div>

                </div>

            </div>


            @if($claim->assessment_remarks)

                <hr>

                <h6>
                    Assessment Remarks
                </h6>

                <div>
                    {!! nl2br(e($claim->assessment_remarks)) !!}
                </div>

            @endif


            @if($claim->approval_remarks)

                <hr>

                <h6>
                    Approval Remarks
                </h6>

                <div>
                    {!! nl2br(e($claim->approval_remarks)) !!}
                </div>

            @endif


            @if($claim->rejection_remarks)

                <hr>

                <h6 class="text-danger">
                    Rejection Remarks
                </h6>

                <div class="text-danger">
                    {!! nl2br(e($claim->rejection_remarks)) !!}
                </div>

            @endif

        </div>

    </div>


    {{-- Supporting Documents --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">
                        Supporting Documents
                    </h5>

                    <small class="text-muted">
                        Documents and evidence attached to this claim
                    </small>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <span class="badge bg-secondary">
                        {{ $claim->documents->count() }}
                    </span>

                    <a href="{{ route(
                        'admin.projects.construction.claims.documents.create',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-sm btn-primary">

                        <i class="bi bi-upload me-1"></i>
                        Upload Document

                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($claim->documents->count())

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
                                    File
                                </th>

                                <th>
                                    Uploaded By
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($claim->documents as $document)

                                <tr>

                                    {{-- Document --}}
                                    <td class="ps-3">

                                        <div class="d-flex align-items-center">

                                            <div class="me-3">

                                                <div class="bg-light rounded p-2">

                                                    @php
                                                        $extension = strtolower(
                                                            pathinfo(
                                                                $document->file_name ?? '',
                                                                PATHINFO_EXTENSION
                                                            )
                                                        );
                                                    @endphp

                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))

                                                        <i class="bi bi-file-earmark-image text-primary fs-5"></i>

                                                    @elseif($extension === 'pdf')

                                                        <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>

                                                    @elseif(in_array($extension, ['doc', 'docx']))

                                                        <i class="bi bi-file-earmark-word text-primary fs-5"></i>

                                                    @elseif(in_array($extension, ['xls', 'xlsx']))

                                                        <i class="bi bi-file-earmark-excel text-success fs-5"></i>

                                                    @else

                                                        <i class="bi bi-file-earmark-text text-secondary fs-5"></i>

                                                    @endif

                                                </div>

                                            </div>


                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $document->document_title }}

                                                </div>

                                                @if($document->description)

                                                    <small class="text-muted">

                                                        {{ \Illuminate\Support\Str::limit(
                                                            $document->description,
                                                            80
                                                        ) }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $document->document_type }}

                                        </span>

                                    </td>


                                    {{-- File --}}
                                    <td>

                                        @if($document->file_name)

                                            <div>

                                                <div class="small fw-semibold">

                                                    {{ \Illuminate\Support\Str::limit(
                                                        $document->file_name,
                                                        35
                                                    ) }}

                                                </div>

                                                @if($document->file_size)

                                                    <small class="text-muted">

                                                        {{ number_format(
                                                            $document->file_size / 1024,
                                                            2
                                                        ) }}
                                                        KB

                                                    </small>

                                                @endif

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Uploaded By --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $document->uploadedBy?->name ?? '-' }}

                                        </div>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        <div class="small">

                                            {{ $document->created_at?->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $document->created_at?->format('H:i') }}

                                        </small>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-3">

                                        <div class="d-flex justify-content-end gap-1">

                                            {{-- View --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.claims.documents.view',
                                                [
                                                    'project' => $project,
                                                    'claim' => $claim,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Document">

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            {{-- Download --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.claims.documents.download',
                                                [
                                                    'project' => $project,
                                                    'claim' => $claim,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Download Document">

                                                <i class="fa fa-download"></i>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- View All Documents --}}
                <div class="border-top p-3 text-end">

                    <a href="{{ route(
                        'admin.projects.construction.claims.documents.index',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-sm btn-outline-secondary">

                        <i class="bi bi-folder2-open me-1"></i>
                        View All Documents

                    </a>

                </div>

            @else

                <div class="text-center py-5 px-3">

                    <div class="mb-3">

                        <div class="bg-light rounded-circle d-inline-flex p-3">

                            <i class="bi bi-file-earmark-text fs-2 text-muted"></i>

                        </div>

                    </div>

                    <h6 class="mb-1">
                        No Supporting Documents
                    </h6>

                    <p class="text-muted mb-3">
                        No documents or supporting evidence have been
                        added to this claim yet.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.claims.documents.create',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-sm btn-primary">

                        <i class="bi bi-upload me-1"></i>
                        Upload Document

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- History --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Claim History
            </h5>

        </div>

        <div class="card-body">

            @if($claim->history->count())

                <div class="table-responsive">

                    <table class="table table-sm align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Action
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Remarks
                                </th>

                                <th>
                                    Performed By
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($claim->history->sortByDesc('performed_at') as $history)

                                <tr>

                                    <td>
                                        {{ $history->performed_at?->format('d-m-Y H:i') ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $history->action }}
                                        </strong>
                                    </td>

                                    <td>

                                        @if($history->old_status)
                                            {{ $history->old_status }}
                                        @else
                                            -
                                        @endif

                                        <i class="bi bi-arrow-right mx-1"></i>

                                        {{ $history->new_status }}

                                    </td>

                                    <td>
                                        {{ $history->remarks ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $history->performedBy->name ?? '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-3">
                    No claim history available.
                </div>

            @endif

        </div>

    </div>


    {{-- Remarks --}}
    @if($claim->remarks)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                {!! nl2br(e($claim->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- Workflow Actions --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Workflow Actions
            </h5>

        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                {{-- Submit --}}
                @if($claim->status === 'Draft')

                    <form method="POST"
                          action="{{ route('admin.projects.construction.claims.submit', [$project, $claim]) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('Submit this claim?')">

                            <i class="bi bi-send"></i>
                            Submit

                        </button>

                    </form>

                @endif


                {{-- Review --}}
                @if($claim->status === 'Submitted')

                    <form method="POST"
                          action="{{ route('admin.projects.construction.claims.review', [$project, $claim]) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-warning"
                                onclick="return confirm('Move this claim to Under Review?')">

                            <i class="bi bi-search"></i>
                            Start Review

                        </button>

                    </form>

                @endif

                {{-- Assessment --}}
                @if($claim->status === 'Under Review')

                    <a href="{{ route(
                        'admin.projects.construction.claims.assessment',
                        [$project, $claim]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-calculator me-1"></i>
                        Assess Claim

                    </a>

                @endif


                {{-- Update Assessment --}}
                @if($claim->status === 'Under Assessment')

                    <a href="{{ route(
                        'admin.projects.construction.claims.assessment',
                        [$project, $claim]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-pencil-square me-1"></i>
                        Update Assessment

                    </a>

                @endif


                {{-- Approve --}}
                @if($claim->status === 'Under Assessment')

                    <a href="{{ route(
                        'admin.projects.construction.claims.approval',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>
                        Approve Claim

                    </a>


                    <a href="{{ route(
                        'admin.projects.construction.claims.rejection',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-danger">

                        <i class="bi bi-x-circle me-1"></i>
                        Reject Claim

                    </a>

                @endif


                {{-- Close --}}
                @if(in_array($claim->status, ['Approved', 'Partially Approved']))

                    <form method="POST"
                          action="{{ route('admin.projects.construction.claims.close', [$project, $claim]) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-dark"
                                onclick="return confirm('Close this claim?')">

                            <i class="bi bi-lock"></i>
                            Close Claim

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection