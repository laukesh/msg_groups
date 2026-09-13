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
                Upload Inspection Document
            </h3>

            <div class="text-muted">
                {{ $project->project_code ?? '—' }}
                -
                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.documents.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Documents
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    {{-- Inspection Summary --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Number
                    </div>

                    <strong>
                        {{ $inspection->inspection_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Date
                    </div>

                    <strong>

                        {{ $inspection->inspection_date
                            ? $inspection->inspection_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <strong>
                        {{ $project->project_code ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    @include(
        'construction.hse.inspection-documents._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.inspections.documents.store',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'formMethod' => null,

            'document' => null,

            'documentNumber' => $documentNumber,

            'project' => $project,

            'inspection' => $inspection,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.documents.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            ),

            'submitLabel' => 'Upload Document',
        ]
    )

</div>

@endsection