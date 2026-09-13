@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1 fw-semibold">
                Construction Materials
            </h3>

            <div class="text-muted small">
                {{ $project->project_number ?? $project->project_code ?? '—' }}
                -
                {{ $project->project_name ?? $project->name ?? 'Project' }}
            </div>
        </div>

        <a href="{{ route(
            'admin.projects.construction.dashboard',
            $project
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Construction Dashboard

        </a>

    </div>


    {{-- =========================================================
        INTRO
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex align-items-start">

                <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                     style="
                        width:52px;
                        height:52px;
                        background:#eef4ff;
                     ">

                    <i class="bi bi-box-seam text-primary fs-3"></i>

                </div>

                <div>

                    <h5 class="mb-1 fw-semibold">
                        Material Management
                    </h5>

                    <p class="text-muted mb-0">
                        Manage construction materials from requirement and
                        procurement through delivery, inspection, receipt
                        and project stock control.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Materials
                            </div>

                            <h3 class="mb-0 mt-1 fw-semibold">
                                {{ $totalMaterials }}
                            </h3>

                        </div>

                        <div class="text-primary fs-3">
                            <i class="bi bi-box-seam"></i>
                        </div>

                    </div>

                    <div class="small text-muted mt-3">
                        Materials available in master catalogue
                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Materials
                            </div>

                            <h3 class="mb-0 mt-1 fw-semibold">
                                {{ $activeMaterials }}
                            </h3>

                        </div>

                        <div class="text-success fs-3">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                    <div class="small text-muted mt-3">
                        Materials currently available for use
                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Inactive Materials
                            </div>

                            <h3 class="mb-0 mt-1 fw-semibold">
                                {{ $inactiveMaterials }}
                            </h3>

                        </div>

                        <div class="text-warning fs-3">
                            <i class="bi bi-pause-circle"></i>
                        </div>

                    </div>

                    <div class="small text-muted mt-3">
                        Materials currently unavailable
                    </div>

                </div>

            </div>

        </div>


        {{-- Stock --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Stock Items
                            </div>

                            <h3 class="mb-0 mt-1 fw-semibold">
                                {{ $stockItems }}
                            </h3>

                        </div>

                        <div class="text-info fs-3">
                            <i class="bi bi-stack"></i>
                        </div>

                    </div>

                    <div class="small text-muted mt-3">
                        Project material stock records
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MODULES
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-end mb-3">

        <div>

            <h5 class="mb-1 fw-semibold">
                Material Management
            </h5>

            <div class="text-muted small">
                Manage the complete material flow across the project.
            </div>

        </div>

    </div>


    <div class="row g-3">


        {{-- =====================================================
            MATERIAL MASTER
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#eef4ff;
                             ">

                            <i class="bi bi-box text-primary fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Material Master
                            </h6>

                            <p class="text-muted small mb-3">
                                Maintain the standard construction material
                                catalogue, specifications, units and status.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.master.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-primary">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            REQUIREMENTS
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#f3efff;
                             ">

                            <i class="bi bi-list-check text-primary fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Material Requirements
                            </h6>

                            <p class="text-muted small mb-3">
                                Define required materials for project
                                activities and construction work orders.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.requirements.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-primary">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            REQUESTS
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#fff8e6;
                             ">

                            <i class="bi bi-send text-warning fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Material Requests
                            </h6>

                            <p class="text-muted small mb-3">
                                Create, submit and track project material
                                requests from site teams.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.requests.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-warning">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DELIVERIES
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#eefaf7;
                             ">

                            <i class="bi bi-truck text-success fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Deliveries
                            </h6>

                            <p class="text-muted small mb-3">
                                Track material deliveries arriving at the
                                project site from suppliers and contractors.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.deliveries.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-success">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RECEIPTS
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#fff0f0;
                             ">

                            <i class="bi bi-clipboard-check text-danger fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Receipts & Inspection
                            </h6>

                            <p class="text-muted small mb-3">
                                Record material receipts, inspections,
                                acceptance and rejection at site.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.receipts.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-danger">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            STOCK
        ====================================================== --}}

        <div class="col-xl-4 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-start">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                             style="
                                width:48px;
                                height:48px;
                                background:#eaf7ee;
                             ">

                            <i class="bi bi-stack text-success fs-4"></i>

                        </div>

                        <div class="flex-grow-1">

                            <h6 class="fw-semibold mb-1">
                                Material Stock
                            </h6>

                            <p class="text-muted small mb-3">
                                Monitor project-wise stock, quantities,
                                batches and material transactions.
                            </p>

                            <a href="{{ route(
                                'admin.projects.construction.materials.stock.index',
                                $project
                            ) }}"
                               class="btn btn-sm btn-outline-success">

                                Open Module
                                <i class="bi bi-arrow-right ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PROCESS FLOW
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body p-4">

            <h6 class="fw-semibold mb-3">
                Material Flow
            </h6>

            <div class="row text-center g-3">

                <div class="col-md-2">

                    <div class="text-primary fs-3">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div class="small fw-semibold">
                        Requirement
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-warning fs-3">
                        <i class="bi bi-send"></i>
                    </div>

                    <div class="small fw-semibold">
                        Request
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-success fs-3">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div class="small fw-semibold">
                        Delivery
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-danger fs-3">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div class="small fw-semibold">
                        Inspection
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-info fs-3">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div class="small fw-semibold">
                        Receipt
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-success fs-3">
                        <i class="bi bi-stack"></i>
                    </div>

                    <div class="small fw-semibold">
                        Stock
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection