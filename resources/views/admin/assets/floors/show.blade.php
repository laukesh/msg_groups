@extends('layouts.app')

@section('title', $floor->floor_name)

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="h3 mb-1">
            <i class="fas fa-tag me-1"></i> {{ $floor->floor_name }}
            </h4>

            <p class="text-muted mb-0">
            <i class="fas fa-barcode me-1"></i>    {{ $floor->floor_code }}
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.assets.floors.edit', $floor->id) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-edit me-1"></i> Edit
            </a>

            <a
                href="{{ route('admin.assets.floors.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>

        </div>

    </div>


    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-1"></i> Floor Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-id-card me-1"></i> Floor ID</strong>
                    <div>{{ $floor->id }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-building me-1"></i> Building</strong>

                    <div>
                        {{ $floor->building->building_name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-barcode me-1"></i> Floor Code</strong>
                    <div>{{ $floor->floor_code }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-tag me-1"></i> Floor Name</strong>
                    <div>{{ $floor->floor_name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-sort-numeric-up me-1"></i> Floor Number</strong>
                    <div>{{ $floor->floor_number }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong><i class="fas fa-toggle-on me-1"></i> Status</strong>

                    <div>

                        @if($floor->status === 1)

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

            </div>

        </div>

    </div>


    {{-- Audit --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-history me-1"></i> Audit Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <strong><i class="fas fa-user me-1"></i> Created By</strong>

                    <div>
                        {{ $floor->creator->name ?? $floor->created_by ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <strong><i class="fas fa-user-edit me-1"></i> Updated By</strong>

                    <div>
                        {{ $floor->updater->name ?? $floor->updated_by ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <strong><i class="fas fa-clock me-1"></i> Created At</strong>

                    <div>
                        {{ $floor->created_at?->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <strong><i class="fas fa-clock me-1"></i> Updated At</strong>

                    <div>
                        {{ $floor->updated_at?->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection