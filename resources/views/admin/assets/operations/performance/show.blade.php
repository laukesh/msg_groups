@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Asset Performance Details
            </h4>

            <p class="text-muted mb-0">
                View complete asset performance information.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.performance.edit',
                    $performance
                ) }}"
                class="btn btn-warning"
            >

                <i class="ri-edit-line"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.assets.operations.performance.index'
                ) }}"
                class="btn btn-secondary"
            >

                <i class="ri-arrow-left-line"></i>

                Back

            </a>

        </div>

    </div>


    <div class="row g-4">


        {{-- Left --}}
        <div class="col-lg-8">


            {{-- Overview --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Performance Overview
                    </h5>

                </div>


                <div class="card-body">
@if($performance->asset?->industryOperatingProfile)

    <div class="alert alert-info">

        <strong>Industry Profile:</strong>

        {{ $performance->asset->industryOperatingProfile->profile_name }}

        <br>

        <strong>Performance Target:</strong>

        {{ $performance->asset->industryOperatingProfile->performance_target }}%

    </div>

@endif
                    <div class="row g-4">


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Asset
                            </label>

                            @if($performance->asset)

                                <div class="fw-semibold">

                                    {{ $performance->asset->asset_name }}

                                </div>

                                <small class="text-muted">

                                    {{ $performance->asset->asset_code }}

                                </small>

                            @else

                                -

                            @endif

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Performance Date
                            </label>

                            <div class="fw-semibold">

                                {{ optional(
                                    $performance->performance_date
                                )->format('d-m-Y') }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Performance Score
                            </label>

                            <div class="fw-semibold">

                                @if($performance->performance_score !== null)

                                    {{ number_format(
                                        $performance->performance_score,
                                        2
                                    ) }}%

                                @else

                                    -

                                @endif

                            </div>


                            @if($performance->performance_score !== null)

                                <div
                                    class="progress mt-2"
                                    style="height:8px;"
                                >

                                    <div
                                        class="progress-bar"
                                        style="width: {{ $performance->performance_score }}%"
                                    ></div>

                                </div>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Status
                            </label>

                            <div class="mt-1">

                                @php

                                    $statusClass = match(
                                        $performance->performance_status
                                    ) {

                                        'Excellent' =>
                                            'bg-success',

                                        'Good' =>
                                            'bg-primary',

                                        'Average' =>
                                            'bg-warning text-dark',

                                        'Poor' =>
                                            'bg-danger',

                                        'Critical' =>
                                            'bg-dark',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span
                                    class="badge {{ $statusClass }} fs-6"
                                >

                                    {{ $performance->performance_status }}

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- KPIs --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Operational KPIs
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        @foreach([
                            'availability_percentage' => 'Availability',
                            'utilization_percentage' => 'Utilization',
                            'efficiency_percentage' => 'Efficiency'
                        ] as $field => $label)

                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        {{ $label }}
                                    </div>

                                    <div class="fs-4 fw-semibold">

                                        {{ $performance->{$field} !== null
                                            ? number_format(
                                                $performance->{$field},
                                                2
                                            ) . '%'
                                            : '-' }}

                                    </div>

                                </div>

                            </div>

                        @endforeach


                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Operating Hours
                                </div>

                                <div class="fs-5 fw-semibold">

                                    {{ number_format(
                                        $performance->operating_hours ?? 0,
                                        2
                                    ) }}

                                    hrs

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Downtime Hours
                                </div>

                                <div class="fs-5 fw-semibold">

                                    {{ number_format(
                                        $performance->downtime_hours ?? 0,
                                        2
                                    ) }}

                                    hrs

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Financial --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Financial Performance
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        @foreach([
                            'maintenance_cost' => 'Maintenance Cost',
                            'operating_cost' => 'Operating Cost',
                            'revenue_generated' => 'Revenue Generated',
                            'target_value' => 'Target Value',
                            'actual_value' => 'Actual Value'
                        ] as $field => $label)

                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        {{ $label }}
                                    </div>

                                    <div class="fw-semibold">

                                        ${{ number_format(
                                            $performance->{$field} ?? 0,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- Remarks --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Remarks
                    </h5>

                </div>


                <div class="card-body">

                    @if($performance->remarks)

                        {!! nl2br(
                            e($performance->remarks)
                        ) !!}

                    @else

                        <span class="text-muted">
                            No remarks provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Right --}}
        <div class="col-lg-4">


            {{-- Inspector --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Inspection
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="text-muted small">
                            Inspector
                        </label>

                        <div class="fw-semibold">

                            {{ $performance->inspector->name ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card mb-4">

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
                            #{{ $performance->id }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created By
                        </label>

                        <div class="fw-semibold">

                            {{ $performance->creator->name ?? '-' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $performance->created_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Updated By
                        </label>

                        <div class="fw-semibold">

                            {{ $performance->updater->name ?? '-' }}

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Updated At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $performance->updated_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Delete --}}
            <div class="card">

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.assets.operations.performance.destroy',
                            $performance
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this performance record?'
                        );"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >

                            <i class="ri-delete-bin-line"></i>

                            Delete Performance

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection