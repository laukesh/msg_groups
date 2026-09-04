@extends('layouts.app')

@section('title', $unitType->type_name)

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                {{ $unitType->type_name }}
            </h1>

            <p class="text-muted mb-0">
                Unit Type #{{ $unitType->id }}
            </p>
        </div>

        <div class="d-flex gap-2">

            @can('unit_types.edit')

                <a
                    href="{{ route(
                        'admin.assets.unit-types.edit',
                        $unitType->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>

            @endcan

            <a
                href="{{ route('admin.assets.unit-types.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- Unit Type Information --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-tags me-1"></i>
                Unit Type Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- ID --}}
                <div class="col-md-4 mb-4">

                    <strong class="d-block text-muted mb-1">
                        ID
                    </strong>

                    <div>
                        {{ $unitType->id }}
                    </div>

                </div>


                {{-- Type Name --}}
                <div class="col-md-4 mb-4">

                    <strong class="d-block text-muted mb-1">
                        Type Name
                    </strong>

                    <div>
                        {{ $unitType->type_name }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4 mb-4">

                    <strong class="d-block text-muted mb-1">
                        Status
                    </strong>

                    <div>

                        @if((string) $unitType->status === '1')

                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Description --}}
                <div class="col-md-12">

                    <strong class="d-block text-muted mb-1">
                        Description
                    </strong>

                    <div>
                        {{ $unitType->description ?: '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-history me-1"></i>
                Audit Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Created By --}}
                <div class="col-md-3 mb-3">

                    <strong class="d-block text-muted mb-1">
                        Created By
                    </strong>

                    <div>
                        {{ $unitType->creator?->name ?? '-' }}
                    </div>

                </div>


                {{-- Updated By --}}
                <div class="col-md-3 mb-3">

                    <strong class="d-block text-muted mb-1">
                        Updated By
                    </strong>

                    <div>
                        {{ $unitType->updater?->name ?? '-' }}
                    </div>

                </div>


                {{-- Created At --}}
                <div class="col-md-3 mb-3">

                    <strong class="d-block text-muted mb-1">
                        Created At
                    </strong>

                    <div>
                        {{ $unitType->created_at?->format('d M Y H:i') ?? '-' }}
                    </div>

                </div>


                {{-- Updated At --}}
                <div class="col-md-3 mb-3">

                    <strong class="d-block text-muted mb-1">
                        Updated At
                    </strong>

                    <div>
                        {{ $unitType->updated_at?->format('d M Y H:i') ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection