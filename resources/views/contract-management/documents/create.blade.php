@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Upload Contract Document
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.documents.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


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


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.documents.store',
              [$project, $contract]
          ) }}"
          enctype="multipart/form-data">

        @csrf


        {{-- Document Information --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Document Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-8">

                        <label class="form-label">
                            Document Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="document_title"
                               value="{{ old('document_title') }}"
                               class="form-control"
                               placeholder="e.g. Signed Contract Agreement"
                               required>

                    </div>


                    <div class="col-md-4">

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
                                'Contract Agreement',
                                'Letter of Award',
                                'Letter of Acceptance',
                                'Work Order',
                                'BOQ',
                                'Scope of Work',
                                'Terms & Conditions',
                                'Special Conditions',
                                'Performance Security',
                                'Insurance',
                                'Bank Guarantee',
                                'Advance Payment Guarantee',
                                'Contractor Document',
                                'Consultant Document',
                                'Variation Document',
                                'Claim Document',
                                'Payment Document',
                                'Correspondence',
                                'Meeting Minutes',
                                'Completion Certificate',
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


                    <div class="col-md-4">

                        <label class="form-label">
                            Document Date
                        </label>

                        <input type="date"
                               name="document_date"
                               value="{{ old('document_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Document Version
                        </label>

                        <input type="text"
                               name="document_version"
                               value="{{ old(
                                   'document_version',
                                   '1.0'
                               ) }}"
                               class="form-control"
                               placeholder="e.g. 1.0">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="Active"
                                @selected(
                                    old(
                                        'status',
                                        'Active'
                                    ) === 'Active'
                                )>
                                Active
                            </option>

                            <option value="Draft"
                                @selected(
                                    old('status') === 'Draft'
                                )>
                                Draft
                            </option>

                            <option value="Archived"
                                @selected(
                                    old('status') === 'Archived'
                                )>
                                Archived
                            </option>

                        </select>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Enter document description...">{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- File --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Upload File
                </h5>

            </div>


            <div class="card-body">

                <label class="form-label">

                    Document File

                    <span class="text-danger">*</span>

                </label>


                <input type="file"
                       name="document_file"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                       required>


                <div class="form-text">

                    Allowed formats:
                    PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.

                    Maximum size: 50 MB.

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.documents.index',
                [$project, $contract]
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

    </form>

</div>

@endsection