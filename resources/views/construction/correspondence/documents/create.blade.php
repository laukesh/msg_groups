@extends('layouts.app')

@section('title', 'Upload Correspondence Document')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Upload Correspondence Document
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

            <div class="small text-muted mt-1">

                {{ $correspondence->correspondence_number }}
                -
                {{ $correspondence->subject }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.correspondence.documents.index',
            [$project, $correspondence]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Documents

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row justify-content-center">

        <div class="col-xl-12 col-lg-12">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.construction.correspondence.documents.store',
                      [$project, $correspondence]
                  ) }}"
                  enctype="multipart/form-data">

                @csrf


                {{-- Correspondence --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            <i class="bi bi-envelope me-1"></i>
                            Correspondence
                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Correspondence Number
                                </div>

                                <div class="fw-semibold">
                                    {{ $correspondence->correspondence_number }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Type
                                </div>

                                <div class="fw-semibold">
                                    {{ $correspondence->correspondence_type }}
                                </div>

                            </div>


                            <div class="col-12">

                                <div class="text-muted small">
                                    Subject
                                </div>

                                <div class="fw-semibold">
                                    {{ $correspondence->subject }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Document Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Document Information
                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Document Type --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Document Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="document_type"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Select Document Type
                                    </option>

                                    @foreach([
                                        'Letter',
                                        'Email',
                                        'Notice',
                                        'Instruction',
                                        'Drawing',
                                        'Report',
                                        'Meeting Minutes',
                                        'Site Photo',
                                        'Supporting Evidence',
                                        'Other'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            {{ old(
                                                'document_type',
                                                'Other'
                                            ) == $type ? 'selected' : '' }}>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Document Title --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Document Title
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="document_title"
                                       value="{{ old('document_title') }}"
                                       class="form-control"
                                       maxlength="255"
                                       placeholder="Enter document title"
                                       required>

                            </div>


                            {{-- File --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Select File
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="file"
                                       name="document"
                                       class="form-control"
                                       required>

                                <div class="form-text">

                                    Maximum file size:
                                    <strong>50 MB</strong>

                                </div>

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Enter document description...">{{ old('description') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Upload Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <div class="fw-semibold mb-2">

                                <i class="bi bi-info-circle me-1"></i>
                                Document Upload

                            </div>

                            <ul class="mb-0">

                                <li>
                                    The document will be linked to this correspondence.
                                </li>

                                <li>
                                    The original file name and file metadata will be stored.
                                </li>

                                <li>
                                    Uploaded documents can be viewed or downloaded from the document register.
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route(
                                'admin.projects.construction.correspondence.documents.index',
                                [$project, $correspondence]
                            ) }}"
                               class="btn btn-outline-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-upload me-1"></i>
                                Upload Document

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection