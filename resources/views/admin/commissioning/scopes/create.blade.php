@extends('layouts.app')

@section('title', 'Add Commissioning Scope')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Commissioning Scope
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? 'Project' }}
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
                Commissioning scope is created against an existing Construction Work Order.
            </div>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.commissioning.scopes.store',
                    ['project' => $project->id]
                ) }}"
            >

                @include('admin.commissioning.scopes._form')

                <div class="d-flex gap-2 mt-4 pt-3 border-top">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Save Scope
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