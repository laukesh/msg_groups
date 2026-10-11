@extends('layouts.app')

@section('title', 'Lease Document Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $document->document_name }}
            </h4>

            <div class="text-muted">

                {{ $document->agreement?->agreement_no ?? '-' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.documents.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>


            <a href="{{ route(
                'admin.leasing.documents.edit',
                $document->id
            ) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit

            </a>

        </div>

    </div>


    {{-- Success --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Main Information --}}

    <div class="row">

        <div class="col-lg-8">


            {{-- Document Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-file-alt me-1"></i>

                        Document Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document Type
                            </div>

                            <div class="fw-semibold">

                                {{ $document->documentType?->document_name ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document Name
                            </div>

                            <div class="fw-semibold">

                                {{ $document->document_name }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document Number
                            </div>

                            <div>

                                {{ $document->document_number ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Version
                            </div>

                            <div>

                                <span class="badge bg-secondary">

                                    v{{ $document->version_no ?? 1 }}

                                </span>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Issue Date
                            </div>

                            <div>

                                {{ $document->issue_date?->format('d M Y') ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Expiry Date
                            </div>

                            <div>

                                @if($document->expiry_date)

                                    @if($document->expiry_date->isPast())

                                        <span class="text-danger fw-semibold">

                                            {{ $document->expiry_date->format('d M Y') }}

                                            <small>
                                                (Expired)
                                            </small>

                                        </span>

                                    @else

                                        {{ $document->expiry_date->format('d M Y') }}

                                    @endif

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- File Information --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-paperclip me-1"></i>

                        File

                    </h5>

                </div>


                <div class="card-body">


                    @if($document->file_path)

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-semibold">

                                    {{ $document->file_name }}

                                </div>


                                <small class="text-muted">

                                    {{ $document->file_extension
                                        ? strtoupper(
                                            $document->file_extension
                                        )
                                        : 'File' }}

                                    @if($document->file_size)

                                        ·

                                        {{ number_format(
                                            $document->file_size / 1024,
                                            2
                                        ) }}

                                        KB

                                    @endif

                                </small>

                            </div>


                            <div>

                                <a href="{{ asset(
                                    'storage/' . $document->file_path
                                ) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary">

                                    <i class="fas fa-eye me-1"></i>

                                    View File

                                </a>

                                <a href="{{ asset(
                                    'storage/' . $document->file_path
                                ) }}"
                                   download
                                   class="btn btn-primary">

                                    <i class="fas fa-download me-1"></i>

                                    Download

                                </a>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-file-slash me-1"></i>

                            No file uploaded.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Remarks --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-comment-alt me-1"></i>

                        Remarks

                    </h5>

                </div>


                <div class="card-body">

                    {!! nl2br(e(
                        $document->remarks ?? '-'
                    )) !!}

                </div>

            </div>

        </div>


        {{-- Right Side --}}

        <div class="col-lg-4">


            {{-- Verification --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-check-circle me-1"></i>

                        Verification

                    </h5>

                </div>


                <div class="card-body text-center">


                    @if(
                        $document->verification_status === 'Verified'
                    )

                        <div class="mb-3">

                            <span class="badge bg-success fs-6 px-3 py-2">

                                <i class="fas fa-check me-1"></i>

                                Verified

                            </span>

                        </div>


                        @if($document->verifiedBy)

                            <div class="text-muted small">

                                Verified by

                            </div>

                            <div class="fw-semibold">

                                {{ $document->verifiedBy->name }}

                            </div>

                        @endif


                        @if($document->verified_at)

                            <div class="text-muted small mt-2">

                                {{ $document->verified_at->format(
                                    'd M Y H:i'
                                ) }}

                            </div>

                        @endif


                    @elseif(
                        $document->verification_status === 'Rejected'
                    )

                        <div class="mb-3">

                            <span class="badge bg-danger fs-6 px-3 py-2">

                                <i class="fas fa-times me-1"></i>

                                Rejected

                            </span>

                        </div>


                        <div class="text-muted small">

                            Rejection Remarks

                        </div>

                        <div class="mt-1">

                            {{ $document->remarks ?? '-' }}

                        </div>


                    @else

                        <div class="mb-3">

                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">

                                <i class="fas fa-clock me-1"></i>

                                Pending Verification

                            </span>

                        </div>


                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.documents.verify',
                                  $document->id
                              ) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success w-100"
                                    onclick="return confirm(
                                        'Are you sure you want to verify this document?'
                                    );">

                                <i class="fas fa-check-circle me-1"></i>

                                Verify Document

                            </button>

                        </form>


                        <button type="button"
                                class="btn btn-outline-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectDocumentModal">

                            <i class="fas fa-times-circle me-1"></i>

                            Reject Document

                        </button>

                    @endif

                </div>

            </div>


            {{-- Agreement --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-file-contract me-1"></i>

                        Agreement

                    </h5>

                </div>


                <div class="card-body">


                    <div class="text-muted small">
                        Agreement No.
                    </div>

                    <div class="fw-semibold mb-3">

                        @if($document->agreement)

                            <a href="{{ route(
                                'admin.leasing.agreements.show',
                                $document->agreement->id
                            ) }}">

                                {{ $document->agreement->agreement_no }}

                            </a>

                        @else

                            -

                        @endif

                    </div>


                    <div class="text-muted small">
                        Tenant
                    </div>

                    <div class="fw-semibold">

                        {{ $document->agreement?->tenant?->company_name ?? '-' }}

                    </div>

                </div>

            </div>


            {{-- Audit --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="fas fa-history me-1"></i>

                        Audit

                    </h5>

                </div>


                <div class="card-body">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div class="mb-3">

                        {{ $document->createdBy?->name ?? '-' }}

                    </div>


                    <div class="text-muted small">
                        Created At
                    </div>

                    <div class="mb-3">

                        {{ $document->created_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}

                    </div>


                    <div class="text-muted small">
                        Updated By
                    </div>

                    <div>

                        {{ $document->updatedBy?->name ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Reject Modal --}}

<div class="modal fade"
     id="rejectDocumentModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">


            <form method="POST"
                  action="{{ route(
                      'admin.leasing.documents.reject',
                      $document->id
                  ) }}">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">

                        Reject Document

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <label class="form-label">

                        Rejection Reason
                        <span class="text-danger">*</span>

                    </label>


                    <textarea name="remarks"
                              class="form-control"
                              rows="4"
                              required
                              placeholder="Enter reason for rejection..."></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fas fa-times-circle me-1"></i>

                        Reject Document

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection