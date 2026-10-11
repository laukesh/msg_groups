@extends('layouts.app')

@section('title', 'Ownership Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-shield-user-line me-1"></i>
                {{ $ownership->name }}
            </h4>

            <p class="text-muted mb-0">
                Ownership classification details
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.ownerships.index') }}"
                class="btn btn-light border"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

            @can('ownerships.edit')
                <a
                    href="{{ route('admin.assets.ownerships.edit', $ownership->id) }}"
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
                        Ownership Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Ownership Name
                            </small>

                            <strong>
                                {{ $ownership->name }}
                            </strong>

                        </div>

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Code
                            </small>

                            <strong>
                                {{ $ownership->code ?: '—' }}
                            </strong>

                        </div>

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @if($ownership->status === 'Active')
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

                            <small class="text-muted d-block">
                                Sort Order
                            </small>

                            <strong>
                                {{ $ownership->sort_order }}
                            </strong>

                        </div>

                        <div class="col-12">

                            <small class="text-muted d-block">
                                Description
                            </small>

                            <div class="mt-1">
                                {{ $ownership->description ?: 'No description available.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Asset Usage --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ri-building-line me-1"></i>
                        Asset Usage
                    </h5>
                </div>

                <div class="card-body text-center">

                    <div class="display-5 fw-bold">
                        {{ $ownership->assets_count }}
                    </div>

                    <div class="text-muted">
                        Assets using this ownership
                    </div>

                </div>

            </div>

            {{-- Audit --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ri-history-line me-1"></i>
                        Audit Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created By
                        </small>

                        <strong>
                            {{ $ownership->creator->name ?? 'System' }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created At
                        </small>

                        <strong>
                            {{ optional($ownership->created_at)->format('d M Y, h:i A') }}
                        </strong>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Last Updated By
                        </small>

                        <strong>
                            {{ $ownership->updater->name ?? 'System' }}
                        </strong>

                    </div>

                    <div class="mt-3">

                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        <strong>
                            {{ optional($ownership->updated_at)->format('d M Y, h:i A') }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection