@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Finding:
                <strong>
                    {{ $finding->finding_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Add Corrective Action
            </h3>

            <div class="text-muted">
                {{ $finding->finding_title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.findings.actions.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Actions
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


    @include(
        'construction.hse.inspection-actions._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.inspections.findings.actions.store',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            ),

            'formMethod' => null,

            'actionModel' => null,

            'actionNumber' => $actionNumber,

            'users' => $users,

            'project' => $project,

            'inspection' => $inspection,

            'finding' => $finding,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.findings.actions.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                ]
            ),

            'submitLabel' => 'Create Corrective Action',
        ]
    )

</div>

@endsection