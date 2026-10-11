@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Toolbox Talk:
                <strong>
                    {{ $toolboxTalk->toolbox_talk_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Toolbox Talk Documents
            </h3>

            <div class="text-muted">
                {{ $toolboxTalk->title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.show',
                    [
                        'project' => $project,
                        'toolboxTalk' => $toolboxTalk,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Toolbox Talk
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.toolbox-talks.documents.create',
                    [
                        'project' => $project,
                        'toolboxTalk' => $toolboxTalk,
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

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Document Register
            </strong>

            <span class="badge bg-primary">
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
                                Document Number
                            </th>

                            <th>
                                Document Name
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
                                            'admin.projects.construction.hse.toolbox-talks.documents.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $document->document_number }}
                                    </a>

                                </td>


                                <td>
                                    {{ $document->document_name }}
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

                                    <span class="text-muted">

                                        {{ $document->original_file_name
                                            ?? 'File'
                                        }}

                                    </span>

                                    <div class="small text-muted">
                                        {{ $document->file_size_formatted }}
                                    </div>

                                </td>


                                <td>
                                    {{ $document->uploadedBy?->name ?? '—' }}
                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.documents.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.documents.download',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $toolboxTalk,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-success"
                                    >
                                        <i class="fa fa-download"></i>
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

                    <p class="text-muted">
                        Upload documents related to this toolbox talk.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.toolbox-talks.documents.create',
                            [
                                'project' => $project,
                                'toolboxTalk' => $toolboxTalk,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-upload me-1"></i>
                        Upload Document
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection