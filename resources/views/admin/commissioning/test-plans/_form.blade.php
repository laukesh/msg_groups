@php
    $plan = $plan ?? null;
@endphp

@csrf

<div class="row g-3">

    {{-- =========================================================
         BASIC INFORMATION
    ========================================================== --}}

    <div class="col-12">

        <div class="border-bottom pb-2 mb-1">

            <h6 class="mb-0">
                <i class="bi bi-clipboard-check me-2"></i>
                Test Plan Information
            </h6>

            <small class="text-muted">
                Define the commissioning test plan and its scope.
            </small>

        </div>

    </div>


    {{-- Test Plan No --}}
    <div class="col-md-4">

        <label class="form-label">
            Test Plan No.
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="test_plan_no"
            class="form-control @error('test_plan_no') is-invalid @enderror"
            value="{{ old('test_plan_no', $plan?->test_plan_no) }}"
            placeholder="e.g. TP-ELEC-001"
            required
        >

        @error('test_plan_no')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Title --}}
    <div class="col-md-8">

        <label class="form-label">
            Test Plan Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $plan?->title) }}"
            placeholder="e.g. Electrical Power Distribution Testing"
            required
        >

        @error('title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Commissioning Scope --}}
    <div class="col-md-6">

        <label class="form-label">
            Commissioning Scope
            <span class="text-danger">*</span>
        </label>

        <select
            name="commissioning_scope_id"
            class="form-select @error('commissioning_scope_id') is-invalid @enderror"
            required
        >

            <option value="">
                Select Commissioning Scope
            </option>

            @foreach($scopes as $scope)

                <option
                    value="{{ $scope->id }}"
                    @selected(
                        old(
                            'commissioning_scope_id',
                            $plan?->commissioning_scope_id
                        ) == $scope->id
                    )
                >

                    {{ $scope->scope_code }}

                    @if($scope->scope_name)
                        - {{ $scope->scope_name }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('commissioning_scope_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

        @if($scopes->isEmpty())

            <div class="form-text text-danger">

                No commissioning scopes are available for this project.

            </div>

        @else

            <div class="form-text">
                Select the construction work scope being commissioned.
            </div>

        @endif

    </div>


    {{-- Discipline --}}
    <div class="col-md-3">

        <label class="form-label">
            Discipline
        </label>

        <input
            type="text"
            name="discipline"
            class="form-control @error('discipline') is-invalid @enderror"
            value="{{ old('discipline', $plan?->discipline) }}"
            placeholder="Electrical"
        >

        @error('discipline')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Test Type --}}
    <div class="col-md-3">

        <label class="form-label">
            Test Type
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="test_type"
            class="form-control @error('test_type') is-invalid @enderror"
            value="{{ old('test_type', $plan?->test_type) }}"
            placeholder="Functional Test"
            required
        >

        @error('test_type')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         PURPOSE / DESCRIPTION
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Purpose
        </label>

        <textarea
            name="purpose"
            class="form-control @error('purpose') is-invalid @enderror"
            rows="4"
            placeholder="Describe the purpose of this test plan..."
        >{{ old('purpose', $plan?->purpose) }}</textarea>

        @error('purpose')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
            placeholder="Additional details..."
        >{{ old('description', $plan?->description) }}</textarea>

        @error('description')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         TEST PROCEDURE
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Prerequisites
        </label>

        <textarea
            name="prerequisites"
            class="form-control @error('prerequisites') is-invalid @enderror"
            rows="5"
            placeholder="Conditions that must be satisfied before testing..."
        >{{ old('prerequisites', $plan?->prerequisites) }}</textarea>

        @error('prerequisites')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Test Procedure
            <span class="text-danger">*</span>
        </label>

        <textarea
            name="test_procedure"
            class="form-control @error('test_procedure') is-invalid @enderror"
            rows="5"
            placeholder="Describe the complete testing procedure..."
            required
        >{{ old('test_procedure', $plan?->test_procedure) }}</textarea>

        @error('test_procedure')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         RESOURCES
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Required Instruments
        </label>

        <textarea
            name="required_instruments"
            class="form-control @error('required_instruments') is-invalid @enderror"
            rows="4"
            placeholder="List instruments, meters, test equipment..."
        >{{ old('required_instruments', $plan?->required_instruments) }}</textarea>

        @error('required_instruments')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Required Personnel
        </label>

        <textarea
            name="required_personnel"
            class="form-control @error('required_personnel') is-invalid @enderror"
            rows="4"
            placeholder="List required personnel / roles..."
        >{{ old('required_personnel', $plan?->required_personnel) }}</textarea>

        @error('required_personnel')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         PLANNING
    ========================================================== --}}

    <div class="col-12">

        <div class="border-bottom pb-2 mb-1 mt-2">

            <h6 class="mb-0">
                <i class="bi bi-calendar3 me-2"></i>
                Planning & Responsibility
            </h6>

        </div>

    </div>


    {{-- Planned Start --}}
    <div class="col-md-3">

        <label class="form-label">
            Planned Start Date
        </label>

        <input
            type="date"
            name="planned_start_date"
            class="form-control @error('planned_start_date') is-invalid @enderror"
            value="{{ old(
                'planned_start_date',
                optional($plan?->planned_start_date)->format('Y-m-d')
            ) }}"
        >

        @error('planned_start_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Planned Completion --}}
    <div class="col-md-3">

        <label class="form-label">
            Planned Completion Date
        </label>

        <input
            type="date"
            name="planned_completion_date"
            class="form-control @error('planned_completion_date') is-invalid @enderror"
            value="{{ old(
                'planned_completion_date',
                optional($plan?->planned_completion_date)->format('Y-m-d')
            ) }}"
        >

        @error('planned_completion_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Responsible --}}
    <div class="col-md-6">

        <label class="form-label">
            Responsible Person
        </label>

        <select
            name="responsible_user_id"
            class="form-select @error('responsible_user_id') is-invalid @enderror"
        >

            <option value="">
                Select Responsible Person
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'responsible_user_id',
                            $plan?->responsible_user_id
                        ) == $user->id
                    )
                >
                    {{ $user->name }}

                    @if($user->email)
                        - {{ $user->email }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('responsible_user_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         WITNESS REQUIREMENTS
    ========================================================== --}}

    <div class="col-12">

        <div class="card bg-light border-0">

            <div class="card-body">

                <h6 class="mb-3">
                    <i class="bi bi-people me-2"></i>
                    Witness Requirements
                </h6>

                <div class="row g-3">

                    {{-- General Witness --}}
                    <div class="col-md-4">

                        <div class="form-check">

                            <input
                                type="hidden"
                                name="witness_required"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="witness_required"
                                value="1"
                                id="witness_required"
                                class="form-check-input"
                                @checked(
                                    old(
                                        'witness_required',
                                        $plan?->witness_required
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="witness_required"
                            >
                                Witness Required
                            </label>

                        </div>

                    </div>


                    {{-- Client Witness --}}
                    <div class="col-md-4">

                        <div class="form-check">

                            <input
                                type="hidden"
                                name="client_witness_required"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="client_witness_required"
                                value="1"
                                id="client_witness_required"
                                class="form-check-input"
                                @checked(
                                    old(
                                        'client_witness_required',
                                        $plan?->client_witness_required
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="client_witness_required"
                            >
                                Client Witness Required
                            </label>

                        </div>

                    </div>


                    {{-- Consultant Witness --}}
                    <div class="col-md-4">

                        <div class="form-check">

                            <input
                                type="hidden"
                                name="consultant_witness_required"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="consultant_witness_required"
                                value="1"
                                id="consultant_witness_required"
                                class="form-check-input"
                                @checked(
                                    old(
                                        'consultant_witness_required',
                                        $plan?->consultant_witness_required
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="consultant_witness_required"
                            >
                                Consultant Witness Required
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         WORKFLOW INFORMATION
    ========================================================== --}}

    @if($plan)

        <div class="col-12">

            <div class="alert alert-info mb-0">

                <div class="d-flex">

                    <i class="bi bi-info-circle me-2"></i>

                    <div>

                        <strong>
                            Workflow Controlled
                        </strong>

                        <div class="small mt-1">

                            This Test Plan will be saved as
                            <strong>Draft</strong>.
                            Submit it from the detail page when all
                            required information is complete.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>