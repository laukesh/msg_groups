@extends('layouts.app')

@section('title', $mall->mall_name)

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                {{ $mall->mall_name }}
            </h1>

            <p class="text-muted mb-0">
                <i class="fas fa-barcode me-1"></i> Mall Code: {{ $mall->mall_code }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.malls.edit', $mall->id) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-edit me-1"></i> Edit Mall
            </a>

            <a
                href="{{ route('admin.assets.malls.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i> Back to Malls
            </a>

        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-1"></i> Basic Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-id-card me-1"></i> Mall ID</label>
                    <div>{{ $mall->id }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-barcode me-1"></i> Mall Code</label>
                    <div>{{ $mall->mall_code }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-store me-1"></i> Mall Name</label>
                    <div>{{ $mall->mall_name }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-tag me-1"></i> Mall Type</label>
                    <div>{{ $mall->mall_type ?? '-' }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-calendar-alt me-1"></i> Opening Date</label>
                    <div>
                        {{ $mall->opening_date
                            ? \Carbon\Carbon::parse($mall->opening_date)->format('d M Y')
                            : '-' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-bold"><i class="fas fa-toggle-on me-1"></i> Status</label>
                    <div>
                        @if($mall->status === 1)
                            <span class="badge bg-success">
                                Active
                            </span>
                        @elseif($mall->status === 0)
                            <span class="badge bg-secondary">
                                Inactive
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($mall->status ?? 'Unknown') }}
                            </span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Address Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-home me-1"></i> Address Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-home me-1"></i> Address Line 1
                    </label>

                    <div>
                        {{ $mall->address_line1 ?? '-' }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-home me-1"></i> Address Line 2
                    </label>

                    <div>
                        {{ $mall->address_line2 ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-city me-1"></i> City
                    </label>

                    <div>
                        {{ $mall->city ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-map-signs me-1"></i> State
                    </label>

                    <div>
                        {{ $mall->state ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-globe me-1"></i> Country
                    </label>

                    <div>
                        {{ $mall->country ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                     <i class="fas fa-mail-bulk me-1"></i>  Postal Code
                    </label>

                    <div>
                        {{ $mall->postal_code ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Location --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> Location</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-map-marker-alt me-1"></i> Latitude
                    </label>

                    <div>
                        {{ $mall->latitude ?? '-' }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-map-marker-alt me-1"></i> Longitude
                    </label>

                    <div>
                        {{ $mall->longitude ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Area & Capacity --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-ruler-combined me-1"></i> Area & Capacity</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-ruler-combined me-1"></i> Total Area
                    </label>

                    <div>
                        {{ $mall->total_area ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-ruler-combined me-1"></i> Leasable Area
                    </label>

                    <div>
                        {{ $mall->leasable_area ?? '-' }}
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-parking me-1"></i> Parking Capacity
                    </label>

                    <div>
                        {{ $mall->parking_capacity ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Contact Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-phone-alt me-1"></i> Contact Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-user me-1"></i> Contact Person
                    </label>

                    <div>
                        {{ $mall->contact_person ?? '-' }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-phone-alt me-1"></i> Contact Number
                    </label>

                    <div>
                        {{ $mall->contact_number ?? '-' }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-envelope me-1"></i> Email
                    </label>

                    <div>
                        @if($mall->email)
                            <a href="mailto:{{ $mall->email }}">
                                {{ $mall->email }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-globe me-1"></i> Website
                    </label>

                    <div>
                        @if($mall->website)
                            <a
                                href="{{ $mall->website }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ $mall->website }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-file-alt me-1"></i> Audit Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-plus-circle me-1"></i> Created By
                    </label>

                    <div>
                        {{ $mall->created_by ?? '-' }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-edit me-1"></i> Updated By
                    </label>

                    <div>
                        {{ $mall->updated_by ?? '-' }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-plus-circle me-1"></i> Created At
                    </label>

                    <div>
                        {{ $mall->created_at
                            ? $mall->created_at->format('d M Y H:i')
                            : '-' }}
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-edit me-1"></i> Updated At
                    </label>

                    <div>
                        {{ $mall->updated_at
                            ? $mall->updated_at->format('d M Y H:i')
                            : '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Deleted Information --}}
    @if($mall->deleted_at)

        <div class="alert alert-warning">

            <strong ><i class="fas fa-trash-alt me-1"></i> Deleted:</strong>

            {{ $mall->deleted_at->format('d M Y H:i') }}

        </div>

    @endif


    {{-- Bottom Actions --}}
    <div class="d-flex justify-content-end gap-2 mb-4">

        <a
            href="{{ route('admin.assets.malls.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i> Back to Malls
        </a>

        <a
            href="{{ route('admin.assets.malls.edit', $mall->id) }}"
            class="btn btn-primary"
        >
         <i class="fas fa-edit me-1"></i> Edit Mall
        </a>

    </div>

</div>

@endsection