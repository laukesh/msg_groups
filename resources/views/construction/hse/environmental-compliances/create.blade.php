@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Add Environmental Compliance
            </h3>

            <div class="text-muted">
                Create a new environmental compliance record.
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.environmental.compliances.index',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Compliance
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <strong>
                        {{ $project->project_code ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Compliance Number
                    </div>

                    <strong>
                        {{ $complianceNumber }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    @include(
        'construction.hse.environmental-compliances._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.environmental.compliances.store',
                [
                    'project' => $project,
                ]
            ),

            'formMethod' => null,

            'compliance' => null,

            'complianceNumber' => $complianceNumber,

            'users' => $users,

            'cancelUrl' => route(
                'admin.projects.construction.hse.environmental.compliances.index',
                [
                    'project' => $project,
                ]
            ),

            'submitLabel' => 'Create Compliance',
        ]
    )

</div>

@endsection