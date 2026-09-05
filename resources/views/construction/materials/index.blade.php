@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Construction Materials
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </p>
        </div>

        <div>
            <a href="{{ route('admin.projects.construction.dashboard', $project) }}"
               class="btn btn-secondary">
                ← Back to Construction
            </a>
        </div>

    </div>


    {{-- Summary --}}

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6 class="text-muted">
                        Total Materials
                    </h6>

                    <h3>
                        {{ $totalMaterials }}
                    </h3>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6 class="text-muted">
                        Active Materials
                    </h6>

                    <h3>
                        {{ $activeMaterials }}
                    </h3>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6 class="text-muted">
                        Inactive Materials
                    </h6>

                    <h3>
                        {{ $inactiveMaterials }}
                    </h3>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6 class="text-muted">
                        Stock Items
                    </h6>

                    <h3>
                        {{ $stockItems }}
                    </h3>

                </div>
            </div>
        </div>

    </div>


    {{-- Modules --}}

    <div class="row">

        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Material Master
                    </h5>

                    <p class="text-muted">
                        Manage the standard construction material
                        catalogue.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.master.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Material Master

                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Material Requirements
                    </h5>

                    <p class="text-muted">
                        Define material requirements for project
                        activities and work orders.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.requirements.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Requirements

                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Material Requests
                    </h5>

                    <p class="text-muted">
                        Create and track material requests.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.requests.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Requests

                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Deliveries
                    </h5>

                    <p class="text-muted">
                        Track material deliveries to the project site.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.deliveries.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Deliveries

                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Receipts & Inspection
                    </h5>

                    <p class="text-muted">
                        Record material receipt, acceptance,
                        rejection and inspection status.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.receipts.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Receipts

                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5>
                        Stock
                    </h5>

                    <p class="text-muted">
                        View project-wise material stock,
                        batches and stock transactions.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.materials.stock.index',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        Open Stock

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection