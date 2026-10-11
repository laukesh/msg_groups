@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-building-4-line me-1"></i>
                Create Industry Operating Profile
            </h4>

            <p class="text-muted mb-0">
                Configure operating rules and targets for an industry.
            </p>

        </div>

        <a href="{{ route('admin.assets.operations.industry-profiles.index') }}"
           class="btn btn-light">

            <i class="ri-arrow-left-line me-1"></i>
            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.assets.operations.industry-profiles.store') }}">

        @csrf

        @include(
            'admin.assets.operations.industry-profiles.partials.form'
        )

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.assets.operations.industry-profiles.index') }}"
               class="btn btn-light">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Create Profile

            </button>

        </div>

    </form>

</div>

@endsection