@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Edit Environmental Assessment
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
        'land-acquisition.environmental-assessments._form',
        [
            'action' => route(
                'admin.land.lands.environmental-assessments.update',
                [
                    $land,
                    $dueDiligence
                ]
            ),
            'method' => 'PUT',
            'dueDiligence' => $dueDiligence
        ]
    )

</div>

@endsection