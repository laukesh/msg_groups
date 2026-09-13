@extends('layouts.app')

@section('title', $building->building_name)

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="h3 mb-1">
                <i class="fas fa-building me-2"></i>
                {{ $building->building_name }}
            </h4>

            <p class="text-muted mb-0">
              <i class="fas fa-hashtag me-1"></i>  Building #{{ $building->id }}
            </p>

        </div>

        <div>

            @can('buildings.edit')

                <a
                    href="{{ route(
                        'admin.assets.buildings.edit',
                        $building->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i> Edit
                </a>

            @endcan

            <a
                href="{{ route('admin.assets.buildings.index') }}"
                class="btn btn-secondary"
            >
               <i class="fas fa-arrow-left me-1"></i> Back
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Building Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
              <i class="fas fa-info-circle me-1"></i>  Building Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-hashtag me-1"></i> Building ID</strong>

                    <div>
                        {{ $building->id }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-building me-1"></i> Mall</strong>

                    <div>

                        @if($building->mall)

                            <a
                                href="{{ route(
                                    'admin.assets.malls.show',
                                    $building->mall_id
                                ) }}"
                            >
                                {{ $building->mall->mall_name }}
                            </a>

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-code me-1"></i> Building Code</strong>

                    <div>
                        {{ $building->building_code }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-building me-1"></i> Building Name</strong>

                    <div>
                        {{ $building->building_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-layer-group me-1"></i> Total Floors</strong>

                    <div>
                        {{ $building->total_floors ?? 0 }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-home me-1"></i> Total Units</strong>

                    <div>
                        {{ $building->total_units ?? 0 }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong><i class="fas fa-check-circle me-1"></i> Status</strong>

                    <div>

                        @if($building->status == 1)

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


                <div class="col-md-12 mb-3">

                    <strong><i class="fas fa-align-left me-1"></i> Description</strong>

                    <div>
                        {{ $building->description ?: '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Audit Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <strong>Created By</strong>

                    <div>
                        {{ $building->creator->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Updated By</strong>

                    <div>
                        {{ $building->updater->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Created At</strong>

                    <div>
                        {{ $building->created_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <strong>Updated At</strong>

                    <div>
                        {{ $building->updated_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Floors --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Floors
                </h5>

                <span class="badge bg-primary">
                    {{ $building->floors->count() }}
                </span>

            </div>

        </div>

        <div class="card-body">

            @if($building->floors->count())

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                            <tr>
                                <th>ID</th>
                                <th>Floor Code</th>
                                <th>Floor Name</th>
                                <th>Floor Number</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                        @foreach($building->floors as $floor)

                            <tr>

                                <td>
                                    {{ $floor->id }}
                                </td>

                                <td>
                                    {{ $floor->floor_code }}
                                </td>

                                <td>
                                    {{ $floor->floor_name }}
                                </td>

                                <td>
                                    {{ $floor->floor_number }}
                                </td>

                                <td>

                                    @if($floor->status == 1)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <p class="text-muted mb-0">
                    No floors have been added to this building yet.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection