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
                Edit Environmental Compliance
            </h3>

            <div class="text-muted">
                Compliance:
                <strong>
                    {{ $compliance->compliance_number }}
                </strong>
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.environmental.compliances.show',
                [
                    'project' => $project,
                    'compliance' => $compliance,
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


    @include(
        'construction.hse.environmental-compliances._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.environmental.compliances.update',
                [
                    'project' => $project,
                    'compliance' => $compliance,
                ]
            ),

            'formMethod' => 'PUT',

            'compliance' => $compliance,

            'complianceNumber' =>
                $compliance->compliance_number,

            'users' => $users,

            'cancelUrl' => route(
                'admin.projects.construction.hse.environmental.compliances.show',
                [
                    'project' => $project,
                    'compliance' => $compliance,
                ]
            ),

            'submitLabel' => 'Update Compliance',
        ]
    )

</div>

@endsection