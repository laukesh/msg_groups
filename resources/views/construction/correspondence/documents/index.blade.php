@extends('layouts.app')

@section('title', 'Correspondence Documents')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1">
                Correspondence Documents
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

            <div class="small text-muted mt-1">
                {{ $correspondence->correspondence_number }}
                -
                {{ $correspondence->subject }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.correspondence.show',
                [$project, $correspondence]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Correspondence

            </a>


            <a href="{{ route(
                'admin.projects.construction.correspondence.documents.create',
                [$project, $correspondence]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>
                Upload Document

            </a>

        </div>

    </div>


    {{-- Flash Messages --}}
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


    {{-- Correspondence Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Correspondence Number
                    </div>

                    <div class="fw-semibold">
                        {{ $correspondence->correspondence_number }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Subject
                    </div>

                    <div class="fw-semibold">
                        {{ $correspondence->subject }}
                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Type
                    </div>

                    <span class="badge bg-primary-subtle text-primary">
                        {{ $correspondence->correspondence_type }}
                    </span>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="text-muted small">
                        Status
                    </div>

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

            </div>

        </div>

    </div>


    {{-- Documents --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-0">
                        <i class="bi bi-paperclip me-1"></i>
                        Attached Documents
                    </h6>

                    <small class="text-muted">
                        Letters, notices, drawings, reports and supporting evidence
                    </small>

                </div>


                <span class="badge bg-light text-dark">
                    {{ $documents->total() }} Documents
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($documents->count())

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
                                    Uploaded On
                                </th>

                                <th class="text-end pe-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($documents as $document)

                                <tr>

                                    {{-- Document --}}
                                    <td class="ps-3">

                                        <div class="fw-semibold">
                                            {{ $document->document_title }}
                                        </div>

                                        @if($document->description)

                                            <div class="small text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $document->description,
                                                    80
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        <span class="badge bg-light text-dark">

                                            {{ $document->document_type }}

                                        </span>

                                    </td>


                                    {{-- File --}}
                                    <td>

                                        @if($document->file_name)

                                            <div class="fw-semibold small">
                                                {{ $document->file_name }}
                                            </div>

                                        @endif

                                        @if($document->file_size)

                                            <div class="small text-muted">

                                                {{ number_format(
                                                    $document->file_size / 1024,
                                                    2
                                                ) }}
                                                KB

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Uploaded By --}}
                                    <td>

                                        {{ optional($document->uploadedBy)->name ?: '—' }}

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ optional(
                                            $document->created_at
                                        )->format('d M Y H:i') ?: '—' }}

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-3">

                                        <div class="btn-group">

                                            <a href="{{ route(
                                                'admin.projects.construction.correspondence.documents.view',
                                                [$project, $correspondence, $document]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            <a href="{{ route(
                                                'admin.projects.construction.correspondence.documents.download',
                                                [$project, $correspondence, $document]
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Download">

                                                <i class="fa fa-download"></i>

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.projects.construction.correspondence.documents.destroy',
                                                      [$project, $correspondence, $document]
                                                  ) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this document?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="card-footer bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">

                            Showing
                            {{ $documents->firstItem() ?? 0 }}
                            to
                            {{ $documents->lastItem() ?? 0 }}
                            of
                            {{ $documents->total() }}
                            documents

                        </div>

                        <div>

                            {{ $documents->withQueryString()->links() }}

                        </div>

                    </div>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>

                    <h6 class="mt-3">
                        No Documents Found
                    </h6>

                    <p class="text-muted">
                        No documents have been attached to this correspondence.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.correspondence.documents.create',
                        [$project, $correspondence]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-upload me-1"></i>
                        Upload First Document

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection