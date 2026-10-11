@extends('layouts.app')

@section('title', 'Commissioning Documents')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Documents & Evidence
            </h4>

            <div class="text-muted">
                Commissioning documents, test reports and supporting evidence
            </div>
        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.commissioning.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-speedometer2 me-1"></i>
                Dashboard
            </a>
            <a
                href="{{ route(
                    'admin.projects.commissioning.documents.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-cloud-upload me-1"></i>
                Upload Document
            </a>
        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search document name, file name or type..."
                        >

                    </div>

                    <div class="col-md-3">

                        <select
                            name="document_type"
                            class="form-select"
                        >

                            <option value="">
                                All Document Types
                            </option>

                            @foreach($documentTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('document_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Rejected',
                                'Archived'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2 d-flex gap-2">

                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>

                        <a
                            href="{{ route(
                                'admin.projects.commissioning.documents.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between">

            <strong>
                Document Register
            </strong>

            <span class="text-muted small">
                {{ $documents->total() }} documents
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Linked To</th>
                            <th>Version</th>
                            <th>Size</th>
                            <th>Uploaded By</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($documents as $document)

                            @php

                                $statusClass = match($document->status) {

                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    'Archived' =>
                                        'bg-secondary',

                                    default =>
                                        'bg-light text-dark border',
                                };

                            @endphp

                            <tr>

                                <td>

                                    <div class="fw-semibold">

                                        <i class="bi bi-file-earmark-text me-1"></i>

                                        {{ $document->document_name }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $document->file_name }}

                                    </div>

                                </td>


                                <td>
                                    {{ $document->document_type }}
                                </td>


                                <td>

                                    @if($document->test)

                                        <span class="badge bg-light text-dark border">
                                            Test:
                                            {{ $document->test->test_no }}
                                        </span>

                                    @elseif($document->testPlan)

                                        <span class="badge bg-light text-dark border">
                                            Plan:
                                            {{ $document->testPlan->test_plan_no }}
                                        </span>

                                    @elseif($document->certificate)

                                        <span class="badge bg-light text-dark border">
                                            Certificate:
                                            {{ $document->certificate->certificate_no }}
                                        </span>

                                    @elseif($document->scope)

                                        <span class="badge bg-light text-dark border">
                                            Scope:
                                            {{ $document->scope->scope_code }}
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark border">
                                            Project
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $document->document_version ?? '-' }}
                                </td>


                                <td>
                                    {{ $document->formatted_file_size }}
                                </td>


                                <td>
                                    {{ $document->uploadedBy?->name ?? '-' }}
                                </td>


                                <td>

                                    <span class="badge {{ $statusClass }}">
                                        {{ $document->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        <a
                                            href="{{ route(
                                                'admin.projects.commissioning.documents.download',
                                                [$project, $document]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Download"
                                        >
                                            <i class="fas fa-download"></i>
                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.commissioning.documents.destroy',
                                                [$project, $document]
                                            ) }}"
                                            onsubmit="return confirm('Delete this document?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <i class="bi bi-folder2-open fs-1 text-muted"></i>

                                    <div class="fw-semibold mt-2">
                                        No documents found
                                    </div>

                                    <div class="text-muted small">
                                        Upload commissioning documents and test evidence.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="mt-3">
        {{ $documents->links() }}
    </div>

</div>

@endsection