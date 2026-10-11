@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Edit Contract Document
            </h4>

            <div class="text-muted">

                {{ $document->document_number }}

                <span class="mx-1">|</span>

                {{ $contract->contract_code }}

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
              'admin.projects.contract-management.contracts.documents.update',
              [$project, $contract, $document]
          ) }}"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        {{-- Information --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Document Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Document Number
                        </label>

                        <input type="text"
                               value="{{ $document->document_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Document Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="document_title"
                               value="{{ old(
                                   'document_title',
                                   $document->document_title
                               ) }}"
                               class="form-control"
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
                                        old(
                                            'document_type',
                                            $document->document_type
                                        ) === $type
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
                               value="{{ old(
                                   'document_date',
                                   $document->document_date
                                       ?->format('Y-m-d')
                               ) }}"
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
                                   $document->document_version
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            @foreach([
                                'Active',
                                'Draft',
                                'Archived'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $document->status
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'description',
                                      $document->description
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Existing File --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Current File
                </h5>

            </div>


            <div class="card-body">

                @if($document->file_name)

                    <div class="d-flex align-items-center justify-content-between border rounded p-3">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-file-earmark-text fs-3 me-3"></i>

                            <div>

                                <div class="fw-semibold">

                                    {{ $document->file_name }}

                                </div>

                                <div class="text-muted small">

                                    {{ $document->formatted_file_size }}

                                    @if($document->mime_type)

                                        <span class="mx-1">|</span>

                                        {{ $document->mime_type }}

                                    @endif

                                </div>

                            </div>

                        </div>


                        <a href="{{ route(
                            'admin.projects.contract-management.contracts.documents.download',
                            [
                                $project,
                                $contract,
                                $document
                            ]
                        ) }}"
                           class="btn btn-outline-success">

                            <i class="bi bi-download me-1"></i>

                            Download

                        </a>

                    </div>

                @else

                    <div class="text-muted">
                        No file attached.
                    </div>

                @endif

            </div>

        </div>


        {{-- Replace File --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Replace File
                </h5>

            </div>


            <div class="card-body">

                <input type="file"
                       name="document_file"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">


                <div class="form-text">

                    Leave empty to keep the existing file.

                    Maximum size: 50 MB.

                </div>

            </div>

        </div>


        {{-- Audit --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Record Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        {{
                            $document->created_at
                                ?->format('d M Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        {{
                            $document->updated_at
                                ?->format('d M Y H:i')
                            ?? '—'
                        }}

                    </div>

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

                <i class="bi bi-check-lg me-1"></i>

                Update Document

            </button>

        </div>

    </form>

</div>

@endsection