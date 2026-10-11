@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.documents.index',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="text-decoration-none"
                >
                    Tender:
                    {{ $procurementTender->tender_number }}
                </a>

            </div>

            <h4 class="mb-1">
                Add Tender Document
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.procurement.tenders.documents.index',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

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

            <strong>
                Tender Document Information
            </strong>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.procurement.tenders.documents.store',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                enctype="multipart/form-data"
            >

                @csrf

                @include(
                    'procurement.tender-documents._form',
                    [
                        'document' => null,
                    ]
                )

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.documents.index',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
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
                        Upload Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection