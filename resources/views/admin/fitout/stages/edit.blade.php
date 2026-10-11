@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Edit Fit-Out Stage</h4>
            <p class="text-muted mb-0">
                {{ $stage->fitoutRequest->request_no ?? '-' }}
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.stages.show', $stage->id) }}"
            class="btn btn-secondary"
        >
            Back
        </a>
    </div>


    <div class="card">

        <div class="card-header">
            <strong>
                {{ $stage->stage_sequence }}.
                {{ $stage->stage_name }}
            </strong>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.fitout.stages.update', $stage->id) }}"
            >

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- Planned Start --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="planned_start_date"
                            class="form-control"
                            value="{{ old('planned_start_date', $stage->planned_start_date?->format('Y-m-d')) }}"
                        >

                    </div>


                    {{-- Planned End --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Planned End Date
                        </label>

                        <input
                            type="date"
                            name="planned_end_date"
                            class="form-control"
                            value="{{ old('planned_end_date', $stage->planned_end_date?->format('Y-m-d')) }}"
                        >

                    </div>


                    {{-- Actual Start --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Actual Start Date
                        </label>

                        <input
                            type="date"
                            name="actual_start_date"
                            class="form-control"
                            value="{{ old('actual_start_date', $stage->actual_start_date?->format('Y-m-d')) }}"
                        >

                    </div>


                    {{-- Actual End --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Actual End Date
                        </label>

                        <input
                            type="date"
                            name="actual_end_date"
                            class="form-control"
                            value="{{ old('actual_end_date', $stage->actual_end_date?->format('Y-m-d')) }}"
                        >

                    </div>


                    {{-- Completion --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Completion Percentage
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="completion_percentage"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.01"
                                value="{{ old('completion_percentage', $stage->completion_percentage) }}"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Stage Status
                        </label>

                        <select
                            name="stage_status"
                            class="form-select"
                        >

                            @foreach([
                                'Pending',
                                'In Progress',
                                'Completed',
                                'On Hold',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(old('stage_status', $stage->stage_status) === $status)
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Engineer --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Engineer ID
                        </label>

                        <input
                            type="number"
                            name="engineer_id"
                            class="form-control"
                            value="{{ old('engineer_id', $stage->engineer_id) }}"
                        >

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="4"
                        >{{ old('remarks', $stage->remarks) }}</textarea>

                    </div>

                </div>


                <div class="text-end">

                    <a
                        href="{{ route('admin.fitout.stages.show', $stage->id) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Stage
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection