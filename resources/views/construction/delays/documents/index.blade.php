@extends('layouts.app')

@section('title', 'Delay Documents')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Delay Documents
            </h4>

            <div class="text-muted">

                {{ $delay->delay_number }}
                -
                {{ $delay->delay_title }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.delays.documents.create',
                [$project, $delay]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-upload"></i>
                Upload Document

            </a>


            <a href="{{ route(
                'admin.projects.construction.delays.show',
                [$project, $delay]
            ) }}"
               class="btn btn-outline-secondary">

                Back

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Supporting Documents
                </h5>

                <span class="badge bg-secondary">
                    {{ $documents->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Document</th>

                            <th>Type</th>

                            <th>File</th>

                            <th>Uploaded By</th>

                            <th>Date</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($documents as $document)

                            <tr>

                                <td>
                                    {{ $documents->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $document->document_title }}
                                    </strong>

                                    @if($document->description)

                                        <div class="small text-muted">

                                            {{ $document->description }}

                                        </div>

                                    @endif

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

                                        <small class="text-muted">

                                            {{ number_format(
                                                $document->file_size / 1024,
                                                1
                                            ) }}
                                            KB

                                        </small>

                                    @endif

                                </td>


                                <td>
                                    {{ optional(
                                        $document->uploadedBy
                                    )->name ?? '-' }}
                                </td>


                                <td>

                                    {{ optional(
                                        $document->created_at
                                    )->format('d-m-Y H:i') }}

                                </td>


                                <td class="text-end">

                                    <div class="btn-group">

                                        <a href="{{ route(
                                            'admin.projects.construction.delays.documents.view',
                                            [$project, $delay, $document]
                                        ) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.projects.construction.delays.documents.download',
                                            [$project, $delay, $document]
                                        ) }}"
                                           class="btn btn-sm btn-outline-secondary">

                                            <i class="fa fa-download"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.construction.delays.documents.destroy',
                                                  [$project, $delay, $document]
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this document?'
                                              );">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>

                                    No documents uploaded.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($documents->hasPages())

            <div class="card-footer bg-white">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection