@csrf

<div class="row g-3">

    {{-- =========================================================
        CONSTRUCTION WORK ORDER
    ========================================================== --}}
    <div class="col-md-6">

        <label class="form-label">
            Construction Work Order <span class="text-danger">*</span>
        </label>

        <select
            name="construction_work_order_id"
            class="form-select @error('construction_work_order_id') is-invalid @enderror"
            required
        >

            <option value="">
                Select Work Order
            </option>

            @foreach($workOrders as $wo)

                <option
                    value="{{ $wo->id }}"
                    @selected(
                        (string) old(
                            'construction_work_order_id',
                            $scope->construction_work_order_id ?? ''
                        ) === (string) $wo->id
                    )
                >
                    {{ $wo->work_order_number }}
                    -
                    {{ $wo->work_order_title }}
                </option>

            @endforeach

        </select>

        @error('construction_work_order_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Select an existing Construction Work Order for this project.
        </div>

    </div>


    {{-- =========================================================
        SCOPE CODE
    ========================================================== --}}
    <div class="col-md-3">

        <label class="form-label">
            Scope Code <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="scope_code"
            value="{{ old('scope_code', $scope->scope_code ?? '') }}"
            class="form-control @error('scope_code') is-invalid @enderror"
            maxlength="50"
            placeholder="e.g. COM-001"
            required
        >

        @error('scope_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        SCOPE TYPE
    ========================================================== --}}
    <div class="col-md-3">

        <label class="form-label">
            Scope Type
        </label>

        <input
            type="text"
            name="scope_type"
            value="{{ old('scope_type', $scope->scope_type ?? '') }}"
            class="form-control @error('scope_type') is-invalid @enderror"
            maxlength="100"
            placeholder="Electrical / HVAC / Civil"
        >

        @error('scope_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        SCOPE NAME
    ========================================================== --}}
    <div class="col-md-8">

        <label class="form-label">
            Scope Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="scope_name"
            value="{{ old('scope_name', $scope->scope_name ?? '') }}"
            class="form-control @error('scope_name') is-invalid @enderror"
            maxlength="255"
            placeholder="e.g. Electrical System Commissioning"
            required
        >

        @error('scope_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        LOCATION
    ========================================================== --}}
    <div class="col-md-4">

        <label class="form-label">
            Location
        </label>

        <input
            type="text"
            name="location"
            value="{{ old('location', $scope->location ?? '') }}"
            class="form-control @error('location') is-invalid @enderror"
            maxlength="255"
            placeholder="Block / Floor / Area"
        >

        @error('location')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        PLANNED START
    ========================================================== --}}
    <div class="col-md-4">

        <label class="form-label">
            Planned Start
        </label>

        <input
            type="date"
            name="planned_start_date"
            value="{{ old(
                'planned_start_date',
                isset($scope) && $scope->planned_start_date
                    ? $scope->planned_start_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control @error('planned_start_date') is-invalid @enderror"
        >

        @error('planned_start_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        PLANNED COMPLETION
    ========================================================== --}}
    <div class="col-md-4">

        <label class="form-label">
            Planned Completion
        </label>

        <input
            type="date"
            name="planned_completion_date"
            value="{{ old(
                'planned_completion_date',
                isset($scope) && $scope->planned_completion_date
                    ? $scope->planned_completion_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control @error('planned_completion_date') is-invalid @enderror"
        >

        @error('planned_completion_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        ACTUAL COMPLETION
    ========================================================== --}}
    <div class="col-md-4">

        <label class="form-label">
            Actual Completion
        </label>

        <input
            type="date"
            name="actual_completion_date"
            value="{{ old(
                'actual_completion_date',
                isset($scope) && $scope->actual_completion_date
                    ? $scope->actual_completion_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control @error('actual_completion_date') is-invalid @enderror"
        >

        @error('actual_completion_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        STATUS
    ========================================================== --}}
    <div class="col-md-4">

        <label class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        @php
            $statuses = [
                'Planned',
                'In Progress',
                'Testing',
                'Completed',
                'Accepted',
                'On Hold',
            ];

            $selectedStatus = old(
                'status',
                $scope->status ?? 'Planned'
            );
        @endphp

        <select
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >

            @foreach($statuses as $value)

                <option
                    value="{{ $value }}"
                    @selected($selectedStatus === $value)
                >
                    {{ $value }}
                </option>

            @endforeach

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}
    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="4"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Enter commissioning scope remarks..."
        >{{ old('remarks', $scope->remarks ?? '') }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>