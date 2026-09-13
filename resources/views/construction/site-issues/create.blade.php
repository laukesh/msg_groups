@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Create Site Issue / RFI
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.site-issues.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
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


    @php
        $issue = null;
    @endphp


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.site-issues.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">
                <strong>Issue / RFI Details</strong>
            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <strong>
                        Issue Number
                    </strong>

                    <div class="small mt-1">
                        The Issue / RFI number will be generated
                        automatically after saving.
                    </div>

                </div>


                @include(
                    'construction.site-issues._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.site-issues.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Issue / RFI
            </button>

        </div>

    </form>

</div>

@endsection