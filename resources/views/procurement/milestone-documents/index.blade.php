@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>

            {{-- Project --}}
            <div class="text-muted small mb-1">

                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? '—' }}
                </strong>

            </div>


            {{-- Contract --}}
            <div class="text-muted small mb-1">

                Contract:
                <strong>
                    {{ $contract->contract_number }}
                </strong>

            </div>


            <h4 class="mb-1 fw-bold">
                Deliverable Documents
            </h4>


            <div class="text-muted">

                {{ $milestone->milestone_number }}

                -

                {{ $milestone->milestone_title }}

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- ====================================================
                BACK TO TENDER
            ===================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- ====================================================
                BACK TO CONTRACT
            ===================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>


            {{-- ====================================================
                BACK TO MILESTONE
            ===================================================== --}}

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.milestones.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $milestone,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-flag me-1"></i>
                Back to Milestone
            </a>


            {{-- ====================================================
                UPLOAD DELIVERABLE
            ===================================================== --}}

            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.contracts.milestones.documents.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-upload me-1"></i>
                    Upload Deliverable
                </a>

            @endif

        </div>

    </div>


    {{-- ============================================================
        FLASH MESSAGES
    ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
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


    {{-- ============================================================
        SUMMARY
    ============================================================= --}}

    @php

        $totalDocuments =
            $documents->count();

        $submittedDocuments =
            $documents
                ->where('status', 'Submitted')
                ->count();

        $verifiedDocuments =
            $documents
                ->where('status', 'Verified')
                ->count();

        $rejectedDocuments =
            $documents
                ->where('status', 'Rejected')
                ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- ========================================================
            TOTAL
        ========================================================= --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Documents
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $totalDocuments }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            SUBMITTED
        ========================================================= --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Submitted
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $submittedDocuments }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-warning bg-opacity-10
                                   text-warning d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            VERIFIED
        ========================================================= --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Verified
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                {{ $verifiedDocuments }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            REJECTED
        ========================================================= --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Rejected
                            </div>

                            <div class="fs-3 fw-bold text-danger">
                                {{ $rejectedDocuments }}
                            </div>

                        </div>

                        <div
                            class="rounded-circle bg-danger bg-opacity-10
                                   text-danger d-flex align-items-center
                                   justify-content-center"
                            style="width:48px;height:48px;"
                        >
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DOCUMENT REGISTER
    ============================================================= --}}

    <div class="card border-0 shadow-sm">

        {{-- Register Header --}}
        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h6 class="fw-bold mb-1">

                        <i class="bi bi-folder2-open me-2 text-primary"></i>

                        Deliverable Document Register

                    </h6>

                    <div class="small text-muted">

                        Documents submitted against this milestone

                    </div>

                </div>


                <div class="d-flex align-items-center gap-2">

                    <span class="badge rounded-pill bg-primary px-3 py-2">

                        {{ $totalDocuments }}

                        {{ $totalDocuments === 1
                            ? 'Document'
                            : 'Documents'
                        }}

                    </span>


                    @if($contract->status === 'Active')

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.milestones.documents.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'milestone' => $milestone,
                                ]
                            ) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="bi bi-upload me-1"></i>
                            Upload
                        </a>

                    @endif

                </div>

            </div>

        </div>


        {{-- Register Body --}}
        <div class="card-body p-0">

            @if($documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 document-register">

                        <thead>

                            <tr>

                                <th
                                    class="text-center"
                                    style="width:55px;"
                                >
                                    #
                                </th>


                                <th style="width:180px;">
                                    Document No.
                                </th>


                                <th style="min-width:300px;">
                                    Document
                                </th>


                                <th style="width:150px;">
                                    Type
                                </th>


                                <th
                                    class="text-center"
                                    style="width:130px;"
                                >
                                    Progress
                                </th>


                                <th style="width:145px;">
                                    Uploaded
                                </th>


                                <th style="width:120px;">
                                    Status
                                </th>


                                <th
                                    class="text-center"
                                    style="width:110px;"
                                >
                                    File
                                </th>


                                <th
                                    class="text-end"
                                    style="width:170px;"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($documents as $document)

                            @php

                                $statusClass = match(
                                    $document->status
                                ) {

                                    'Verified' =>
                                        'bg-success-subtle text-success',

                                    'Rejected' =>
                                        'bg-danger-subtle text-danger',

                                    'Submitted' =>
                                        'bg-warning-subtle text-warning-emphasis',

                                    default =>
                                        'bg-secondary-subtle text-secondary',

                                };

                            @endphp


                            <tr>

                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}

                                <td class="text-center text-muted">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =================================================
                                    DOCUMENT NUMBER
                                ================================================== --}}

                                <td>

                                    @if($document->document_number)

                                        <span class="document-number">

                                            {{ $document->document_number }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    DOCUMENT
                                ================================================== --}}

                                <td>

                                    <div class="document-title">

                                        {{ $document->document_title }}

                                    </div>


                                    @if($document->description)

                                        <div class="small text-muted mt-1 document-description">

                                            {{ \Illuminate\Support\Str::limit(
                                                $document->description,
                                                100
                                            ) }}

                                        </div>

                                    @endif


                                    @if($document->file_name)

                                        <div class="small text-muted mt-2">

                                            <i class="bi bi-paperclip me-1"></i>

                                            {{ $document->file_name }}

                                            @if($document->file_size)

                                                <span class="ms-1">

                                                    ·

                                                    {{
                                                        number_format(
                                                            $document->file_size / 1024,
                                                            1
                                                        )
                                                    }}
                                                    KB

                                                </span>

                                            @endif

                                        </div>

                                    @endif

                                </td>


                                {{-- =================================================
                                    TYPE
                                ================================================== --}}

                                <td>

                                    @if($document->document_type)

                                        <span class="badge bg-light text-dark border">

                                            {{ $document->document_type }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    PROGRESS
                                ================================================== --}}

                                <td class="text-center">

                                    @if($document->progress)

                                        <span class="badge bg-info-subtle text-info-emphasis">

                                            {{ $document->progress->progress_percentage }}%

                                        </span>


                                        @if($document->progress->progress_date)

                                            <div class="small text-muted mt-1">

                                                {{
                                                    $document->progress
                                                        ->progress_date
                                                        ->format('d-m-Y')
                                                }}

                                            </div>

                                        @endif

                                    @else

                                        <span class="text-muted small">
                                            Not linked
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    UPLOADED
                                ================================================== --}}

                                <td>

                                    @if($document->uploaded_at)

                                        <div class="fw-semibold">

                                            {{
                                                $document->uploaded_at
                                                    ->format('d-m-Y')
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $document->uploaded_at
                                                    ->format('H:i')
                                            }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    <span
                                        class="badge {{ $statusClass }} px-2 py-1"
                                    >
                                        {{ $document->status }}
                                    </span>


                                    @if($document->verified_at)

                                        <div class="small text-muted mt-1">

                                            {{
                                                $document->verified_at
                                                    ->format('d-m-Y H:i')
                                            }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =================================================
                                    FILE
                                ================================================== --}}

                                <td class="text-center">

                                    @if($document->file_path)

                                        <a
                                            href="{{ Storage::disk('public')->url(
                                                $document->file_path
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success"
                                            title="Open document"
                                        >

                                            <i class="bi bi-eye me-1"></i>
                                            View

                                        </a>

                                    @else

                                        <span class="text-muted small">
                                            No file
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}

                                <td>

                                    <div class="d-flex justify-content-end flex-wrap gap-1">


                                        {{-- ========================================
                                            VERIFY
                                        ========================================= --}}

                                        @if($document->status === 'Submitted')

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-success js-verify-document"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verifyDocumentModal"
                                                data-document-id="{{ $document->id }}"
                                                data-document-title="{{ $document->document_title }}"
                                                data-action="{{ route(
                                                    'admin.projects.procurement.tenders.contracts.milestones.documents.verify',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'contract' => $contract,
                                                        'milestone' => $milestone,
                                                        'document' => $document,
                                                    ]
                                                ) }}"
                                                title="Verify document"
                                            >

                                                <i class="bi bi-check-lg"></i>

                                            </button>


                                            {{-- ====================================
                                                REJECT
                                            ===================================== --}}

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger js-reject-document"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectDocumentModal"
                                                data-document-id="{{ $document->id }}"
                                                data-document-title="{{ $document->document_title }}"
                                                data-action="{{ route(
                                                    'admin.projects.procurement.tenders.contracts.milestones.documents.reject',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'contract' => $contract,
                                                        'milestone' => $milestone,
                                                        'document' => $document,
                                                    ]
                                                ) }}"
                                                title="Reject document"
                                            >

                                                <i class="bi bi-x-lg"></i>

                                            </button>

                                        @endif


                                        {{-- ========================================
                                            DELETE
                                        ========================================= --}}

                                        @if($document->status !== 'Verified')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.procurement.tenders.contracts.milestones.documents.destroy',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $procurementTender,
                                                        'contract' => $contract,
                                                        'milestone' => $milestone,
                                                        'document' => $document,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Delete this deliverable document?'
                                                );"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete document"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- ========================================================
                    EMPTY STATE
                ========================================================= --}}

                <div class="text-center py-5 px-3">

                    <div
                        class="rounded-circle bg-primary bg-opacity-10
                               text-primary d-inline-flex align-items-center
                               justify-content-center mb-3"
                        style="width:64px;height:64px;"
                    >
                        <i class="bi bi-file-earmark-text fs-2"></i>
                    </div>


                    <h6 class="fw-bold">
                        No Deliverable Documents
                    </h6>


                    <p class="text-muted mb-4">

                        No deliverable documents have been uploaded
                        for this milestone yet.

                    </p>


                    @if($contract->status === 'Active')

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.contracts.milestones.documents.create',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                    'contract' => $contract,
                                    'milestone' => $milestone,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-upload me-1"></i>

                            Upload First Deliverable

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
    VERIFY DOCUMENT MODAL
================================================================= --}}

<div
    class="modal fade"
    id="verifyDocumentModal"
    tabindex="-1"
    aria-labelledby="verifyDocumentModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="verifyDocumentModalLabel"
                >
                    Verify Document
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                id="verifyDocumentForm"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-info">

                        <strong>
                            Document:
                        </strong>

                        <span id="verifyDocumentTitle">
                            —
                        </span>

                    </div>


                    <div class="mb-3">

                        <label
                            for="verifyVerificationRemarks"
                            class="form-label"
                        >
                            Verification Remarks
                        </label>

                        <textarea
                            name="verification_remarks"
                            id="verifyVerificationRemarks"
                            class="form-control"
                            rows="4"
                            placeholder="Enter verification remarks..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Verify Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
    REJECT DOCUMENT MODAL
================================================================= --}}

<div
    class="modal fade"
    id="rejectDocumentModal"
    tabindex="-1"
    aria-labelledby="rejectDocumentModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="rejectDocumentModalLabel"
                >
                    Reject Document
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                id="rejectDocumentForm"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-warning">

                        <strong>
                            Document:
                        </strong>

                        <span id="rejectDocumentTitle">
                            —
                        </span>

                    </div>


                    <div class="mb-3">

                        <label
                            for="rejectVerificationRemarks"
                            class="form-label"
                        >
                            Rejection Reason
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="verification_remarks"
                            id="rejectVerificationRemarks"
                            class="form-control"
                            rows="4"
                            required
                            placeholder="Explain why this document is rejected..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        <i class="bi bi-x-lg me-1"></i>
                        Reject Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
    JAVASCRIPT
================================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | VERIFY
    |--------------------------------------------------------------------------
    */

    const verifyForm =
        document.getElementById(
            'verifyDocumentForm'
        );

    const verifyTitle =
        document.getElementById(
            'verifyDocumentTitle'
        );

    const verifyRemarks =
        document.getElementById(
            'verifyVerificationRemarks'
        );


    document
        .querySelectorAll('.js-verify-document')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const title =
                        this.getAttribute(
                            'data-document-title'
                        );

                    const action =
                        this.getAttribute(
                            'data-action'
                        );


                    verifyTitle.textContent =
                        title || 'Document';


                    verifyForm.action =
                        action;


                    verifyRemarks.value =
                        '';

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    const rejectForm =
        document.getElementById(
            'rejectDocumentForm'
        );

    const rejectTitle =
        document.getElementById(
            'rejectDocumentTitle'
        );

    const rejectRemarks =
        document.getElementById(
            'rejectVerificationRemarks'
        );


    document
        .querySelectorAll('.js-reject-document')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const title =
                        this.getAttribute(
                            'data-document-title'
                        );

                    const action =
                        this.getAttribute(
                            'data-action'
                        );


                    rejectTitle.textContent =
                        title || 'Document';


                    rejectForm.action =
                        action;


                    rejectRemarks.value =
                        '';

                }
            );

        });

});

</script>


{{-- ================================================================
    STYLES
================================================================= --}}

<style>

    .document-register {
        font-size: 0.875rem;
    }

    .document-register thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
        padding: 14px 12px;
        border-bottom: 1px solid #dee2e6;
    }

    .document-register tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f2;
    }

    .document-register tbody tr:last-child td {
        border-bottom: 0;
    }

    .document-number {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f0f4ff;
        color: #4056c6;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid #dce3ff;
    }

    .document-title {
        color: #344054;
        font-weight: 700;
        line-height: 1.4;
        max-width: 360px;
    }

    .document-description {
        max-width: 360px;
        line-height: 1.4;
    }

    .document-register .badge {
        font-weight: 600;
        white-space: nowrap;
    }

    .document-register .btn {
        white-space: nowrap;
    }

    @media (max-width: 1200px) {

        .document-title {
            max-width: 280px;
        }

        .document-description {
            max-width: 280px;
        }

    }

</style>

@endsection