@extends('layouts.app')

@section('title', 'Construction Delays')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Construction Delays
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-speedometer2"></i>
                Construction Dashboard

            </a>
            <a href="{{ route(
                'admin.projects.construction.delays.create',
                $project
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>
                Report Delay

            </a>
        </div>

    </div>


    {{-- Flash Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Delays
                    </small>

                    <h4 class="mb-0 mt-1">
                        {{ $delays->total() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Open Delays
                    </small>

                    <h4 class="mb-0 mt-1">

                        {{ \App\Models\ConstructionDelay::where(
                            'project_id',
                            $project->id
                        )->whereNotIn('status', [
                            'Closed',
                            'Rejected',
                            'Withdrawn'
                        ])->count() }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Reported Delay Days
                    </small>

                    <h4 class="mb-0 mt-1">

                        {{ number_format(
                            \App\Models\ConstructionDelay::where(
                                'project_id',
                                $project->id
                            )->sum('reported_days')
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Approved Delay Days
                    </small>

                    <h4 class="mb-0 mt-1">

                        {{ number_format(
                            \App\Models\ConstructionDelay::where(
                                'project_id',
                                $project->id
                            )->sum('approved_days')
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Delay Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Delay Register
                </h5>

                <span class="badge bg-secondary">
                    {{ $delays->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($delays->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    Delay
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th>
                                    Activity
                                </th>

                                <th>
                                    Reported Days
                                </th>

                                <th>
                                    EOT
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Documents
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($delays as $delay)

                                <tr>

                                    <td class="ps-3">

                                        <div class="fw-semibold">

                                            {{ $delay->delay_number }}

                                        </div>

                                        <small class="text-muted">

                                            {{ \Illuminate\Support\Str::limit(
                                                $delay->delay_title,
                                                45
                                            ) }}

                                        </small>

                                        <div>

                                            <small class="text-muted">

                                                {{ $delay->delay_date?->format('d M Y') }}

                                            </small>

                                        </div>

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $delay->delay_type }}

                                        </span>

                                    </td>


                                    <td>

                                        @if($delay->workOrder)

                                            <div class="fw-semibold">

                                                {{ $delay->workOrder->work_order_number }}

                                            </div>

                                            <small class="text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $delay->workOrder->work_order_title,
                                                    30
                                                ) }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($delay->scheduleActivity)

                                            <div class="fw-semibold">

                                                {{ $delay->scheduleActivity->activity_code }}

                                            </div>

                                            <small class="text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $delay->scheduleActivity->activity_name,
                                                    30
                                                ) }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="fw-semibold">

                                            {{ $delay->reported_days }}

                                        </span>

                                        days

                                        @if($delay->assessed_days !== null)

                                            <br>

                                            <small class="text-muted">

                                                Assessed:
                                                {{ $delay->assessed_days }}

                                            </small>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $delay->eot_requested_days }}

                                        days

                                        @if($delay->eot_approved_days !== null)

                                            <br>

                                            <small class="text-success">

                                                Approved:
                                                {{ $delay->eot_approved_days }}

                                            </small>

                                        @endif

                                    </td>


                                    <td>

                                        @php

                                            $statusClass = match($delay->status) {

                                                'Draft' => 'bg-secondary',

                                                'Submitted' => 'bg-info text-dark',

                                                'Under Review' => 'bg-warning text-dark',

                                                'Under Assessment' => 'bg-warning text-dark',

                                                'Approved' => 'bg-success',

                                                'Partially Approved' => 'bg-success',

                                                'Rejected' => 'bg-danger',

                                                'Closed' => 'bg-dark',

                                                'Withdrawn' => 'bg-secondary',

                                                default => 'bg-secondary',

                                            };

                                        @endphp

                                        <span class="badge {{ $statusClass }}">

                                            {{ $delay->status }}

                                        </span>

                                    </td>


                                    <td>

                                        @if($delay->documents_count)

                                            <a href="{{ route(
                                                'admin.projects.construction.delays.documents.index',
                                                [
                                                    'project' => $project,
                                                    'delay' => $delay,
                                                ]
                                            ) }}"
                                               class="text-decoration-none">

                                                <i class="bi bi-folder me-1"></i>

                                                {{ $delay->documents_count }}

                                            </a>

                                        @else

                                            <a href="{{ route(
                                                'admin.projects.construction.delays.documents.create',
                                                [
                                                    'project' => $project,
                                                    'delay' => $delay,
                                                ]
                                            ) }}"
                                               class="text-muted text-decoration-none">

                                                <i class="fa fa-upload me-1"></i>

                                                Add

                                            </a>

                                        @endif

                                    </td>


                                    <td class="text-end pe-3">

                                        <div class="d-flex justify-content-end gap-1">

                                            {{-- View --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.delays.show',
                                                [
                                                    'project' => $project,
                                                    'delay' => $delay,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            @if(in_array($delay->status, [
                                                'Draft',
                                                'Rejected'
                                            ]))

                                                <a href="{{ route(
                                                    'admin.projects.construction.delays.edit',
                                                    [
                                                        'project' => $project,
                                                        'delay' => $delay,
                                                    ]
                                                ) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">

                                                    <i class="fa fa-edit"></i>

                                                </a>

                                            @endif


                                            {{-- Documents --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.delays.documents.index',
                                                [
                                                    'project' => $project,
                                                    'delay' => $delay,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Documents">

                                                <i class="fa fa-folder"></i>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="p-3">

                    {{ $delays->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-calendar-x fs-1 text-muted"></i>

                    </div>

                    <h6>
                        No Delays Reported
                    </h6>

                    <p class="text-muted mb-3">
                        No construction delay events have been recorded
                        for this project.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.delays.create',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>
                        Report First Delay

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection