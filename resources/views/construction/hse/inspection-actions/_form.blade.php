@php
    $actionModel = $actionModel ?? null;
    $users = $users ?? collect();
@endphp

<form method="POST" action="{{ $formAction }}">

    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif

    <div class="card">

        <div class="card-header">
            <strong>Corrective Action Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Action Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Action Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $actionNumber ?? $actionModel?->action_number }}"
                        readonly
                    >

                </div>


                {{-- Action Type --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Action Type
                    </label>

                    <select
                        name="action_type"
                        class="form-select @error('action_type') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Action Type --
                        </option>

                        @foreach([
                            'Corrective Action',
                            'Preventive Action',
                            'Immediate Action',
                            'PPE Action',
                            'Training Action',
                            'Housekeeping Action',
                            'Maintenance Action',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'action_type',
                                        $actionModel?->action_type
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


                {{-- Due Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old(
                            'due_date',
                            $actionModel?->due_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('due_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Action Description --}}
                <div class="col-12">

                    <label class="form-label">
                        Action Description
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="action_description"
                        rows="5"
                        class="form-control @error('action_description') is-invalid @enderror"
                        placeholder="Describe the corrective action to be taken..."
                        required
                    >{{ old(
                        'action_description',
                        $actionModel?->action_description
                    ) }}</textarea>

                    @error('action_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Responsible User --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Responsible Person
                    </label>

                    <select
                        name="responsible_user_id"
                        class="form-select @error('responsible_user_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Responsible Person --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    old(
                                        'responsible_user_id',
                                        $actionModel?->responsible_user_id
                                    ) == $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('responsible_user_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Responsible Name --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Responsible Name
                    </label>

                    <input
                        type="text"
                        name="responsible_name"
                        class="form-control"
                        value="{{ old(
                            'responsible_name',
                            $actionModel?->responsible_name
                        ) }}"
                        placeholder="Optional manual name"
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
                                        $actionModel?->status ?? 'Open'
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


                {{-- Completed Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Completed Date
                    </label>

                    <input
                        type="date"
                        name="completed_date"
                        class="form-control @error('completed_date') is-invalid @enderror"
                        value="{{ old(
                            'completed_date',
                            $actionModel?->completed_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('completed_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Verification Status --}}
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
                        ] as $verification)

                            <option
                                value="{{ $verification }}"
                                @selected(
                                    old(
                                        'verification_status',
                                        $actionModel?->verification_status ?? 'Pending'
                                    ) === $verification
                                )
                            >
                                {{ $verification }}
                            </option>

                        @endforeach

                    </select>

                    @error('verification_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Completion Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Completion Remarks
                    </label>

                    <textarea
                        name="completion_remarks"
                        rows="3"
                        class="form-control"
                    >{{ old(
                        'completion_remarks',
                        $actionModel?->completion_remarks
                    ) }}</textarea>

                </div>


                {{-- Verified Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Verified Date
                    </label>

                    <input
                        type="date"
                        name="verified_date"
                        class="form-control"
                        value="{{ old(
                            'verified_date',
                            $actionModel?->verified_date?->format('Y-m-d')
                        ) }}"
                    >

                </div>


                {{-- Verification Remarks --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Verification Remarks
                    </label>

                    <textarea
                        name="verification_remarks"
                        rows="2"
                        class="form-control"
                    >{{ old(
                        'verification_remarks',
                        $actionModel?->verification_remarks
                    ) }}</textarea>

                </div>


                {{-- Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control"
                    >{{ old(
                        'remarks',
                        $actionModel?->remarks
                    ) }}</textarea>

                </div>

            </div>

        </div>


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