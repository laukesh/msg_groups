@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Edit Feasibility Assessment
        </h3>

        <p class="text-muted">

            {{ $land->land_code }}
            -
            {{ $land->land_name }}

        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @include(
        'feasibility.assessments._form',
        [
            'action' => route(
                'admin.land.lands.feasibility-assessments.update',
                [
                    $land,
                    $feasibilityAssessment
                ]
            ),
            'method' => 'PUT',
            'feasibilityAssessment' => $feasibilityAssessment
        ]
    )

</div>

@endsection