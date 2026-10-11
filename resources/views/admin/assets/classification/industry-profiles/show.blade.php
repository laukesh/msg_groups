@extends('layouts.app')

@section('title', 'Industry Profile Details')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>

            <h4 class="mb-1">
                <i class="ri-information-line me-2"></i>
                Industry Profile Details
            </h4>

            <p class="text-muted mb-0">
                View industry profile classification details.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.industry-profiles.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

            @can('industry_profiles.edit')

                <a
                    href="{{ route('admin.assets.industry-profiles.edit', $industryProfile->id) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endcan

        </div>

    </div>


    <div class="row g-4">

        {{-- Details --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Industry Profile Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Industry Profile
                            </div>

                            <div class="fw-semibold fs-5">
                                {{ $industryProfile->name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Code
                            </div>

                            @if($industryProfile->code)

                                <span class="badge bg-light text-dark border">
                                    {{ $industryProfile->code }}
                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            @if($industryProfile->status === 'Active')

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Sort Order
                            </div>

                            <div class="fw-semibold">
                                {{ $industryProfile->sort_order ?? 0 }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            @if($industryProfile->description)

                                <div>
                                    {!! nl2br(e($industryProfile->description)) !!}
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


        {{-- Statistics / Audit --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Asset Usage
                    </h5>

                </div>

                <div class="card-body text-center">

                    <div class="display-5 fw-bold text-primary">
                        {{ $industryProfile->assets_count ?? $industryProfile->assets()->count() }}
                    </div>

                    <div class="text-muted">
                        Assets using this profile
                    </div>

                </div>

            </div>


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
                            {{ $industryProfile->creator->name ?? 'System' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>
                            {{ $industryProfile->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ $industryProfile->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection