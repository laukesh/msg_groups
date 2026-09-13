@extends('layouts.app')

@section('title', 'Fit-Out Documents')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Documents
            </h4>

            <p class="text-muted mb-0">
                Manage documents submitted for fit-out requests.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.fitout.documents.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-upload"></i>
                Upload Document
            </a>
        </div>

    </div>


    {{-- Success Message --}}
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


    {{-- Error Message --}}
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


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Documents Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    All Documents
                </h5>

                <span class="text-muted">
                    Total:
                    {{ $documents->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Request No.
                            </th>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Version
                            </th>

                            <th>
                                Submitted By
                            </th>

                            <th>
                                Submitted At
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($documents as $document)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    {{ $documents->firstItem() + $loop->index }}

                                </td>


                                {{-- Request --}}
                                <td>

                                    @if($document->fitoutRequest)

                                        <a
                                            href="#"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $document->fitoutRequest->request_no }}
                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Document --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $document->document_title }}

                                    </div>

                                    @if($document->document_number)

                                        <small class="text-muted">

                                            No:
                                            {{ $document->document_number }}

                                        </small>

                                    @endif

                                    @if($document->file_extension)

                                        <div>

                                            <span class="badge bg-light text-dark">

                                                {{ strtoupper($document->file_extension) }}

                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- Document Type --}}
                                <td>

                                    {{ $document->documentType->document_name ?? 'N/A' }}

                                </td>


                                {{-- Version --}}
                                <td>

                                    <span class="badge bg-secondary">

                                        v{{ $document->version_no }}

                                    </span>

                                </td>


                                {{-- Submitted By --}}
                                <td>

                                    {{ $document->submittedBy->name ?? 'N/A' }}

                                </td>


                                {{-- Submitted At --}}
                                <td>

                                    @if($document->submitted_at)

                                        {{ \Carbon\Carbon::parse($document->submitted_at)->format('d M Y H:i') }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match($document->approval_status) {

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

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">
                                    <style type="text/css">
.fitout-documents-action-dropdown {
    position: static !important;
}

        </style>

                                    <div class="dropdown fitout-documents-action-dropdown">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                            Actions

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.fitout.documents.show', $document->id) }}"
                                                >

                                                    <i class="bi bi-eye me-2"></i>

                                                    View

                                                </a>

                                            </li>


                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ asset('storage/' . $document->file_path) }}"
                                                    target="_blank"
                                                >

                                                    <i class="bi bi-file-earmark me-2"></i>

                                                    View File

                                                </a>

                                            </li>


                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ asset('storage/' . $document->file_path) }}"
                                                    download
                                                >

                                                    <i class="bi bi-download me-2"></i>

                                                    Download

                                                </a>

                                            </li>


                                            @if($document->approval_status === 'Pending')

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.fitout.documents.review', $document->id) }}"
                                                    >
                                                        <i class="bi bi-check-circle me-2"></i>
                                                        Review
                                                    </a>

                                                </li>

                                            @endif

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="bi bi-folder2-open"
                                            style="font-size: 40px;"
                                        ></i>

                                    </div>

                                    <h6>
                                        No Documents Found
                                    </h6>

                                    <p class="text-muted mb-3">
                                        No fit-out documents have been uploaded yet.
                                    </p>

                                    <a
                                        href="{{ route('admin.fitout.documents.create') }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        Upload First Document
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($documents->hasPages())

            <div class="card-footer">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection