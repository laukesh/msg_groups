@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Documents
            </div>

            <h3 class="mb-1">
                Meeting Documents
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                @if($meeting->meeting_title)
                    · {{ $meeting->meeting_title }}
                @endif

                · {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.show',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Meeting
            </a>


            <a
                href="{{ route(
                    'admin.projects.governance-meetings.documents.create',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + Upload Document
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MEETING INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Meeting Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Number
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->meeting_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Date
                    </div>

                    <div class="fw-semibold">

                        @if($meeting->meeting_date)

                            {{ $meeting->meeting_date->format('d-m-Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meeting Type
                    </div>

                    <div class="fw-semibold">
                        {{ $meeting->meeting_type ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div>

                        @php

                            $statusClass = match(
                                $meeting->status
                            ) {

                                'Scheduled'
                                    => 'bg-primary',

                                'Held'
                                    => 'bg-success',

                                'Cancelled'
                                    => 'bg-danger',

                                'Postponed'
                                    => 'bg-warning text-dark',

                                default
                                    => 'bg-secondary',

                            };

                        @endphp


                        <span class="badge {{ $statusClass }}">
                            {{ $meeting->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DOCUMENT SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Documents
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $documents->count() }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Documents
                    </div>

                    <div class="fs-3 fw-semibold text-success">

                        {{
                            $documents
                                ->where('status', 'Active')
                                ->count()
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Archived Documents
                    </div>

                    <div class="fs-3 fw-semibold text-secondary">

                        {{
                            $documents
                                ->where('status', 'Archived')
                                ->count()
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DOCUMENTS --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Documents
                    </strong>

                    <div class="text-muted small mt-1">
                        Files attached to this governance meeting.
                    </div>

                </div>


                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.documents.create',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    + Upload Document
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if($documents->count())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th style="width:60px;">
                                    #
                                </th>

                                <th>
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

                                <th>
                                    Status
                                </th>

                                <th
                                    class="text-end"
                                    style="width:180px;"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($documents as $document)

                                @php

                                    $documentStatusClass =
                                        $document->status === 'Active'
                                            ? 'bg-success'
                                            : 'bg-secondary';


                                    $fileSize = null;

                                    if ($document->file_size) {

                                        $bytes =
                                            (int) $document->file_size;

                                        if ($bytes >= 1073741824) {

                                            $fileSize =
                                                number_format(
                                                    $bytes / 1073741824,
                                                    2
                                                ) . ' GB';

                                        } elseif ($bytes >= 1048576) {

                                            $fileSize =
                                                number_format(
                                                    $bytes / 1048576,
                                                    2
                                                ) . ' MB';

                                        } elseif ($bytes >= 1024) {

                                            $fileSize =
                                                number_format(
                                                    $bytes / 1024,
                                                    2
                                                ) . ' KB';

                                        } else {

                                            $fileSize =
                                                $bytes . ' B';

                                        }

                                    }

                                @endphp


                                <tr>

                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Document Name --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $document->document_name }}

                                        </div>


                                        @if($document->description)

                                            <div
                                                class="text-muted small mt-1"
                                            >

                                                {{ $document->description }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        @if($document->document_type)

                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                {{ $document->document_type }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- File --}}

                                    <td>

                                        <div>

                                            {{ $document->original_file_name
                                                ?: basename(
                                                    $document->file_path
                                                )
                                            }}

                                        </div>


                                        @if($fileSize)

                                            <div class="text-muted small">

                                                {{ $fileSize }}

                                                @if($document->mime_type)

                                                    ·
                                                    {{ $document->mime_type }}

                                                @endif

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Uploaded By --}}

                                    <td>

                                        {{ $document->uploader->name ?? '—' }}

                                    </td>


                                    {{-- Uploaded At --}}

                                    <td>

                                        @if($document->uploaded_at)

                                            {{ $document->uploaded_at
                                                ->format('d-m-Y H:i')
                                            }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge
                                                {{ $documentStatusClass }}"
                                        >
                                            {{ $document->status }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td>

                                        <div
                                            class="d-flex
                                                   justify-content-end
                                                   gap-2"
                                        >

                                            <a
                                                href="{{ route(
                                                    'admin.projects.governance-meetings.documents.preview',
                                                    [
                                                        'project' => $project->id,
                                                        'meeting' => $meeting->id,
                                                        'document' => $document->id,
                                                    ]
                                                ) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                View
                                            </a>

                                            {{-- Download --}}

                                            @if(
                                                $document->status === 'Active'
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.governance-meetings.documents.download',
                                                        [
                                                            'project' =>
                                                                $project->id,
                                                            'meeting' =>
                                                                $meeting->id,
                                                            'document' =>
                                                                $document->id,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm
                                                           btn-outline-primary"
                                                    title="Download"
                                                >
                                                    Download
                                                </a>

                                            @endif


                                            {{-- Delete --}}

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.governance-meetings.documents.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,
                                                        'meeting' =>
                                                            $meeting->id,
                                                        'document' =>
                                                            $document->id,
                                                    ]
                                                ) }}"
                                                onsubmit="
                                                    return confirm(
                                                        'Are you sure you want to delete this document?'
                                                    );
                                                "
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm
                                                           btn-outline-danger"
                                                    title="Delete"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <span
                            class="d-inline-flex
                                   align-items-center
                                   justify-content-center
                                   rounded-circle
                                   border"
                            style="
                                width:64px;
                                height:64px;
                                font-size:28px;
                            "
                        >
                            📎
                        </span>

                    </div>


                    <h5>
                        No Documents
                    </h5>


                    <p class="text-muted mb-4">

                        No documents have been uploaded
                        for this governance meeting yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.governance-meetings.documents.create',
                            [
                                'project' => $project->id,
                                'meeting' => $meeting->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Upload First Document
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BACK --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-end mt-4 mb-5">

        <a
            href="{{ route(
                'admin.projects.governance-meetings.show',
                [
                    'project' => $project->id,
                    'meeting' => $meeting->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Meeting
        </a>

    </div>

</div>

@endsection