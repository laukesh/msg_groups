@extends('layouts.app')

@section('title', 'Edit Ownership')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-edit-line me-1"></i>
                Edit Ownership
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.ownerships.update', $ownership->id) }}"
            >

                @csrf
                @method('PUT')

                @include(
                    'admin.assets.classification.ownerships._form',
                    ['ownership' => $ownership]
                )

            </form>

        </div>

    </div>

</div>

@endsection