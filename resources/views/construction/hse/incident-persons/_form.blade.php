@php

    $person = $person ?? null;

@endphp


<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if($method)

        @method($method)

    @endif


    {{-- =========================================================
        PERSON DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Person Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-6">

                    <label class="form-label">

                        Person Name

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="person_name"
                        class="form-control @error('person_name') is-invalid @enderror"
                        value="{{ old(
                            'person_name',
                            $person?->person_name
                        ) }}"
                        required
                    >

                    @error('person_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">

                        Person Type

                        <span class="text-danger">*</span>

                    </label>


                    <select
                        name="person_type"
                        class="form-select @error('person_type') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Person Type --
                        </option>


                        @foreach([
                            'Employee',
                            'Contractor',
                            'Visitor',
                            'Subcontractor',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'person_type',
                                        $person?->person_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>


                    @error('person_type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Employee / Worker Code
                    </label>

                    <input
                        type="text"
                        name="employee_code"
                        class="form-control"
                        value="{{ old(
                            'employee_code',
                            $person?->employee_code
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Company Name
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="{{ old(
                            'company_name',
                            $person?->company_name
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Designation
                    </label>

                    <input
                        type="text"
                        name="designation"
                        class="form-control"
                        value="{{ old(
                            'designation',
                            $person?->designation
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old(
                            'phone',
                            $person?->phone
                        ) }}"
                    >

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        INJURY DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Injury Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-4">

                    <label class="form-label">
                        Injury Occurred
                    </label>


                    <select
                        name="injury_occurred"
                        id="injury_occurred"
                        class="form-select"
                    >

                        <option
                            value="0"
                            @selected(
                                old(
                                    'injury_occurred',
                                    $person?->injury_occurred
                                ) == false
                            )
                        >
                            No
                        </option>


                        <option
                            value="1"
                            @selected(
                                old(
                                    'injury_occurred',
                                    $person?->injury_occurred
                                ) == true
                            )
                        >
                            Yes
                        </option>

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Injury Type
                    </label>

                    <input
                        type="text"
                        name="injury_type"
                        class="form-control"
                        value="{{ old(
                            'injury_type',
                            $person?->injury_type
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Body Part Affected
                    </label>

                    <input
                        type="text"
                        name="body_part_affected"
                        class="form-control"
                        value="{{ old(
                            'body_part_affected',
                            $person?->body_part_affected
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Injury Severity
                    </label>


                    <select
                        name="injury_severity"
                        class="form-select"
                    >

                        <option value="">
                            -- Select Severity --
                        </option>


                        @foreach([
                            'Minor',
                            'Moderate',
                            'Serious',
                            'Critical',
                            'Fatal',
                        ] as $severity)

                            <option
                                value="{{ $severity }}"
                                @selected(
                                    old(
                                        'injury_severity',
                                        $person?->injury_severity
                                    ) === $severity
                                )
                            >
                                {{ $severity }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Treatment Type
                    </label>

                    <input
                        type="text"
                        name="treatment_type"
                        class="form-control"
                        value="{{ old(
                            'treatment_type',
                            $person?->treatment_type
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Medical Facility
                    </label>

                    <input
                        type="text"
                        name="medical_facility"
                        class="form-control"
                        value="{{ old(
                            'medical_facility',
                            $person?->medical_facility
                        ) }}"
                    >

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        HOSPITALIZATION / LOST TIME
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Hospitalization & Lost Work
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-4">

                    <label class="form-label">
                        Hospitalized
                    </label>


                    <select
                        name="hospitalized"
                        class="form-select"
                    >

                        <option
                            value="0"
                            @selected(
                                old(
                                    'hospitalized',
                                    $person?->hospitalized
                                ) == false
                            )
                        >
                            No
                        </option>


                        <option
                            value="1"
                            @selected(
                                old(
                                    'hospitalized',
                                    $person?->hospitalized
                                ) == true
                            )
                        >
                            Yes
                        </option>

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Hospitalization Date
                    </label>


                    <input
                        type="date"
                        name="hospitalization_date"
                        class="form-control"
                        value="{{ old(
                            'hospitalization_date',
                            $person?->hospitalization_date?->format('Y-m-d')
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Lost Work Days
                    </label>


                    <input
                        type="number"
                        name="lost_work_days"
                        min="0"
                        class="form-control"
                        value="{{ old(
                            'lost_work_days',
                            $person?->lost_work_days ?? 0
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Returned to Work
                    </label>


                    <select
                        name="returned_to_work"
                        class="form-select"
                    >

                        <option
                            value="0"
                            @selected(
                                old(
                                    'returned_to_work',
                                    $person?->returned_to_work
                                ) == false
                            )
                        >
                            No
                        </option>


                        <option
                            value="1"
                            @selected(
                                old(
                                    'returned_to_work',
                                    $person?->returned_to_work
                                ) == true
                            )
                        >
                            Yes
                        </option>

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Return to Work Date
                    </label>


                    <input
                        type="date"
                        name="return_to_work_date"
                        class="form-control"
                        value="{{ old(
                            'return_to_work_date',
                            $person?->return_to_work_date?->format('Y-m-d')
                        ) }}"
                    >

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>


        <div class="card-body">

            <textarea
                name="remarks"
                rows="4"
                class="form-control"
                placeholder="Additional remarks..."
            >{{ old(
                'remarks',
                $person?->remarks
            ) }}</textarea>

        </div>

    </div>



    {{-- =========================================================
        BUTTONS
    ========================================================== --}}

    <div class="d-flex justify-content-end gap-2">

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

</form>