@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Asset Lifecycle Plan
            </h4>

            <p class="text-muted mb-0">
                Update lifecycle and replacement planning information.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.assets.operations.lifecycle.show', $lifecycle) }}"
               class="btn btn-outline-info">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route('admin.assets.operations.lifecycle.index') }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

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
          action="{{ route('admin.assets.operations.lifecycle.update', $lifecycle) }}">

        @csrf
        @method('PUT')


        {{-- Lifecycle Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="ri-information-line me-1"></i>
                    Lifecycle Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Asset <span class="text-danger">*</span>
                        </label>

                        <select name="asset_id"
                                class="form-select"
                                required>

                            @foreach($assets as $asset)

                                <option value="{{ $asset->id }}"
                                    @selected(old('asset_id', $lifecycle->asset_id) == $asset->id)>

                                    {{ $asset->asset_code }}
                                    -
                                    {{ $asset->asset_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Lifecycle Stage
                        </label>

                        <select name="lifecycle_stage"
                                class="form-select"
                                required>

                            @foreach([
                                'Planning',
                                'Acquisition',
                                'Active',
                                'Maintenance',
                                'Renewal',
                                'Replacement',
                                'Disposal',
                                'Disposed'
                            ] as $stage)

                                <option value="{{ $stage }}"
                                    @selected(old('lifecycle_stage', $lifecycle->lifecycle_stage) === $stage)>

                                    {{ $stage }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="lifecycle_status"
                                class="form-select"
                                required>

                            @foreach([
                                'Planned',
                                'In Progress',
                                'Active',
                                'Due',
                                'Completed',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(old('lifecycle_status', $lifecycle->lifecycle_status) === $status)>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Priority
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
                                    @selected(old('priority', $lifecycle->priority) === $priority)>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Lifecycle Score
                        </label>

                        <input type="number"
                               name="lifecycle_score"
                               class="form-control"
                               min="0"
                               max="100"
                               step="0.01"
                               value="{{ old('lifecycle_score', $lifecycle->lifecycle_score) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Recommended Action
                        </label>

                        <select name="recommended_action"
                                class="form-select">

                            <option value="">
                                Select Action
                            </option>

                            @foreach([
                                'Continue Operation',
                                'Monitor',
                                'Repair',
                                'Upgrade',
                                'Renew',
                                'Replace',
                                'Dispose'
                            ] as $action)

                                <option value="{{ $action }}"
                                    @selected(old('recommended_action', $lifecycle->recommended_action) === $action)>

                                    {{ $action }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Dates --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="ri-calendar-line me-1"></i>
                    Lifecycle Dates & Useful Life
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Acquisition Date
                        </label>

                        <input type="date"
                               name="acquisition_date"
                               class="form-control"
                               value="{{ old('acquisition_date', optional($lifecycle->acquisition_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Commissioning Date
                        </label>

                        <input type="date"
                               name="commissioning_date"
                               class="form-control"
                               value="{{ old('commissioning_date', optional($lifecycle->commissioning_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Expected Life (Years)
                        </label>

                        <input type="number"
                               name="expected_life_years"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('expected_life_years', $lifecycle->expected_life_years) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Remaining Life (Years)
                        </label>

                        <input type="number"
                               name="remaining_life_years"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('remaining_life_years', $lifecycle->remaining_life_years) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Planned Renewal Date
                        </label>

                        <input type="date"
                               name="planned_renewal_date"
                               class="form-control"
                               value="{{ old('planned_renewal_date', optional($lifecycle->planned_renewal_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Planned Replacement Date
                        </label>

                        <input type="date"
                               name="planned_replacement_date"
                               class="form-control"
                               value="{{ old('planned_replacement_date', optional($lifecycle->planned_replacement_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Actual Replacement Date
                        </label>

                        <input type="date"
                               name="actual_replacement_date"
                               class="form-control"
                               value="{{ old('actual_replacement_date', optional($lifecycle->actual_replacement_date)->format('Y-m-d')) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="ri-money-rupee-circle-line me-1"></i>
                    Financial Information
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Acquisition Cost
                        </label>

                        <input type="number"
                               name="acquisition_cost"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('acquisition_cost', $lifecycle->acquisition_cost) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Current Book Value
                        </label>

                        <input type="number"
                               name="current_book_value"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('current_book_value', $lifecycle->current_book_value) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Estimated Replacement Cost
                        </label>

                        <input type="number"
                               name="estimated_replacement_cost"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('estimated_replacement_cost', $lifecycle->estimated_replacement_cost) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Residual Value
                        </label>

                        <input type="number"
                               name="residual_value"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('residual_value', $lifecycle->residual_value) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Depreciation %
                        </label>

                        <input type="number"
                               name="depreciation_percentage"
                               class="form-control"
                               min="0"
                               max="100"
                               step="0.01"
                               value="{{ old('depreciation_percentage', $lifecycle->depreciation_percentage) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Review --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="ri-user-settings-line me-1"></i>
                    Review & Responsibility
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Responsible User
                        </label>

                        <select name="responsible_user_id"
                                class="form-select">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}"
                                    @selected(old('responsible_user_id', $lifecycle->responsible_user_id) == $user->id)>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Last Reviewed Date
                        </label>

                        <input type="date"
                               name="last_reviewed_at"
                               class="form-control"
                               value="{{ old('last_reviewed_at', optional($lifecycle->last_reviewed_at)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Next Review Date
                        </label>

                        <input type="date"
                               name="next_review_date"
                               class="form-control"
                               value="{{ old('next_review_date', optional($lifecycle->next_review_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="4"
                                  class="form-control">{{ old('remarks', $lifecycle->remarks) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.assets.operations.lifecycle.index') }}"
               class="btn btn-light">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Update Lifecycle Plan

            </button>

        </div>

    </form>

</div>

@endsection