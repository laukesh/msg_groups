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
                Edit Environmental Action
            </h3>

            <div class="text-muted">

                Action:

                <strong>
                    {{ $action->action_number }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.hse.environmental.actions.show',
                    [
                        'project' => $project,
                        'action' => $action,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Action
            </a>

        </div>

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

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Action Source
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Record --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Environmental Record
                    </div>

                    @if($action->environmentalRecord)

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.environmental.records.show',
                                [
                                    'project' => $project,
                                    'record' => $action->environmentalRecord,
                                ]
                            ) }}"
                            class="fw-semibold text-decoration-none"
                        >
                            {{ $action->environmentalRecord->record_number }}
                        </a>

                        <div class="small text-muted">

                            {{ $action->environmentalRecord->record_title }}

                        </div>

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>


                {{-- Compliance --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Environmental Compliance
                    </div>

                    @if($action->environmentalCompliance)

                        <a
                            href="{{ route(
                                'admin.projects.construction.hse.environmental.compliances.show',
                                [
                                    'project' => $project,
                                    'compliance' => $action->environmentalCompliance,
                                ]
                            ) }}"
                            class="fw-semibold text-decoration-none"
                        >
                            {{ $action->environmentalCompliance->compliance_number }}
                        </a>

                        <div class="small text-muted">

                            {{ $action->environmentalCompliance->compliance_title }}

                        </div>

                    @else

                        <span class="text-muted">
                            Not linked
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
                'admin.projects.construction.hse.environmental.actions.update',
                [
                    'project' => $project,
                    'action' => $action,
                ]
            ),

            'formMethod' => 'PUT',

            'action' => $action,

            'actionNumber' => $action->action_number,

            'project' => $project,

            'users' => $users,

            'records' => $records,

            'compliances' => $compliances,

            'selectedRecordId' =>
                $action->environmental_record_id,

            'selectedComplianceId' =>
                $action->environmental_compliance_id,

            'cancelUrl' => route(
                'admin.projects.construction.hse.environmental.actions.show',
                [
                    'project' => $project,
                    'action' => $action,
                ]
            ),

            'submitLabel' => 'Update Action',
        ]
    )

</div>

@endsection