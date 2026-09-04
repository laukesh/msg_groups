@extends('layouts.app')

@section('title', 'Edit Unit Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-file-alt me-2"></i>
                Edit Unit Document
            </h4>

            <div class="text-muted">
                Update unit document information.
            </div>
        </div>

        <a
            href="{{ route('admin.assets.unit-documents.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Document Information
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.unit-documents.update',
                    $document->id
                ) }}"
            >

                @csrf
                @method('PUT')

                @include('admin.assets.unit_documents._form')

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a
                        href="{{ route(
                            'admin.assets.unit-documents.show',
                            $document->id
                        ) }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i>
                        Update Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection