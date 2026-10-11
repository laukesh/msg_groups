@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">
                Incident:
                <strong>
                    {{ $incident->incident_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Documents & Evidence
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? 'Project' }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Incident
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.documents.create',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-upload me-1"></i>
                Upload Document
            </a>

        </div>

    </div>


    {{-- Success --}}
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


    {{-- Error --}}
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


    {{-- Document Register --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Document Register
                </strong>

                <span class="badge bg-primary">
                    {{ $documents->count() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Evidence
                            </th>

                            <th>
                                Uploaded By
                            </th>

                            <th>
                                Size
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

                                    <div class="fw-semibold">

                                        {{ $document->document_title }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $document->file_name }}

                                    </div>

                                    @if($document->description)

                                        <div class="small text-muted mt-1">

                                            {{ \Illuminate\Support\Str::limit(
                                                $document->description,
                                                80
                                            ) }}

                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $document->document_type }}

                                    </span>

                                </td>


                                <td>

                                    {{ $document->document_date
                                        ? $document->document_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    @if($document->is_evidence)

                                        <span class="badge bg-danger">
                                            Evidence
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            Supporting
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $document->uploader?->name ?? '—' }}

                                </td>


                                <td>

                                    {{ $document->formatted_file_size }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.incidents.documents.show',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-download"></i>
                                        Download
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.projects.construction.hse.incidents.documents.destroy',
                                            [
                                                'project' => $project,
                                                'incident' => $incident,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="d-inline"
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
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-file-earmark-text"
                            style="font-size: 42px;"
                        ></i>

                    </div>

                    <h6>
                        No Documents or Evidence
                    </h6>

                    <p class="text-muted mb-3">

                        No documents have been uploaded
                        for this incident yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.incidents.documents.create',
                            [
                                'project' => $project,
                                'incident' => $incident,
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