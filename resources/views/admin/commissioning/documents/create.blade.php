@extends('layouts.app')

@section('title', 'Upload Commissioning Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Upload Commissioning Document
            </h4>

            <div class="text-muted">
                Add documents and testing evidence to the commissioning record.
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.commissioning.documents.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <strong>Document Information</strong>
        </div>

        <div class="card-body">

            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route(
                    'admin.projects.commissioning.documents.store',
                    $project
                ) }}"
            >

                @csrf

                <div class="row g-3">

                    {{-- Document Name --}}
                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Document Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="document_name"
                            class="form-control"
                            value="{{ old('document_name') }}"
                            placeholder="Electrical Panel Test Report"
                            required
                        >

                    </div>


                    {{-- Document Type --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Document Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="document_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Type
                            </option>

                            @foreach($documentTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(old('document_type') === $type)
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Scope --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Commissioning Scope
                        </label>

                        <select
                            name="commissioning_scope_id"
                            class="form-select"
                        >

                            <option value="">
                                Project Level
                            </option>

                            @foreach($scopes as $scope)

                                <option
                                    value="{{ $scope->id }}"
                                    @selected(
                                        old('commissioning_scope_id')
                                        == $scope->id
                                    )
                                >
                                    {{ $scope->scope_code }}
                                    -
                                    {{ $scope->scope_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Test Plan --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Test Plan
                        </label>

                        <select
                            name="test_plan_id"
                            class="form-select"
                        >

                            <option value="">
                                Not Linked
                            </option>

                            @foreach($testPlans as $plan)

                                <option
                                    value="{{ $plan->id }}"
                                    @selected(
                                        old('test_plan_id')
                                        == $plan->id
                                    )
                                >
                                    {{ $plan->test_plan_no }}
                                    -
                                    {{ $plan->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Test --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Test Execution
                        </label>

                        <select
                            name="test_id"
                            class="form-select"
                        >

                            <option value="">
                                Not Linked
                            </option>

                            @foreach($tests as $test)

                                <option
                                    value="{{ $test->id }}"
                                    @selected(
                                        old('test_id')
                                        == $test->id
                                    )
                                >
                                    {{ $test->test_no }}
                                    -
                                    {{ $test->test_type ?? 'Test' }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Certificate --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Certificate
                        </label>

                        <select
                            name="certificate_id"
                            class="form-select"
                        >

                            <option value="">
                                Not Linked
                            </option>

                            @foreach($certificates as $certificate)

                                <option
                                    value="{{ $certificate->id }}"
                                    @selected(
                                        old('certificate_id')
                                        == $certificate->id
                                    )
                                >
                                    {{ $certificate->certificate_no }}
                                    -
                                    {{ $certificate->certificate_type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Version --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Document Version
                        </label>

                        <input
                            type="text"
                            name="document_version"
                            class="form-control"
                            value="{{ old(
                                'document_version',
                                '1.0'
                            ) }}"
                            placeholder="1.0"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Approved',
                                'Rejected',
                                'Archived'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Draft'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- File --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="file"
                            class="form-control"
                            required
                        >

                        <div class="form-text">
                            Maximum file size: 50 MB
                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Describe the document or evidence..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route(
                            'admin.projects.commissioning.documents.index',
                            $project
                        ) }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-cloud-upload me-1"></i>
                        Upload Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection