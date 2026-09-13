@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Meeting:
                <strong>
                    {{ $meeting->meeting_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Document Details
            </h3>

            <div class="text-muted">
                {{ $document->document_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.documents.download',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                        'document' => $document,
                    ]
                ) }}"
                class="btn btn-success"
            >
                <i class="bi bi-download me-1"></i>
                Download
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.documents.index',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Documents
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.show',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Meeting
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
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
                        {{ $document->document_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Document Name
                    </div>

                    <strong>
                        {{ $document->document_name }}
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


                <div class="col-md-4">

                    <div class="text-muted small">
                        Original File Name
                    </div>

                    <strong>
                        {{ $document->original_file_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        File Size
                    </div>

                    <strong>
                        {{ $document->file_size_formatted }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        MIME Type
                    </div>

                    <strong>
                        {{ $document->mime_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Uploaded By
                    </div>

                    <strong>
                        {{ $document->uploadedBy?->name ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}

    @if($document->description)

        <div class="card mb-4">

            <div class="card-header">
                <strong>Description</strong>
            </div>

            <div
                class="card-body"
                style="white-space:pre-line;"
            >
                {{ $document->description }}
            </div>

        </div>

    @endif


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


    {{-- File --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>File</strong>
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <i class="bi bi-file-earmark-text me-2"></i>

                    {{ $document->original_file_name
                        ?? $document->document_name
                    }}

                </div>


                <a
                    href="{{ route(
                        'admin.projects.construction.hse.safety-meetings.documents.download',
                        [
                            'project' => $project,
                            'meeting' => $meeting,
                            'document' => $document,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-download me-1"></i>
                    Download File
                </a>

            </div>

        </div>

    </div>


    {{-- Delete --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.safety-meetings.documents.destroy',
                [
                    'project' => $project,
                    'meeting' => $meeting,
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