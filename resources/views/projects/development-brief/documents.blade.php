@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Supporting Documents</h4>

            <p class="text-muted mb-0">
                Development Brief:
                {{ $developmentBrief->brief_reference ?? 'N/A' }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.development-brief.show',
                    [
                        'project' => $projectId,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fa fa-arrow-left"></i>
                Back to Brief
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Upload Form --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>
                <i class="fa fa-upload"></i>
                Upload Supporting Document
            </strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route(
                    'admin.projects.development-brief.documents.store',
                    [
                        'project' => $projectId,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                ) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Document Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="document_title"
                            class="form-control @error('document_title') is-invalid @enderror"
                            value="{{ old('document_title') }}"
                            required
                        >

                        @error('document_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Document Type
                        </label>

                        <select
                            name="document_type"
                            class="form-select @error('document_type') is-invalid @enderror"
                        >
                            <option value="">Select Document Type</option>

                            <option value="Land Ownership Document">
                                Land Ownership Document
                            </option>

                            <option value="Market Study">
                                Market Study
                            </option>

                            <option value="Site Survey">
                                Site Survey
                            </option>

                            <option value="Feasibility Report">
                                Feasibility Report
                            </option>

                            <option value="Planning Approval">
                                Planning Approval
                            </option>

                            <option value="Technical Report">
                                Technical Report
                            </option>

                            <option value="Environmental Report">
                                Environmental Report
                            </option>

                            <option value="Other">
                                Other
                            </option>
                        </select>

                        @error('document_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Document Date
                        </label>

                        <input
                            type="date"
                            name="document_date"
                            class="form-control @error('document_date') is-invalid @enderror"
                            value="{{ old('document_date') }}"
                        >

                        @error('document_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Upload File <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="document"
                            class="form-control @error('document') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            required
                        >

                        <small class="text-muted">
                            Allowed: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
                            Maximum size: 10 MB.
                        </small>

                        @error('document')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i>
                            Upload Document
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Documents Listing --}}
    <div class="card">

        <div class="card-header">
            <strong>
                Uploaded Documents
            </strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Title</th>
                            <th>Document Type</th>
                            <th>Document Date</th>
                            <th>File</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($documents as $document)

                            <tr>

                                <td>
                                    {{ $documents->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $document->document_title }}
                                    </strong>

                                    @if($document->description)
                                        <br>
                                        <small class="text-muted">
                                            {{ $document->description }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $document->document_type ?? '-' }}
                                </td>

                                <td>
                                    {{ $document->document_date
                                        ? $document->document_date->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $document->file_name }}

                                    @if($document->file_size)
                                        <br>
                                        <small class="text-muted">
                                            {{ number_format($document->file_size / 1024, 2) }}
                                            KB
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $document->uploader->name ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($document->created_at)->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        {{-- Download --}}
                                        <a
                                            href="{{ route(
                                                'admin.projects.development-brief.documents.download',
                                                [
                                                    'project' => $projectId,
                                                    'developmentBrief' => $developmentBrief->id,
                                                    'document' => $document->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-success"
                                            title="Download"
                                        >
                                            <i class="fa fa-download"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form
                                            action="{{ route(
                                                'admin.projects.development-brief.documents.destroy',
                                                [
                                                    'project' => $projectId,
                                                    'developmentBrief' => $developmentBrief->id,
                                                    'document' => $document->id,
                                                ]
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this document?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No supporting documents uploaded yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($documents->hasPages())
            <div class="card-footer">
                {{ $documents->links() }}
            </div>
        @endif

    </div>

</div>

@endsection