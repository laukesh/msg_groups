@extends('layouts.app')

@section('title', 'Create Department')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="fas fa-sitemap me-2"></i>

                Create Department

            </h4>

            <div class="text-muted">
                Add a new department.
            </div>

        </div>

        <a
            href="{{ route('admin.assets.departments.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <div class="fw-semibold mb-2">

                <i class="fas fa-exclamation-triangle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-info-circle me-2"></i>

                Department Information

            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.departments.store') }}"
            >

                @csrf

                @include(
                    'admin.assets.departments._form'
                )

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a
                        href="{{ route(
                            'admin.assets.departments.index'
                        ) }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="fas fa-times me-1"></i>

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Create Department

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection