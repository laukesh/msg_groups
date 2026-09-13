@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ============================================================
         HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Project:

                <strong>
                    {{ $project->project_code ?? $project->name ?? '—' }}
                </strong>

            </div>

            <h3 class="mb-1">
                Add Environmental Action
            </h3>

            <div class="text-muted">
                Create a corrective, preventive or environmental
                compliance action.
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.environmental.actions.index',
                ['project' => $project]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Actions
        </a>

    </div>


    {{-- ============================================================
         VALIDATION ERRORS
    ============================================================= --}}

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


    {{-- ============================================================
         SOURCE INFORMATION
    ============================================================= --}}

    @if($selectedRecordId || $selectedComplianceId)

        <div class="alert alert-info">

            <div class="d-flex align-items-start">

                <i class="bi bi-link-45deg fs-4 me-2"></i>

                <div>

                    <strong>
                        Source linked
                    </strong>

                    @if($selectedRecordId)

                        <div class="small mt-1">
                            This action will be linked to the selected
                            Environmental Record.
                        </div>

                    @elseif($selectedComplianceId)

                        <div class="small mt-1">
                            This action will be linked to the selected
                            Environmental Compliance.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         ACTION NUMBER
    ============================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <strong>
                        {{ $project->project_code ?? $project->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Action Number
                    </div>

                    <strong>
                        {{ $actionNumber }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Source
                    </div>

                    @if($selectedRecordId)

                        <span class="badge bg-info text-dark">
                            Environmental Record
                        </span>

                    @elseif($selectedComplianceId)

                        <span class="badge bg-secondary">
                            Environmental Compliance
                        </span>

                    @else

                        <span class="badge bg-light text-dark">
                            Not Selected
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FORM
    ============================================================= --}}

    @include(
        'construction.hse.environmental-actions._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.environmental.actions.store',
                [
                    'project' => $project,
                ]
            ),

            'formMethod' => null,

            'action' => null,

            'actionNumber' => $actionNumber,

            'project' => $project,

            'users' => $users,

            'records' => $records,

            'compliances' => $compliances,

            'selectedRecordId' => $selectedRecordId,

            'selectedComplianceId' => $selectedComplianceId,

            'cancelUrl' => route(
                'admin.projects.construction.hse.environmental.actions.index',
                [
                    'project' => $project,
                ]
            ),

            'submitLabel' => 'Create Action',
        ]
    )

</div>

@endsection