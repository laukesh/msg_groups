@extends('layouts.app')

@section('title', 'Risk Documents')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1">
                Risk Documents
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                -
                {{ $risk->risk_title }}
            </div>

            <div class="text-muted small">
                {{ $project->project_code ?? $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.show',
                [$project, $risk]
            ) }}"
            class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Risk

            </a>


            <a href="{{ route(
                'admin.projects.construction.risks.documents.create',
                [$project, $risk]
            ) }}"
            class="btn btn-primary">

                <i class="bi bi-upload"></i>
                Upload Document

            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Errors --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Risk Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Number
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->risk_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Category
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->risk_category }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Rating
                    </div>

                    @php

                        $ratingClass = match($risk->risk_rating) {
                            'Critical' => 'danger',
                            'High' => 'warning',
                            'Medium' => 'info',
                            default => 'success',
                        };

                    @endphp

                    <span class="badge bg-{{ $ratingClass }}">
                        {{ $risk->risk_rating }}
                    </span>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Risk Status
                    </div>

                    <div class="fw-semibold">
                        {{ $risk->status }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Documents --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Supporting Documents
                </strong>

                <span class="text-muted small">
                    {{ $documents->total() }} documents
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="30%">
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
                            Uploaded At
                        </th>

                        <th width="180">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($documents as $document)

                        @php

                            $extension = strtolower(
                                pathinfo(
                                    $document->file_name,
                                    PATHINFO_EXTENSION
                                )
                            );

                            $icon = match($extension) {
                                'pdf' => 'bi-file-earmark-pdf',
                                'doc', 'docx' => 'bi-file-earmark-word',
                                'xls', 'xlsx' => 'bi-file-earmark-excel',
                                'jpg', 'jpeg', 'png', 'webp' => 'bi-file-earmark-image',
                                'zip', 'rar' => 'bi-file-earmark-zip',
                                default => 'bi-file-earmark',
                            };

                        @endphp


                        <tr>

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="me-3 fs-4 text-muted">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $document->document_title }}
                                        </div>

                                        @if($document->description)

                                            <div class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit(
                                                    $document->description,
                                                    100
                                                ) }}
                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="badge bg-light text-dark border">
                                    {{ $document->document_type }}
                                </span>

                            </td>


                            <td>

                                <div>
                                    {{ $document->file_name }}
                                </div>

                                @if($document->file_size)

                                    <div class="text-muted small">
                                        {{ number_format(
                                            $document->file_size / 1024,
                                            1
                                        ) }}
                                        KB
                                    </div>

                                @endif

                            </td>


                            <td>

                                {{ $document->uploadedBy->name ?? '—' }}

                            </td>


                            <td>

                                @if($document->created_at)

                                    {{ $document->created_at->format(
                                        'd-m-Y H:i'
                                    ) }}

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route(
                                        'admin.projects.construction.risks.documents.view',
                                        [$project, $risk, $document]
                                    ) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="View">

                                        <i class="fa fa-eye"></i>

                                    </a>


                                    <a href="{{ route(
                                        'admin.projects.construction.risks.documents.download',
                                        [$project, $risk, $document]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Download">

                                        <i class="fa fa-download"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route(
                                              'admin.projects.construction.risks.documents.destroy',
                                              [$project, $risk, $document]
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

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <div class="text-muted mb-3">

                                    <i class="bi bi-file-earmark-text fs-1"></i>

                                </div>

                                <div class="text-muted mb-3">
                                    No documents uploaded for this risk.
                                </div>

                                <a href="{{ route(
                                    'admin.projects.construction.risks.documents.create',
                                    [$project, $risk]
                                ) }}"
                                class="btn btn-primary btn-sm">

                                    <i class="bi bi-upload"></i>
                                    Upload First Document

                                </a>

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

@endsection