@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted mb-1">

                {{ $stage->fitoutRequest->request_no }}

            </div>

            <h4 class="mb-0">

                {{ $stage->stage_name }}

            </h4>

        </div>


        <div class="d-flex gap-2">
            <a
                href="{{ route('admin.fitout.stages.edit', $stage->id) }}"
                class="btn btn-primary"
            >
                Edit Stage
            </a>

            <a
                href="{{ route(
                    'admin.fitout.stages.index',
                    $stage->fitout_request_id
                ) }}"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Stages

            </a>

        </div>

    </div>


    {{-- Stage Information --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Stage Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Sequence
                    </small>

                    <strong>
                        {{ $stage->stage_sequence }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Stage
                    </small>

                    <strong>
                        {{ $stage->stage_name }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <span class="badge bg-primary">

                        {{ $stage->stage_status }}

                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Contractor
                    </small>

                    <strong>

                        {{ $stage->contractor->contractor_name
                            ?? '-' }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Progress --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Stage Progress
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    Completion
                </strong>

                <strong>
                    {{ number_format(
                        (float) $stage->completion_percentage,
                        0
                    ) }}%
                </strong>

            </div>


            <div
                class="progress mb-4"
                style="height: 15px;">

                <div
                    class="progress-bar"
                    style="width: {{ (float) $stage->completion_percentage }}%;">

                </div>

            </div>


            @if($stage->stage_status === 'In Progress')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.fitout.stages.progress',
                        $stage->id
                    ) }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">

                                Completion %

                            </label>

                            <input
                                type="number"
                                name="completion_percentage"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.01"
                                value="{{ $stage->completion_percentage }}"
                                required>

                        </div>


                        <div class="col-md-8">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea
                                name="remarks"
                                class="form-control"
                                rows="2">{{ $stage->remarks }}</textarea>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary mt-3">

                        Update Progress

                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- Schedule --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Schedule
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Planned Start
                    </small>

                    <strong>
                        {{ $stage->planned_start_date
                            ? $stage->planned_start_date->format('d M Y')
                            : '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Planned End
                    </small>

                    <strong>
                        {{ $stage->planned_end_date
                            ? $stage->planned_end_date->format('d M Y')
                            : '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual Start
                    </small>

                    <strong>
                        {{ $stage->actual_start_date
                            ? $stage->actual_start_date->format('d M Y')
                            : '-' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Actual End
                    </small>

                    <strong>
                        {{ $stage->actual_end_date
                            ? $stage->actual_end_date->format('d M Y')
                            : '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Stage Actions
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex gap-2">


                @if($stage->stage_status === 'Pending')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fitout.stages.start',
                            $stage->id
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-play-fill me-1"></i>

                            Start Stage

                        </button>

                    </form>

                @endif


                @if($stage->stage_status === 'In Progress')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fitout.stages.complete',
                            $stage->id
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Complete Stage

                        </button>

                    </form>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fitout.stages.hold',
                            $stage->id
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="bi bi-pause-circle me-1"></i>

                            Put On Hold

                        </button>

                    </form>

                @endif


                @if($stage->stage_status === 'On Hold')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.fitout.stages.resume',
                            $stage->id
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-play-circle me-1"></i>

                            Resume Stage

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection