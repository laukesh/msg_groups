@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Edit Legal Due Diligence
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
        'land-acquisition.legal-due-diligences._form',
        [
            'action' => route(
                'admin.land.lands.legal-due-diligences.update',
                [
                    'land' => $land,
                    'legal_due_diligence' => $dueDiligence
                ]
            ),
            'method' => 'PUT',
            'dueDiligence' => $dueDiligence
        ]
    )

</div>

@endsection