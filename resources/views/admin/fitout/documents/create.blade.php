@extends('layouts.app')

@section('title', 'Upload Fit-Out Document')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Upload Fit-Out Document
            </h4>

            <p class="text-muted mb-0">
                Upload documents related to a fit-out request.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.fitout.documents.index') }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <form
        action="{{ route('admin.fitout.documents.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- FIT-OUT INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Fit-Out Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Fit-Out Request --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Fit-Out Request
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="fitout_request_id"
                            id="fitout_request_id"
                            class="form-select @error('fitout_request_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Fit-Out Request
                            </option>

                            @foreach($fitoutRequests as $fitoutRequest)

                                <option
                                    value="{{ $fitoutRequest->id }}"
                                    @selected(
                                        old('fitout_request_id') == $fitoutRequest->id
                                    )
                                >

                                    {{ $fitoutRequest->request_no }}

                                    @if($fitoutRequest->tenant)
                                        -
                                        {{ $fitoutRequest->tenant->company_name ?? $fitoutRequest->tenant->company_name ?? 'Tenant' }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('fitout_request_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Document Type --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="document_type_id"
                            id="document_type_id"
                            class="form-select @error('document_type_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Document Type
                            </option>

                            @foreach($documentTypes as $type)

                                <option
                                    value="{{ $type->id }}"
                                    @selected(
                                        old('document_type_id') == $type->id
                                    )
                                >

                                    {{ $type->document_name }}

                                    @if($type->is_mandatory)
                                        *
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Documents marked with * are mandatory.
                        </small>

                        @error('document_type_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DOCUMENT INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Document Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Document Title --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="document_title"
                            id="document_title"
                            class="form-control @error('document_title') is-invalid @enderror"
                            value="{{ old('document_title') }}"
                            placeholder="Enter document title"
                            maxlength="200"
                            required
                        >

                        @error('document_title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Document Number --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Number
                        </label>

                        <input
                            type="text"
                            name="document_number"
                            id="document_number"
                            class="form-control @error('document_number') is-invalid @enderror"
                            value="{{ old('document_number') }}"
                            placeholder="Enter document number"
                            maxlength="100"
                        >

                        @error('document_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Version --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Version
                        </label>

                        <input
                            type="text"
                            name="version_no"
                            id="version_no"
                            class="form-control"
                            value="{{ old('version_no', '1.0') }}"
                            readonly
                        >

                        <small class="text-muted">
                            Version is automatically generated by the system.
                        </small>

                    </div>


                    {{-- Approval Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Approval Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Pending"
                            readonly
                        >

                        <small class="text-muted">
                            Newly uploaded documents are submitted for review.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILE UPLOAD --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Document File
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Select File
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="file"
                            id="file"
                            class="form-control @error('file') is-invalid @enderror"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                            required
                        >

                        <div class="form-text">

                            Allowed formats:
                            PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX.

                            <br>

                            Maximum file size:
                            <strong>50 MB</strong>.

                        </div>

                        @error('file')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- File Preview Information --}}
                    <div class="col-md-4">

                        <div
                            id="fileInfo"
                            class="border rounded p-3 bg-light d-none"
                        >

                            <strong>
                                Selected File
                            </strong>

                            <hr>

                            <div class="small">

                                <div class="mb-1">
                                    <strong>Name:</strong>
                                    <span id="fileName"></span>
                                </div>

                                <div class="mb-1">
                                    <strong>Size:</strong>
                                    <span id="fileSize"></span>
                                </div>

                                <div>
                                    <strong>Type:</strong>
                                    <span id="fileType"></span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    id="remarks"
                    class="form-control @error('remarks') is-invalid @enderror"
                    rows="4"
                    placeholder="Enter any additional remarks..."
                >{{ old('remarks') }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a
                href="{{ route('admin.fitout.documents.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
                id="submitBtn"
            >

                <i class="bi bi-upload"></i>

                Upload Document

            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('file');

    const fileInfo = document.getElementById('fileInfo');

    const fileName = document.getElementById('fileName');

    const fileSize = document.getElementById('fileSize');

    const fileType = document.getElementById('fileType');

    const form = document.querySelector('form');

    const submitBtn = document.getElementById('submitBtn');


    /*
    |--------------------------------------------------------------------------
    | File Selection
    |--------------------------------------------------------------------------
    */

    if (fileInput) {

        fileInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {

                fileInfo.classList.add('d-none');

                return;
            }


            /*
            |--------------------------------------------------------------
            | Maximum 50 MB
            |--------------------------------------------------------------
            */

            const maxSize = 50 * 1024 * 1024;

            if (file.size > maxSize) {

                alert(
                    'File size cannot be greater than 50 MB.'
                );

                this.value = '';

                fileInfo.classList.add('d-none');

                return;
            }


            /*
            |--------------------------------------------------------------
            | Show File Information
            |--------------------------------------------------------------
            */

            fileName.textContent = file.name;

            fileType.textContent =
                file.type || 'Unknown';

            fileSize.textContent =
                formatFileSize(file.size);

            fileInfo.classList.remove('d-none');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | File Size Formatter
    |--------------------------------------------------------------------------
    */

    function formatFileSize(bytes) {

        if (bytes === 0) {
            return '0 Bytes';
        }

        const units = [
            'Bytes',
            'KB',
            'MB',
            'GB'
        ];

        const index = Math.floor(
            Math.log(bytes) / Math.log(1024)
        );

        return (
            parseFloat(
                (bytes / Math.pow(1024, index))
                    .toFixed(2)
            )
            + ' '
            + units[index]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Double Submit
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener('submit', function () {

            if (submitBtn) {

                submitBtn.disabled = true;

                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Uploading...';

            }

        });

    }

});

</script>

@endsection