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
                Documents
            </h3>

            <div class="text-muted">
                {{ $meeting->title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.show',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Meeting
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.hse.safety-meetings.documents.create',
                    [
                        'project' => $project,
                        'meeting' => $meeting,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-upload me-1"></i>
                Upload Document
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


    <div class="card">

        <div class="card-header">

            <strong>
                Document Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $documents->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Document Date
                            </th>

                            <th>
                                File
                            </th>

                            <th>
                                Uploaded By
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($documents as $document)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.safety-meetings.documents.show',
                                            [
                                                'project' => $project,
                                                'meeting' => $meeting,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $document->document_name }}
                                    </a>


                                    @if($document->document_number)

                                        <div class="small text-muted">
                                            {{ $document->document_number }}
                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $document->document_type ?? '—' }}
                                </td>


                                <td>

                                    {{ $document->document_date
                                        ? $document->document_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    <div class="small">

                                        {{ $document->original_file_name
                                            ?? '—'
                                        }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $document->file_size_formatted }}

                                    </div>

                                </td>


                                <td>

                                    {{ $document->uploadedBy?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.safety-meetings.documents.show',
                                                [
                                                    'project' => $project,
                                                    'meeting' => $meeting,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.hse.safety-meetings.documents.download',
                                                [
                                                    'project' => $project,
                                                    'meeting' => $meeting,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-success"
                                        >
                                            <i class="fa fa-download"></i>
                                        </a>


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
                                                class="btn btn-sm btn-outline-danger"
                                            >
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

            @else

                <div class="text-center py-5">

                    <i
                        class="bi bi-file-earmark-text"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Documents Found
                    </h6>

                    <p class="text-muted mb-3">
                        No documents have been uploaded for this
                        safety meeting yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.safety-meetings.documents.create',
                            [
                                'project' => $project,
                                'meeting' => $meeting,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-upload me-1"></i>
                        Upload First Document
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection