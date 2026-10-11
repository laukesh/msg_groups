@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.documents.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'document' => $document,
                        ]
                    ) }}"
                    class="text-decoration-none"
                >
                    Document:
                    {{ $document->document_number }}
                </a>

            </div>

            <h4 class="mb-1">
                Edit Tender Document
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_number }}
                —
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.documents.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'document' => $document,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    {{-- Form --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Tender Document Information
                </strong>

                <span class="badge bg-primary">
                    {{ $document->document_number }}
                </span>

            </div>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.procurement.tenders.documents.update',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'document' => $document,
                    ]
                ) }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                @include(
                    'procurement.tender-documents._form',
                    [
                        'document' => $document,
                    ]
                )


                <hr class="my-4">


                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        Document number
                        <strong>
                            {{ $document->document_number }}
                        </strong>
                        cannot be changed.

                    </div>


                    <div class="d-flex gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.documents.show',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'document' => $document,
                                ]
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update Document
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection