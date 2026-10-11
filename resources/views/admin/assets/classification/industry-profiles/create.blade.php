@extends('layouts.app')

@section('title', 'Create Industry Profile')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>
            <h4 class="mb-1">
                <i class="ri-add-circle-line me-2"></i>
                Create Industry Profile
            </h4>

            <p class="text-muted mb-0">
                Add a new industry profile.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.industry-profiles.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Back
        </a>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Industry Profile Information
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.industry-profiles.store') }}"
            >

                @csrf

                @include(
                    'admin.assets.classification.industry-profiles._form'
                )

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.assets.industry-profiles.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="ri-save-line me-1"></i>
                        Save Industry Profile
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection