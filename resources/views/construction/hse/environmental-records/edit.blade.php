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
                Edit Environmental Record
            </h3>

            <div class="text-muted">

                Record:
                <strong>
                    {{ $record->record_number }}
                </strong>

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.environmental.records.show',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Record
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


    @include(
        'construction.hse.environmental-records._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.environmental.records.update',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            ),

            'formMethod' => 'PUT',

            'record' => $record,

            'recordNumber' => $record->record_number,

            'project' => $project,

            'users' => $users,

            'cancelUrl' => route(
                'admin.projects.construction.hse.environmental.records.show',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            ),

            'submitLabel' => 'Update Environmental Record',
        ]
    )

</div>

@endsection