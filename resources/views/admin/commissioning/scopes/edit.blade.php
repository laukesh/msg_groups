@extends('layouts.app')

@section('title', 'Edit Commissioning Scope')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Commissioning Scope
            </h4>

            <div class="text-muted">

                {{ $scope->scope_code }}

                -

                {{ $scope->scope_name }}

            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.commissioning.scopes.index',
                ['project' => $project->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-1">
                Commissioning Scope Details
            </h5>

            <div class="text-muted small">
                Update the commissioning scope information.
            </div>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.commissioning.scopes.update',
                    [
                        'project' => $project->id,
                        'scope' => $scope->id,
                    ]
                ) }}"
            >

                @method('PUT')

                @include('admin.commissioning.scopes._form')

                <div class="d-flex gap-2 mt-4 pt-3 border-top">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Scope
                    </button>

                    <a
                        href="{{ route(
                            'admin.projects.commissioning.scopes.index',
                            ['project' => $project->id]
                        ) }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection