@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Stages
            </h4>

            <div class="text-muted">
                Request:
                <strong>
                    {{ $fitoutRequest->request_no }}
                </strong>
            </div>
        </div>

        <a href="{{ route(
            'admin.fitout.requests.show',
            $fitoutRequest->id
        ) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Request

        </a>

    </div>


    {{-- Overall Progress --}}
    @php

        $totalStages = $stages->count();

        $completedStages = $stages
            ->where('stage_status', 'Completed')
            ->count();

        $overallProgress = $totalStages > 0
            ? round(
                $stages->avg(
                    fn ($stage) =>
                        (float) $stage->completion_percentage
                ),
                2
            )
            : 0;

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Total Stages
                    </small>

                    <h4 class="mb-0">
                        {{ $totalStages }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Completed
                    </small>

                    <h4 class="mb-0 text-success">
                        {{ $completedStages }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        In Progress
                    </small>

                    <h4 class="mb-0 text-primary">
                        {{ $stages->where(
                            'stage_status',
                            'In Progress'
                        )->count() }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small class="text-muted">
                        Overall Progress
                    </small>

                    <h4 class="mb-0">
                        {{ $overallProgress }}%
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Progress --}}
    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    Overall Fit-Out Progress
                </strong>

                <strong>
                    {{ $overallProgress }}%
                </strong>

            </div>

            <div
                class="progress"
                style="height: 12px;">

                <div
                    class="progress-bar"
                    style="width: {{ $overallProgress }}%;">

                </div>

            </div>

        </div>

    </div>


    {{-- Stage Table --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Stage Schedule
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Planned Start
                            </th>

                            <th>
                                Planned End
                            </th>

                            <th>
                                Actual Start
                            </th>

                            <th>
                                Actual End
                            </th>

                            <th width="180">
                                Progress
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="100">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($stages as $stage)

                            @php

                                $statusClass = match (
                                    $stage->stage_status
                                ) {

                                    'Pending' =>
                                        'secondary',

                                    'In Progress' =>
                                        'primary',

                                    'Completed' =>
                                        'success',

                                    'On Hold' =>
                                        'warning',

                                    'Cancelled' =>
                                        'danger',

                                    default =>
                                        'secondary',

                                };

                            @endphp


                            <tr>

                                <td>
                                    <strong>
                                        {{ $stage->stage_sequence }}
                                    </strong>
                                </td>


                                <td>

                                    <strong>
                                        {{ $stage->stage_name }}
                                    </strong>

                                    @if($stage->contractor)

                                        <small
                                            class="d-block text-muted">

                                            {{ $stage->contractor->contractor_name }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ $stage->planned_start_date
                                        ? $stage->planned_start_date->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    {{ $stage->planned_end_date
                                        ? $stage->planned_end_date->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    {{ $stage->actual_start_date
                                        ? $stage->actual_start_date->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    {{ $stage->actual_end_date
                                        ? $stage->actual_end_date->format('d M Y')
                                        : '-' }}

                                </td>


                                <td>

                                    <div class="d-flex justify-content-between">

                                        <small>
                                            {{ number_format(
                                                (float) $stage->completion_percentage,
                                                0
                                            ) }}%
                                        </small>

                                    </div>

                                    <div
                                        class="progress"
                                        style="height: 6px;">

                                        <div
                                            class="progress-bar"
                                            style="width: {{ (float) $stage->completion_percentage }}%;">

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="badge bg-{{ $statusClass }}">

                                        {{ $stage->stage_status }}

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.fitout.stages.show',
                                            $stage->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5 text-muted">

                                    No stages found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection