@extends('layouts.app')

@section('title', 'Material Requirement')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Material Requirement
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.materials.requirements.index', ['project' => $project->id]) }}"
               class="btn btn-secondary">
                ← Back to Requirements
            </a>

            @if(in_array($requirement->status, ['Draft', 'Requested']))

                <a href="{{ route('admin.projects.construction.materials.requirements.edit', [
                    'project' => $project->id,
                    'requirement' => $requirement->id,
                ]) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Material
                    </div>

                    <div class="fw-bold fs-5">
                        {{ $requirement->material?->material_name ?? '—' }}
                    </div>

                    <small class="text-muted">
                        {{ $requirement->material?->material_code ?? '' }}
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Required Quantity
                    </div>

                    <div class="fw-bold fs-4">

                        {{ number_format((float) $requirement->required_quantity, 4) }}

                        <small class="fs-6 text-muted">
                            {{ $requirement->unit }}
                        </small>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Required Date
                    </div>

                    <div class="fw-bold fs-5">

                        @if($requirement->required_date)
                            {{ $requirement->required_date->format('d M Y') }}
                        @else
                            —
                        @endif

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php

                        $badgeClass = match($requirement->status) {

                            'Draft' =>
                                'bg-secondary',

                            'Requested' =>
                                'bg-primary',

                            'Partially Fulfilled' =>
                                'bg-warning text-dark',

                            'Fulfilled' =>
                                'bg-success',

                            'Cancelled' =>
                                'bg-danger',

                            default =>
                                'bg-secondary',
                        };

                    @endphp

                    <span class="badge {{ $badgeClass }} fs-6 mt-1">
                        {{ $requirement->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-lg-8">

            {{-- Requirement Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Requirement Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Project
                            </div>

                            <div class="fw-semibold">
                                {{ $project->project_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Project Number
                            </div>

                            <div class="fw-semibold">
                                {{ $project->project_number ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Material
                            </div>

                            <div class="fw-semibold">
                                {{ $requirement->material?->material_name ?? '—' }}
                            </div>

                            @if($requirement->material)

                                <small class="text-muted">
                                    {{ $requirement->material->material_code }}
                                </small>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($requirement->workOrder)

                                <div class="fw-semibold">
                                    {{ $requirement->workOrder->work_order_number }}
                                </div>

                                <small class="text-muted">
                                    {{ $requirement->workOrder->work_order_title }}
                                </small>

                            @else

                                <span class="text-muted">
                                    General Project Requirement
                                </span>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Required Quantity
                            </div>

                            <div class="fw-semibold">

                                {{ number_format((float) $requirement->required_quantity, 4) }}

                                {{ $requirement->unit }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Required Date
                            </div>

                            <div class="fw-semibold">

                                @if($requirement->required_date)
                                    {{ $requirement->required_date->format('d M Y') }}
                                @else
                                    —
                                @endif

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Purpose
                            </div>

                            <div class="fw-semibold">
                                {{ $requirement->purpose ?: '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Remarks
                            </div>

                            <div>
                                {{ $requirement->remarks ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Audit --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Record Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Created By
                            </div>

                            <div>
                                {{ $requirement->creator?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Created At
                            </div>

                            <div>
                                {{ $requirement->created_at?->format('d M Y H:i') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Updated By
                            </div>

                            <div>
                                {{ $requirement->updater?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Updated At
                            </div>

                            <div>
                                {{ $requirement->updated_at?->format('d M Y H:i') ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Workflow --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Workflow
                    </h5>

                </div>

                <div class="card-body">


                    @if($requirement->status === 'Draft')

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Draft
                            </div>

                            <div class="text-muted small">
                                Requirement is being prepared.
                            </div>

                        </div>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requirements.request', [
                                  'project' => $project->id,
                                  'requirement' => $requirement->id,
                              ]) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                Submit Requirement
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requirements.cancel', [
                                  'project' => $project->id,
                                  'requirement' => $requirement->id,
                              ]) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Cancel this requirement?')">
                                Cancel Requirement
                            </button>

                        </form>


                    @elseif($requirement->status === 'Requested')

                        <div class="mb-3">

                            <div class="fw-semibold mb-1">
                                Requirement Submitted
                            </div>

                            <div class="text-muted small">
                                This requirement has been submitted
                                for material arrangement.
                            </div>

                        </div>


                        <a href="{{ route('admin.projects.construction.materials.requirements.edit', [
                            'project' => $project->id,
                            'requirement' => $requirement->id,
                        ]) }}"
                           class="btn btn-outline-primary w-100 mb-2">
                            Edit Requirement
                        </a>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requirements.cancel', [
                                  'project' => $project->id,
                                  'requirement' => $requirement->id,
                              ]) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Cancel this requirement?')">
                                Cancel Requirement
                            </button>

                        </form>


                    @elseif($requirement->status === 'Partially Fulfilled')

                        <div class="alert alert-warning mb-0">

                            <strong>
                                Partially Fulfilled
                            </strong>

                            <div class="small mt-1">
                                Material has been arranged partially.
                            </div>

                        </div>


                    @elseif($requirement->status === 'Fulfilled')

                        <div class="alert alert-success mb-0">

                            <strong>
                                Fulfilled
                            </strong>

                            <div class="small mt-1">
                                This material requirement has been fulfilled.
                            </div>

                        </div>


                    @elseif($requirement->status === 'Cancelled')

                        <div class="alert alert-danger mb-0">

                            <strong>
                                Cancelled
                            </strong>

                            <div class="small mt-1">
                                This requirement is no longer active.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection