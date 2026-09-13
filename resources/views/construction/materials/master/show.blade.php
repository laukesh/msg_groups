@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Material Details
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                <span class="mx-1">•</span>
                {{ $project->project_name }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.materials.master.index',
                $project
            ) }}"
               class="btn btn-light border shadow-sm px-3">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.projects.construction.materials.master.edit',
                [
                    'project' => $project->id,
                    'material' => $material->id,
                ]
            ) }}"
               class="btn btn-warning shadow-sm px-3">

                <i class="bi bi-pencil me-1"></i>
                Edit

            </a>

        </div>

    </div>


    {{-- Material Card --}}
    <div class="card border-0 shadow-sm material-detail-card">

        {{-- Card Header --}}
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">

            <div class="d-flex align-items-center">

                {{-- Material Icon --}}
                <div class="material-icon me-3">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>

                    <h5 class="mb-1 fw-bold">
                        {{ $material->material_name }}
                    </h5>

                    <div class="d-flex align-items-center gap-2">

                        <span class="material-code">
                            {{ $material->material_code }}
                        </span>

                        @if($material->status === 'Active')

                            <span class="status-badge active">
                                <span class="status-dot"></span>
                                Active
                            </span>

                        @else

                            <span class="status-badge inactive">
                                <span class="status-dot"></span>
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Divider --}}
        <div class="material-divider"></div>


        {{-- Information --}}
        <div class="card-body px-4 py-4">

            <div class="row g-4">


                {{-- Category --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-grid me-1"></i>
                            Category
                        </div>

                        <div class="info-value">
                            {{ $material->category ?: '—' }}
                        </div>

                    </div>

                </div>


                {{-- Specification --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-card-text me-1"></i>
                            Specification
                        </div>

                        <div class="info-value">
                            {{ $material->specification ?: '—' }}
                        </div>

                    </div>

                </div>


                {{-- Unit --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-rulers me-1"></i>
                            Unit
                        </div>

                        <div class="info-value">
                            {{ $material->unit ?: '—' }}
                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-toggle-on me-1"></i>
                            Status
                        </div>

                        <div class="info-value">

                            @if($material->status === 'Active')

                                <span class="status-badge active">
                                    <span class="status-dot"></span>
                                    Active
                                </span>

                            @else

                                <span class="status-badge inactive">
                                    <span class="status-dot"></span>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Created Date --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-calendar3 me-1"></i>
                            Created
                        </div>

                        <div class="info-value">

                            @if($material->created_at)
                                {{ $material->created_at->format('d M Y') }}
                            @else
                                —
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Last Updated --}}
                <div class="col-md-6 col-lg-4">

                    <div class="info-box">

                        <div class="info-label">
                            <i class="bi bi-clock-history me-1"></i>
                            Last Updated
                        </div>

                        <div class="info-value">

                            @if($material->updated_at)
                                {{ $material->updated_at->format('d M Y') }}
                            @else
                                —
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <div class="description-box">

                        <div class="info-label mb-2">
                            <i class="bi bi-file-text me-1"></i>
                            Description
                        </div>

                        <div class="description-text">

                            {{ $material->description ?: 'No description provided.' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

    /* -------------------------------------------------------
       Material Detail Card
    ------------------------------------------------------- */

    .material-detail-card {
        border-radius: 12px;
        overflow: hidden;
    }


    /* -------------------------------------------------------
       Material Icon
    ------------------------------------------------------- */

    .material-icon {

        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #f1f5f9;

        color: #334155;

        font-size: 23px;
    }


    /* -------------------------------------------------------
       Material Code
    ------------------------------------------------------- */

    .material-code {

        display: inline-flex;
        align-items: center;

        padding: 4px 9px;

        border-radius: 5px;

        background: #f1f5f9;

        color: #475569;

        font-size: 12px;

        font-weight: 600;

        letter-spacing: .3px;
    }


    /* -------------------------------------------------------
       Divider
    ------------------------------------------------------- */

    .material-divider {
        height: 1px;
        background: #eef0f3;
    }


    /* -------------------------------------------------------
       Information Box
    ------------------------------------------------------- */

    .info-box {

        background: #fafbfc;

        border: 1px solid #edf0f2;

        border-radius: 9px;

        padding: 15px 17px;

        min-height: 82px;

        transition: all .15s ease;
    }

    .info-box:hover {

        background: #ffffff;

        border-color: #e1e5e9;

        box-shadow: 0 2px 7px rgba(0,0,0,.04);
    }


    /* -------------------------------------------------------
       Labels
    ------------------------------------------------------- */

    .info-label {

        color: #64748b;

        font-size: 12px;

        font-weight: 600;

        text-transform: uppercase;

        letter-spacing: .35px;

        margin-bottom: 7px;
    }


    /* -------------------------------------------------------
       Values
    ------------------------------------------------------- */

    .info-value {

        color: #1e293b;

        font-size: 15px;

        font-weight: 500;

        line-height: 1.4;
    }


    /* -------------------------------------------------------
       Description
    ------------------------------------------------------- */

    .description-box {

        background: #fafbfc;

        border: 1px solid #edf0f2;

        border-radius: 9px;

        padding: 17px;
    }

    .description-text {

        color: #475569;

        font-size: 14px;

        line-height: 1.7;

        white-space: pre-line;
    }


    /* -------------------------------------------------------
       Status
    ------------------------------------------------------- */

    .status-badge {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 5px 10px;

        border-radius: 20px;

        font-size: 12px;

        font-weight: 600;
    }


    .status-dot {

        width: 6px;
        height: 6px;

        border-radius: 50%;

        display: inline-block;
    }


    .status-badge.active {

        background: #dff7ec;

        color: #16835b;
    }

    .status-badge.active .status-dot {

        background: #22c987;
    }


    .status-badge.inactive {

        background: #eef0f2;

        color: #64748b;
    }

    .status-badge.inactive .status-dot {

        background: #94a3b8;
    }


    /* -------------------------------------------------------
       Header
    ------------------------------------------------------- */

    .material-detail-card .card-header h5 {

        color: #172033;

        font-size: 18px;
    }


    /* -------------------------------------------------------
       Responsive
    ------------------------------------------------------- */

    @media (max-width: 767px) {

        .material-detail-card .card-header {
            padding: 20px !important;
        }

        .material-detail-card .card-body {
            padding: 20px !important;
        }

        .material-icon {

            width: 44px;
            height: 44px;

            font-size: 19px;
        }

    }

</style>

@endsection