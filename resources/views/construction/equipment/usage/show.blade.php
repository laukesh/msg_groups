@extends('layouts.app')

@section('title', 'Equipment Usage Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Equipment Usage Details
            </h4>

            <div class="text-muted">

                {{ $usageLog->usage_number }}

                |

                {{ $project->project_number }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.usage.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary">

                ← Usage Logs

            </a>

            <a
                href="{{ route(
                    'admin.projects.construction.equipment.deployments.show',
                    [
                        'project' => $project,
                        'deployment' =>
                            $usageLog->equipment_deployment_id
                    ]
                ) }}"
                class="btn btn-outline-primary">

                View Deployment

            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Operating Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format(
                            $usageLog->operating_hours,
                            2
                        ) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Idle Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format(
                            $usageLog->idle_hours,
                            2
                        ) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Breakdown Hours
                    </div>

                    <h3 class="mb-0">
                        {{ number_format(
                            $usageLog->breakdown_hours,
                            2
                        ) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Fuel Consumed
                    </div>

                    <h3 class="mb-0">

                        {{ $usageLog->fuel_consumed !== null
                            ? number_format(
                                $usageLog->fuel_consumed,
                                2
                            )
                            : '—'
                        }}

                        @if($usageLog->fuel_unit)
                            <small class="text-muted">
                                {{ $usageLog->fuel_unit }}
                            </small>
                        @endif

                    </h3>

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
                        {{ $usageLog->equipment?->equipment_code ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Equipment Name
                    </div>

                    <div class="fw-semibold">
                        {{ $usageLog->equipment?->equipment_name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Usage Date
                    </div>

                    <div class="fw-semibold">
                        {{ optional(
                            $usageLog->usage_date
                        )->format('d M Y') }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Operator
                    </div>

                    <div class="fw-semibold">
                        {{ $usageLog->operator?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Deployment
                    </div>

                    <div class="fw-semibold">
                        {{ $usageLog->deployment?->deployment_number ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Work Order
                    </div>

                    <div class="fw-semibold">
                        {{ $usageLog->workOrder?->work_order_number ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Meter --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Meter & Utilization
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Opening Meter
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $usageLog->opening_meter !== null
                            ? number_format(
                                $usageLog->opening_meter,
                                2
                            )
                            : '—'
                        }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Closing Meter
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $usageLog->closing_meter !== null
                            ? number_format(
                                $usageLog->closing_meter,
                                2
                            )
                            : '—'
                        }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Operating Hours
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format(
                            $usageLog->operating_hours,
                            2
                        ) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Idle Hours
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format(
                            $usageLog->idle_hours,
                            2
                        ) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Breakdown Hours
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format(
                            $usageLog->breakdown_hours,
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Work Details --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <strong>
                Work Details
            </strong>

        </div>

        <div class="card-body">

            <div class="mb-4">

                <div class="text-muted small mb-1">
                    Work Description
                </div>

                <div>
                    {!! nl2br(
                        e(
                            $usageLog->work_description
                            ?? '—'
                        )
                    ) !!}
                </div>

            </div>


            <div>

                <div class="text-muted small mb-1">
                    Remarks
                </div>

                <div>
                    {!! nl2br(
                        e(
                            $usageLog->remarks
                            ?? '—'
                        )
                    ) !!}
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
                        {{ $usageLog->creator?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div>
                        {{ optional(
                            $usageLog->created_at
                        )->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <div>
                        {{ $usageLog->updater?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <div>
                        {{ optional(
                            $usageLog->updated_at
                        )->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection