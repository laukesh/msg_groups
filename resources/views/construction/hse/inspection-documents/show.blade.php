@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Inspection:
                <strong>
                    {{ $inspection->inspection_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                {{ $document->document_number }}
            </h3>

            <div class="text-muted">
                {{ $document->document_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if($document->file_path)

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.inspections.documents.download',
                        [
                            'project' => $project,
                            'inspection' => $inspection,
                            'document' => $document,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-download me-1"></i>
                    Download
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.documents.index',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Documents
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Inspection
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Document Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Document Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Document Number
                    </div>

                    <strong>
                        {{ $document->document_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Document Type
                    </div>

                    <strong>
                        {{ $document->document_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Document Date
                    </div>

                    <strong>

                        {{ $document->document_date
                            ? $document->document_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small">
                        Document Title
                    </div>

                    <strong>
                        {{ $document->document_title }}
                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Description
                    </div>

                    <div style="white-space:pre-line;">
                        {{ $document->description ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- File Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>File Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        File Name
                    </div>

                    <strong>
                        {{ $document->file_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        File Type
                    </div>

                    <strong>
                        {{ $document->file_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        File Size
                    </div>

                    <strong>
                        {{ $document->formattedFileSize() }}
                    </strong>

                </div>


                <div class="col-12">

                    @if($document->file_path)

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.inspections.documents.download',
                                [
                                    'project' => $project,
                                    'inspection' => $inspection,
                                    'document' => $document,
                                ]
                            ) }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-download me-1"></i>
                            Download File
                        </a>

                    @else

                        <span class="text-muted">
                            No file attached.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}

    @if($document->remarks)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $document->remarks }}
            </div>

        </div>

    @endif


    {{-- Record Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Record Information</strong>
        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Uploaded By
                    </div>

                    <strong>
                        {{ $document->uploadedBy?->name
                            ?? $document->creator?->name
                            ?? '—'
                        }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $document->created_at
                            ? $document->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $document->updated_at
                            ? $document->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.inspections.documents.destroy',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'document' => $document,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this document?'
            );"
        >

            @csrf

            @method('DELETE')


            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete Document
            </button>

        </form>

    </div>

</div>

@endsection