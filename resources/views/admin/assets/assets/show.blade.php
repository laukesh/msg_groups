@extends('layouts.app')

@section('title', 'Asset Details')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-box me-2"></i>
                Asset Details
            </h4>

            <div class="text-muted">
                View asset information.
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.assets.index') }}"
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            @can('assets.edit')
                <a href="{{ route('admin.assets.assets.edit', $asset->id) }}"
                   class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>
            @endcan

        </div>

    </div>


    {{-- Asset Information --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Asset Information
            </h5>
        </div>


        <div class="card-body">

            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-6">

                    <dl class="row">

                        {{-- Asset Code --}}
                        <dt class="col-sm-5">
                            Asset Code
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->asset_code ?: '-' }}
                        </dd>


                        {{-- Asset Name --}}
                        <dt class="col-sm-5">
                            Asset Name
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->asset_name ?: '-' }}
                        </dd>


                        {{-- Category --}}
                        <dt class="col-sm-5">
                            Category
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->assetCategory)->category_name
                                ?? '-' }}

                        </dd>


                        {{-- Asset Type --}}
                        <dt class="col-sm-5">
                            Asset Type
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->asset_type ?: '-' }}
                        </dd>


                        {{-- Serial Number --}}
                        <dt class="col-sm-5">
                            Serial Number
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->serial_number ?: '-' }}
                        </dd>


                        {{-- Model Number --}}
                        <dt class="col-sm-5">
                            Model Number
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->model_number ?: '-' }}
                        </dd>


                        {{-- Manufacturer --}}
                        <dt class="col-sm-5">
                            Manufacturer
                        </dt>

                        <dd class="col-sm-7">
                            {{ $asset->manufacturer ?: '-' }}
                        </dd>


                        {{-- Status --}}
                        <dt class="col-sm-5">
                            Status
                        </dt>

                        <dd class="col-sm-7">

                            @if((int) $asset->status === 1)

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

                        </dd>


                        {{-- Condition --}}
                        <dt class="col-sm-5">
                            Condition
                        </dt>

                        <dd class="col-sm-7">

                            @if($asset->conditions)

                                <span class="badge bg-info text-dark">
                                    {{ $asset->conditions }}
                                </span>

                            @else
                                -
                            @endif

                        </dd>

                    </dl>

                </div>


                {{-- RIGHT COLUMN --}}
                <div class="col-lg-6">

                    <dl class="row">

                        {{-- Unit --}}
                        <dt class="col-sm-5">
                            Unit
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->unit)->unit_no
                                ?? optional($asset->unit)->name
                                ?? '-' }}

                        </dd>


                        {{-- Building --}}
                        <dt class="col-sm-5">
                            Building
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->building)->building_name
                                ?? optional($asset->building)->name
                                ?? '-' }}

                        </dd>


                        {{-- Floor --}}
                        <dt class="col-sm-5">
                            Floor
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->floor)->floor_name
                                ?? optional($asset->floor)->name
                                ?? '-' }}

                        </dd>


                        {{-- Zone --}}
                        <dt class="col-sm-5">
                            Zone
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->zone)->zone_name
                                ?? optional($asset->zone)->name
                                ?? '-' }}

                        </dd>


                        {{-- Department --}}
                        <dt class="col-sm-5">
                            Department
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->department)->name
                                ?? optional($asset->department)->department_name
                                ?? $asset->department_id
                                ?? '-' }}

                        </dd>


                        {{-- Assigned To --}}
                        <dt class="col-sm-5">
                            Assigned To
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->assignedUser)->name
                                ?? $asset->assigned_to
                                ?? '-' }}

                        </dd>


                        {{-- Vendor --}}
                        <dt class="col-sm-5">
                            Vendor
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->vendor)->name
                                ?? optional($asset->vendor)->vendor_name
                                ?? $asset->vendor_id
                                ?? '-' }}

                        </dd>


                        {{-- Location --}}
                        <dt class="col-sm-5">
                            Location
                        </dt>

                        <dd class="col-sm-7">

                            {{ $asset->location_description ?: '-' }}

                        </dd>

                    </dl>

                </div>

            </div>


            <hr>


            {{-- Purchase & Installation --}}
            <div class="row">

                <div class="col-lg-6">

                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Purchase & Installation
                    </h6>

                    <dl class="row">

                        <dt class="col-sm-5">
                            Purchase Date
                        </dt>

                        <dd class="col-sm-7">
                            {{ optional($asset->purchase_date)->format('d-m-Y') ?? '-' }}
                        </dd>


                        <dt class="col-sm-5">
                            Installation Date
                        </dt>

                        <dd class="col-sm-7">
                            {{ optional($asset->installation_date)->format('d-m-Y') ?? '-' }}
                        </dd>


                        <dt class="col-sm-5">
                            Purchase Cost
                        </dt>

                        <dd class="col-sm-7">

                            @if($asset->purchase_cost !== null)

                                ${{ number_format((float) $asset->purchase_cost, 2) }}

                            @else
                                -
                            @endif

                        </dd>


                        <dt class="col-sm-5">
                            Useful Life
                        </dt>

                        <dd class="col-sm-7">

                            @if($asset->useful_life_years !== null)
                                {{ $asset->useful_life_years }} years
                            @else
                                -
                            @endif

                        </dd>

                    </dl>

                </div>


                {{-- Warranty --}}
                <div class="col-lg-6">

                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-shield-alt me-1"></i>
                        Warranty
                    </h6>

                    <dl class="row">

                        <dt class="col-sm-5">
                            Warranty Start
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->warranty_start_date)->format('d-m-Y') ?? '-' }}

                        </dd>


                        <dt class="col-sm-5">
                            Warranty End
                        </dt>

                        <dd class="col-sm-7">

                            {{ optional($asset->warranty_end_date)->format('d-m-Y') ?? '-' }}

                        </dd>

                    </dl>

                </div>

            </div>


            <hr>


            {{-- Remarks --}}
            <h6 class="fw-semibold mb-2">

                <i class="fas fa-comment-alt me-1"></i>
                Remarks

            </h6>

            <div class="border rounded p-3 bg-light">

                {{ $asset->remarks ?: 'No remarks available.' }}

            </div>

        </div>

    </div>

</div>
@endsection