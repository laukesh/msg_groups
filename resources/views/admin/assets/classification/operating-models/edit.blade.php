@extends('layouts.app')

@section('title', 'Edit Operating Model')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>

            <h4 class="mb-1">
                <i class="ri-edit-line me-2"></i>
                Edit Operating Model
            </h4>

            <p class="text-muted mb-0">
                Update operating model information.
            </p>

        </div>


        <a
            href="{{ route('admin.assets.operating-models.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Back
        </a>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Edit: {{ $operatingModel->name }}
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.operating-models.update', $operatingModel->id) }}"
            >

                @csrf

                @method('PUT')


                @include(
                    'admin.assets.classification.operating-models._form'
                )


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.assets.operating-models.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="ri-save-line me-1"></i>
                        Update Operating Model
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection