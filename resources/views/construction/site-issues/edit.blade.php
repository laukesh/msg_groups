@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Edit Site Issue / RFI
            </h4>

            <div class="text-muted">
                {{ $issue->issue_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.site-issues.show',
                    [
                        'project' => $project,
                        'issue' => $issue,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View
            </a>


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
        action="{{ route(
            'admin.projects.construction.site-issues.update',
            [
                'project' => $project,
                'issue' => $issue,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Issue / RFI Details
                </strong>

                <span class="float-end badge bg-secondary">
                    {{ $issue->issue_number }}
                </span>

            </div>


            <div class="card-body">

                @include(
                    'construction.site-issues._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.site-issues.show',
                    [
                        'project' => $project,
                        'issue' => $issue,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Issue / RFI
            </button>

        </div>

    </form>

</div>

@endsection