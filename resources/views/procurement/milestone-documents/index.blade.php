@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Deliverable Documents
            </h4>

            <div class="text-muted">
                {{ $milestone->milestone_number }}
                -
                {{ $milestone->milestone_title }}
            </div>

        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Contract --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.show',
                    [
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>


            {{-- Back to Milestone --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.show',
                    [
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


            {{-- Upload Deliverable --}}
            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.documents.create',
                        [
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

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

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

            <ul class="mb-0">

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

        {{-- TOTAL --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Documents
                    </small>

                    <h4 class="mt-2 mb-0">
                        {{ $totalDocuments }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- SUBMITTED --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Submitted
                    </small>

                    <h4 class="mt-2 mb-0 text-warning">
                        {{ $submittedDocuments }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- VERIFIED --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Verified
                    </small>

                    <h4 class="mt-2 mb-0 text-success">
                        {{ $verifiedDocuments }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- REJECTED --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Rejected
                    </small>

                    <h4 class="mt-2 mb-0 text-danger">
                        {{ $rejectedDocuments }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DOCUMENT REGISTER
    ============================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Document Register
            </strong>


            @if($contract->status === 'Active')

                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.milestones.documents.create',
                        [
                            'procurementTender' => $procurementTender,
                            'contract' => $contract,
                            'milestone' => $milestone,
                        ]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    + Upload
                </a>

            @endif

        </div>


        <div class="card-body p-0">

            @if($documents->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Document
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Progress
                            </th>

                            <th>
                                Uploaded
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

                        @foreach($documents as $document)

                            @php

                                $statusClass = match(
                                    $document->status
                                ) {

                                    'Verified' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    default =>
                                        'bg-secondary',

                                };


                                /*
                                |--------------------------------------------------------------------------
                                | Verify URL
                                |--------------------------------------------------------------------------
                                */

                                $verifyUrl = route(
                                    'admin.procurement.tenders.contracts.milestones.documents.verify',
                                    [
                                        'procurementTender' =>
                                            $procurementTender,

                                        'contract' =>
                                            $contract,

                                        'milestone' =>
                                            $milestone,

                                        'document' =>
                                            $document,
                                    ]
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Reject URL
                                |--------------------------------------------------------------------------
                                */

                                $rejectUrl = route(
                                    'admin.procurement.tenders.contracts.milestones.documents.reject',
                                    [
                                        'procurementTender' =>
                                            $procurementTender,

                                        'contract' =>
                                            $contract,

                                        'milestone' =>
                                            $milestone,

                                        'document' =>
                                            $document,
                                    ]
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Delete URL
                                |--------------------------------------------------------------------------
                                */

                                $deleteUrl = route(
                                    'admin.procurement.tenders.contracts.milestones.documents.destroy',
                                    [
                                        'procurementTender' =>
                                            $procurementTender,

                                        'contract' =>
                                            $contract,

                                        'milestone' =>
                                            $milestone,

                                        'document' =>
                                            $document,
                                    ]
                                );

                            @endphp


                            <tr>

                                {{-- NUMBER --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- DOCUMENT --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $document->document_title }}

                                    </div>


                                    @if($document->document_number)

                                        <div class="small text-muted">

                                            No:
                                            {{ $document->document_number }}

                                        </div>

                                    @endif


                                    <div class="small text-muted">

                                        {{ $document->file_name }}

                                        @if($document->file_size)

                                            <br>

                                            {{
                                                number_format(
                                                    $document->file_size / 1024,
                                                    1
                                                )
                                            }}
                                            KB

                                        @endif

                                    </div>


                                    @if($document->description)

                                        <div class="small mt-1">

                                            {{ $document->description }}

                                        </div>

                                    @endif

                                </td>


                                {{-- TYPE --}}

                                <td>

                                    {{ $document->document_type ?? '—' }}

                                </td>


                                {{-- PROGRESS --}}

                                <td>

                                    @if($document->progress)

                                        <span class="badge bg-info">

                                            {{
                                                $document->progress
                                                    ->progress_percentage
                                            }}%

                                        </span>

                                        <div class="small text-muted">

                                            {{
                                                $document->progress
                                                    ->progress_date
                                                    ?->format('d-m-Y')
                                            }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Not linked
                                        </span>

                                    @endif

                                </td>


                                {{-- UPLOADED --}}

                                <td>

                                    {{
                                        $document->uploaded_at
                                            ?->format('d-m-Y H:i')
                                        ?? '—'
                                    }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    <span
                                        class="badge {{ $statusClass }}"
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


                                    @if($document->verification_remarks)

                                        <div class="small mt-2">

                                            <strong>
                                                Remarks:
                                            </strong>

                                            {{ $document->verification_remarks }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end">

                                    <div class="d-flex justify-content-end flex-wrap gap-1">


                                        {{-- VIEW --}}

                                        <a
                                            href="{{ Storage::disk('public')->url(
                                                $document->file_path
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>


                                        {{-- VERIFY --}}

                                        @if($document->status === 'Submitted')

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-success js-verify-document"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verifyDocumentModal"
                                                data-document-id="{{ $document->id }}"
                                                data-document-title="{{ $document->document_title }}"
                                                data-action="{{ $verifyUrl }}"
                                            >
                                                Verify
                                            </button>


                                            {{-- REJECT --}}

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger js-reject-document"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectDocumentModal"
                                                data-document-id="{{ $document->id }}"
                                                data-document-title="{{ $document->document_title }}"
                                                data-action="{{ $rejectUrl }}"
                                            >
                                                Reject
                                            </button>

                                        @endif


                                        {{-- DELETE --}}

                                        @if($document->status !== 'Verified')

                                            <form
                                                method="POST"
                                                action="{{ $deleteUrl }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Delete this document?'
                                                );"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
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

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No deliverable documents uploaded.
                    </div>


                    @if($contract->status === 'Active')

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.milestones.documents.create',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'contract' =>
                                        $contract,

                                    'milestone' =>
                                        $milestone,
                                ]
                            ) }}"
                            class="btn btn-primary"
                        >
                            + Upload First Deliverable
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
    VERIFY DOCUMENT MODAL

    IMPORTANT:
    This modal is OUTSIDE the table.
================================================================ --}}

<div
    class="modal fade"
    id="verifyDocumentModal"
    tabindex="-1"
    aria-labelledby="verifyDocumentModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            {{-- HEADER --}}

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
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}

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


                {{-- FOOTER --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Verify Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
    REJECT DOCUMENT MODAL

    IMPORTANT:
    This modal is OUTSIDE the table.
================================================================ --}}

<div
    class="modal fade"
    id="rejectDocumentModal"
    tabindex="-1"
    aria-labelledby="rejectDocumentModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            {{-- HEADER --}}

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
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}

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

                            <span class="text-danger">
                                *
                            </span>

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


                {{-- FOOTER --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================
    MODAL JAVASCRIPT
================================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | VERIFY MODAL
    |--------------------------------------------------------------------------
    */

    const verifyModal =
        document.getElementById('verifyDocumentModal');

    const verifyForm =
        document.getElementById('verifyDocumentForm');

    const verifyTitle =
        document.getElementById('verifyDocumentTitle');

    const verifyRemarks =
        document.getElementById('verifyVerificationRemarks');


    document
        .querySelectorAll('.js-verify-document')
        .forEach(function (button) {

            button.addEventListener('click', function () {

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

            });

        });


    /*
    |--------------------------------------------------------------------------
    | REJECT MODAL
    |--------------------------------------------------------------------------
    */

    const rejectModal =
        document.getElementById('rejectDocumentModal');

    const rejectForm =
        document.getElementById('rejectDocumentForm');

    const rejectTitle =
        document.getElementById('rejectDocumentTitle');

    const rejectRemarks =
        document.getElementById('rejectVerificationRemarks');


    document
        .querySelectorAll('.js-reject-document')
        .forEach(function (button) {

            button.addEventListener('click', function () {

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

            });

        });


    /*
    |--------------------------------------------------------------------------
    | CLEANUP AFTER MODAL CLOSE
    |--------------------------------------------------------------------------
    */

    if (verifyModal) {

        verifyModal.addEventListener(
            'hidden.bs.modal',
            function () {

                verifyRemarks.value = '';

            }
        );

    }


    if (rejectModal) {

        rejectModal.addEventListener(
            'hidden.bs.modal',
            function () {

                rejectRemarks.value = '';

            }
        );

    }

});

</script>

@endsection