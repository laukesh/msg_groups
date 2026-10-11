@extends('layouts.app')

@section('title', 'Create Asset Type')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">
            Create Asset Type
        </h4>

        <a
            href="{{ route('admin.assets.types.index') }}"
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
                action="{{ route('admin.assets.types.store') }}"
            >

                @include(
                    'admin.assets.classification.asset-types._form',
                    [
                        'routePrefix' => 'admin.assets.types'
                    ]
                )

            </form>

        </div>

    </div>

</div>

@endsection