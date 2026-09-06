@extends('layouts.app')

@section('title', 'Upload Risk Document')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Upload Risk Document
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                -
                {{ $risk->risk_title }}
            </div>

            <div class="text-muted small">
                {{ $project->project_code ?? $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.risks.documents.index',
            [$project, $risk]
        ) }}"
        class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Documents

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
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


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.risks.documents.store',
              [$project, $risk]
          ) }}"
          enctype="multipart/form-data">

        @csrf


        {{-- Document Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Document Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

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
                                'Risk Assessment',
                                'Risk Register',
                                'Risk Analysis',
                                'Mitigation Plan',
                                'Schedule',
                                'Cost Estimate',
                                'Correspondence',
                                'Drawing',
                                'RFI',
                                'Meeting Minutes',
                                'Site Photo',
                                'Report',
                                'Supporting Evidence',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old('document_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Document Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="document_title"
                               class="form-control"
                               value="{{ old('document_title') }}"
                               placeholder="Enter document title"
                               required>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                               name="document"
                               class="form-control"
                               required>

                        <div class="form-text">
                            Maximum file size: 50 MB.
                        </div>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter document description">{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Risk Reference --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Risk Reference
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Risk Number
                        </div>

                        <div class="fw-semibold">
                            {{ $risk->risk_number }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Risk Category
                        </div>

                        <div class="fw-semibold">
                            {{ $risk->risk_category }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Risk Rating
                        </div>

                        @php

                            $ratingClass = match($risk->risk_rating) {
                                'Critical' => 'danger',
                                'High' => 'warning',
                                'Medium' => 'info',
                                default => 'success',
                            };

                        @endphp

                        <span class="badge bg-{{ $ratingClass }}">
                            {{ $risk->risk_rating }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.documents.index',
                [$project, $risk]
            ) }}"
            class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-upload"></i>
                Upload Document

            </button>

        </div>

    </form>

</div>

@endsection