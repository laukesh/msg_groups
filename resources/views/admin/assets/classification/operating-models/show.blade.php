@extends('layouts.app')

@section('title', 'Operating Model Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>

            <h4 class="mb-1">
                <i class="ri-information-line me-2"></i>
                Operating Model Details
            </h4>

            <p class="text-muted mb-0">
                View operating model classification details.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.operating-models.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>


            @can('operating_models.edit')

                <a
                    href="{{ route('admin.assets.operating-models.edit', $operatingModel->id) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endcan

        </div>

    </div>


    <div class="row g-4">

        {{-- Main Information --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Operating Model Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Operating Model
                            </div>

                            <div class="fw-semibold fs-5">
                                {{ $operatingModel->name }}
                            </div>

                        </div>


                        {{-- Code --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code
                            </div>

                            @if($operatingModel->code)

                                <span class="badge bg-light text-dark border">
                                    {{ $operatingModel->code }}
                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Status
                            </div>


                            @if($operatingModel->status === 'Active')

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </div>


                        {{-- Sort Order --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Sort Order
                            </div>

                            <div class="fw-semibold">
                                {{ $operatingModel->sort_order ?? 0 }}
                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>


                            @if($operatingModel->description)

                                <div>
                                    {!! nl2br(e($operatingModel->description)) !!}
                                </div>

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


        {{-- Right Side --}}
        <div class="col-lg-4">

            {{-- Asset Usage --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Asset Usage
                    </h5>

                </div>


                <div class="card-body text-center">

                    <div class="display-5 fw-bold text-primary">

                        {{
                            $operatingModel->assets_count
                            ?? $operatingModel->assets()->count()
                        }}

                    </div>

                    <div class="text-muted">
                        Assets using this operating model
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
                            {{ $operatingModel->creator->name ?? 'System' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>
                            {{ $operatingModel->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ $operatingModel->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection