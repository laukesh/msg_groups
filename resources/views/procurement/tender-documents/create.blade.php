@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Tender Document
            </h4>

            <div class="text-muted">

                Tender:
                {{ $procurementTender->tender_number }}
                -
                {{ $procurementTender->tender_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.procurement.tenders.documents.index',
            $procurementTender
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
            'admin.procurement.tenders.documents.store',
            $procurementTender
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf


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
                        'document' => null,
                    ]
                )

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.procurement.tenders.documents.index',
                    $procurementTender
                ) }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    Upload Document

                </button>

            </div>

        </div>

    </form>

</div>

@endsection