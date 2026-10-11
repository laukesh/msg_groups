@extends('layouts.app')

@section('title', 'Add Revenue Status')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="ri-add-circle-line me-1"></i>
                Add Revenue Status
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.assets.revenue-statuses.store') }}"
            >

                @include(
                    'admin.assets.classification.revenue-statuses._form'
                )

            </form>

        </div>

    </div>

</div>

@endsection