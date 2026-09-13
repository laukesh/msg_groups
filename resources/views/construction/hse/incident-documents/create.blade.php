@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">
                Incident:
                <strong>
                    {{ $incident->incident_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Upload Incident Document
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? 'Project' }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.incidents.documents.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Documents
        </a>

    </div>


    {{-- Validation --}}
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


    {{-- Incident Summary --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Incident Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Number
                    </div>

                    <div class="fw-semibold">
                        {{ $incident->incident_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Type
                    </div>

                    <div class="fw-semibold">
                        {{ $incident->incident_type ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Incident Date
                    </div>

                    <div class="fw-semibold">

                        {{ $incident->incident_date
                            ? $incident->incident_date->format('d-m-Y')
                            : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <span class="badge bg-secondary">
                        {{ $incident->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Upload Form --}}
    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.hse.incidents.documents.store',
            [
                'project' => $project,
                'incident' => $incident,
            ]
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Document Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Document Title --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Document Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="document_title"
                            class="form-control @error('document_title') is-invalid @enderror"
                            value="{{ old('document_title') }}"
                            placeholder="e.g. Incident Site Photograph"
                            required
                        >

                        @error('document_title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Document Type --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Document Type

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="document_type"
                            class="form-select @error('document_type') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Document Type --
                            </option>

                            @foreach([
                                'Incident Photograph',
                                'Site Photograph',
                                'Medical Report',
                                'Police Report',
                                'Investigation Report',
                                'Witness Document',
                                'Supporting PDF',
                                'Other',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old('document_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>


                        @error('document_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Document Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Document Date
                        </label>

                        <input
                            type="date"
                            name="document_date"
                            class="form-control @error('document_date') is-invalid @enderror"
                            value="{{ old('document_date') }}"
                        >

                        @error('document_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Evidence --}}

                    <div class="col-md-8">

                        <label class="form-label d-block">
                            Document Classification
                        </label>


                        <div class="form-check form-switch mt-2">

                            <input
                                type="hidden"
                                name="is_evidence"
                                value="0"
                            >

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_evidence"
                                value="1"
                                id="is_evidence"
                                @checked(old('is_evidence'))
                            >

                            <label
                                class="form-check-label"
                                for="is_evidence"
                            >

                                Mark as Evidence

                            </label>

                        </div>


                        <div class="form-text">

                            Enable this when the uploaded document
                            is part of the official incident evidence.

                        </div>

                    </div>


                    {{-- Description --}}

                    <div class="col-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Describe this document or evidence"
                        >{{ old('description') }}</textarea>


                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- File --}}

                    <div class="col-12">

                        <label class="form-label">

                            File

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input
                            type="file"
                            name="document"
                            class="form-control @error('document') is-invalid @enderror"
                            required
                        >


                        <div class="form-text">

                            Allowed:
                            JPG, JPEG, PNG, PDF, DOC, DOCX,
                            XLS and XLSX.

                            Maximum size: 50 MB.

                        </div>


                        @error('document')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.documents.index',
                        [
                            'project' => $project,
                            'incident' => $incident,
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

                    <i class="bi bi-upload me-1"></i>

                    Upload Document

                </button>

            </div>

        </div>

    </form>

</div>

@endsection