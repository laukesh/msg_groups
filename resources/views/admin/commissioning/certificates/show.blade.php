@extends('layouts.app')

@section('title', 'Certificate - '.$certificate->certificate_no)

@section('content')

<div class="container-fluid">

    @php

        $statusClass = match($certificate->status) {
            'Approved' => 'success',
            'Submitted' => 'warning',
            'Rejected' => 'danger',
            'Expired' => 'dark',
            default => 'secondary',
        };

    @endphp


    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0">
                    {{ $certificate->certificate_no }}
                </h4>

                <span class="badge bg-{{ $statusClass }}">
                    {{ $certificate->status }}
                </span>

            </div>

            <div class="text-muted mt-1">

                {{ $certificate->certificate_type }}

                @if($certificate->scope)
                    · {{ $certificate->scope->scope_name }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.commissioning.certificates.index', $project) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            @if(in_array($certificate->status, ['Draft', 'Rejected']))

                <a
                    href="{{ route('admin.projects.commissioning.certificates.edit', [$project, $certificate]) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>

            @endif

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- WORKFLOW ACTIONS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-diagram-3 me-1"></i>
                Certificate Workflow
            </h6>

        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                @if($certificate->status === 'Draft')

                    <form
                        method="POST"
                        action="{{ route('admin.projects.commissioning.certificates.submit', [$project, $certificate]) }}"
                    >

                        @csrf

                        <button
                            class="btn btn-warning"
                            onclick="return confirm('Submit this certificate for approval?')"
                        >
                            <i class="bi bi-send me-1"></i>
                            Submit for Approval
                        </button>

                    </form>

                @endif


                @if($certificate->status === 'Submitted')

                    <form
                        method="POST"
                        action="{{ route('admin.projects.commissioning.certificates.approve', [$project, $certificate]) }}"
                    >

                        @csrf

                        <button
                            class="btn btn-success"
                            onclick="return confirm('Approve this certificate?')"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            Approve
                        </button>

                    </form>


                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal"
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Reject
                    </button>

                @endif


                @if(
                    $certificate->status === 'Approved'
                    && $certificate->valid_until
                    && $certificate->valid_until->isPast()
                )

                    <form
                        method="POST"
                        action="{{ route('admin.projects.commissioning.certificates.expire', [$project, $certificate]) }}"
                    >

                        @csrf

                        <button
                            class="btn btn-dark"
                            onclick="return confirm('Mark this certificate as expired?')"
                        >
                            <i class="bi bi-calendar-x me-1"></i>
                            Mark Expired
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- LEFT --}}

        <div class="col-lg-8">


            {{-- DETAILS --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-1"></i>
                        Certificate Details
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Certificate Number
                            </div>

                            <div class="fw-semibold">
                                {{ $certificate->certificate_no }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Certificate Type
                            </div>

                            <div>
                                {{ $certificate->certificate_type }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Commissioning Scope
                            </div>

                            <div>

                                @if($certificate->scope)

                                    <a
                                        href="{{ route('admin.projects.commissioning.scopes.show', [$project, $certificate->scope]) }}"
                                        class="fw-semibold"
                                    >
                                        {{ $certificate->scope->scope_code }}
                                    </a>

                                    <div class="small text-muted">
                                        {{ $certificate->scope->scope_name }}
                                    </div>

                                @else

                                    Project Level

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Issue Date
                            </div>

                            <div>
                                {{ $certificate->issue_date?->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Valid Until
                            </div>

                            <div>

                                {{ $certificate->valid_until?->format('d M Y') ?? 'No Expiry' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Issued By
                            </div>

                            <div>
                                {{ $certificate->issuedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div class="border rounded p-3">

                                {!! nl2br(e($certificate->description ?: '—')) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DOCUMENT --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Certificate Document
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document Reference
                            </div>

                            <div class="fw-semibold">
                                {{ $certificate->document_reference ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Document
                            </div>

                            @if($certificate->document_path)

                                <a
                                    href="{{ asset('storage/'.$certificate->document_path) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    Open Document
                                </a>

                            @else

                                <span class="text-muted">
                                    No document uploaded.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- REMARKS --}}

            @if($certificate->remarks)

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h6 class="mb-0 fw-semibold">
                            Remarks
                        </h6>

                    </div>

                    <div class="card-body">

                        {!! nl2br(e($certificate->remarks)) !!}

                    </div>

                </div>

            @endif

        </div>


        {{-- RIGHT --}}

        <div class="col-lg-4">


            {{-- APPROVAL --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-check2-square me-1"></i>
                        Approval Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Submitted At
                        </div>

                        <div>
                            {{ $certificate->submitted_at?->format('d M Y h:i A') ?? '—' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Approved By
                        </div>

                        <div class="fw-semibold">
                            {{ $certificate->approvedBy?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Approved At
                        </div>

                        <div>
                            {{ $certificate->approved_at?->format('d M Y h:i A') ?? '—' }}
                        </div>

                    </div>


                    @if($certificate->rejection_reason)

                        <div class="alert alert-danger mb-0">

                            <div class="fw-semibold">
                                Rejection Reason
                            </div>

                            <div class="small mt-1">
                                {{ $certificate->rejection_reason }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- RECORD --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0 fw-semibold">
                        Record Information
                    </h6>

                </div>

                <div class="card-body small">

                    <div class="mb-3">

                        <div class="text-muted">
                            Created By
                        </div>

                        <div class="fw-semibold">
                            {{ $certificate->createdBy?->name ?? 'System' }}
                        </div>

                        <div class="text-muted">
                            {{ $certificate->created_at?->format('d M Y h:i A') }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted">
                            Last Updated
                        </div>

                        <div class="fw-semibold">
                            {{ $certificate->updatedBy?->name ?? 'System' }}
                        </div>

                        <div class="text-muted">
                            {{ $certificate->updated_at?->format('d M Y h:i A') }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- DELETE --}}

            @if(in_array($certificate->status, ['Draft', 'Rejected']))

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <form
                            method="POST"
                            action="{{ route('admin.projects.commissioning.certificates.destroy', [$project, $certificate]) }}"
                            onsubmit="return confirm('Delete this certificate?')"
                        >

                            @csrf

                            @method('DELETE')

                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i>
                                Delete Certificate
                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- REJECT MODAL --}}

<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <form
            method="POST"
            action="{{ route('admin.projects.commissioning.certificates.reject', [$project, $certificate]) }}"
            class="modal-content"
        >

            @csrf

            <div class="modal-header">

                <h5 class="modal-title">
                    Reject Certificate
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
                    <span class="text-danger">*</span>
                </label>

                <textarea
                    name="rejection_reason"
                    rows="5"
                    class="form-control"
                    required
                ></textarea>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Reject Certificate
                </button>

            </div>

        </form>

    </div>

</div>

@endsection