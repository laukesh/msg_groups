@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Site Instruction
            </h4>

            <div class="text-muted">
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.site-instructions.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.site-instructions.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Site Instruction Details
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'construction.site-instructions._form'
                )

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.projects.construction.site-instructions.index',
                        $project
                    ) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Site Instruction
                </button>

            </div>

        </div>

    </form>

</div>

@endsection