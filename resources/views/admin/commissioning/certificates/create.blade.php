@extends('layouts.app')

@section('title', 'New Commissioning Certificate')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                New Commissioning Certificate
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? '' }}
            </div>
        </div>

        <a
            href="{{ route('admin.projects.commissioning.certificates.index', $project) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.projects.commissioning.certificates.store', $project) }}"
    >

        @include(
            'admin.commissioning.certificates._form',
            ['certificate' => null]
        )

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.projects.commissioning.certificates.index', $project) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>
                Save Certificate
            </button>

        </div>

    </form>

</div>

@endsection