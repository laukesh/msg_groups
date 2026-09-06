@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Consultant
            </h4>

            <div class="text-muted">

                {{ $consultant->consultant_code ?? 'Consultant' }}

                @if($consultant->company_name)
                    — {{ $consultant->company_name }}
                @endif

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.design-management.consultants.show',
                [$project, $consultant]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @include('design-management.partials.alerts')


    <form
        method="POST"
        action="{{ route(
            'admin.projects.design-management.consultants.update',
            [$project, $consultant]
        ) }}"
    >

        @csrf

        @method('PUT')


        @include(
            'design-management.consultants._form'
        )


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.design-management.consultants.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-check-lg me-1"></i>

                Update Consultant

            </button>

        </div>

    </form>

</div>

@endsection