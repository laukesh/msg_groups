@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                   class="text-decoration-none">

                    Tender:
                    {{ $procurementTender->tender_number }}

                </a>

            </div>

            <h4 class="mb-1">
                Tender Documents
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.procurement.tenders.show',
                $procurementTender
            ) }}"
               class="btn btn-outline-secondary">

                Back to Tender

            </a>


            <a href="{{ route(
                'admin.procurement.tenders.documents.create',
                $procurementTender
            ) }}"
               class="btn btn-primary">

                + Add Document

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


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Documents
                    </div>

                    <h4 class="mb-0">
                        {{ $documents->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <h4 class="mb-0">

                        {{
                            $documents
                                ->where('status', 'Draft')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Published
                    </div>

                    <h4 class="mb-0">

                        {{
                            $documents
                                ->where('status', 'Published')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted small">
                        Superseded
                    </div>

                    <h4 class="mb-0">

                        {{
                            $documents
                                ->where('status', 'Superseded')
                                ->count()
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Documents --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Tender Document Register
            </strong>

            <span class="badge bg-primary">
                {{ $documents->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Document No.</th>

                            <th>Document</th>

                            <th>Type</th>

                            <th>Version</th>

                            <th>Issue Date</th>

                            <th>Status</th>

                            <th>File</th>

                            <th class="text-end">
                                Action
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

                                    {{ $document->document_number ?: '—' }}

                                </td>


                                <td>

                                    <a href="{{ route(
                                        'admin.procurement.tenders.documents.show',
                                        [
                                            'procurementTender' =>
                                                $procurementTender,

                                            'document' =>
                                                $document,
                                        ]
                                    ) }}"
                                       class="fw-semibold text-decoration-none">

                                        {{ $document->document_title }}

                                    </a>

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $document->document_type }}

                                    </span>

                                </td>


                                <td>

                                    {{ $document->version }}

                                </td>


                                <td>

                                    {{
                                        $document->issue_date
                                            ? $document
                                                ->issue_date
                                                ->format('d-m-Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

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

                                </td>


                                <td>

                                    @if($document->file_path)

                                        <a
                                            href="{{ asset(
                                                'storage/' .
                                                $document->file_path
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success"
                                        >

                                            View File

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            No file
                                        </span>

                                    @endif

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.procurement.tenders.documents.show',
                                            [
                                                'procurementTender' =>
                                                    $procurementTender,

                                                'document' =>
                                                    $document,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


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
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No tender documents have been added.

                    </div>


                    <a
                        href="{{ route(
                            'admin.procurement.tenders.documents.create',
                            $procurementTender
                        ) }}"
                        class="btn btn-primary"
                    >

                        + Add First Document

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection