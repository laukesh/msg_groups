@extends('layouts.app')

@section('title', 'Review Document')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Review Document
            </h4>

            <p class="text-muted mb-0">
                Review and approve or reject the submitted document.
            </p>

        </div>

        <a
            href="{{ route('admin.fitout.documents.show', $document->id) }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Validation --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">


        {{-- ================================================= --}}
        {{-- DOCUMENT --}}
        {{-- ================================================= --}}

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Document
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Title
                            </label>

                            <div class="fw-semibold">
                                {{ $document->document_title }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Type
                            </label>

                            <div>
                                {{ $document->documentType->document_name ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="text-muted small">
                                Document Number
                            </label>

                            <div>
                                {{ $document->document_number ?: 'N/A' }}
                            </div>

                        </div>


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


                        <div class="col-md-12 mb-3">

                            <label class="text-muted small">
                                File
                            </label>

                            <div class="border rounded p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <i class="bi bi-file-earmark me-2"></i>

                                        {{ $document->file_name }}

                                    </div>

                                    <div>

                                        <a
                                            href="{{ asset('storage/' . $document->file_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="bi bi-eye"></i>
                                            View
                                        </a>

                                        <a
                                            href="{{ asset('storage/' . $document->file_path) }}"
                                            download
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="bi bi-download"></i>
                                            Download
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Submitted By
                            </label>

                            <div>
                                {{ $document->submittedBy->name ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-6">

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

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FIT-OUT --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Fit-Out Request
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <label class="text-muted small">
                                Request No.
                            </label>

                            <div class="fw-semibold">
                                {{ $document->fitoutRequest->request_no ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Tenant
                            </label>

                            <div>
                                {{ $document->fitoutRequest->tenant->company_name
                                    ?? $document->fitoutRequest->tenant->company_name
                                    ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Unit
                            </label>

                            <div>
                                {{ $document->fitoutRequest->unit->unit_no ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-12 mt-3">

                            <label class="text-muted small">
                                Contractor
                            </label>

                            <div>
                                {{ $document->fitoutRequest->contractor->contractor_name ?? 'N/A' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- REVIEW PANEL --}}
        {{-- ================================================= --}}

        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Review Decision
                    </h5>

                </div>


                <div class="card-body">


                    {{-- Current Status --}}
                    <div class="mb-4">

                        <label class="text-muted small">
                            Current Status
                        </label>

                        <div class="mt-1">

                            <span class="badge bg-info text-dark">

                                {{ $document->approval_status }}

                            </span>

                        </div>

                    </div>


                    {{-- Start Review --}}
                    @if($document->approval_status === 'Pending')

                        <form
                            action="{{ route('admin.fitout.documents.start-review', $document->id) }}"
                            method="POST"
                            class="mb-3"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-warning w-100"
                            >

                                <i class="bi bi-search"></i>

                                Start Review

                            </button>

                        </form>

                    @endif


                    {{-- Approve --}}
                    @if(in_array(
                        $document->approval_status,
                        ['Pending', 'Under Review']
                    ))

                        <form
                            action="{{ route('admin.fitout.documents.approve', $document->id) }}"
                            method="POST"
                            class="mb-3"
                            onsubmit="return confirm('Are you sure you want to approve this document?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >

                                <i class="bi bi-check-circle"></i>

                                Approve Document

                            </button>

                        </form>


                        {{-- Reject --}}
                        <form
                            action="{{ route('admin.fitout.documents.reject', $document->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to reject this document?');"
                        >

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">

                                    Rejection Reason

                                    <span class="text-danger">*</span>

                                </label>

                                <textarea
                                    name="rejection_reason"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter reason for rejection..."
                                    required
                                >{{ old('rejection_reason') }}</textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-danger w-100"
                            >

                                <i class="bi bi-x-circle"></i>

                                Reject Document

                            </button>

                        </form>

                    @elseif($document->approval_status === 'Approved')

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle me-1"></i>

                            This document has already been approved.

                        </div>


                    @elseif($document->approval_status === 'Rejected')

                        <div class="alert alert-danger">

                            <i class="bi bi-x-circle me-1"></i>

                            This document has already been rejected.

                            @if($document->rejection_reason)

                                <hr>

                                <strong>
                                    Reason:
                                </strong>

                                <div class="mt-1">
                                    {{ $document->rejection_reason }}
                                </div>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection