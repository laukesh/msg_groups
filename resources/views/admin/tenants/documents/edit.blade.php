@extends('layouts.app')

@section('title', 'Edit Tenant Document')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Tenant Document
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.documents.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Documents

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

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


    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-10">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-file-alt
                                  text-primary
                                  me-2"></i>

                        Document Information

                    </h5>

                    <small class="text-muted">

                        Update the tenant document details.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.documents.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'document' =>
                                      $document->id,
                              ]
                          ) }}"
                          enctype="multipart/form-data">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             DOCUMENT TYPE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Document Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="document_type_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Document Type
                                </option>

                                @foreach(
                                    $documentTypes as $type
                                )

                                    <option
                                        value="{{ $type->id }}"
                                        @selected(
                                            old(
                                                'document_type_id',
                                                $document->document_type_id
                                            ) == $type->id
                                        )>

                                        {{ $type->document_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             DOCUMENT NUMBER
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Document Number

                            </label>

                            <input type="text"
                                   name="document_number"
                                   value="{{ old(
                                       'document_number',
                                       $document->document_number
                                   ) }}"
                                   class="form-control"
                                   maxlength="100">

                        </div>


                        {{-- =================================================
                             CURRENT FILE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Current Document

                            </label>

                            @if($document->file_path)

                                <div class="border rounded p-3
                                            bg-light">

                                    <div class="d-flex
                                                justify-content-between
                                                align-items-center">

                                        <div>

                                            <i class="fas fa-file-alt
                                                      text-primary
                                                      me-2"></i>

                                            <span class="fw-semibold">

                                                {{ $document->file_name }}

                                            </span>

                                        </div>

                                        <a href="{{ asset(
                                            'storage/' .
                                            $document->file_path
                                        ) }}"
                                           target="_blank"
                                           class="btn btn-sm
                                                  btn-outline-primary">

                                            <i class="fas fa-eye me-1"></i>

                                            View

                                        </a>

                                    </div>

                                </div>

                            @else

                                <div class="text-muted">

                                    No file uploaded.

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                             REPLACE FILE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Replace Document File

                            </label>

                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                            <small class="text-muted">

                                Leave empty to keep the existing file.

                                Allowed:
                                PDF, JPG, JPEG, PNG, DOC, DOCX.
                                Maximum 5 MB.

                            </small>

                        </div>


                        {{-- =================================================
                             ISSUE + EXPIRY DATE
                        ================================================== --}}

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">

                                <label class="form-label">

                                    Issue Date

                                </label>

                                <input type="date"
                                       name="issue_date"
                                       value="{{ old(
                                           'issue_date',
                                           $document->issue_date
                                               ? $document
                                                   ->issue_date
                                                   ->format('Y-m-d')
                                               : null
                                       ) }}"
                                       class="form-control">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">

                                    Expiry Date

                                </label>

                                <input type="date"
                                       name="expiry_date"
                                       value="{{ old(
                                           'expiry_date',
                                           $document->expiry_date
                                               ? $document
                                                   ->expiry_date
                                                   ->format('Y-m-d')
                                               : null
                                       ) }}"
                                       class="form-control">

                            </div>

                        </div>


                        {{-- =================================================
                             VERIFICATION STATUS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Verification Status
                                <span class="text-danger">*</span>

                            </label>

                            <select name="verification_status"
                                    class="form-select"
                                    required>

                                <option value="Pending"
                                    @selected(
                                        old(
                                            'verification_status',
                                            $document
                                                ->verification_status
                                        ) === 'Pending'
                                    )>

                                    Pending

                                </option>

                                <option value="Verified"
                                    @selected(
                                        old(
                                            'verification_status',
                                            $document
                                                ->verification_status
                                        ) === 'Verified'
                                    )>

                                    Verified

                                </option>

                                <option value="Rejected"
                                    @selected(
                                        old(
                                            'verification_status',
                                            $document
                                                ->verification_status
                                        ) === 'Rejected'
                                    )>

                                    Rejected

                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control">{{ old(
                                          'remarks',
                                          $document->remarks
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.documents.index',
                                $tenant->id
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

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection