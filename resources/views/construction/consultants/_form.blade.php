<div class="row g-3">

@if(isset($consultant))

    <div class="col-md-4">

        <label class="form-label">
            Consultant Code
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $consultant->consultant_code }}"
            readonly
        >

    </div>

@endif


    <div class="col-md-4">

        <label class="form-label">
            Consultant Type
        </label>

        <select
            name="consultant_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Architect',
                'Structural Consultant',
                'MEP Consultant',
                'Project Management Consultant',
                'PMC',
                'Quantity Surveyor',
                'Legal Consultant',
                'Environmental Consultant',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'consultant_type',
                            $consultant->consultant_type ?? ''
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Status
        </label>

        <select
            name="status"
            class="form-select"
        >

            @foreach([
                'Active',
                'Inactive',
                'Completed',
                'Terminated',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $consultant->status ?? 'Active'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Company Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="company_name"
            class="form-control"
            required
            value="{{ old(
                'company_name',
                $consultant->company_name ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Consultant / Lead Name
        </label>

        <input
            type="text"
            name="consultant_name"
            class="form-control"
            value="{{ old(
                'consultant_name',
                $consultant->consultant_name ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Contact Person
        </label>

        <input
            type="text"
            name="contact_person"
            class="form-control"
            value="{{ old(
                'contact_person',
                $consultant->contact_person ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old(
                'email',
                $consultant->email ?? ''
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
                $consultant->phone ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

    <label class="form-label">
        Appointment Date
    </label>

    <input
        type="date"
        name="appointment_date"
        class="form-control"
        value="{{ old(
            'appointment_date',
            ($consultant ?? null)?->appointment_date?->format('Y-m-d')
        ) }}"
    >

</div>


<div class="col-md-4">

    <label class="form-label">
        Start Date
    </label>

    <input
        type="date"
        name="start_date"
        class="form-control"
        value="{{ old(
            'start_date',
            ($consultant ?? null)?->start_date?->format('Y-m-d')
        ) }}"
    >

</div>


<div class="col-md-4">

    <label class="form-label">
        End Date
    </label>

    <input
        type="date"
        name="end_date"
        class="form-control"
        value="{{ old(
            'end_date',
            ($consultant ?? null)?->end_date?->format('Y-m-d')
        ) }}"
    >

</div>


    <div class="col-md-8">

        <label class="form-label">
            Address
        </label>

        <input
            type="text"
            name="address"
            class="form-control"
            value="{{ old(
                'address',
                $consultant->address ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Postal Code
        </label>

        <input
            type="text"
            name="postal_code"
            class="form-control"
            value="{{ old(
                'postal_code',
                $consultant->postal_code ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            City
        </label>

        <input
            type="text"
            name="city"
            class="form-control"
            value="{{ old(
                'city',
                $consultant->city ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            State
        </label>

        <input
            type="text"
            name="state"
            class="form-control"
            value="{{ old(
                'state',
                $consultant->state ?? ''
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Country
        </label>

        <input
            type="text"
            name="country"
            class="form-control"
            value="{{ old(
                'country',
                $consultant->country ?? 'India'
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Contract Value
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="contract_value"
            class="form-control"
            value="{{ old(
                'contract_value',
                $consultant->contract_value ?? 0
            ) }}"
        >

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Currency
        </label>

        <input
            type="text"
            name="currency"
            class="form-control"
            value="{{ old(
                'currency',
                $consultant->currency ?? 'USD'
            ) }}"
        >

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Scope of Services
        </label>

        <textarea
            name="scope_of_services"
            rows="4"
            class="form-control"
        >{{ old(
            'scope_of_services',
            $consultant->scope_of_services ?? ''
        ) }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
        >{{ old(
            'remarks',
            $consultant->remarks ?? ''
        ) }}</textarea>

    </div>

</div>