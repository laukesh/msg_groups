@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Action:
                <strong>
                    {{ $action->action_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Edit Corrective Action
            </h3>

            <div class="text-muted">
                Finding:
                {{ $finding->finding_number }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.findings.actions.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                    'action' => $action,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Action
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


    @if($action->status === 'Closed')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This corrective action is already
            <strong>Closed</strong>.

        </div>

    @endif


    @include(
        'construction.hse.inspection-actions._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.inspections.findings.actions.update',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                    'action' => $action,
                ]
            ),

            'formMethod' => 'PUT',

            'actionModel' => $action,

            'actionNumber' => $action->action_number,

            'users' => $users,

            'project' => $project,

            'inspection' => $inspection,

            'finding' => $finding,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.findings.actions.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'finding' => $finding,
                    'action' => $action,
                ]
            ),

            'submitLabel' => 'Update Corrective Action',
        ]
    )

</div>

@endsection