@extends('layouts.app')

@section('title', 'Maintenance Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Maintenance Details
            </h4>

            <div class="text-muted">

                {{ $maintenance->maintenance_number }}

                |

                {{ $project->project_number }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.maintenance.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                ← Maintenance

            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.show',
                    [
                        'project' => $project,
                        'equipment' =>
                            $maintenance->equipment_id
                    ]
                ) }}"
                class="btn btn-outline-primary">

                View Equipment

            </a>

        </div>

    </div>


    {{-- Status --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="text-muted small">
                        Maintenance Status
                    </div>

                    @php

                        $statusClass = match(
                            $maintenance->status
                        ) {

                            'Scheduled' =>
                                'bg-warning text-dark',

                            'In Progress' =>
                                'bg-primary',

                            'Completed' =>
                                'bg-success',

                            'Cancelled' =>
                                'bg-secondary',

                            default =>
                                'bg-light text-dark',
                        };

                    @endphp

                    <span
                        class="badge {{ $statusClass }} fs-6">

                        {{ $maintenance->status }}

                    </span>

                </div>


                <div class="d-flex gap-2">

                    @if($maintenance->status === 'Scheduled')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.equipment.maintenance.start',
                                [
                                    'project' => $project,
                                    'maintenance' => $maintenance
                                ]
                            ) }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Start Maintenance

                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.equipment.maintenance.cancel',
                                [
                                    'project' => $project,
                                    'maintenance' => $maintenance
                                ]
                            ) }}">

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm(
                                    'Cancel this maintenance record?'
                                )">

                                Cancel

                            </button>

                        </form>

                    @endif


                    @if($maintenance->status === 'In Progress')

                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#completeMaintenanceModal">

                            Complete Maintenance

                        </button>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Equipment --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Equipment Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Equipment Code
                    </div>

                    <div class="fw-semibold">
                        {{ $maintenance->equipment?->equipment_code ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Equipment Name
                    </div>

                    <div class="fw-semibold">
                        {{ $maintenance->equipment?->equipment_name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Maintenance Type
                    </div>

                    <div class="fw-semibold">
                        {{ $maintenance->maintenance_type }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Vendor
                    </div>

                    <div class="fw-semibold">
                        {{ $maintenance->maintenance_vendor ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Maintenance Details --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Maintenance Details
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Scheduled Date
                    </div>

                    <div>
                        {{ optional(
                            $maintenance->scheduled_date
                        )->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Maintenance Date
                    </div>

                    <div>
                        {{ optional(
                            $maintenance->maintenance_date
                        )->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Meter Reading
                    </div>

                    <div>
                        {{ $maintenance->meter_reading !== null
                            ? number_format(
                                $maintenance->meter_reading,
                                2
                            )
                            : '—'
                        }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Cost
                    </div>

                    <div class="fw-semibold">
                        ${{ number_format(
                            $maintenance->cost,
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Work --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Maintenance Work
            </strong>

        </div>

        <div class="card-body">

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Issue Description
                </div>

                <div>
                    {!! nl2br(
                        e(
                            $maintenance->issue_description
                            ?? '—'
                        )
                    ) !!}
                </div>

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Work Performed
                </div>

                <div>
                    {!! nl2br(
                        e(
                            $maintenance->work_performed
                            ?? '—'
                        )
                    ) !!}
                </div>

            </div>

        </div>

    </div>


    {{-- Next Service --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Next Service
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Next Service Date
                    </div>

                    <div class="fw-semibold">

                        {{ optional(
                            $maintenance->next_service_date
                        )->format('d M Y') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Next Service Meter
                    </div>

                    <div class="fw-semibold">

                        {{ $maintenance->next_service_meter !== null
                            ? number_format(
                                $maintenance->next_service_meter,
                                2
                            )
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Audit --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Record Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div>
                        {{ $maintenance->creator?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div>
                        {{ optional(
                            $maintenance->created_at
                        )->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <div>
                        {{ $maintenance->updater?->name ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Complete Maintenance Modal --}}
@if($maintenance->status === 'In Progress')

<div
    class="modal fade"
    id="completeMaintenanceModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.equipment.maintenance.complete',
                    [
                        'project' => $project,
                        'maintenance' => $maintenance
                    ]
                ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Complete Maintenance
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label">
                                Maintenance Date
                            </label>

                            <input
                                type="date"
                                name="maintenance_date"
                                class="form-control"
                                value="{{ now()->format('Y-m-d') }}"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Meter Reading
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="meter_reading"
                                class="form-control"
                                value="{{ $maintenance->meter_reading }}">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Cost
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="cost"
                                class="form-control"
                                value="{{ $maintenance->cost }}">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Maintenance Vendor
                            </label>

                            <input
                                type="text"
                                name="maintenance_vendor"
                                class="form-control"
                                value="{{ $maintenance->maintenance_vendor }}">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Next Service Date
                            </label>

                            <input
                                type="date"
                                name="next_service_date"
                                class="form-control"
                                value="{{ optional(
                                    $maintenance->next_service_date
                                )->format('Y-m-d') }}">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Next Service Meter
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="next_service_meter"
                                class="form-control"
                                value="{{ $maintenance->next_service_meter }}">

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Work Performed
                            </label>

                            <textarea
                                name="work_performed"
                                class="form-control"
                                rows="5"
                                required>{{ $maintenance->work_performed }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                class="form-control"
                                rows="3">{{ $maintenance->remarks }}</textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Complete Maintenance

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection