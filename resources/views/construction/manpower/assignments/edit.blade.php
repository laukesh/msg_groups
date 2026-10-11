@extends('layouts.app')

@section('title', 'Edit Manpower Assignment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Manpower Assignment
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.show',
                [
                    'project' => $project,
                    'assignment' => $assignment
                ]
            ) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Assignment Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Assignment Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.construction.manpower.assignments.update',
                      [
                          'project' => $project,
                          'assignment' => $assignment
                      ]
                  ) }}">

                @csrf
                @method('PUT')


                <div class="row g-3">


                    {{-- Assignment Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Assignment Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $assignment->assignment_number }}"
                               readonly>

                    </div>


                    {{-- Project --}}
                    <div class="col-md-8">

                        <label class="form-label">
                            Project
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $project->project_number }} - {{ $project->project_name }}"
                               readonly>

                    </div>


                    {{-- Manpower --}}
                    <div class="col-md-6">

                        <label for="manpower_id" class="form-label">
                            Manpower <span class="text-danger">*</span>
                        </label>

                        <select name="manpower_id"
                                id="manpower_id"
                                class="form-select @error('manpower_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Manpower
                            </option>

                            @foreach ($manpower as $person)

                                <option value="{{ $person->id }}"
                                    {{ old(
                                        'manpower_id',
                                        $assignment->manpower_id
                                    ) == $person->id ? 'selected' : '' }}>

                                    {{ $person->manpower_code }}
                                    -
                                    {{ $person->manpower_name }}

                                    @if ($person->trade)
                                        ({{ $person->trade }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('manpower_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Work Order --}}
                    <div class="col-md-6">

                        <label for="construction_work_order_id"
                               class="form-label">

                            Construction Work Order

                        </label>

                        <select name="construction_work_order_id"
                                id="construction_work_order_id"
                                class="form-select @error('construction_work_order_id') is-invalid @enderror">

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach ($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    {{ old(
                                        'construction_work_order_id',
                                        $assignment->construction_work_order_id
                                    ) == $workOrder->id ? 'selected' : '' }}>

                                    {{ $workOrder->work_order_number }}

                                    @if (!empty($workOrder->work_order_title))
                                        -
                                        {{ $workOrder->work_order_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('construction_work_order_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Assignment Date --}}
                    <div class="col-md-4">

                        <label for="assignment_date"
                               class="form-label">

                            Assignment Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="assignment_date"
                               id="assignment_date"
                               class="form-control @error('assignment_date') is-invalid @enderror"
                               value="{{ old(
                                   'assignment_date',
                                   optional($assignment->assignment_date)->format('Y-m-d')
                               ) }}"
                               required>

                        @error('assignment_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Release Date --}}
                    <div class="col-md-4">

                        <label for="release_date"
                               class="form-label">

                            Release Date

                        </label>

                        <input type="date"
                               name="release_date"
                               id="release_date"
                               class="form-control @error('release_date') is-invalid @enderror"
                               value="{{ old(
                                   'release_date',
                                   optional($assignment->release_date)->format('Y-m-d')
                               ) }}">

                        @error('release_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Daily Rate --}}
                    <div class="col-md-4">

                        <label for="daily_rate"
                               class="form-label">

                            Daily Rate
                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="daily_rate"
                                   id="daily_rate"
                                   class="form-control @error('daily_rate') is-invalid @enderror"
                                   value="{{ old(
                                       'daily_rate',
                                       $assignment->daily_rate
                                   ) }}"
                                   min="0"
                                   step="0.01"
                                   required>

                        </div>

                        @error('daily_rate')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Role --}}
                    <div class="col-md-6">

                        <label for="role"
                               class="form-label">

                            Role / Responsibility

                        </label>

                        <input type="text"
                               name="role"
                               id="role"
                               class="form-control @error('role') is-invalid @enderror"
                               value="{{ old(
                                   'role',
                                   $assignment->role
                               ) }}"
                               placeholder="e.g. Mason, Electrician, Site Engineer">

                        @error('role')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="Planned"
                                {{ old(
                                    'status',
                                    $assignment->status
                                ) == 'Planned' ? 'selected' : '' }}>

                                Planned

                            </option>

                            <option value="Active"
                                {{ old(
                                    'status',
                                    $assignment->status
                                ) == 'Active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="Released"
                                {{ old(
                                    'status',
                                    $assignment->status
                                ) == 'Released' ? 'selected' : '' }}>

                                Released

                            </option>

                            <option value="Cancelled"
                                {{ old(
                                    'status',
                                    $assignment->status
                                ) == 'Cancelled' ? 'selected' : '' }}>

                                Cancelled

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-12">

                        <label for="remarks"
                               class="form-label">

                            Remarks

                        </label>

                        <textarea name="remarks"
                                  id="remarks"
                                  rows="4"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Enter remarks">{{ old(
                                      'remarks',
                                      $assignment->remarks
                                  ) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route(
                        'admin.projects.construction.manpower.assignments.show',
                        [
                            'project' => $project,
                            'assignment' => $assignment
                        ]
                    ) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg"></i>
                        Update Assignment

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Current Status Information --}}
    <div class="card shadow-sm">

        <div class="card-body">

            <div class="d-flex align-items-start">

                <div class="me-3">

                    @if ($assignment->status === 'Planned')

                        <span class="badge bg-warning text-dark">
                            Planned
                        </span>

                    @elseif ($assignment->status === 'Active')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @elseif ($assignment->status === 'Released')

                        <span class="badge bg-secondary">
                            Released
                        </span>

                    @elseif ($assignment->status === 'Cancelled')

                        <span class="badge bg-danger">
                            Cancelled
                        </span>

                    @endif

                </div>

                <div>

                    <h6 class="mb-1">
                        Assignment Status
                    </h6>

                    <p class="text-muted mb-0">

                        Assignment Number:
                        <strong>
                            {{ $assignment->assignment_number }}
                        </strong>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection