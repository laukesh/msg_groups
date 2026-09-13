@php
    $action = $action ?? null;

    /*
    |--------------------------------------------------------------------------
    | Source Selection
    |--------------------------------------------------------------------------
    */

    $selectedRecordId =
        $selectedRecordId
        ?? $action?->environmental_record_id;

    $selectedComplianceId =
        $selectedComplianceId
        ?? $action?->environmental_compliance_id;
@endphp


<form method="POST" action="{{ $formAction }}">

    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif


    <div class="card">

        <div class="card-header">
            <strong>
                Environmental Action Details
            </strong>
        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- =========================================================
                     ACTION NUMBER
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Action Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $actionNumber ?? $action?->action_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="action_number"
                        value="{{ $actionNumber ?? $action?->action_number }}"
                    >

                </div>


                {{-- =========================================================
                     ACTION TITLE
                ========================================================== --}}

                <div class="col-md-8">

                    <label class="form-label">
                        Action Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="action_title"
                        class="form-control @error('action_title') is-invalid @enderror"
                        value="{{ old('action_title', $action?->action_title) }}"
                        placeholder="Enter action title"
                        required
                    >

                    @error('action_title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     ENVIRONMENTAL RECORD
                ========================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Environmental Record
                    </label>

                    <select
                        name="environmental_record_id"
                        class="form-select @error('environmental_record_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Environmental Record --
                        </option>

                        @foreach($records as $record)

                            <option
                                value="{{ $record->id }}"
                                @selected(
                                    (string) old(
                                        'environmental_record_id',
                                        $selectedRecordId
                                    ) === (string) $record->id
                                )
                            >
                                {{ $record->record_number }}
                                —
                                {{ $record->record_title }}
                            </option>

                        @endforeach

                    </select>

                    @error('environmental_record_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    @if($selectedRecordId)

                        <div class="form-text text-success">
                            Environmental record has been selected automatically.
                        </div>

                    @endif

                </div>


                {{-- =========================================================
                     ENVIRONMENTAL COMPLIANCE
                ========================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Environmental Compliance
                    </label>

                    <select
                        name="environmental_compliance_id"
                        class="form-select @error('environmental_compliance_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Environmental Compliance --
                        </option>

                        @foreach($compliances as $compliance)

                            <option
                                value="{{ $compliance->id }}"
                                @selected(
                                    (string) old(
                                        'environmental_compliance_id',
                                        $selectedComplianceId
                                    ) === (string) $compliance->id
                                )
                            >
                                {{ $compliance->compliance_number }}
                                —
                                {{ $compliance->compliance_title }}
                            </option>

                        @endforeach

                    </select>

                    @error('environmental_compliance_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    @if($selectedComplianceId)

                        <div class="form-text text-success">
                            Environmental compliance has been selected automatically.
                        </div>

                    @endif

                </div>


                {{-- =========================================================
                     ACTION TYPE
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Action Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="action_type"
                        class="form-select @error('action_type') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Corrective Action',
                            'Preventive Action',
                            'Improvement Action',
                            'Legal / Compliance Action',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'action_type',
                                        $action?->action_type
                                            ?? 'Corrective Action'
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('action_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     PRIORITY
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Priority
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="priority"
                        class="form-select @error('priority') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Low',
                            'Medium',
                            'High',
                            'Critical',
                        ] as $priority)

                            <option
                                value="{{ $priority }}"
                                @selected(
                                    old(
                                        'priority',
                                        $action?->priority ?? 'Medium'
                                    ) === $priority
                                )
                            >
                                {{ $priority }}
                            </option>

                        @endforeach

                    </select>

                    @error('priority')
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
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Open',
                            'In Progress',
                            'Completed',
                            'Closed',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $action?->status ?? 'Open'
                                    ) === $status
                                )
                            >
                                {{ $status }}
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
                     ACTION DESCRIPTION
                ========================================================== --}}

                <div class="col-12">

                    <label class="form-label">
                        Action Description
                    </label>

                    <textarea
                        name="action_description"
                        rows="4"
                        class="form-control @error('action_description') is-invalid @enderror"
                        placeholder="Describe the environmental action..."
                    >{{ old('action_description', $action?->action_description) }}</textarea>

                    @error('action_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     ROOT CAUSE
                ========================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Root Cause
                    </label>

                    <textarea
                        name="root_cause"
                        rows="4"
                        class="form-control @error('root_cause') is-invalid @enderror"
                        placeholder="Describe the root cause..."
                    >{{ old('root_cause', $action?->root_cause) }}</textarea>

                    @error('root_cause')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     PREVENTIVE ACTION
                ========================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Preventive Action
                    </label>

                    <textarea
                        name="preventive_action"
                        rows="4"
                        class="form-control @error('preventive_action') is-invalid @enderror"
                        placeholder="Describe preventive measures..."
                    >{{ old('preventive_action', $action?->preventive_action) }}</textarea>

                    @error('preventive_action')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     ASSIGNED TO
                ========================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Assigned To
                    </label>

                    <select
                        name="assigned_to"
                        class="form-select @error('assigned_to') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Responsible Person --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'assigned_to',
                                        $action?->assigned_to
                                    ) === (string) $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('assigned_to')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     ASSIGNED DATE
                ========================================================== --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Assigned Date
                    </label>

                    <input
                        type="date"
                        name="assigned_date"
                        class="form-control @error('assigned_date') is-invalid @enderror"
                        value="{{ old(
                            'assigned_date',
                            $action?->assigned_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('assigned_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     DUE DATE
                ========================================================== --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old(
                            'due_date',
                            $action?->due_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('due_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     COMPLETION DATE
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Completion Date
                    </label>

                    <input
                        type="date"
                        name="completion_date"
                        class="form-control @error('completion_date') is-invalid @enderror"
                        value="{{ old(
                            'completion_date',
                            $action?->completion_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('completion_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     VERIFICATION REQUIRED
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label d-block">
                        Verification
                    </label>

                    <div class="form-check form-switch">

                        <input
                            type="hidden"
                            name="verification_required"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="verification_required"
                            value="1"
                            class="form-check-input"
                            id="verification_required"
                            @checked(
                                old(
                                    'verification_required',
                                    $action?->verification_required ?? false
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="verification_required"
                        >
                            Verification Required
                        </label>

                    </div>

                </div>


                {{-- =========================================================
                     VERIFICATION STATUS
                ========================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Verification Status
                    </label>

                    <select
                        name="verification_status"
                        class="form-select @error('verification_status') is-invalid @enderror"
                    >

                        @foreach([
                            'Pending',
                            'Verified',
                            'Rejected',
                            'Not Required',
                        ] as $verificationStatus)

                            <option
                                value="{{ $verificationStatus }}"
                                @selected(
                                    old(
                                        'verification_status',
                                        $action?->verification_status
                                            ?? 'Pending'
                                    ) === $verificationStatus
                                )
                            >
                                {{ $verificationStatus }}
                            </option>

                        @endforeach

                    </select>

                    @error('verification_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     COMPLETION REMARKS
                ========================================================== --}}

                <div class="col-12">

                    <label class="form-label">
                        Completion Remarks
                    </label>

                    <textarea
                        name="completion_remarks"
                        rows="3"
                        class="form-control @error('completion_remarks') is-invalid @enderror"
                    >{{ old('completion_remarks', $action?->completion_remarks) }}</textarea>

                    @error('completion_remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- =========================================================
                     VERIFICATION REMARKS
                ========================================================== --}}

                <div class="col-12">

                    <label class="form-label">
                        Verification Remarks
                    </label>

                    <textarea
                        name="verification_remarks"
                        rows="3"
                        class="form-control @error('verification_remarks') is-invalid @enderror"
                    >{{ old('verification_remarks', $action?->verification_remarks) }}</textarea>

                    @error('verification_remarks')
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
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old('remarks', $action?->remarks) }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- =============================================================
             FOOTER
        ============================================================== --}}

        <div class="card-footer d-flex justify-content-end gap-2">

            <a
                href="{{ $cancelUrl }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>

                {{ $submitLabel }}

            </button>

        </div>

    </div>

</form>