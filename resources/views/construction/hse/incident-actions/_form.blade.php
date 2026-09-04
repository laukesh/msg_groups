@php

    $actionModel = $actionModel ?? null;

    $users = $users ?? collect();

@endphp


<form
    method="POST"
    action="{{ $formAction }}"
>

    @csrf

    @if($formMethod)

        @method($formMethod)

    @endif


    {{-- =========================================================
        ACTION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Incident Action Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- =================================================
                    ACTION NUMBER
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Action Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $actionModel?->action_number ?? $actionNumber }}"
                        readonly
                    >

                </div>


                {{-- =================================================
                    ACTION TYPE
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Action Type

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <select
                        name="action_type"
                        class="form-select @error('action_type') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Action Type --
                        </option>


                        @foreach([
                            'Corrective Action',
                            'Preventive Action',
                            'Immediate Action',
                            'Disciplinary Action',
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


                {{-- =================================================
                    DUE DATE
                ================================================== --}}

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


                {{-- =================================================
                    RESPONSIBLE USER
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Responsible User
                    </label>


                    <select
                        name="responsible_user_id"
                        class="form-select @error('responsible_user_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Responsible User --
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


                {{-- =================================================
                    RESPONSIBLE NAME
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Responsible Name
                    </label>


                    <input
                        type="text"
                        name="responsible_name"
                        class="form-control @error('responsible_name') is-invalid @enderror"
                        value="{{ old(
                            'responsible_name',
                            $actionModel?->responsible_name
                        ) }}"
                        maxlength="255"
                    >


                    <div class="form-text">
                        If a responsible user is selected,
                        the user's name will be used automatically.
                    </div>


                    @error('responsible_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                    ACTION DESCRIPTION
                ================================================== --}}

                <div class="col-12">

                    <label class="form-label">

                        Action Description

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <textarea
                        name="action_description"
                        rows="5"
                        class="form-control @error('action_description') is-invalid @enderror"
                        placeholder="Describe the action that must be completed..."
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


                {{-- =================================================
                    WORKFLOW INFORMATION
                ================================================== --}}

                <div class="col-12">

                    <div class="alert alert-info mb-0">

                        <div class="fw-semibold mb-1">
                            Action Workflow
                        </div>

                        <div class="small">

                            New actions start as

                            <span class="badge bg-secondary">
                                Open
                            </span>

                            and require workflow progression:

                            <strong>
                                Open → In Progress → Completed
                                → Verification → Closed
                            </strong>

                        </div>

                        <div class="small mt-2">

                            Status and verification status are managed
                            through the workflow buttons and cannot be
                            changed directly from this form.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    REMARKS
                ================================================== --}}

                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>


                    <textarea
                        name="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                        placeholder="Additional remarks..."
                    >{{ old(
                        'remarks',
                        $actionModel?->remarks
                    ) }}</textarea>


                    @error('remarks')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FORM ACTIONS
    ========================================================== --}}

    <div class="d-flex justify-content-end gap-2">

        <a
            href="{{ $cancelUrl }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-x-lg me-1"></i>
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

</form>