@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Create Daily Site Report
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
                'admin.projects.construction.site-reports.index',
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
        $report = null;
    @endphp


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.site-reports.store',
            $project
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Daily Site Report Details
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <strong>
                        Report Number
                    </strong>

                    <div class="small mt-1">
                        The Daily Site Report number will be
                        generated automatically after saving.
                    </div>

                </div>


                @include(
                    'construction.site-reports._form'
                )

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.construction.site-reports.index',
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
                Save Site Report
            </button>

        </div>

    </form>

</div>

@endsection