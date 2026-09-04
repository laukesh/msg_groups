@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Meeting:
                <strong>
                    {{ $meeting->meeting_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                Upload Document
            </h3>

            <div class="text-muted">
                {{ $meeting->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.safety-meetings.documents.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
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


    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Meeting Number
                    </div>

                    <strong>
                        {{ $meeting->meeting_number }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Meeting Date
                    </div>

                    <strong>

                        {{ $meeting->meeting_date
                            ? $meeting->meeting_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Meeting Type
                    </div>

                    <strong>
                        {{ $meeting->meeting_type }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    @include(
        'construction.hse.safety-meeting-documents._form',
        [
            'formAction' => route(
                'admin.projects.construction.hse.safety-meetings.documents.store',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            ),

            'formMethod' => null,

            'document' => null,

            'documentNumber' => $documentNumber,

            'project' => $project,

            'meeting' => $meeting,

            'cancelUrl' => route(
                'admin.projects.construction.hse.safety-meetings.documents.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            ),

            'submitLabel' => 'Upload Document',
        ]
    )

</div>

@endsection