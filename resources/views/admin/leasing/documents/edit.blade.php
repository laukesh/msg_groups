@extends('layouts.app')

@section('title', 'Edit Lease Document')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Lease Document</h4>

            <div class="text-muted">
                Update document information or replace the file.
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.documents.show',
                $document->id
            ) }}"
               class="btn btn-info">

                <i class="fas fa-eye me-1"></i>
                View

            </a>

            <a href="{{ route(
                'admin.leasing.documents.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.leasing.documents.update',
              $document->id
          ) }}"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        {{-- Agreement --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Agreement

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <label class="form-label">

                            Lease Agreement
                            <span class="text-danger">*</span>

                        </label>


                        <select name="lease_agreement_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Agreement --
                            </option>


                            @foreach($agreements as $agreement)

                                <option value="{{ $agreement->id }}"
                                    {{ old(
                                        'lease_agreement_id',
                                        $document->lease_agreement_id
                                    ) == $agreement->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $agreement->agreement_no }}

                                    @if($agreement->tenant)

                                        -
                                        {{ $agreement->tenant->company_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Document Details --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-alt me-1"></i>

                    Document Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Document Type --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Document Type
                            <span class="text-danger">*</span>

                        </label>


                        <select name="document_type_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Document Type --
                            </option>


                            @foreach($documentTypes as $type)

                                <option value="{{ $type->id }}"
                                    {{ old(
                                        'document_type_id',
                                        $document->document_type_id
                                    ) == $type->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $type->document_name }}

                                    @if($type->is_mandatory)

                                        (Mandatory)

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Document Name --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Document Name
                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="document_name"
                               class="form-control"
                               maxlength="200"
                               value="{{ old(
                                   'document_name',
                                   $document->document_name
                               ) }}"
                               required>

                    </div>


                    {{-- Document Number --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Document Number

                        </label>


                        <input type="text"
                               name="document_number"
                               class="form-control"
                               maxlength="100"
                               value="{{ old(
                                   'document_number',
                                   $document->document_number
                               ) }}"
                               placeholder="Optional document number">

                    </div>


                    {{-- Version --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Version No.

                        </label>


                        <input type="number"
                               name="version_no"
                               class="form-control"
                               min="1"
                               value="{{ old(
                                   'version_no',
                                   $document->version_no ?? 1
                               ) }}">

                    </div>


                    {{-- Issue Date --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Issue Date

                        </label>


                        <input type="date"
                               name="issue_date"
                               class="form-control"
                               value="{{ old(
                                   'issue_date',
                                   $document->issue_date
                                       ? $document->issue_date->format('Y-m-d')
                                       : ''
                               ) }}">

                    </div>


                    {{-- Expiry Date --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Expiry Date

                        </label>


                        <input type="date"
                               name="expiry_date"
                               class="form-control"
                               value="{{ old(
                                   'expiry_date',
                                   $document->expiry_date
                                       ? $document->expiry_date->format('Y-m-d')
                                       : ''
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Current File --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-paperclip me-1"></i>

                    Current File

                </h5>

            </div>


            <div class="card-body">

                @if($document->file_path)

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-semibold">

                                {{ $document->file_name }}

                            </div>


                            <small class="text-muted">

                                {{ strtoupper(
                                    $document->file_extension ?? 'FILE'
                                ) }}

                                @if($document->file_size)

                                    ·

                                    {{ number_format(
                                        $document->file_size / 1024,
                                        2
                                    ) }}

                                    KB

                                @endif

                            </small>

                        </div>


                        <div>

                            <a href="{{ asset(
                                'storage/' . $document->file_path
                            ) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">

                                <i class="fas fa-eye me-1"></i>

                                View

                            </a>

                            <a href="{{ asset(
                                'storage/' . $document->file_path
                            ) }}"
                               download
                               class="btn btn-primary btn-sm">

                                <i class="fas fa-download me-1"></i>

                                Download

                            </a>

                        </div>

                    </div>

                @else

                    <div class="text-muted">

                        No file currently uploaded.

                    </div>

                @endif

            </div>

        </div>


        {{-- Replace File --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-upload me-1"></i>

                    Replace File

                </h5>

            </div>


            <div class="card-body">

                <label class="form-label">

                    New File

                </label>


                <input type="file"
                       name="file"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">


                <div class="form-text">

                    Leave empty if you do not want to replace
                    the existing file.

                    Allowed: PDF, JPG, JPEG, PNG, DOC, DOCX,
                    XLS, XLSX. Maximum size: 10 MB.

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-comment-alt me-1"></i>

                    Remarks

                </h5>

            </div>


            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control"
                          placeholder="Enter remarks...">{{ old(
                              'remarks',
                              $document->remarks
                          ) }}</textarea>

            </div>

        </div>


        {{-- Verification Information --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-check-circle me-1"></i>

                    Verification Status

                </h5>

            </div>


            <div class="card-body">

                @if(
                    $document->verification_status === 'Verified'
                )

                    <div class="alert alert-success mb-0">

                        <i class="fas fa-check-circle me-1"></i>

                        This document has already been verified.

                        @if($document->verifiedBy)

                            Verified by
                            <strong>
                                {{ $document->verifiedBy->name }}
                            </strong>

                        @endif

                        @if($document->verified_at)

                            on
                            {{ $document->verified_at->format(
                                'd M Y H:i'
                            ) }}

                        @endif

                    </div>

                @elseif(
                    $document->verification_status === 'Rejected'
                )

                    <div class="alert alert-danger mb-0">

                        <i class="fas fa-times-circle me-1"></i>

                        This document is currently rejected.

                    </div>

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-clock me-1"></i>

                        This document is pending verification.

                    </div>

                @endif

            </div>

        </div>


        {{-- Actions --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.documents.show',
                        $document->id
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Update Document

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection