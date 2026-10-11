@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Document Details
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
                    'admin.land.lands.documents.download',
                    [
                        $land,
                        $document
                    ]
                ) }}"
                class="btn btn-success"
            >
                Download
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.documents.index',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            <strong>
                {{ $document->title }}
            </strong>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Document Type
                    </small>

                    <div>
                        {{ $document->document_type }}
                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Document Number
                    </small>

                    <div>
                        {{ $document->document_number ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Version
                    </small>

                    <div>
                        {{ $document->version }}
                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Approval Status
                    </small>

                    <div>
                        {{ $document->approval_status }}
                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Document Date
                    </small>

                    <div>

                        {{ $document->document_date
                            ? $document->document_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-6 mb-4">

                    <small class="text-muted">
                        Expiry Date
                    </small>

                    <div>

                        {{ $document->expiry_date
                            ? $document->expiry_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-12 mb-4">

                    <small class="text-muted">
                        Description
                    </small>

                    <div>

                        {!! nl2br(
                            e(
                                $document->description ?? '-'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-12">

                    <small class="text-muted">
                        File
                    </small>

                    <div>

                        {{ $document->file_name }}

                        <br>

                        <small class="text-muted">

                            {{ $document->file_extension }}

                            |

                            {{ number_format(
                                ($document->file_size ?? 0) / 1024,
                                2
                            ) }}
                            KB

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-danger mt-4">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.land.lands.documents.destroy',
                    [
                        $land,
                        $document
                    ]
                ) }}"
                onsubmit="return confirm('Are you sure you want to delete this document?');"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Delete Document
                </button>

            </form>

        </div>

    </div>

</div>

@endsection