@extends('layouts.app')

@section('title', 'Add Risk Action')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Risk Action
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                -
                {{ $risk->risk_title }}
            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.risks.actions.index',
            [$project, $risk]
        ) }}"
        class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Actions

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.risks.actions.store',
              [$project, $risk]
          ) }}">

        @csrf


        {{-- Action Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Action Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-8">

                        <label class="form-label">
                            Action Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="action_title"
                               class="form-control"
                               value="{{ old('action_title') }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Action Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="action_type"
                                class="form-select"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Preventive',
                                'Mitigation',
                                'Corrective',
                                'Contingency',
                                'Monitoring'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old('action_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Action Description
                        </label>

                        <textarea name="action_description"
                                  class="form-control"
                                  rows="4">{{ old('action_description') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Assignment --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Assignment
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Assign To User
                        </label>

                        <select name="assigned_to"
                                class="form-select">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}"
                                    @selected(
                                        old('assigned_to') == $user->id
                                    )>

                                    {{ $user->name }}

                                    @if($user->email)
                                        - {{ $user->email }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            External / Other Assignee
                        </label>

                        <input type="text"
                               name="assigned_to_name"
                               class="form-control"
                               value="{{ old('assigned_to_name') }}"
                               placeholder="Contractor / Consultant / Other">

                    </div>

                </div>

            </div>

        </div>


        {{-- Schedule --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Action Schedule
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Target Date
                        </label>

                        <input type="date"
                               name="target_date"
                               class="form-control"
                               value="{{ old('target_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Completion Date
                        </label>

                        <input type="date"
                               name="completion_date"
                               class="form-control"
                               value="{{ old('completion_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Priority
                            <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select"
                                required>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(
                                        old(
                                            'priority',
                                            'Medium'
                                        ) === $priority
                                    )>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            @foreach([
                                'Open',
                                'In Progress',
                                'Completed',
                                'Overdue',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Open'
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                <textarea name="remarks"
                          class="form-control"
                          rows="4"
                          placeholder="Additional remarks">{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.actions.index',
                [$project, $risk]
            ) }}"
            class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-save"></i>
                Save Action

            </button>

        </div>

    </form>

</div>

@endsection