@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Industry Operating Profile
            </h4>

            <p class="text-muted mb-0">
                Update industry operating rules and targets.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- VIEW PROFILE --}}
            @if($profile->getKey())

                @can('industry_profiles.view')

                    <a href="{{ route(
                        'admin.assets.operations.industry-profiles.show',
                        ['industry_profile' => $profile->getKey()]
                    ) }}"
                       class="btn btn-outline-info">

                        <i class="ri-eye-line me-1"></i>
                        View

                    </a>

                @endcan

            @endif


            {{-- BACK --}}
            <a href="{{ route(
                'admin.assets.operations.industry-profiles.index'
            ) }}"
               class="btn btn-light border">

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

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
        EDIT FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route(
              'admin.assets.operations.industry-profiles.update',
              ['industry_profile' => $profile->getKey()]
          ) }}">

        @csrf

        @method('PUT')


        {{-- FORM FIELDS --}}
        @include(
            'admin.assets.operations.industry-profiles.partials.form',
            ['profile' => $profile]
        )


        {{-- =====================================================
            FORM ACTIONS
        ====================================================== --}}
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-4">

            {{-- CANCEL --}}
            <a href="{{ route(
                'admin.assets.operations.industry-profiles.show',
                ['industry_profile' => $profile->getKey()]
            ) }}"
               class="btn btn-light border">

                <i class="ri-close-line me-1"></i>
                Cancel

            </a>


            {{-- UPDATE --}}
            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Update Profile

            </button>

        </div>

    </form>

</div>

@endsection