@extends('layouts.app')

@section('title', 'Record Commissioning Test')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Record Commissioning Test
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? 'Project' }}
                -
                {{ $project->project_name ?? '' }}
            </div>
        </div>

        <a
            href="{{ route('admin.projects.commissioning.tests.index', $project) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.projects.commissioning.tests.store', $project) }}"
    >

        @include(
            'admin.commissioning.tests._form',
            [
                'test' => null,
                'parentTest' => $parentTest ?? null,
                'isRetest' => $isRetest ?? false,
            ]
        )


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.projects.commissioning.tests.index', $project) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check2-circle me-1"></i>
                Save Test
            </button>

        </div>

    </form>

</div>

@endsection