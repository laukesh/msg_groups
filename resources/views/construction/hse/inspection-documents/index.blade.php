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
                Inspection Documents
            </h3>

            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Inspection
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.documents.create',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
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
                                            'admin.projects.construction.hse.inspections.documents.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $document->document_number }}
                                    </a>


                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit(
                                            $document->document_title,
                                            80
                                        ) }}

                                    </div>

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

                                    @if($document->file_name)

                                        <i class="bi bi-file-earmark me-1"></i>

                                        {{ \Illuminate\Support\Str::limit(
                                            $document->file_name,
                                            35
                                        ) }}

                                        <div class="small text-muted">

                                            {{ $document->formattedFileSize() }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{ $document->uploadedBy?->name
                                        ?? $document->creator?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.documents.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

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
                        No documents have been uploaded
                        for this inspection yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.documents.create',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
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