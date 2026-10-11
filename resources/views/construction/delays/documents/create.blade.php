@extends('layouts.app')

@section('title', 'Upload Delay Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Upload Delay Document
            </h4>

            <div class="text-muted">

                {{ $delay->delay_number }}
                -
                {{ $delay->delay_title }}

            </div>

        </div>

        <a href="{{ route(
            'admin.projects.construction.delays.documents.index',
            [$project, $delay]
        ) }}"
           class="btn btn-outline-secondary">

            Back

        </a>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.delays.documents.store',
              [$project, $delay]
          ) }}"
          enctype="multipart/form-data">

        @csrf


        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
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

                        <select name="document_type"
                                class="form-select"
                                required>

                            <option value="">
                                Select Document Type
                            </option>

                            @foreach([
                                'Delay Notice',
                                'Schedule',
                                'Progress Report',
                                'Site Report',
                                'Correspondence',
                                'Drawing',
                                'RFI',
                                'Meeting Minutes',
                                'Site Photo',
                                'Engineer Report',
                                'Supporting Evidence',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(old('document_type') === $type)>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Document Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="document_title"
                               class="form-control"
                               value="{{ old('document_title') }}"
                               required>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                               name="document"
                               class="form-control"
                               required>

                        <div class="form-text">

                            Maximum file size: 50 MB.
                            Supported formats: PDF, Word, Excel,
                            JPG, PNG, WEBP and TXT.

                        </div>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control">{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="card-footer bg-white d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.projects.construction.delays.documents.index',
                    [$project, $delay]
                ) }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-upload"></i>
                    Upload Document

                </button>

            </div>

        </div>

    </form>

</div>

@endsection