@extends('layouts.app')

@section('title', 'Revenue Status Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="mb-1">

                <i class="ri-money-dollar-circle-line me-1"></i>

                {{ $revenueStatus->name }}

            </h4>

            <p class="text-muted mb-0">
                Revenue status classification details
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.revenue-statuses.index') }}"
                class="btn btn-light border"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

            @can('revenue_statuses.edit')

                <a
                    href="{{ route('admin.assets.revenue-statuses.edit', $revenueStatus->id) }}"
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
                        Revenue Status Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Revenue Status Name
                            </small>

                            <strong>
                                {{ $revenueStatus->name }}
                            </strong>

                        </div>

                        {{-- Code --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Code
                            </small>

                            <strong>
                                {{ $revenueStatus->code ?: '—' }}
                            </strong>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @if($revenueStatus->status === 'Active')

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

                            <small class="text-muted d-block">
                                Sort Order
                            </small>

                            <strong>
                                {{ $revenueStatus->sort_order }}
                            </strong>

                        </div>

                        {{-- Description --}}
                        <div class="col-12">

                            <small class="text-muted d-block">
                                Description
                            </small>

                            <div class="mt-1">
                                {{ $revenueStatus->description ?: 'No description available.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="col-lg-4">

            {{-- Asset Usage --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="ri-building-line me-1"></i>

                        Asset Usage

                    </h5>

                </div>

                <div class="card-body text-center">

                    <div class="display-5 fw-bold">
                        {{ $revenueStatus->assets_count }}
                    </div>

                    <div class="text-muted">
                        Assets using this revenue status
                    </div>

                </div>

            </div>

            {{-- Audit Information --}}
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
                            {{ $revenueStatus->creator->name ?? 'System' }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created At
                        </small>

                        <strong>
                            {{ optional($revenueStatus->created_at)->format('d M Y, h:i A') }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Updated By
                        </small>

                        <strong>
                            {{ $revenueStatus->updater->name ?? 'System' }}
                        </strong>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        <strong>
                            {{ optional($revenueStatus->updated_at)->format('d M Y, h:i A') }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection