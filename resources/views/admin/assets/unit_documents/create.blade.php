@extends('layouts.app')

@section('title', 'Create Unit Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-file-alt me-2"></i>
                Create Unit Document
            </h4>

            <div class="text-muted">
                Add a document for a unit.
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

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Document Information
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.unit-documents.store') }}"
            >

                @csrf

                @include('admin.assets.unit_documents._form')

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a
                        href="{{ route('admin.assets.unit-documents.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i>
                        Create Document
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection