
@extends('layouts.app')

@section('title', 'Asset Purpose Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>

            <h4 class="mb-1">
                <i class="ri-information-line me-2"></i>
                Asset Purpose Details
            </h4>

            <p class="text-muted mb-0">
                View asset purpose classification details.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.purposes.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

            @can('asset_purposes.edit')

                <a
                    href="{{ route('admin.assets.purposes.edit', $assetPurpose->id) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endcan

        </div>

    </div>


    <div class="row g-4">

        {{-- Main Details --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Asset Purpose Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Asset Purpose
                            </label>

                            <div class="fw-semibold fs-5">
                                {{ $assetPurpose->name }}
                            </div>

                        </div>


                        {{-- Code --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Code
                            </label>

                            <div>

                                @if($assetPurpose->code)

                                    <span class="badge bg-light text-dark border">
                                        {{ $assetPurpose->code }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Status
                            </label>

                            <div class="mt-1">

                                @if($assetPurpose->status === 'Active')

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


                        {{-- Sort Order --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Sort Order
                            </label>

                            <div class="fw-semibold">
                                {{ $assetPurpose->sort_order ?? 0 }}
                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <label class="text-muted small">
                                Description
                            </label>

                            <div class="mt-1">

                                @if($assetPurpose->description)

                                    {!! nl2br(e($assetPurpose->description)) !!}

                                @else

                                    <span class="text-muted">
                                        No description available.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Statistics --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Asset Usage
                    </h5>

                </div>

                <div class="card-body text-center">

                    <div class="display-5 fw-bold text-primary">
                        {{ $assetPurpose->assets_count ?? $assetPurpose->assets()->count() }}
                    </div>

                    <div class="text-muted">
                        Assets using this purpose
                    </div>

                </div>

            </div>


            {{-- Audit Information --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Audit Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div class="fw-semibold">
                            {{ $assetPurpose->creator->name ?? 'System' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>
                            {{ $assetPurpose->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ $assetPurpose->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection