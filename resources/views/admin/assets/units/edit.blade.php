@extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-store me-2"></i>
                Edit Unit
            </h4>

            <div class="text-muted">
                Update the unit information and configuration.
            </div>
        </div>

        <a href="{{ route('admin.assets.units.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Units

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-1">
                <i class="fas fa-exclamation-circle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        EDIT UNIT FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex align-items-center">

                <div class="me-3">

                    <span class="bg-primary bg-opacity-10 text-primary rounded p-2">
                        <i class="fas fa-store"></i>
                    </span>

                </div>

                <div>

                    <h6 class="mb-0">
                        Unit Information
                    </h6>

                    <small class="text-muted">
                        Update location, classification, area,
                        financial and status information.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.assets.units.update', $unit->id) }}">

                @csrf
                @method('PUT')

                {{-- Unit Form Fields --}}
                @include('admin.assets.units._form')


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('admin.assets.units.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="fas fa-times me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>
                        Update Unit

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection