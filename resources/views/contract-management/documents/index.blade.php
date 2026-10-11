@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Contract Documents
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.show',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Contract

            </a>


            <a href="{{ route(
                'admin.projects.contract-management.contracts.documents.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-upload me-1"></i>

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


    {{-- Summary --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Documents
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active Documents
                    </div>

                    <div class="fs-4 fw-semibold text-success">
                        {{ $summary['active'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Archived
                    </div>

                    <div class="fs-4 fw-semibold text-secondary">
                        {{ $summary['archived'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Document Types
                    </div>

                    <div class="fs-4 fw-semibold text-primary">
                        {{ $summary['document_types'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Type Summary --}}

    @if($typeSummary->count())

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Document Categories
                </h5>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    @foreach($typeSummary as $type => $count)

                        <span class="badge bg-light text-dark border p-2">

                            {{ $type }}

                            <span class="badge bg-primary ms-1">
                                {{ $count }}
                            </span>

                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- Document Register --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Document Register
                </h5>

                <span class="text-muted small">

                    {{ $summary['total'] }}

                    document(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Document No.
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
                                    Version
                                </th>

                                <th>
                                    File
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($documents as $document)

                                <tr>

                                    <td class="px-3">

                                        <strong>
                                            {{ $document->document_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $document->document_title }}

                                        </div>


                                        @if($document->description)

                                            <div class="small text-muted">

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $document->description,
                                                        80
                                                    )
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $document->document_type }}

                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $document->document_date
                                                ?->format('d M Y')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{ $document->document_version ?? '—' }}

                                    </td>


                                    <td>

                                        @if($document->file_name)

                                            <div class="d-flex align-items-center">

                                                <i class="bi bi-file-earmark-text me-2"></i>

                                                <div>

                                                    <div class="small fw-semibold">

                                                        {{
                                                            \Illuminate\Support\Str::limit(
                                                                $document->file_name,
                                                                30
                                                            )
                                                        }}

                                                    </div>

                                                    <div class="small text-muted">

                                                        {{
                                                            $document->formatted_file_size
                                                        }}

                                                    </div>

                                                </div>

                                            </div>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($document->status === 'Active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @elseif($document->status === 'Archived')

                                            <span class="badge bg-secondary">
                                                Archived
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Draft
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        @if($document->file_path)

                                            <a href="{{ route(
                                                'admin.projects.contract-management.contracts.documents.download',
                                                [
                                                    $project,
                                                    $contract,
                                                    $document
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Download">

                                                <i class="fa fa-download"></i>

                                            </a>

                                        @endif


                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.documents.edit',
                                            [
                                                $project,
                                                $contract,
                                                $document
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.documents.destroy',
                                                  [
                                                      $project,
                                                      $contract,
                                                      $document
                                                  ]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Delete this document and its uploaded file?');">

                                                <i class="fa fa-trash"></i>

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

                    <i class="bi bi-folder2-open fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Contract Documents
                    </h5>

                    <p class="text-muted">
                        No documents have been uploaded for this contract.
                    </p>


                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.documents.create',
                        [$project, $contract]
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