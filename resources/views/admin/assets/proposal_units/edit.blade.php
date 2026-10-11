@extends('layouts.app')

@section('title', 'Edit Proposal Unit')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Edit Proposal Unit
            </h1>

            <p class="text-muted mb-0">
                Update proposal unit details.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.proposal_units.index') }}"
            class="btn btn-secondary"
        >
            ← Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form Card --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                Proposal Unit Information
            </h5>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.assets.proposal_units.update',
                    $item->id
                ) }}"
            >

                @csrf
                @method('PUT')

                @include('admin.assets.proposal_units._form')

                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('admin.assets.proposal_units.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Proposal Unit
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection