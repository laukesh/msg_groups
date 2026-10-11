@extends('layouts.app')

@section('title', 'Edit Commissioning Test')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Commissioning Test
            </h4>

            <div class="text-muted">
                {{ $test->test_no }}
                —
                {{ $project->project_code ?? 'Project' }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.commissioning.tests.show', [$project, $test]) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

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


    @if($test->status === 'Completed')

        <div class="alert alert-warning">
            <i class="bi bi-lock me-1"></i>
            Completed tests are locked and cannot be edited.
        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.projects.commissioning.tests.update', [$project, $test]) }}"
    >

        @csrf

        @method('PUT')

        @include(
            'admin.commissioning.tests._form',
            [
                'test' => $test,
                'parentTest' => null,
                'isRetest' => false,
            ]
        )


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.projects.commissioning.tests.show', [$project, $test]) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>
                Update Test
            </button>

        </div>

    </form>

</div>

@endsection