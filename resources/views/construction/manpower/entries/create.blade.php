@extends('layouts.app')

@section('title', 'Add Daily Manpower Entry')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Add Daily Manpower Entry
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.manpower.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-people"></i>
                Manpower

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            ) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-person-check"></i>
                Assignments

            </a>

            <a href="{{ route(
                'admin.projects.construction.manpower.entries.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Daily Entries

            </a>

        </div>

    </div>


    {{-- Errors --}}
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


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.manpower.entries.store',
              $project
          ) }}">

        @csrf


        {{-- Assignment --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Assignment
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Assignment --}}
                    <div class="col-md-6">

                        <label for="manpower_assignment_id"
                               class="form-label">

                            Manpower Assignment
                            <span class="text-danger">*</span>

                        </label>

                        <select name="manpower_assignment_id"
                                id="manpower_assignment_id"
                                class="form-select @error('manpower_assignment_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Active Assignment
                            </option>

                            @foreach ($assignments as $assignment)

                                <option
                                    value="{{ $assignment->id }}"
                                    data-manpower-id="{{ $assignment->manpower_id }}"
                                    data-manpower-code="{{ $assignment->manpower->manpower_code ?? '' }}"
                                    data-manpower-name="{{ $assignment->manpower->manpower_name ?? '' }}"
                                    data-trade="{{ $assignment->manpower->trade ?? '' }}"
                                    data-type="{{ $assignment->manpower->manpower_type ?? '' }}"
                                    data-employment="{{ $assignment->manpower->employment_type ?? '' }}"
                                    data-work-order="{{ $assignment->workOrder->work_order_number ?? '' }}"
                                    data-daily-rate="{{ $assignment->daily_rate }}"
                                    {{ old(
                                        'manpower_assignment_id',
                                        optional($selectedAssignment)->id
                                    ) == $assignment->id ? 'selected' : '' }}>

                                    {{ $assignment->assignment_number }}

                                    -
                                    {{ $assignment->manpower->manpower_name ?? 'Unknown Manpower' }}

                                    @if($assignment->workOrder)
                                        -
                                        {{ $assignment->workOrder->work_order_number }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('manpower_assignment_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Only active manpower assignments are available.
                        </div>

                    </div>


                    {{-- Entry Date --}}
                    <div class="col-md-3">

                        <label for="entry_date"
                               class="form-label">

                            Entry Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="entry_date"
                               id="entry_date"
                               class="form-control @error('entry_date') is-invalid @enderror"
                               value="{{ old(
                                   'entry_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               required>

                        @error('entry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Attendance --}}
                    <div class="col-md-3">

                        <label for="attendance_status"
                               class="form-label">

                            Attendance
                            <span class="text-danger">*</span>

                        </label>

                        <select name="attendance_status"
                                id="attendance_status"
                                class="form-select @error('attendance_status') is-invalid @enderror"
                                required>

                            <option value="Present"
                                {{ old('attendance_status', 'Present') === 'Present'
                                    ? 'selected'
                                    : '' }}>

                                Present

                            </option>

                            <option value="Absent"
                                {{ old('attendance_status') === 'Absent'
                                    ? 'selected'
                                    : '' }}>

                                Absent

                            </option>

                            <option value="Half Day"
                                {{ old('attendance_status') === 'Half Day'
                                    ? 'selected'
                                    : '' }}>

                                Half Day

                            </option>

                            <option value="Leave"
                                {{ old('attendance_status') === 'Leave'
                                    ? 'selected'
                                    : '' }}>

                                Leave

                            </option>

                        </select>

                        @error('attendance_status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Manpower Information --}}
        <div class="card shadow-sm mb-4"
             id="manpowerInformation">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Manpower Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Manpower Code
                        </div>

                        <div class="fw-semibold"
                             id="displayManpowerCode">
                            —
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Manpower Name
                        </div>

                        <div class="fw-semibold"
                             id="displayManpowerName">
                            —
                        </div>

                    </div>


                    <div class="col-md-2">

                        <div class="text-muted small">
                            Type
                        </div>

                        <div class="fw-semibold"
                             id="displayManpowerType">
                            —
                        </div>

                    </div>


                    <div class="col-md-2">

                        <div class="text-muted small">
                            Trade
                        </div>

                        <div class="fw-semibold"
                             id="displayTrade">
                            —
                        </div>

                    </div>


                    <div class="col-md-2">

                        <div class="text-muted small">
                            Daily Rate
                        </div>

                        <div class="fw-semibold">
                            $<span id="displayDailyRate">0.00</span>
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small">
                            Work Order
                        </div>

                        <div class="fw-semibold"
                             id="displayWorkOrder">
                            —
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small">
                            Employment Type
                        </div>

                        <div class="fw-semibold"
                             id="displayEmploymentType">
                            —
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Attendance & Hours --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Attendance & Hours
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Regular Hours --}}
                    <div class="col-md-4">

                        <label for="regular_hours"
                               class="form-label">

                            Regular Hours

                        </label>

                        <input type="number"
                               name="regular_hours"
                               id="regular_hours"
                               class="form-control @error('regular_hours') is-invalid @enderror"
                               value="{{ old('regular_hours', 8) }}"
                               min="0"
                               max="24"
                               step="0.01">

                        @error('regular_hours')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Overtime Hours --}}
                    <div class="col-md-4">

                        <label for="overtime_hours"
                               class="form-label">

                            Overtime Hours

                        </label>

                        <input type="number"
                               name="overtime_hours"
                               id="overtime_hours"
                               class="form-control @error('overtime_hours') is-invalid @enderror"
                               value="{{ old('overtime_hours', 0) }}"
                               min="0"
                               max="24"
                               step="0.01">

                        @error('overtime_hours')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Total Hours --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Total Hours
                        </label>

                        <input type="text"
                               id="total_hours_display"
                               class="form-control"
                               value="8.00"
                               readonly>

                    </div>


                    {{-- Overtime Rate --}}
                    <div class="col-md-4">

                        <label for="overtime_rate"
                               class="form-label">

                            Overtime Rate / Hour

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="overtime_rate"
                                   id="overtime_rate"
                                   class="form-control @error('overtime_rate') is-invalid @enderror"
                                   value="{{ old('overtime_rate', 0) }}"
                                   min="0"
                                   step="0.01">

                        </div>

                        @error('overtime_rate')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Daily Rate --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Daily Rate
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="text"
                                   id="daily_rate_display"
                                   class="form-control"
                                   value="0.00"
                                   readonly>

                        </div>

                    </div>


                    {{-- Total Cost --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Estimated Total Cost
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="text"
                                   id="total_cost_display"
                                   class="form-control fw-semibold"
                                   value="0.00"
                                   readonly>

                        </div>

                        <div class="form-text">
                            Final cost is calculated by the server.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Work Details --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Work Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Work Order --}}
                    <div class="col-md-6">

                        <label for="construction_work_order_id"
                               class="form-label">

                            Work Order

                        </label>

                        <select name="construction_work_order_id"
                                id="construction_work_order_id"
                                class="form-select @error('construction_work_order_id') is-invalid @enderror">

                            <option value="">
                                Use Assignment Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    {{ old('construction_work_order_id') == $workOrder->id ? 'selected' : '' }}>

                                    {{ $workOrder->work_order_number }}

                                    @if(!empty($workOrder->work_order_title))
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

                        <div class="form-text">
                            Leave blank to use the work order from the assignment.
                        </div>

                    </div>


                    {{-- Work Description --}}
                    <div class="col-md-6">

                        <label for="work_description"
                               class="form-label">

                            Work Description

                        </label>

                        <textarea name="work_description"
                                  id="work_description"
                                  rows="3"
                                  class="form-control @error('work_description') is-invalid @enderror"
                                  placeholder="Describe work performed today">{{ old('work_description') }}</textarea>

                        @error('work_description')

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
                                  rows="3"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Additional remarks">{{ old('remarks') }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route(
                'admin.projects.construction.manpower.entries.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg"></i>
                Save Daily Entry

            </button>

        </div>

    </form>

</div>


{{-- Calculation Script --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const assignmentSelect =
        document.getElementById('manpower_assignment_id');

    const attendanceSelect =
        document.getElementById('attendance_status');

    const regularHoursInput =
        document.getElementById('regular_hours');

    const overtimeHoursInput =
        document.getElementById('overtime_hours');

    const overtimeRateInput =
        document.getElementById('overtime_rate');

    const totalHoursDisplay =
        document.getElementById('total_hours_display');

    const dailyRateDisplay =
        document.getElementById('daily_rate_display');

    const totalCostDisplay =
        document.getElementById('total_cost_display');


    function getSelectedAssignment()
    {
        if (!assignmentSelect.value) {
            return null;
        }

        return assignmentSelect.options[
            assignmentSelect.selectedIndex
        ];
    }


    function updateAssignmentInformation()
    {
        const option = getSelectedAssignment();

        if (!option) {

            document.getElementById(
                'displayManpowerCode'
            ).textContent = '—';

            document.getElementById(
                'displayManpowerName'
            ).textContent = '—';

            document.getElementById(
                'displayManpowerType'
            ).textContent = '—';

            document.getElementById(
                'displayTrade'
            ).textContent = '—';

            document.getElementById(
                'displayEmploymentType'
            ).textContent = '—';

            document.getElementById(
                'displayWorkOrder'
            ).textContent = '—';

            document.getElementById(
                'displayDailyRate'
            ).textContent = '0.00';

            dailyRateDisplay.value = '0.00';

            calculate();

            return;
        }


        const dailyRate =
            parseFloat(
                option.dataset.dailyRate || 0
            );


        document.getElementById(
            'displayManpowerCode'
        ).textContent =
            option.dataset.manpowerCode || '—';


        document.getElementById(
            'displayManpowerName'
        ).textContent =
            option.dataset.manpowerName || '—';


        document.getElementById(
            'displayManpowerType'
        ).textContent =
            option.dataset.type || '—';


        document.getElementById(
            'displayTrade'
        ).textContent =
            option.dataset.trade || '—';


        document.getElementById(
            'displayEmploymentType'
        ).textContent =
            option.dataset.employment || '—';


        document.getElementById(
            'displayWorkOrder'
        ).textContent =
            option.dataset.workOrder || '—';


        document.getElementById(
            'displayDailyRate'
        ).textContent =
            dailyRate.toFixed(2);


        dailyRateDisplay.value =
            dailyRate.toFixed(2);


        calculate();
    }


    function calculate()
    {
        let regularHours =
            parseFloat(
                regularHoursInput.value || 0
            );

        let overtimeHours =
            parseFloat(
                overtimeHoursInput.value || 0
            );

        const attendance =
            attendanceSelect.value;


        if (
            attendance === 'Absent' ||
            attendance === 'Leave'
        ) {

            regularHours = 0;
            overtimeHours = 0;
        }


        if (
            attendance === 'Half Day' &&
            regularHours <= 0
        ) {

            regularHours = 4;
        }


        const dailyRate =
            parseFloat(
                dailyRateDisplay.value || 0
            );

        const overtimeRate =
            parseFloat(
                overtimeRateInput.value || 0
            );


        const totalHours =
            regularHours + overtimeHours;


        const regularCost =
            dailyRate * (regularHours / 8);


        const overtimeCost =
            overtimeHours * overtimeRate;


        const totalCost =
            regularCost + overtimeCost;


        totalHoursDisplay.value =
            totalHours.toFixed(2);


        totalCostDisplay.value =
            totalCost.toFixed(2);
    }


    assignmentSelect.addEventListener(
        'change',
        updateAssignmentInformation
    );


    attendanceSelect.addEventListener(
        'change',
        function () {

            if (
                this.value === 'Absent' ||
                this.value === 'Leave'
            ) {

                regularHoursInput.value = 0;
                overtimeHoursInput.value = 0;
            }

            else if (
                this.value === 'Half Day'
            ) {

                regularHoursInput.value = 4;

                overtimeHoursInput.value = 0;
            }

            else if (
                this.value === 'Present' &&
                parseFloat(regularHoursInput.value || 0) === 0
            ) {

                regularHoursInput.value = 8;
            }

            calculate();
        }
    );


    regularHoursInput.addEventListener(
        'input',
        calculate
    );


    overtimeHoursInput.addEventListener(
        'input',
        calculate
    );


    overtimeRateInput.addEventListener(
        'input',
        calculate
    );


    updateAssignmentInformation();

});

</script>

@endsection