@extends('layouts.app')

@section('title', 'Assign Manpower')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Assign Manpower
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <a href="{{ route(
            'admin.projects.construction.manpower.assignments.index',
            $project
        ) }}"
           class="btn btn-outline-secondary">

            ← Back to Assignments

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.manpower.assignments.store',
              $project
          ) }}">

        @csrf


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Assignment Details
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Manpower --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Manpower <span class="text-danger">*</span>
                        </label>

                        <select
                            name="manpower_id"
                            id="manpower_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Manpower
                            </option>

                            @foreach($manpower as $person)

                                <option
                                    value="{{ $person->id }}"
                                    data-rate="{{ $person->daily_rate ?? 0 }}"
                                    @selected(
                                        old('manpower_id') == $person->id
                                    )
                                >

                                    {{ $person->manpower_code }}
                                    -
                                    {{ $person->manpower_name }}

                                    @if($person->trade)
                                        ({{ $person->trade }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Work Order --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select
                            name="construction_work_order_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option
                                    value="{{ $workOrder->id }}"
                                    @selected(
                                        old(
                                            'construction_work_order_id'
                                        ) == $workOrder->id
                                    )
                                >

                                    {{ $workOrder->work_order_number }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Assignment Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Assignment Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="assignment_date"
                            class="form-control"
                            value="{{ old(
                                'assignment_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Release Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Release Date
                        </label>

                        <input
                            type="date"
                            name="release_date"
                            class="form-control"
                            value="{{ old('release_date') }}"
                        >

                    </div>


                    {{-- Role --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Role
                        </label>

                        <input
                            type="text"
                            name="role"
                            class="form-control"
                            value="{{ old('role') }}"
                            placeholder="e.g. Mason, Electrician, Supervisor"
                        >

                    </div>


                    {{-- Daily Rate --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Daily Rate
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="daily_rate"
                            class="form-control"
                            value="{{ old('daily_rate', 0) }}"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Planned',
                                'Active',
                                'Released',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Planned'
                                        ) === $status
                                    )
                                >

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                            placeholder="Enter remarks..."
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-light">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-success"
            >

                Save Assignment

            </button>

        </div>

    </form>

</div>

@endsection