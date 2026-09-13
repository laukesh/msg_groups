@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Documents
            </div>

            <h3 class="mb-1">
                Upload Meeting Document
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                @if($meeting->meeting_title)
                    · {{ $meeting->meeting_title }}
                @endif

                · {{ $project->project_name }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.documents.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Documents
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- UPLOAD FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.documents.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- MEETING INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Meeting Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Meeting Number
                        </div>

                        <div class="fw-semibold">
                            {{ $meeting->meeting_number }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Meeting Date
                        </div>

                        <div class="fw-semibold">

                            @if($meeting->meeting_date)

                                {{ $meeting->meeting_date->format('d-m-Y') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Project
                        </div>

                        <div class="fw-semibold">
                            {{ $project->project_name }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DOCUMENT DETAILS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Document Details
                </strong>

                <div class="text-muted small mt-1">
                    Enter the document information before uploading.
                </div>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Document Name --}}

                    <div class="col-md-8 mb-3">

                        <label
                            for="document_name"
                            class="form-label"
                        >

                            Document Name

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            id="document_name"
                            name="document_name"
                            class="form-control @error('document_name') is-invalid @enderror"
                            value="{{ old('document_name') }}"
                            maxlength="255"
                            placeholder="e.g. Board Presentation - August 2026"
                            required
                        >


                        @error('document_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Document Type --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="document_type"
                            class="form-label"
                        >
                            Document Type
                        </label>


                        <select
                            id="document_type"
                            name="document_type"
                            class="form-select @error('document_type') is-invalid @enderror"
                        >

                            <option value="">
                                — Select Type —
                            </option>

                            <option
                                value="Agenda"
                                @selected(
                                    old('document_type') === 'Agenda'
                                )
                            >
                                Agenda / Notice
                            </option>

                            <option
                                value="Presentation"
                                @selected(
                                    old('document_type') === 'Presentation'
                                )
                            >
                                Presentation
                            </option>

                            <option
                                value="Report"
                                @selected(
                                    old('document_type') === 'Report'
                                )
                            >
                                Report
                            </option>

                            <option
                                value="Minutes"
                                @selected(
                                    old('document_type') === 'Minutes'
                                )
                            >
                                Minutes
                            </option>

                            <option
                                value="Attendance"
                                @selected(
                                    old('document_type') === 'Attendance'
                                )
                            >
                                Attendance Sheet
                            </option>

                            <option
                                value="Supporting Document"
                                @selected(
                                    old('document_type') === 'Supporting Document'
                                )
                            >
                                Supporting Document
                            </option>

                            <option
                                value="Other"
                                @selected(
                                    old('document_type') === 'Other'
                                )
                            >
                                Other
                            </option>

                        </select>


                        @error('document_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Description --}}

                    <div class="col-12 mb-3">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                        </label>


                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            maxlength="5000"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Briefly describe the purpose or contents of this document..."
                        >{{ old('description') }}</textarea>


                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FILE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Document File
                </strong>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="file"
                        class="form-label"
                    >

                        Select File

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="file"
                        id="file"
                        name="file"
                        class="form-control @error('file') is-invalid @enderror"
                        required
                    >


                    @error('file')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror


                    <div class="form-text">

                        Maximum file size:
                        <strong>50 MB</strong>.

                        You may upload PDF, Word, Excel,
                        PowerPoint, images or other supported
                        meeting documents.

                    </div>

                </div>


                {{-- File Preview --}}

                <div
                    id="filePreview"
                    class="border rounded p-3 d-none"
                >

                    <div class="text-muted small">
                        Selected File
                    </div>

                    <div
                        id="fileName"
                        class="fw-semibold mt-1"
                    ></div>

                    <div
                        id="fileSize"
                        class="text-muted small mt-1"
                    ></div>

                    <div
                        id="fileType"
                        class="text-muted small"
                    ></div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STORAGE INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <div class="fw-semibold mb-1">
                File Storage
            </div>

            <div class="small text-muted">

                This document will be securely associated with
                meeting

                <strong>
                    {{ $meeting->meeting_number }}
                </strong>

                and stored in the project's public document
                storage.

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.documents.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Upload Document
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- FILE PREVIEW --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const fileInput =
            document.getElementById('file');

        const preview =
            document.getElementById('filePreview');

        const fileName =
            document.getElementById('fileName');

        const fileSize =
            document.getElementById('fileSize');

        const fileType =
            document.getElementById('fileType');


        if (!fileInput) {
            return;
        }


        fileInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files &&
                    this.files.length
                        ? this.files[0]
                        : null;


                if (!file) {

                    preview.classList.add(
                        'd-none'
                    );

                    return;
                }


                preview.classList.remove(
                    'd-none'
                );


                fileName.textContent =
                    file.name;


                fileType.textContent =
                    'Type: ' +
                    (
                        file.type ||
                        'Unknown'
                    );


                let size =
                    file.size;


                if (size >= 1024 * 1024 * 1024) {

                    size =
                        (
                            size /
                            (
                                1024 *
                                1024 *
                                1024
                            )
                        ).toFixed(2)
                        + ' GB';

                } else if (
                    size >=
                    1024 * 1024
                ) {

                    size =
                        (
                            size /
                            (
                                1024 *
                                1024
                            )
                        ).toFixed(2)
                        + ' MB';

                } else if (
                    size >=
                    1024
                ) {

                    size =
                        (
                            size /
                            1024
                        ).toFixed(2)
                        + ' KB';

                } else {

                    size =
                        size + ' B';

                }


                fileSize.textContent =
                    'Size: ' + size;

            }
        );

    }
);

</script>

@endsection