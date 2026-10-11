@extends('layouts.app')

@section('title', $category->category_name)

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-tags me-2"></i>
                {{ $category->category_name }}
            </h4>

            <div class="text-muted">
                Asset Category #{{ $category->id }}
            </div>
        </div>

        <div>

            @can('asset_categories.edit')

                <a
                    href="{{ route(
                        'admin.assets.asset-categories.edit',
                        $category->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>

            @endcan

            <a
                href="{{ route('admin.assets.asset-categories.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Category Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <strong>ID</strong>

                    <div>
                        {{ $category->id }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Category Name</strong>

                    <div>
                        {{ $category->category_name }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Status</strong>

                    <div>

                        @if($category->is_active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

                <div class="col-12 mb-3">

                    <strong>Description</strong>

                    <div>
                        {{ $category->description ?: '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection