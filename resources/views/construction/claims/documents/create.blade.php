@extends('layouts.app')

@section('title', 'Upload Claim Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Upload Claim Document
            </h4>

            <div class="text-muted">
                {{ $claim->claim_number }}
                -
                {{ $claim->subject }}
            </div>

        </div>

        <a href="{{ route(
            'admin.projects.construction.claims.documents.index',
            [
                'project' => $project,
                'claim' => $claim,
            ]
        ) }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>
            Back to Documents

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Document Information
                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.claims.documents.store',
                              [
                                  'project' => $project,
                                  'claim' => $claim,
                              ]
                          ) }}"
                          enctype="multipart/form-data">

                        @csrf


                        {{-- Document Type --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Document Type
                                <span class="text-danger">*</span>
                            </label>

                            <select name="document_type"
                                    class="form-select @error('document_type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Document Type
                                </option>

                                <option value="Claim Letter"
                                    @selected(old('document_type') === 'Claim Letter')>
                                    Claim Letter
                                </option>

                                <option value="Cost Calculation"
                                    @selected(old('document_type') === 'Cost Calculation')>
                                    Cost Calculation
                                </option>

                                <option value="Site Photo"
                                    @selected(old('document_type') === 'Site Photo')>
                                    Site Photo
                                </option>

                                <option value="Drawing"
                                    @selected(old('document_type') === 'Drawing')>
                                    Drawing
                                </option>

                                <option value="Correspondence"
                                    @selected(old('document_type') === 'Correspondence')>
                                    Correspondence
                                </option>

                                <option value="Engineer Report"
                                    @selected(old('document_type') === 'Engineer Report')>
                                    Engineer Report
                                </option>

                                <option value="Supporting Evidence"
                                    @selected(old('document_type') === 'Supporting Evidence')>
                                    Supporting Evidence
                                </option>

                                <option value="Other"
                                    @selected(old('document_type') === 'Other')>
                                    Other
                                </option>

                            </select>

                            @error('document_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Document Title --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Document Title
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="document_title"
                                   value="{{ old('document_title') }}"
                                   class="form-control @error('document_title') is-invalid @enderror"
                                   placeholder="Enter document title"
                                   required>

                            @error('document_title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- File --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Document File
                                <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                   name="document"
                                   class="form-control @error('document') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp,.txt"
                                   required>

                            <div class="form-text">

                                Allowed:
                                PDF, DOC, DOCX, XLS, XLSX,
                                JPG, JPEG, PNG, WEBP, TXT.

                                Maximum size: 50 MB.

                            </div>

                            @error('document')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter document description">{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route(
                                'admin.projects.construction.claims.documents.index',
                                [
                                    'project' => $project,
                                    'claim' => $claim,
                                ]
                            ) }}"
                               class="btn btn-light border">

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

            </div>

        </div>


        {{-- Claim Summary --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Claim Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Claim Number
                        </small>

                        <div class="fw-semibold">
                            {{ $claim->claim_number }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted">
                            Claim Type
                        </small>

                        <div>
                            {{ $claim->claim_type }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted">
                            Claimant
                        </small>

                        <div>
                            {{ $claim->claimant_name ?: '-' }}
                        </div>

                    </div>

                    <div>

                        <small class="text-muted">
                            Status
                        </small>

                        <div>
                            {{ $claim->status }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection