@extends('layouts.app')

@section('title', 'Add Ownership')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="ri-add-circle-line me-1"></i>
                Add Ownership
            </h5>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.ownerships.store') }}"
            >

                @include(
                    'admin.assets.classification.ownerships._form'
                )

            </form>

        </div>

    </div>

</div>

@endsection