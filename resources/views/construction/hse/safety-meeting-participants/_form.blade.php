@php
    $participant = $participant ?? null;
@endphp

<form
    method="POST"
    action="{{ $formAction }}"
>

    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif


    <div class="card">

        <div class="card-header">
            <strong>Participant Details</strong>
        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Participant Name --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Participant Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="participant_name"
                        class="form-control @error('participant_name') is-invalid @enderror"
                        value="{{ old(
                            'participant_name',
                            $participant?->participant_name
                        ) }}"
                        required
                    >

                    @error('participant_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Participant Type --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Participant Type
                    </label>

                    <select
                        name="participant_type"
                        class="form-select @error('participant_type') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Type --
                        </option>

                        @foreach([
                            'Employee',
                            'Contractor',
                            'Subcontractor',
                            'Supervisor',
                            'Engineer',
                            'Safety Officer',
                            'Visitor',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'participant_type',
                                        $participant?->participant_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('participant_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Employee Code --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Employee Code
                    </label>

                    <input
                        type="text"
                        name="employee_code"
                        class="form-control @error('employee_code') is-invalid @enderror"
                        value="{{ old(
                            'employee_code',
                            $participant?->employee_code
                        ) }}"
                    >

                    @error('employee_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Company --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Company
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control @error('company_name') is-invalid @enderror"
                        value="{{ old(
                            'company_name',
                            $participant?->company_name
                        ) }}"
                    >

                    @error('company_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Designation --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Designation
                    </label>

                    <input
                        type="text"
                        name="designation"
                        class="form-control @error('designation') is-invalid @enderror"
                        value="{{ old(
                            'designation',
                            $participant?->designation
                        ) }}"
                    >

                    @error('designation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Phone --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old(
                            'phone',
                            $participant?->phone
                        ) }}"
                    >

                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Email --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old(
                            'email',
                            $participant?->email
                        ) }}"
                    >

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Attendance --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Attendance
                    </label>

                    <div class="form-check mt-2">

                        <input
                            type="hidden"
                            name="attended"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="attended"
                            value="1"
                            class="form-check-input"
                            id="attended"
                            @checked(
                                old(
                                    'attended',
                                    $participant?->attended ?? true
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="attended"
                        >
                            Present
                        </label>

                    </div>

                </div>


                {{-- Attendance Time --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Attendance Time
                    </label>

                    <input
                        type="time"
                        name="attendance_time"
                        class="form-control @error('attendance_time') is-invalid @enderror"
                        value="{{ old(
                            'attendance_time',
                            $participant?->attendance_time
                        ) }}"
                    >

                    @error('attendance_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                        placeholder="Additional remarks"
                    >{{ old(
                        'remarks',
                        $participant?->remarks
                    ) }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

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