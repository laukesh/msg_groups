@php

    $witness = $witness ?? null;

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
        WITNESS DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Witness Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Witness Name --}}

                <div class="col-md-6">

                    <label class="form-label">

                        Witness Name

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="witness_name"
                        class="form-control @error('witness_name') is-invalid @enderror"
                        value="{{ old(
                            'witness_name',
                            $witness?->witness_name
                        ) }}"
                        required
                    >

                    @error('witness_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Witness Type --}}

                <div class="col-md-6">

                    <label class="form-label">

                        Witness Type

                        <span class="text-danger">*</span>

                    </label>


                    <select
                        name="witness_type"
                        class="form-select @error('witness_type') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Witness Type --
                        </option>


                        @foreach([
                            'Employee',
                            'Contractor',
                            'Subcontractor',
                            'Visitor',
                            'Client',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'witness_type',
                                        $witness?->witness_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>


                    @error('witness_type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Employee Code --}}

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
                            $witness?->employee_code
                        ) }}"
                    >

                </div>


                {{-- Company --}}

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
                            $witness?->company_name
                        ) }}"
                    >

                </div>


                {{-- Designation --}}

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
                            $witness?->designation
                        ) }}"
                    >

                </div>


                {{-- Phone --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old(
                            'phone',
                            $witness?->phone
                        ) }}"
                    >

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
                            $witness?->email
                        ) }}"
                    >

                    @error('email')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        WITNESS STATEMENT
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Witness Statement
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                <div class="col-md-8">

                    <label class="form-label">
                        Statement
                    </label>

                    <textarea
                        name="statement"
                        rows="7"
                        class="form-control"
                        placeholder="Enter witness statement..."
                    >{{ old(
                        'statement',
                        $witness?->statement
                    ) }}</textarea>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Statement Date
                    </label>

                    <input
                        type="date"
                        name="statement_date"
                        class="form-control"
                        value="{{ old(
                            'statement_date',
                            $witness?->statement_date?->format('Y-m-d')
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
                $witness?->remarks
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