@extends('layouts.app')

@section('title', 'Handover Documents')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handover Documents
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? $project->name ?? '' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.handover.index',
                $project
            ) }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line"></i>
                Handover

            </a>

            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addDocumentModal">

                <i class="ri-upload-2-line"></i>
                Upload Document

            </button>

        </div>

    </div>


    {{-- ALERTS --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- KPI --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <h3 class="mb-0">
                        {{ $totalDocuments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <h3 class="mb-0">
                        {{ $draftDocuments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <h3 class="mb-0">
                        {{ $submittedDocuments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <h3 class="mb-0">
                        {{ $reviewDocuments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ $approvedDocuments }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-2 col-md-4 col-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ $rejectedDocuments }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTERS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-lg-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Document no, title, file name...">

                    </div>


                    <div class="col-lg-3">

                        <label class="form-label">
                            Document Type
                        </label>

                        <select
                            name="document_type"
                            class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach($documentTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('document_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach($statuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex align-items-end">

                        <button
                            class="btn btn-primary w-100">

                            <i class="ri-search-line"></i>
                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- DOCUMENT TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between">

                <div>

                    <h5 class="mb-0">
                        Handover Documentation
                    </h5>

                    <small class="text-muted">
                        {{ $handover->handover_no }}
                    </small>

                </div>

                <span class="badge bg-light text-dark">
                    {{ $documents->total() }} Records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Document No.</th>

                        <th>Document</th>

                        <th>Requirement</th>

                        <th>Version</th>

                        <th>Uploaded By</th>

                        <th>Status</th>

                        <th width="180">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($documents as $document)

                    <tr>

                        <td>

                            <div class="fw-semibold">
                                {{ $document->document_no }}
                            </div>

                            <small class="text-muted">
                                {{ $document->document_type }}
                            </small>

                        </td>


                        <td>

                            <div class="fw-semibold">
                                {{ $document->title }}
                            </div>

                            @if($document->file_name)

                                <div class="small text-muted">

                                    <i class="ri-file-line"></i>

                                    {{ $document->file_name }}

                                </div>

                            @endif

                        </td>


                        <td>

                            @if($document->requirement)

                                <div class="fw-semibold">

                                    {{ $document->requirement->requirement_code }}

                                </div>

                                <small class="text-muted">

                                    {{ $document->requirement->title }}

                                </small>

                            @else

                                <span class="text-muted">
                                    Not linked
                                </span>

                            @endif

                        </td>


                        <td>

                            {{ $document->document_version ?? '1.0' }}

                        </td>


                        <td>

                            {{ $document->uploadedBy?->name ?? '-' }}

                            @if($document->uploaded_at)

                                <small class="text-muted d-block">

                                    {{ $document->uploaded_at->format(
                                        'd M Y H:i'
                                    ) }}

                                </small>

                            @endif

                        </td>


                        <td>

                            @php

                                $statusClass = match($document->status) {

                                    'Draft' =>
                                        'bg-secondary',

                                    'Submitted' =>
                                        'bg-primary',

                                    'Under Review' =>
                                        'bg-warning text-dark',

                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Archived' =>
                                        'bg-dark',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span class="badge {{ $statusClass }}">

                                {{ $document->status }}

                            </span>

                        </td>


                        <td>

                            <div class="dropdown">

                                <button
                                    class="btn btn-sm btn-light"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-2-fill"></i>

                                </button>


                                <ul class="dropdown-menu dropdown-menu-end">

                                    @if($document->file_path)

                                        <li>

                                            <a
                                                href="{{ asset(
                                                    'storage/' .
                                                    $document->file_path
                                                ) }}"
                                                target="_blank"
                                                class="dropdown-item">

                                                <i class="ri-eye-line me-2"></i>
                                                View Document

                                            </a>

                                        </li>

                                    @endif


                                    @if(in_array($document->status, [
                                        'Draft',
                                        'Rejected'
                                    ]))

                                        <li>

                                            <button
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDocument{{ $document->id }}">

                                                <i class="ri-edit-line me-2"></i>
                                                Edit

                                            </button>

                                        </li>

                                    @endif


                                    @if($document->status === 'Draft')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.documents.submit',
                                                    [$project, $document]
                                                ) }}">

                                                @csrf

                                                <button
                                                    class="dropdown-item">

                                                    <i class="ri-send-plane-line me-2"></i>
                                                    Submit for Review

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    @if($document->status === 'Submitted')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.documents.review',
                                                    [$project, $document]
                                                ) }}">

                                                @csrf

                                                <button
                                                    class="dropdown-item">

                                                    <i class="ri-search-eye-line me-2"></i>
                                                    Start Review

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    @if($document->status === 'Under Review')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.documents.approve',
                                                    [$project, $document]
                                                ) }}">

                                                @csrf

                                                <button
                                                    class="dropdown-item text-success"
                                                    onclick="return confirm('Approve this document?')">

                                                    <i class="ri-checkbox-circle-line me-2"></i>
                                                    Approve

                                                </button>

                                            </form>

                                        </li>


                                        <li>

                                            <button
                                                class="dropdown-item text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectDocument{{ $document->id }}">

                                                <i class="ri-close-circle-line me-2"></i>
                                                Reject

                                            </button>

                                        </li>

                                    @endif


                                    @if($document->status === 'Approved')

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.documents.archive',
                                                    [$project, $document]
                                                ) }}">

                                                @csrf

                                                <button
                                                    class="dropdown-item">

                                                    <i class="ri-archive-line me-2"></i>
                                                    Archive

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    @if(in_array($document->status, [
                                        'Draft',
                                        'Rejected'
                                    ]))

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.handover.documents.destroy',
                                                    [$project, $document]
                                                ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete this document?')">

                                                    <i class="ri-delete-bin-line me-2"></i>
                                                    Delete

                                                </button>

                                            </form>

                                        </li>

                                    @endif

                                </ul>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5">

                            <i class="ri-folder-open-line fs-1 text-muted"></i>

                            <h6 class="mt-3">
                                No handover documents found
                            </h6>

                            <p class="text-muted mb-0">
                                Upload the required handover documentation.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($documents->hasPages())

            <div class="card-footer bg-white">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ================================================================
     ADD DOCUMENT
================================================================ --}}

<div
    class="modal fade"
    id="addDocumentModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route(
                    'admin.projects.handover.documents.store',
                    $project
                ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Upload Handover Document
                    </h5>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    @include(
                        'admin.handover.documents.partials.form',
                        [
                            'document' => null
                        ]
                    )

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary">

                        <i class="ri-upload-2-line"></i>
                        Upload

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
     EDIT / REJECT MODALS
================================================================ --}}

@foreach($documents as $document)

    @if(in_array($document->status, [
        'Draft',
        'Rejected'
    ]))

        <div
            class="modal fade"
            id="editDocument{{ $document->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route(
                            'admin.projects.handover.documents.update',
                            [$project, $document]
                        ) }}">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Edit Document -
                                {{ $document->document_no }}
                            </h5>

                            <button
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>


                        <div class="modal-body">

                            @include(
                                'admin.handover.documents.partials.form',
                                [
                                    'document' => $document
                                ]
                            )

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                class="btn btn-primary">

                                Update Document

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    @if($document->status === 'Under Review')

        <div
            class="modal fade"
            id="rejectDocument{{ $document->id }}"
            tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.handover.documents.reject',
                            [$project, $document]
                        ) }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title text-danger">
                                Reject Document
                            </h5>

                            <button
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>


                        <div class="modal-body">

                            <label class="form-label">
                                Rejection Reason
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="rejection_reason"
                                class="form-control"
                                rows="5"
                                required></textarea>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button
                                class="btn btn-danger">

                                Reject Document

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

@endforeach

@endsection