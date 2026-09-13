@php
    $test = $test ?? null;
    $parentTest = $parentTest ?? null;
    $isRetest = $isRetest ?? false;

    $selectedScope = old(
        'commissioning_scope_id',
        $test?->commissioning_scope_id ?? $parentTest?->commissioning_scope_id
    );

    $selectedPlan = old(
        'test_plan_id',
        $test?->test_plan_id ?? $parentTest?->test_plan_id
    );

    $selectedParent = old(
        'parent_test_id',
        $test?->parent_test_id ?? $parentTest?->id
    );
@endphp

@csrf

@if($isRetest && $parentTest)
    <div class="alert alert-warning">
        <div class="fw-semibold">
            <i class="bi bi-arrow-repeat me-1"></i>
            Retest Execution
        </div>

        <div class="small mt-1">
            This test is being created as a retest of
            <strong>{{ $parentTest->test_no }}</strong>.
        </div>

        <input
            type="hidden"
            name="parent_test_id"
            value="{{ $parentTest->id }}"
        >
    </div>
@endif


{{-- ============================================================= --}}
{{-- TEST INFORMATION --}}
{{-- ============================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-clipboard-check me-1"></i>
            Test Information
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            {{-- Scope --}}
            <div class="col-md-6">

                <label class="form-label">
                    Commissioning Scope
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="commissioning_scope_id"
                    id="commissioning_scope_id"
                    class="form-select @error('commissioning_scope_id') is-invalid @enderror"
                    required
                >

                    <option value="">
                        Select Scope
                    </option>

                    @foreach($scopes as $scope)

                        <option
                            value="{{ $scope->id }}"
                            @selected((string) $selectedScope === (string) $scope->id)
                        >
                            {{ $scope->scope_code }}
                            -
                            {{ $scope->scope_name }}
                        </option>

                    @endforeach

                </select>

                @error('commissioning_scope_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Test Plan --}}
            <div class="col-md-6">

                <label class="form-label">
                    Test Plan
                </label>

                <select
                    name="test_plan_id"
                    id="test_plan_id"
                    class="form-select @error('test_plan_id') is-invalid @enderror"
                >

                    <option value="">
                        Select Test Plan
                    </option>

                    @foreach($plans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            data-scope="{{ $plan->commissioning_scope_id }}"
                            @selected((string) $selectedPlan === (string) $plan->id)
                        >
                            {{ $plan->test_plan_no }}
                            -
                            {{ $plan->title }}
                        </option>

                    @endforeach

                </select>

                <div class="form-text">
                    Only Test Plans belonging to the selected scope should be selected.
                </div>

                @error('test_plan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Test No --}}
            <div class="col-md-4">

                <label class="form-label">
                    Test Number
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="test_no"
                    value="{{ old('test_no', $test?->test_no) }}"
                    class="form-control @error('test_no') is-invalid @enderror"
                    placeholder="TEST-001"
                    required
                >

                @error('test_no')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Date --}}
            <div class="col-md-4">

                <label class="form-label">
                    Test Date
                </label>

                <input
                    type="date"
                    name="test_date"
                    value="{{ old('test_date', $test?->test_date?->format('Y-m-d')) }}"
                    class="form-control @error('test_date') is-invalid @enderror"
                >

                @error('test_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Time --}}
            <div class="col-md-4">

                <label class="form-label">
                    Test Time
                </label>

                <input
                    type="time"
                    name="test_time"
                    value="{{ old('test_time', $test?->test_time ? \Carbon\Carbon::parse($test->test_time)->format('H:i') : '') }}"
                    class="form-control @error('test_time') is-invalid @enderror"
                >

                @error('test_time')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Test Type --}}
            <div class="col-md-6">

                <label class="form-label">
                    Test Type
                </label>

                <input
                    type="text"
                    name="test_type"
                    value="{{ old('test_type', $test?->test_type ?? $parentTest?->test_type) }}"
                    class="form-control"
                    placeholder="Functional / Performance / Pressure / Electrical..."
                >

            </div>


            {{-- Location --}}
            <div class="col-md-6">

                <label class="form-label">
                    Location
                </label>

                <input
                    type="text"
                    name="location"
                    value="{{ old('location', $test?->location) }}"
                    class="form-control"
                    placeholder="Building / Floor / Room / Equipment location"
                >

            </div>

        </div>

    </div>
</div>


{{-- ============================================================= --}}
{{-- EXPECTED / ACTUAL RESULT --}}
{{-- ============================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-bar-chart-line me-1"></i>
            Test Result
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Expected Result
                </label>

                <textarea
                    name="expected_result"
                    rows="4"
                    class="form-control"
                    placeholder="Enter acceptance / expected result..."
                >{{ old('expected_result', $test?->expected_result ?? $parentTest?->expected_result) }}</textarea>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Actual Result
                </label>

                <textarea
                    name="actual_result"
                    rows="4"
                    class="form-control"
                    placeholder="Enter actual observed result..."
                >{{ old('actual_result', $test?->actual_result) }}</textarea>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Measured Value
                </label>

                <input
                    type="text"
                    name="measured_value"
                    value="{{ old('measured_value', $test?->measured_value) }}"
                    class="form-control"
                    placeholder="e.g. 230"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Unit
                </label>

                <input
                    type="text"
                    name="measured_unit"
                    value="{{ old('measured_unit', $test?->measured_unit) }}"
                    class="form-control"
                    placeholder="V / bar / °C / %"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Result
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="result"
                    class="form-select"
                    required
                >

                    @foreach([
                        'Not Tested',
                        'Pass',
                        'Fail',
                        'Conditional Pass'
                    ] as $result)

                        <option
                            value="{{ $result }}"
                            @selected(old('result', $test?->result ?? 'Not Tested') === $result)
                        >
                            {{ $result }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>
</div>


{{-- ============================================================= --}}
{{-- EXECUTION DETAILS --}}
{{-- ============================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-person-check me-1"></i>
            Execution Details
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Performed By
                </label>

                <select
                    name="performed_by"
                    class="form-select"
                >

                    <option value="">
                        Select User
                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            @selected((string) old('performed_by', $test?->performed_by) === (string) $user->id)
                        >
                            {{ $user->name }}
                            @if(!empty($user->email))
                                - {{ $user->email }}
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Witnessed By
                </label>

                <select
                    name="witnessed_by"
                    class="form-select"
                >

                    <option value="">
                        Select User
                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            @selected((string) old('witnessed_by', $test?->witnessed_by) === (string) $user->id)
                        >
                            {{ $user->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Consultant Representative
                </label>

                <input
                    type="text"
                    name="consultant_representative"
                    value="{{ old('consultant_representative', $test?->consultant_representative) }}"
                    class="form-control"
                >

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Client Representative
                </label>

                <input
                    type="text"
                    name="client_representative"
                    value="{{ old('client_representative', $test?->client_representative) }}"
                    class="form-control"
                >

            </div>


            <div class="col-12">

                <label class="form-label">
                    Site Condition
                </label>

                <textarea
                    name="site_condition"
                    rows="3"
                    class="form-control"
                    placeholder="Weather, site condition, equipment condition, utilities available, etc."
                >{{ old('site_condition', $test?->site_condition) }}</textarea>

            </div>

        </div>

    </div>
</div>


{{-- ============================================================= --}}
{{-- RETEST --}}
{{-- ============================================================= --}}

@if(!$isRetest)

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-arrow-repeat me-1"></i>
            Retest
        </h6>
    </div>

    <div class="card-body">

        <input
            type="hidden"
            name="retest_required"
            value="0"
        >

        <div class="form-check mb-3">

            <input
                class="form-check-input"
                type="checkbox"
                name="retest_required"
                value="1"
                id="retest_required"
                @checked(old('retest_required', $test?->retest_required))
            >

            <label
                class="form-check-label"
                for="retest_required"
            >
                Retest required
            </label>

        </div>


        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Parent Test
                </label>

                <select
                    name="parent_test_id"
                    id="parent_test_id"
                    class="form-select"
                >

                    <option value="">
                        Original Test
                    </option>

                    @foreach($parentTests as $parent)

                        <option
                            value="{{ $parent->id }}"
                            @selected((string) $selectedParent === (string) $parent->id)
                        >
                            {{ $parent->test_no }}

                            @if($parent->result)
                                - {{ $parent->result }}
                            @endif

                            @if($parent->status)
                                - {{ $parent->status }}
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Retest Date
                </label>

                <input
                    type="date"
                    name="retest_date"
                    value="{{ old('retest_date', $test?->retest_date?->format('Y-m-d')) }}"
                    class="form-control"
                >

            </div>

        </div>

    </div>
</div>

@endif


{{-- ============================================================= --}}
{{-- STATUS --}}
{{-- ============================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-activity me-1"></i>
            Execution Status
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

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
                        'Scheduled',
                        'In Progress',
                        'Completed',
                        'Retest Required',
                        'Cancelled'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(old('status', $test?->status ?? 'Planned') === $status)
                        >
                            {{ $status }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>
</div>


{{-- ============================================================= --}}
{{-- REMARKS --}}
{{-- ============================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-chat-left-text me-1"></i>
            Remarks & Attachment
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-8">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old('remarks', $test?->remarks) }}</textarea>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Attachment Path
                </label>

                <input
                    type="text"
                    name="attachment_path"
                    value="{{ old('attachment_path', $test?->attachment_path) }}"
                    class="form-control"
                    placeholder="storage/commissioning/tests/..."
                >

                @if($test?->attachment_path)

                    <div class="small text-muted mt-2">
                        Existing attachment:
                        {{ $test->attachment_path }}
                    </div>

                @endif

            </div>

        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const scopeSelect = document.getElementById('commissioning_scope_id');
    const planSelect = document.getElementById('test_plan_id');

    if (!scopeSelect || !planSelect) {
        return;
    }

    function filterPlans() {

        const scopeId = scopeSelect.value;
        const selectedPlan = planSelect.value;

        Array.from(planSelect.options).forEach(function (option) {

            if (!option.value) {
                option.hidden = false;
                return;
            }

            const optionScope = option.dataset.scope;

            option.hidden =
                scopeId !== '' &&
                optionScope !== scopeId;

        });

        const selectedOption =
            planSelect.options[planSelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value &&
            selectedOption.hidden
        ) {
            planSelect.value = '';
        }
    }

    scopeSelect.addEventListener(
        'change',
        filterPlans
    );

    filterPlans();
});
</script>