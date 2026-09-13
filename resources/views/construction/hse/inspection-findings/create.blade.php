@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Inspection:
                <strong>
                    {{ $inspection->inspection_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Add Inspection Finding
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.findings.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Findings
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
        'construction.hse.inspection-findings._form',
        [
            'action' => route(
                'admin.projects.construction.hse.inspections.findings.store',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'method' => null,

            'finding' => null,

            'findingNumber' => $findingNumber,

            'items' => $items,

            'users' => $users,

            'project' => $project,

            'inspection' => $inspection,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.findings.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'submitLabel' => 'Create Finding',
        ]
    )

</div>

@endsection