@extends('layouts.app')

@section('title', 'Edit Unit Type')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                Edit Unit Type
            </h4>

            <div class="text-muted">
                Update unit type information.
            </div>
        </div>

        <a
            href="{{ route('admin.assets.unit-types.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
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
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        UNIT TYPE FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Unit Type Information
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.unit-types.update',
                    $unitType->id
                ) }}"
            >

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- =================================================
                        TYPE NAME
                    ================================================== --}}
                    <div class="col-lg-6 col-md-6 mb-3">

                        <label
                            for="type_name"
                            class="form-label fw-semibold"
                        >
                            Type Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="type_name"
                            id="type_name"
                            class="form-control @error('type_name') is-invalid @enderror"
                            value="{{ old(
                                'type_name',
                                $unitType->type_name
                            ) }}"
                            placeholder="e.g. Retail Shop"
                            maxlength="150"
                            required
                        >

                        @error('type_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-lg-6 col-md-6 mb-3">

                        <label
                            for="status"
                            class="form-label fw-semibold"
                        >
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Status
                            </option>

                            <option
                                value="1"
                                {{ old(
                                    'status',
                                    (string) $unitType->status
                                ) === '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old(
                                    'status',
                                    (string) $unitType->status
                                ) === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================== --}}
                    <div class="col-12 mb-3">

                        <label
                            for="description"
                            class="form-label fw-semibold"
                        >
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter unit type description..."
                            maxlength="1000"
                        >{{ old(
                            'description',
                            $unitType->description
                        ) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a
                        href="{{ route(
                            'admin.assets.unit-types.show',
                            $unitType->id
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
                        Update Unit Type
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection