@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small">
                {{ $siteInstruction->instruction_number }}
            </div>

            <h4 class="mb-1">
                Edit Site Instruction
            </h4>

            <div class="text-muted">
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.site-instructions.show',
                    [
                        'project' =>
                            $project,

                        'siteInstruction' =>
                            $siteInstruction,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

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
            'admin.projects.construction.site-instructions.update',
            [
                'project' =>
                    $project,

                'siteInstruction' =>
                    $siteInstruction,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


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
                        'admin.projects.construction.site-instructions.show',
                        [
                            'project' =>
                                $project,

                            'siteInstruction' =>
                                $siteInstruction,
                        ]
                    ) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Site Instruction
                </button>

            </div>

        </div>

    </form>

</div>

@endsection