@extends('layouts.app')

@section('title', 'Tenant Documents')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant Documents
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.show',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Tenant Details

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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


    <div class="row g-4">


        {{-- =====================================================
             UPLOAD DOCUMENT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-file-upload
                                  text-primary
                                  me-2"></i>

                        Upload Document

                    </h5>

                    <small class="text-muted">

                        Upload a document for this tenant.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.documents.store',
                              $tenant->id
                          ) }}"
                          enctype="multipart/form-data">

                        @csrf


                        {{-- DOCUMENT TYPE --}}

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
                                                'document_type_id'
                                            ) == $type->id
                                        )>

                                        {{ $type->document_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- DOCUMENT NUMBER --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Document Number

                            </label>

                            <input type="text"
                                   name="document_number"
                                   value="{{ old(
                                       'document_number'
                                   ) }}"
                                   class="form-control"
                                   maxlength="100">

                        </div>


                        {{-- FILE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Document File
                                <span class="text-danger">*</span>

                            </label>

                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   required>

                            <small class="text-muted">

                                Allowed:
                                PDF, JPG, JPEG, PNG, DOC, DOCX.
                                Maximum 5 MB.

                            </small>

                        </div>


                        {{-- ISSUE DATE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Issue Date

                            </label>

                            <input type="date"
                                   name="issue_date"
                                   value="{{ old(
                                       'issue_date'
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- EXPIRY DATE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Expiry Date

                            </label>

                            <input type="date"
                                   name="expiry_date"
                                   value="{{ old(
                                       'expiry_date'
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- REMARKS --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control">{{ old('remarks') }}</textarea>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-upload me-1"></i>

                            Upload Document

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             DOCUMENT LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">
                        Document List
                    </h5>

                    <small class="text-muted">

                        {{ $documents->count() }}
                        document(s)

                    </small>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table
                                      table-hover
                                      align-middle
                                      mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Document
                                    </th>

                                    <th>
                                        Number
                                    </th>

                                    <th>
                                        Validity
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse(
                                $documents as $document
                            )

                                <tr>

                                    {{-- DOCUMENT --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $document->documentType->name
                                                ?? 'Unknown Type' }}

                                        </div>


                                        @if(
                                            $document->file_name
                                        )

                                            <small class="text-muted">

                                                <i class="fas fa-file
                                                          me-1"></i>

                                                {{ $document->file_name }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- DOCUMENT NUMBER --}}

                                    <td>

                                        {{ $document->document_number
                                            ?: '-' }}

                                    </td>


                                    {{-- VALIDITY --}}

                                    <td>

                                        @if(
                                            $document->expiry_date
                                        )

                                            @php

                                                $today =
                                                    now()->startOfDay();

                                                $expiry =
                                                    $document
                                                        ->expiry_date
                                                        ->startOfDay();

                                                $daysLeft =
                                                    $today->diffInDays(
                                                        $expiry,
                                                        false
                                                    );

                                            @endphp


                                            @if($daysLeft < 0)

                                                <span class="badge bg-danger">

                                                    Expired

                                                </span>

                                                <small class="d-block
                                                             text-muted">

                                                    {{ $document
                                                        ->expiry_date
                                                        ->format(
                                                            'd M Y'
                                                        ) }}

                                                </small>

                                            @elseif($daysLeft <= 30)

                                                <span class="badge bg-warning
                                                             text-dark">

                                                    Expiring Soon

                                                </span>

                                                <small class="d-block
                                                             text-muted">

                                                    {{ $document
                                                        ->expiry_date
                                                        ->format(
                                                            'd M Y'
                                                        ) }}

                                                </small>

                                            @else

                                                <span class="badge bg-success">

                                                    Valid

                                                </span>

                                                <small class="d-block
                                                             text-muted">

                                                    {{ $document
                                                        ->expiry_date
                                                        ->format(
                                                            'd M Y'
                                                        ) }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                No expiry
                                            </span>

                                        @endif

                                    </td>


                                    {{-- VERIFICATION STATUS --}}

                                    <td>

                                        @if(
                                            $document
                                                ->verification_status
                                                === 'Verified'
                                        )

                                            <span class="badge bg-success">

                                                <i class="fas fa-check me-1"></i>

                                                Verified

                                            </span>

                                        @elseif(
                                            $document
                                                ->verification_status
                                                === 'Rejected'
                                        )

                                            <span class="badge bg-danger">

                                                <i class="fas fa-times me-1"></i>

                                                Rejected

                                            </span>

                                        @else

                                            <span class="badge bg-warning
                                                         text-dark">

                                                <i class="fas fa-clock me-1"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}

                                    <td class="text-end">

                                        <div class="btn-group">

                                            @if(
                                                $document->file_path
                                            )

                                                <a href="{{ asset(
                                                    'storage/' .
                                                    $document->file_path
                                                ) }}"
                                                   target="_blank"
                                                   class="btn btn-sm
                                                          btn-outline-primary"
                                                   title="View">

                                                    <i class="fas fa-eye"></i>

                                                </a>

                                            @endif


                                            <a href="{{ route(
                                                'admin.tenants.documents.edit',
                                                [
                                                    'tenant' =>
                                                        $tenant->id,
                                                    'document' =>
                                                        $document->id,
                                                ]
                                            ) }}"
                                               class="btn btn-sm
                                                      btn-outline-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.tenants.documents.destroy',
                                                      [
                                                          'tenant' =>
                                                              $tenant->id,
                                                          'document' =>
                                                              $document->id,
                                                      ]
                                                  ) }}"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this document?'
                                                  );">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm
                                                           btn-outline-danger"
                                                    title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center
                                               text-muted
                                               py-5">

                                        <i class="fas fa-folder-open
                                                  fa-3x
                                                  d-block
                                                  mb-3">
                                        </i>

                                        <h6>
                                            No documents found
                                        </h6>

                                        <p class="mb-0">

                                            Upload the first document
                                            using the form.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection