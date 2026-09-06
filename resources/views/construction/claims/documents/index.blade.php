@extends('layouts.app')

@section('title', 'Claim Documents')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Claim Documents
            </h4>

            <div class="text-muted">
                {{ $claim->claim_number }}
                -
                {{ $claim->subject }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.claims.show',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            ) }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left"></i>
                Back to Claim
            </a>

            <a href="{{ route(
                'admin.projects.construction.claims.documents.create',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-upload"></i>
                Upload Document
            </a>

        </div>

    </div>


    {{-- Flash Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Claim Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Claim Number
                    </small>

                    <div class="fw-semibold">
                        {{ $claim->claim_number }}
                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Claim Type
                    </small>

                    <div class="fw-semibold">
                        {{ $claim->claim_type }}
                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="fw-semibold">
                        {{ $claim->status }}
                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Documents
                    </small>

                    <div class="fw-semibold">
                        {{ $documents->count() }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Documents --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Documents
                </h5>

                <span class="badge bg-secondary">
                    {{ $documents->count() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="50">
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
                                    Uploaded On
                                </th>

                                <th width="180">
                                    Actions
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

                                        @if($document->description)

                                            <small class="text-muted">
                                                {{ $document->description }}
                                            </small>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $document->document_type }}

                                        </span>

                                    </td>


                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <i class="bi bi-file-earmark-text fs-5"></i>

                                            <div>

                                                <div class="small fw-semibold">
                                                    {{ $document->file_name }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ number_format(($document->file_size ?? 0) / 1024, 2) }}
                                                    KB
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        {{ $document->uploadedBy?->name ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $document->created_at?->format('d M Y H:i') }}

                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- View --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.claims.documents.view',
                                                [
                                                    'project' => $project,
                                                    'claim' => $claim,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            {{-- Download --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.claims.documents.download',
                                                [
                                                    'project' => $project,
                                                    'claim' => $claim,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Download">

                                                <i class="fa fa-download"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.projects.construction.claims.documents.destroy',
                                                      [
                                                          'project' => $project,
                                                          'claim' => $claim,
                                                          'document' => $document,
                                                      ]
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

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-file-earmark-x fs-1 text-muted"></i>

                    </div>

                    <h6>
                        No Documents Found
                    </h6>

                    <p class="text-muted mb-3">
                        No documents have been uploaded for this claim yet.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.claims.documents.create',
                        [
                            'project' => $project,
                            'claim' => $claim,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-upload"></i>
                        Upload First Document

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection