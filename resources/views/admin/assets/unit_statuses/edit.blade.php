@extends('layouts.app')

@section('title', 'Edit Unit Status')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-edit-2-line me-2"></i>
                Edit Unit Status
            </h4>

            <div class="text-muted">
                Update unit status information.
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.unit-statuses.show',
                    $status->id
                ) }}"
                class="btn btn-outline-info"
            >
                <i class="ri-eye-line me-1"></i>
                View
            </a>

            <a
                href="{{ route('admin.assets.unit-statuses.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-2">

                <i class="ri-error-warning-line me-1"></i>

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
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        UNIT STATUS FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="ri-information-line me-2"></i>
                Unit Status Information

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.unit-statuses.update',
                    $status->id
                ) }}"
            >

                @csrf
                @method('PUT')

                @include('admin.assets.unit_statuses._form')


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route(
                            'admin.assets.unit-statuses.show',
                            $status->id
                        ) }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="ri-close-line me-1"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="ri-save-3-line me-1"></i>
                        Update Unit Status
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection