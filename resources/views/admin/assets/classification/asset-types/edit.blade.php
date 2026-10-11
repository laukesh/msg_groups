@extends('layouts.app')

@section('title', 'Edit Asset Type')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">
            Edit Asset Type
        </h4>

        <a
            href="{{ route(
                'admin.assets.types.show',
                $item->id
            ) }}"
            class="btn btn-light border"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Back
        </a>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.types.update',
                    $item->id
                ) }}"
            >

                @method('PUT')

                @include(
                    'admin.assets.classification.asset-types._form',
                    [
                        'routePrefix' => 'admin.assets.asset-types'
                    ]
                )

            </form>

        </div>

    </div>

</div>

@endsection