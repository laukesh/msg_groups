@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Land Documents
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.documents.create',
                    $land
                ) }}"
                class="btn btn-primary"
            >
                + Upload Document
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back to Land
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <strong>
                Documents
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Version
                            </th>

                            <th>
                                Approval
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $documents as $document
                    )

                        <tr>

                            <td>

                                <strong>
                                    {{ $document->title }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $document->file_name }}

                                </small>

                            </td>


                            <td>
                                {{ $document->document_type }}
                            </td>


                            <td>
                                {{ $document->version }}
                            </td>


                            <td>

                                <span class="badge bg-secondary">

                                    {{ $document->approval_status }}

                                </span>

                            </td>


                            <td>

                                {{ $document->document_date
                                    ? $document->document_date->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.documents.show',
                                        [
                                            $land,
                                            $document
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.documents.download',
                                        [
                                            $land,
                                            $document
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-success"
                                >
                                    Download
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                No documents found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $documents->links() }}

        </div>

    </div>

</div>

@endsection