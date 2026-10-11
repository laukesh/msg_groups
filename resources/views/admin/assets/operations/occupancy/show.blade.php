@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Occupancy Details
            </h4>

            <p class="text-muted mb-0">
                View asset occupancy information.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.occupancy.edit',
                    $occupancy
                ) }}"
                class="btn btn-warning"
            >

                <i class="ri-edit-line"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.assets.operations.occupancy.index'
                ) }}"
                class="btn btn-secondary"
            >

                <i class="ri-arrow-left-line"></i>

                Back

            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <div class="row g-4">


        {{-- Occupancy Information --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Occupancy Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Asset --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Asset
                            </label>

                            @if($occupancy->asset)

                                <div class="fw-semibold">

                                    {{ $occupancy->asset->asset_name }}

                                </div>

                                <small class="text-muted">

                                    {{ $occupancy->asset->asset_code }}

                                </small>

                            @else

                                <div>
                                    -
                                </div>

                            @endif

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Occupancy Status
                            </label>

                            <div>

                                @php

                                    $statusClass = match(
                                        $occupancy->occupancy_status
                                    ) {

                                        'occupied' =>
                                            'bg-success',

                                        'vacant' =>
                                            'bg-secondary',

                                        'partially_occupied' =>
                                            'bg-warning text-dark',

                                        'under_maintenance' =>
                                            'bg-danger',

                                        'reserved' =>
                                            'bg-info',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span class="badge {{ $statusClass }}">

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $occupancy->occupancy_status
                                            )
                                        )
                                    }}

                                </span>

                            </div>

                        </div>


                        {{-- Occupancy --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Occupancy Percentage
                            </label>

                            <div class="fw-semibold">

                                {{ number_format(
                                    $occupancy->occupancy_percentage,
                                    2
                                ) }}%

                            </div>


                            <div
                                class="progress mt-2"
                                style="height: 8px;"
                            >

                                <div
                                    class="progress-bar"
                                    style="width: {{ $occupancy->occupancy_percentage }}%"
                                ></div>

                            </div>

                        </div>


                        {{-- Tenant --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Tenant Name
                            </label>

                            <div class="fw-semibold">

                                {{ $occupancy->tenant_name ?? '-' }}

                            </div>

                        </div>


                        {{-- Lease Start --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Lease Start Date
                            </label>

                            <div class="fw-semibold">

                                @if($occupancy->lease_start_date)

                                    {{ $occupancy->lease_start_date->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- Lease End --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Lease End Date
                            </label>

                            <div class="fw-semibold">

                                @if($occupancy->lease_end_date)

                                    {{ $occupancy->lease_end_date->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- Monthly Rent --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Monthly Rent
                            </label>

                            <div class="fw-semibold">

                                ${{ number_format(
                                    $occupancy->monthly_rent ?? 0,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Created --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Created At
                            </label>

                            <div class="fw-semibold">

                                {{ optional(
                                    $occupancy->created_at
                                )->format('d-m-Y H:i') }}

                            </div>

                        </div>


                        {{-- Remarks --}}
                        <div class="col-12">

                            <label class="text-muted small">
                                Remarks
                            </label>

                            <div class="border rounded p-3 mt-1">

                                {!! nl2br(
                                    e(
                                        $occupancy->remarks
                                        ?? 'No remarks available.'
                                    )
                                ) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Audit Information --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <label class="text-muted small">
                            Record ID
                        </label>

                        <div class="fw-semibold">
                            #{{ $occupancy->id }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created By
                        </label>

                        <div class="fw-semibold">

                            {{ $occupancy->creator->name ?? '-' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $occupancy->created_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Updated By
                        </label>

                        <div class="fw-semibold">

                            {{ $occupancy->updater->name ?? '-' }}

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Updated At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $occupancy->updated_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Delete --}}
            <div class="card mt-4">

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.assets.operations.occupancy.destroy',
                            $occupancy
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this occupancy record?'
                        );"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >

                            <i class="ri-delete-bin-line"></i>

                            Delete Occupancy

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection