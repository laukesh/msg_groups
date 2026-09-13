@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Tender Document
            </h4>

            <div class="text-muted">

                {{ $document->document_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.procurement.tenders.documents.show',
            [
                'procurementTender' => $procurementTender,
                'document' => $document,
            ]
        ) }}"
           class="btn btn-outline-secondary">

            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.documents.update',
            [
                'procurementTender' => $procurementTender,
                'document' => $document,
            ]
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Tender Document Information
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'procurement.tender-documents._form',
                    [
                        'document' => $document,
                    ]
                )

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.procurement.tenders.documents.show',
                    [
                        'procurementTender' => $procurementTender,
                        'document' => $document,
                    ]
                ) }}"
                   class="btn btn-outline-secondary">

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

@endsection