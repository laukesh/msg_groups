@extends('layouts.app')

@section('title', 'Edit Manpower')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Manpower
            </h4>

            <div class="text-muted">

                {{ $project->project_number }}
                -
                {{ $project->project_name }}

                <span class="mx-1">|</span>

                {{ $manpower->manpower_code }}

            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.construction.manpower.show',
                [
                    'project' => $project,
                    'manpower' => $manpower
                ]
            ) }}"
            class="btn btn-outline-secondary">

            ← Back to Details

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.construction.manpower.update',
            [
                'project' => $project,
                'manpower' => $manpower
            ]
        ) }}">

        @csrf

        @method('PUT')


        {{-- Manpower Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Manpower Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Manpower Code --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Manpower Code
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $manpower->manpower_code }}"
                            readonly>

                    </div>


                    {{-- Project --}}
                    <div class="col-md-8">

                        <label class="form-label">
                            Project
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_number }} - {{ $project->project_name }}"
                            readonly>

                    </div>


                    {{-- Name --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="manpower_name"
                            class="form-control @error('manpower_name') is-invalid @enderror"
                            value="{{ old(
                                'manpower_name',
                                $manpower->manpower_name
                            ) }}"
                            required>

                        @error('manpower_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Manpower Type --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Manpower Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="manpower_type"
                            class="form-select"
                            required>

                            @foreach([
                                'Skilled',
                                'Semi-Skilled',
                                'Unskilled',
                                'Supervisor',
                                'Engineer',
                                'Technician',
                                'Operator',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'manpower_type',
                                            $manpower->manpower_type
                                        ) === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Trade --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Trade
                        </label>

                        <input
                            type="text"
                            name="trade"
                            class="form-control"
                            value="{{ old(
                                'trade',
                                $manpower->trade
                            ) }}"
                            placeholder="Mason, Electrician...">

                    </div>


                    {{-- Employment Type --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Employment Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="employment_type"
                            class="form-select"
                            required>

                            @foreach([
                                'Direct',
                                'Contract',
                                'Subcontract',
                                'Temporary'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'employment_type',
                                            $manpower->employment_type
                                        ) === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old(
                                'phone',
                                $manpower->phone
                            ) }}">

                    </div>


                    {{-- Joining Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Joining Date
                        </label>

                        <input
                            type="date"
                            name="joining_date"
                            class="form-control"
                            value="{{ old(
                                'joining_date',
                                optional(
                                    $manpower->joining_date
                                )->format('Y-m-d')
                            ) }}">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option
                                value="Active"
                                @selected(
                                    old(
                                        'status',
                                        $manpower->status
                                    ) === 'Active'
                                )>

                                Active

                            </option>

                            <option
                                value="Inactive"
                                @selected(
                                    old(
                                        'status',
                                        $manpower->status
                                    ) === 'Inactive'
                                )>

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    class="form-control"
                    rows="4"
                    placeholder="Enter remarks...">{{ old(
                        'remarks',
                        $manpower->remarks
                    ) }}</textarea>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.manpower.show',
                    [
                        'project' => $project,
                        'manpower' => $manpower
                    ]
                ) }}"
                class="btn btn-outline-secondary">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                Update Manpower

            </button>

        </div>

    </form>

</div>

@endsection