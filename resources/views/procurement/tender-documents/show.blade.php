@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}"
                    class="text-decoration-none"
                >

                    Tender:
                    {{ $procurementTender->tender_number }}

                </a>

            </div>


            <h4 class="mb-1">

                {{ $document->document_title }}

            </h4>


            <div class="text-muted">

                {{ $document->document_type }}

                @if($document->version)
                    · Version {{ $document->version }}
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.documents.edit',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'document' =>
                            $document,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.documents.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >

                Back

            </a>

        </div>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Document Information --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Document Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Document Number --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Document Number
                    </div>

                    <div class="fw-semibold">

                        {{
                            $document->document_number ?: '—'
                        }}

                    </div>

                </div>


                {{-- Title --}}
                <div class="col-md-5">

                    <div class="text-muted small">
                        Document Title
                    </div>

                    <div class="fw-semibold">

                        {{ $document->document_title }}

                    </div>

                </div>


                {{-- Type --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Document Type
                    </div>

                    <span class="badge bg-secondary">

                        {{ $document->document_type }}

                    </span>

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Status
                    </div>


                    @php

                        $statusClass = match(
                            $document->status
                        ) {

                            'Published'
                                => 'bg-success',

                            'Superseded'
                                => 'bg-warning text-dark',

                            'Cancelled'
                                => 'bg-danger',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $document->status }}

                    </span>

                </div>


                {{-- Version --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Version
                    </div>

                    <div>
                        {{ $document->version }}
                    </div>

                </div>


                {{-- Issue Date --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Issue Date
                    </div>

                    <div>

                        {{
                            $document->issue_date
                                ? $document
                                    ->issue_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Uploaded By --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Uploaded By
                    </div>

                    <div>

                        {{
                            $document->uploaded_by_name
                            ?: '—'
                        }}

                    </div>

                </div>


                {{-- Tender --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Tender
                    </div>

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.show',
                            $procurementTender
                        ) }}"
                    >

                        {{ $procurementTender->tender_number }}

                    </a>

                </div>


                {{-- Description --}}
                <div class="col-md-12">

                    <div class="text-muted small">
                        Description
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $document->description ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $document->remarks ?: '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- File --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Document File
            </strong>

        </div>


        <div class="card-body">

            @if($document->file_path)

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-semibold">

                            {{ $document->file_name }}

                        </div>

                        <div class="small text-muted">

                            Version {{ $document->version }}

                        </div>

                    </div>


                    <a
                        href="{{ asset(
                            'storage/' .
                            $document->file_path
                        ) }}"
                        target="_blank"
                        class="btn btn-success"
                    >

                        Open Document

                    </a>

                </div>

            @else

                <div class="text-muted">

                    No file has been uploaded for this document.

                </div>

            @endif

        </div>

    </div>


    {{-- Delete --}}
    <div class="card border-danger mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">

                        Delete Document

                    </strong>

                    <div class="small text-muted">

                        The uploaded file will also be removed.

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.documents.destroy',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'document' =>
                                $document,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Delete this tender document and its uploaded file?'
                        )"
                    >

                        Delete Document

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection