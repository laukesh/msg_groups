@extends('layouts.app')

@section('title', 'Create Unit Status')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Create Unit Status
            </h1>

            <p class="text-muted mb-0">
                Add a new unit status.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.unit-statuses.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
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


    {{-- Unit Status Form --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-1"></i>
                Unit Status Information
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.unit-statuses.store') }}"
            >

                @csrf

                @include('admin.assets.unit_statuses._form')


                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('admin.assets.unit-statuses.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i>
                        Create Unit Status
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection