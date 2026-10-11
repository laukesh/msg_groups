@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                CAPEX Details
            </h4>

            <p class="text-muted mb-0">
                {{ $capex->capex_code ?? 'CAPEX-' . $capex->id }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.assets.capex.index'
            ) }}"
            class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            @can('capex.edit')

                <a href="{{ route(
                    'admin.assets.capex.edit',
                    $capex->id
                ) }}"
                class="btn btn-warning">

                    <i class="fas fa-edit"></i>
                    Edit

                </a>

            @endcan

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- Basic Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>CAPEX Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted">
                        CAPEX Code
                    </small>

                    <div class="fw-bold">
                        {{ $capex->capex_code ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Asset
                    </small>

                    <div class="fw-bold">

                        @if($capex->asset)

                            {{ $capex->asset->asset_name }}

                            <br>

                            <small class="text-muted">
                                {{ $capex->asset->asset_code }}
                            </small>

                        @else

                            -

                        @endif

                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Title
                    </small>

                    <div class="fw-bold">
                        {{ $capex->title }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Category
                    </small>

                    <div>
                        {{ $capex->category ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Priority
                    </small>

                    <div>
                        <span class="badge bg-secondary">
                            {{ ucfirst($capex->priority) }}
                        </span>
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>
                        <span class="badge bg-info">
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $capex->status
                                )
                            ) }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Description --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Description</strong>
        </div>

        <div class="card-body">

            {!! nl2br(e(
                $capex->description ?? '-'
            )) !!}

        </div>

    </div>

    {{-- Dates --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Project Dates</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Planned Date
                    </small>

                    <div>
                        {{ $capex->planned_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Start Date
                    </small>

                    <div>
                        {{ $capex->start_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Completion Date
                    </small>

                    <div>
                        {{ $capex->completion_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Financial Information --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Financial Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Estimated Cost
                    </small>

                    <h5>
                        ${{ number_format(
                            $capex->estimated_cost,
                            2
                        ) }}
                    </h5>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Approved Budget
                    </small>

                    <h5>
                        ${{ number_format(
                            $capex->approved_budget,
                            2
                        ) }}
                    </h5>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Actual Cost
                    </small>

                    <h5>
                        ${{ number_format(
                            $capex->actual_cost,
                            2
                        ) }}
                    </h5>

                </div>

            </div>

        </div>

    </div>

    {{-- Vendor/User --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Vendor / Responsible User</strong>
        </div>

        <div class="card-body">

            @if($capex->vendor)

                <strong>
                    {{ $capex->vendor->name }}
                </strong>

                @if($capex->vendor->email)

                    <br>

                    <small class="text-muted">
                        {{ $capex->vendor->email }}
                    </small>

                @endif

            @else

                <span class="text-muted">
                    Not assigned
                </span>

            @endif

        </div>

    </div>

    {{-- Remarks --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            {!! nl2br(e(
                $capex->remarks ?? '-'
            )) !!}

        </div>

    </div>

    {{-- Audit --}}

    <div class="card">

        <div class="card-header">
            <strong>Audit Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Created By
                    </small>

                    <div>
                        {{ $capex->createdBy?->name ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Updated By
                    </small>

                    <div>
                        {{ $capex->updatedBy?->name ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted">
                        Created At
                    </small>

                    <div>
                        {{ $capex->created_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection