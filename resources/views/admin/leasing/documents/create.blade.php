@extends('layouts.app')

@section('title', 'Upload Lease Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Upload Lease Document
            </h4>

            <div class="text-muted">
                Add a document to a lease agreement.
            </div>

        </div>

        <a href="{{ route(
            'admin.leasing.documents.index'
        ) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


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
              'admin.leasing.documents.store'
          ) }}"
          enctype="multipart/form-data">

        @csrf


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
                                        $selectedAgreementId
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
                                        'document_type_id'
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
                                   'document_name'
                               ) }}"
                               placeholder="Enter document name"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Document Number
                        </label>

                        <input type="text"
                               name="document_number"
                               class="form-control"
                               maxlength="100"
                               value="{{ old(
                                   'document_number'
                               ) }}"
                               placeholder="Optional document number">

                    </div>


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
                                   1
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Issue Date
                        </label>

                        <input type="date"
                               name="issue_date"
                               class="form-control"
                               value="{{ old(
                                   'issue_date'
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input type="date"
                               name="expiry_date"
                               class="form-control"
                               value="{{ old(
                                   'expiry_date'
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- File --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="fas fa-upload me-1"></i>
                    Upload File
                </h5>

            </div>


            <div class="card-body">

                <label class="form-label">
                    Document File
                </label>


                <input type="file"
                       name="file"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">


                <div class="form-text">

                    Allowed: PDF, JPG, JPEG, PNG, DOC, DOCX,
                    XLS, XLSX. Maximum size: 10 MB.

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>


            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control"
                          placeholder="Enter remarks...">{{ old(
                              'remarks'
                          ) }}</textarea>

            </div>

        </div>


        {{-- Actions --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.documents.index'
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-upload me-1"></i>

                        Upload Document

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection