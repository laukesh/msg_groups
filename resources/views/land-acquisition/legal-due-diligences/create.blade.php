@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Add Legal Due Diligence
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>

        <a
            href="{{ route(
                'admin.land.lands.legal-due-diligences.index',
                $land
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back to Legal Due Diligence
        </a>

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
                'admin.land.lands.legal-due-diligences.store',
                $land
            ),

            'method' => null,

            'dueDiligence' => null
        ]
    )

</div>

@endsection