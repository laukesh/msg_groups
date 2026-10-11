@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="mb-2">
                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.show',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                    class="text-decoration-none small fw-semibold"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Tender {{ $procurementTender->tender_number }}
                </a>
            </div>

            <h4 class="fw-bold mb-1">
                Tender Documents
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.documents.create',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Document
            </a>

        </div>

    </div>


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        SUMMARY
    ============================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Documents
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $documents->count() }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Draft
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $documents->where('status', 'Draft')->count() }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-secondary bg-opacity-10
                                   text-secondary d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-file-earmark fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Published --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Published
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                {{ $documents->where('status', 'Published')->count() }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Superseded --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Superseded
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $documents->where('status', 'Superseded')->count() }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-warning bg-opacity-10
                                   text-warning d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DOCUMENT REGISTER
    ============================================================= --}}

    <div class="card border-0 shadow-sm">

        {{-- Register Header --}}
        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-folder2-open me-2 text-primary"></i>
                        Tender Document Register
                    </h6>

                    <div class="small text-muted">
                        All documents associated with this tender
                    </div>

                </div>


                <span class="badge rounded-pill bg-primary px-3 py-2">

                    {{ $documents->count() }}

                    {{ $documents->count() === 1 ? 'Document' : 'Documents' }}

                </span>

            </div>

        </div>


        {{-- Register Body --}}
        <div class="card-body p-0">

            @if($documents->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 document-register">

                        <thead>

                            <tr>

                                <th class="text-center" style="width:55px;">
                                    #
                                </th>

                                <th style="width:175px;">
                                    Document No.
                                </th>

                                <th style="min-width:300px;">
                                    Document
                                </th>

                                <th style="width:145px;">
                                    Type
                                </th>

                                <th class="text-center" style="width:90px;">
                                    Version
                                </th>

                                <th style="width:120px;">
                                    Issue Date
                                </th>

                                <th style="width:120px;">
                                    Status
                                </th>

                                <th class="text-center" style="width:120px;">
                                    File
                                </th>

                                <th
                                    class="text-end"
                                    style="width:150px;"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($documents as $document)

                            @php

                                $statusClass = match($document->status) {

                                    'Published' =>
                                        'bg-success-subtle text-success',

                                    'Superseded' =>
                                        'bg-warning-subtle text-warning-emphasis',

                                    'Cancelled' =>
                                        'bg-danger-subtle text-danger',

                                    default =>
                                        'bg-secondary-subtle text-secondary',

                                };

                            @endphp


                            <tr>

                                {{-- Number --}}
                                <td class="text-center text-muted">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Document Number --}}
                                <td>

                                    <span
                                        class="document-number"
                                    >
                                        {{ $document->document_number }}
                                    </span>

                                </td>


                                {{-- Document --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.procurement.tenders.documents.show',
                                            [
                                                'project' => $project,
                                                'procurementTender' => $procurementTender,
                                                'document' => $document,
                                            ]
                                        ) }}"
                                        class="document-title"
                                    >
                                        {{ $document->document_title }}
                                    </a>

                                    @if($document->description)

                                        <div class="small text-muted mt-1 document-description">

                                            {{ \Illuminate\Support\Str::limit(
                                                $document->description,
                                                100
                                            ) }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $document->document_type }}

                                    </span>

                                </td>


                                {{-- Version --}}
                                <td class="text-center">

                                    <span class="fw-semibold">
                                        {{ $document->version }}
                                    </span>

                                </td>


                                {{-- Issue Date --}}
                                <td>

                                    @if($document->issue_date)

                                        <div class="fw-semibold">
                                            {{ $document->issue_date->format('d-m-Y') }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge {{ $statusClass }} px-2 py-1">

                                        {{ $document->status }}

                                    </span>

                                </td>


                                {{-- File --}}
                                <td class="text-center">

                                    @if($document->file_path)

                                        <a
                                            href="{{ asset(
                                                'storage/' .
                                                $document->file_path
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success file-btn"
                                            title="Open document file"
                                        >

                                            <i class="bi bi-file-earmark-arrow-up me-1"></i>
                                            View File

                                        </a>

                                    @else

                                        <span class="text-muted small">
                                            No file
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex justify-content-end gap-1">

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.documents.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View document details"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.documents.edit',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'document' => $document,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Edit document"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10
                               text-primary d-inline-flex align-items-center
                               justify-content-center mb-3"
                        style="width:64px;height:64px;"
                    >
                        <i class="bi bi-file-earmark-text fs-2"></i>
                    </div>


                    <h6 class="fw-bold">
                        No Tender Documents
                    </h6>


                    <p class="text-muted mb-4">
                        No documents have been added to this tender yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.documents.create',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Document
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
    PAGE STYLES
================================================================ --}}

<style>

    .document-register {
        font-size: 0.875rem;
    }

    .document-register thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
        padding: 14px 12px;
        border-bottom: 1px solid #dee2e6;
    }

    .document-register tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f2;
    }

    .document-register tbody tr:last-child td {
        border-bottom: 0;
    }

    .document-number {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f0f4ff;
        color: #4056c6;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid #dce3ff;
    }

    .document-title {
        display: block;
        color: #344054;
        font-weight: 700;
        line-height: 1.4;
        text-decoration: none;
        max-width: 360px;
    }

    .document-title:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    .document-description {
        max-width: 360px;
        line-height: 1.4;
    }

    .file-btn {
        white-space: nowrap;
    }

    .document-register .badge {
        font-weight: 600;
        white-space: nowrap;
    }

    @media (max-width: 1200px) {

        .document-title {
            max-width: 280px;
        }

        .document-description {
            max-width: 280px;
        }

    }

</style>

@endsection