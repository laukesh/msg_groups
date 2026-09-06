@extends('layouts.app')

@section('title', 'Equipment Deployment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Equipment Deployment
            </h4>

            <p class="text-muted mb-0">

                {{ $project->project_number }}

                <span class="mx-1">•</span>

                {{ $project->project_name }}

            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.deployments.index',
                    $project
                ) }}"
                class="btn btn-light border"
            >
                ← Back to Deployments
            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.usage.create',
                    $project
                ) }}?equipment_deployment_id={{ $deployment->id }}"
                class="btn btn-success">

                + Add Usage

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @php

        $statusClass = match(
            $deployment->status
        ) {

            'Planned'
                => 'bg-warning text-dark',

            'Deployed'
                => 'bg-primary',

            'Returned'
                => 'bg-success',

            'Cancelled'
                => 'bg-secondary',

            default
                => 'bg-secondary',
        };

    @endphp


    {{-- Summary --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Deployment Number
                    </div>

                    <h3 class="fw-bold mb-0">
                        {{ $deployment->deployment_number }}
                    </h3>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <span class="badge {{ $statusClass }} px-3 py-2">
                        {{ $deployment->status }}
                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Deployment Date
                    </div>

                    <div class="fw-semibold">

                        {{ optional(
                            $deployment->deployment_date
                        )->format('d M Y') }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Equipment --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Equipment Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Equipment Code
                    </div>

                    <div class="fw-bold">

                        {{ $deployment->equipment?->equipment_code ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Equipment Name
                    </div>

                    <div class="fw-semibold">

                        {{ $deployment->equipment?->equipment_name ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Ownership
                    </div>

                    <div class="fw-semibold">

                        {{ $deployment->equipment?->ownership_type ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Work Order
                    </div>

                    <div class="fw-semibold">

                        @if($deployment->workOrder)

                            {{
                                $deployment->workOrder->work_order_number
                                ?? $deployment->workOrder->work_order_no
                                ?? $deployment->workOrder->order_number
                                ?? '#'.$deployment->construction_work_order_id
                            }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Operator
                    </div>

                    <div class="fw-semibold">

                        {{ $deployment->operator?->name ?? '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Location
                    </div>

                    <div class="fw-semibold">

                        {{ $deployment->location ?: '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Meter Information --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Starting Meter
                    </div>

                    <h3 class="fw-bold mb-0">

                        @if($deployment->starting_meter !== null)

                            {{ number_format(
                                $deployment->starting_meter,
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Ending Meter
                    </div>

                    <h3 class="fw-bold mb-0">

                        @if($deployment->ending_meter !== null)

                            {{ number_format(
                                $deployment->ending_meter,
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Return Information --}}

    @if($deployment->return_date)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Return Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            Return Date
                        </div>

                        <div class="fw-semibold">

                            {{ $deployment->return_date->format('d M Y') }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            Meter Used
                        </div>

                        <div class="fw-semibold">

                            @if(
                                $deployment->starting_meter !== null &&
                                $deployment->ending_meter !== null
                            )

                                {{
                                    number_format(
                                        $deployment->ending_meter
                                        - $deployment->starting_meter,
                                        2
                                    )
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Remarks --}}

    @if($deployment->remarks)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold">
                    Remarks
                </h6>

            </div>

            <div class="card-body">

                {!! nl2br(e($deployment->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- Usage --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                Usage Logs
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Usage No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Opening Meter
                            </th>

                            <th>
                                Closing Meter
                            </th>

                            <th>
                                Operating Hours
                            </th>

                            <th>
                                Operator
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse(
                        $deployment->usageLogs
                        as $usage
                    )

                        <tr>

                            <td class="ps-4 fw-semibold">
                                {{ $usage->usage_number }}
                            </td>

                            <td>
                                {{ optional(
                                    $usage->usage_date
                                )->format('d M Y') }}
                            </td>

                            <td>
                                {{ $usage->opening_meter !== null
                                    ? number_format($usage->opening_meter, 2)
                                    : '—' }}
                            </td>

                            <td>
                                {{ $usage->closing_meter !== null
                                    ? number_format($usage->closing_meter, 2)
                                    : '—' }}
                            </td>

                            <td>
                                {{ number_format(
                                    $usage->operating_hours,
                                    2
                                ) }}
                            </td>

                            <td>
                                {{ $usage->operator?->name ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No usage logs recorded yet.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Actions --}}

    <div class="d-flex justify-content-end gap-2 mb-4">

        @if($deployment->status === 'Planned')

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.equipment.deployments.deploy',
                    [
                        'project' => $project,
                        'deployment' => $deployment,
                    ]
                ) }}"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-primary"
                    onclick="return confirm(
                        'Deploy this equipment?'
                    )"
                >
                    Deploy Equipment
                </button>

            </form>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.equipment.deployments.cancel',
                    [
                        'project' => $project,
                        'deployment' => $deployment,
                    ]
                ) }}"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-danger"
                    onclick="return confirm(
                        'Cancel this deployment?'
                    )"
                >
                    Cancel Deployment
                </button>

            </form>

        @endif


        @if($deployment->status === 'Deployed')

            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#returnEquipmentModal"
            >
                Return Equipment
            </button>

        @endif

    </div>

</div>


{{-- ================================================================ --}}
{{-- RETURN MODAL --}}
{{-- ================================================================ --}}

@if($deployment->status === 'Deployed')

<div
    class="modal fade"
    id="returnEquipmentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.equipment.deployments.return',
                    [
                        'project' => $project,
                        'deployment' => $deployment,
                    ]
                ) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Return Equipment
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Return Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="return_date"
                            value="{{ now()->format('Y-m-d') }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Ending Meter
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="ending_meter"
                            class="form-control"
                            placeholder="Enter final meter reading"
                        >

                    </div>


                    <div>

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                            placeholder="Return remarks..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Confirm Return
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection