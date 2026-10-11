@extends('layouts.app')

@section('title', 'Document Details')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Document Details
            </h4>

            <p class="text-muted mb-0">
                View fit-out document information and version history.
            </p>

        </div>


        <div>

            <a
                href="{{ route('admin.fitout.documents.index') }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

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


    <div class="row">


        {{-- ===================================================== --}}
        {{-- LEFT SIDE --}}
        {{-- ===================================================== --}}

        <div class="col-lg-8">


            {{-- ------------------------------------------------- --}}
            {{-- DOCUMENT INFORMATION --}}
            {{-- ------------------------------------------------- --}}

            <div class="card mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Document Information
                        </h5>


                        @php

                            $statusClass = match(
                                $document->approval_status
                            ) {

                                'Approved' =>
                                    'bg-success',

                                'Rejected' =>
                                    'bg-danger',

                                'Under Review' =>
                                    'bg-warning text-dark',

                                'Superseded' =>
                                    'bg-secondary',

                                default =>
                                    'bg-info text-dark',

                            };

                        @endphp


                        <span class="badge {{ $statusClass }}">

                            {{ $document->approval_status }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Document Title --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Title
                            </label>

                            <div class="fw-semibold">

                                {{ $document->document_title }}

                            </div>

                        </div>


                        {{-- Document Type --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Type
                            </label>

                            <div class="fw-semibold">

                                {{ $document->documentType->document_name ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Document Number --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Number
                            </label>

                            <div>

                                {{ $document->document_number ?: 'N/A' }}

                            </div>

                        </div>


                        {{-- Version --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Version
                            </label>

                            <div>

                                <span class="badge bg-secondary">

                                    v{{ $document->version_no }}

                                </span>

                            </div>

                        </div>


                        {{-- File Name --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                File Name
                            </label>

                            <div class="text-break">

                                {{ $document->file_name }}

                            </div>

                        </div>


                        {{-- File Extension --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                File Type
                            </label>

                            <div>

                                {{ strtoupper($document->file_extension ?? 'N/A') }}

                            </div>

                        </div>


                        {{-- File Size --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                File Size
                            </label>

                            <div>

                                @if($document->file_size)

                                    {{ number_format($document->file_size / 1024 / 1024, 2) }}
                                    MB

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>


                        {{-- Submitted By --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Submitted By
                            </label>

                            <div>

                                {{ $document->submittedBy->name ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Submitted At --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Submitted At
                            </label>

                            <div>

                                @if($document->submitted_at)

                                    {{ \Carbon\Carbon::parse($document->submitted_at)->format('d M Y H:i') }}

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>


                        {{-- Approved By --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Approved By
                            </label>

                            <div>

                                {{ $document->approvedBy->name ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Approved At --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Approved At
                            </label>

                            <div>

                                @if($document->approved_at)

                                    {{ \Carbon\Carbon::parse($document->approved_at)->format('d M Y H:i') }}

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>


                        {{-- Remarks --}}
                        <div class="col-md-12 mb-3">

                            <label class="text-muted small">
                                Remarks
                            </label>

                            <div>

                                {{ $document->remarks ?: 'No remarks.' }}

                            </div>

                        </div>


                        {{-- Rejection Reason --}}
                        @if($document->rejection_reason)

                            <div class="col-md-12">

                                <div class="alert alert-danger mb-0">

                                    <strong>
                                        Rejection Reason:
                                    </strong>

                                    <div class="mt-1">

                                        {{ $document->rejection_reason }}

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ------------------------------------------------- --}}
            {{-- FILE ACTIONS --}}
            {{-- ------------------------------------------------- --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Document File
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="me-3">

                            @php

                                $extension =
                                    strtolower(
                                        $document->file_extension ?? ''
                                    );

                            @endphp


                            @if($extension === 'pdf')

                                <i
                                    class="bi bi-file-earmark-pdf"
                                    style="font-size:45px;"
                                ></i>

                            @elseif(in_array($extension, ['jpg', 'jpeg', 'png']))

                                <i
                                    class="bi bi-file-earmark-image"
                                    style="font-size:45px;"
                                ></i>

                            @elseif(in_array($extension, ['doc', 'docx']))

                                <i
                                    class="bi bi-file-earmark-word"
                                    style="font-size:45px;"
                                ></i>

                            @elseif(in_array($extension, ['xls', 'xlsx']))

                                <i
                                    class="bi bi-file-earmark-excel"
                                    style="font-size:45px;"
                                ></i>

                            @else

                                <i
                                    class="bi bi-file-earmark"
                                    style="font-size:45px;"
                                ></i>

                            @endif

                        </div>


                        <div class="flex-grow-1">

                            <div class="fw-semibold">

                                {{ $document->file_name }}

                            </div>

                            <small class="text-muted">

                                {{ strtoupper($document->file_extension ?? 'FILE') }}

                                @if($document->file_size)
                                    -
                                    {{ number_format($document->file_size / 1024 / 1024, 2) }}
                                    MB
                                @endif

                            </small>

                        </div>


                        <div class="ms-3">

                            <a
                                href="{{ asset('storage/' . $document->file_path) }}"
                                target="_blank"
                                class="btn btn-outline-primary me-1"
                            >

                                <i class="bi bi-eye"></i>
                                View

                            </a>


                            <a
                                href="{{ asset('storage/' . $document->file_path) }}"
                                download
                                class="btn btn-primary"
                            >

                                <i class="bi bi-download"></i>
                                Download

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ------------------------------------------------- --}}
            {{-- VERSION HISTORY --}}
            {{-- ------------------------------------------------- --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Version History
                    </h5>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Version
                                    </th>

                                    <th>
                                        File
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Submitted
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($versions as $version)

                                    <tr>

                                        <td>

                                            <span class="badge bg-secondary">

                                                v{{ $version->version_no }}

                                            </span>

                                            @if($version->id === $document->id)

                                                <span class="badge bg-primary">
                                                    Current
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <div class="fw-semibold">

                                                {{ $version->file_name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ strtoupper($version->file_extension ?? '') }}

                                            </small>

                                        </td>


                                        <td>

                                            @php

                                                $versionStatusClass =
                                                    match($version->approval_status) {

                                                        'Approved' =>
                                                            'bg-success',

                                                        'Rejected' =>
                                                            'bg-danger',

                                                        'Under Review' =>
                                                            'bg-warning text-dark',

                                                        'Superseded' =>
                                                            'bg-secondary',

                                                        default =>
                                                            'bg-info text-dark',

                                                    };

                                            @endphp


                                            <span
                                                class="badge {{ $versionStatusClass }}"
                                            >

                                                {{ $version->approval_status }}

                                            </span>

                                        </td>


                                        <td>

                                            @if($version->submitted_at)

                                                {{ \Carbon\Carbon::parse($version->submitted_at)->format('d M Y H:i') }}

                                            @else

                                                N/A

                                            @endif

                                        </td>


                                        <td class="text-end">

                                            <a
                                                href="{{ route('admin.fitout.documents.show', $version->id) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >

                                                <i class="fas fa-eye"></i>

                                            </a>

                                            <a
                                                href="{{ asset('storage/' . $version->file_path) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary"
                                            >

                                                <i class="fas fa-file"></i>

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4 text-muted"
                                        >

                                            No version history found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RIGHT SIDE --}}
        {{-- ===================================================== --}}

        <div class="col-lg-4">


            {{-- ------------------------------------------------- --}}
            {{-- FIT-OUT REQUEST --}}
            {{-- ------------------------------------------------- --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Request
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="text-muted small">
                            Request No.
                        </label>

                        <div class="fw-semibold">

                            {{ $document->fitoutRequest->request_no ?? 'N/A' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Tenant
                        </label>

                        <div>

                            {{ $document->fitoutRequest->tenant->company_name
                                ?? $document->fitoutRequest->tenant->company_name
                                ?? 'N/A' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Unit
                        </label>

                        <div>

                            {{ $document->fitoutRequest->unit->unit_no ?? 'N/A' }}

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Contractor
                        </label>

                        <div>

                            {{ $document->fitoutRequest->contractor->contractor_name ?? 'N/A' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ------------------------------------------------- --}}
            {{-- ACTIONS --}}
            {{-- ------------------------------------------------- --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Actions
                    </h5>

                </div>


                <div class="card-body">


                    @if($document->approval_status === 'Pending')

                        <div class="d-grid gap-2">

                            <a
                                href="{{ route('admin.fitout.documents.review', $document->id) }}"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-check-circle"></i>

                                Review Document

                            </a>

                        </div>


                    @elseif($document->approval_status === 'Under Review')

                        <div class="d-grid gap-2">

                            <a
                                href="{{ route('admin.fitout.documents.edit', $document->id) }}"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-check-circle"></i>

                                Continue Review

                            </a>

                        </div>


                    @elseif($document->approval_status === 'Approved')

                        <div class="alert alert-success mb-0">

                            <i class="bi bi-check-circle me-1"></i>

                            This document has been approved.

                        </div>


                    @elseif($document->approval_status === 'Rejected')

                        <div class="alert alert-danger">

                            <i class="bi bi-x-circle me-1"></i>

                            This document has been rejected.

                        </div>


                        <div class="d-grid">

                            <a
                                href="{{ route('admin.fitout.documents.create') }}"
                                class="btn btn-primary"
                            >

                                Upload New Version

                            </a>

                        </div>


                    @elseif($document->approval_status === 'Superseded')

                        <div class="alert alert-secondary mb-0">

                            This document version has been superseded.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ------------------------------------------------- --}}
            {{-- AUDIT INFORMATION --}}
            {{-- ------------------------------------------------- --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Audit Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="text-muted small">
                            Created At
                        </label>

                        <div>

                            {{ $document->created_at
                                ? $document->created_at->format('d M Y H:i')
                                : 'N/A' }}

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Last Updated
                        </label>

                        <div>

                            {{ $document->updated_at
                                ? $document->updated_at->format('d M Y H:i')
                                : 'N/A' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection