{{-- ========================================================= --}}
{{-- Consultant Information --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-building me-2"></i>
            Consultant Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Consultant Code --}}
            <div class="col-md-3">

                <label class="form-label">
                    Consultant Code
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $consultant->consultant_code ?? '—' }}"
                    readonly
                >

                <small class="text-muted">
                    System generated
                </small>

            </div>


            {{-- Consultant Type --}}
            <div class="col-md-3">

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
                        'Electrical Consultant',
                        'Mechanical Consultant',
                        'Plumbing Consultant',
                        'Fire & Life Safety Consultant',
                        'ELV Consultant',
                        'Interior Consultant',
                        'Landscape Consultant',
                        'BIM Consultant',
                        'Geotechnical Consultant',
                        'Environmental Consultant',
                        'Quantity Surveyor',
                        'Project Management Consultant',
                        'Other'
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


            {{-- Consultant Role --}}
            <div class="col-md-3">

                <label class="form-label">
                    Consultant Role
                </label>

                <select
                    name="consultant_role"
                    class="form-select"
                >

                    <option value="">
                        Select Role
                    </option>

                    @foreach([
                        'Design Consultant',
                        'Supervision Consultant',
                        'PMC',
                        'Technical Consultant',
                        'Independent Consultant',
                        'Other'
                    ] as $role)

                        <option
                            value="{{ $role }}"
                            @selected(
                                old(
                                    'consultant_role',
                                    $consultant->consultant_role ?? ''
                                ) === $role
                            )
                        >
                            {{ $role }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Discipline --}}
            <div class="col-md-3">

                <label class="form-label">
                    Discipline
                </label>

                <select
                    name="discipline"
                    class="form-select"
                >

                    <option value="">
                        Select Discipline
                    </option>

                    @foreach([
                        'Architectural',
                        'Structural',
                        'MEPF',
                        'Electrical',
                        'Mechanical',
                        'Plumbing',
                        'Fire & Life Safety',
                        'ELV',
                        'Interior',
                        'Landscape',
                        'BIM',
                        'Geotechnical',
                        'Environmental',
                        'Other'
                    ] as $discipline)

                        <option
                            value="{{ $discipline }}"
                            @selected(
                                old(
                                    'discipline',
                                    $consultant->discipline ?? ''
                                ) === $discipline
                            )
                        >
                            {{ $discipline }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Appointment Type --}}
            <div class="col-md-4">

                <label class="form-label">
                    Appointment Type
                </label>

                <select
                    name="appointment_type"
                    class="form-select"
                >

                    <option value="">
                        Select Appointment Type
                    </option>

                    @foreach([
                        'Design Only',
                        'Design + Supervision',
                        'Supervision Only',
                        'PMC',
                        'Technical Advisory',
                        'Other'
                    ] as $appointmentType)

                        <option
                            value="{{ $appointmentType }}"
                            @selected(
                                old(
                                    'appointment_type',
                                    $consultant->appointment_type ?? ''
                                ) === $appointmentType
                            )
                        >
                            {{ $appointmentType }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Company --}}
            <div class="col-md-4">

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


            {{-- Consultant Name --}}
            <div class="col-md-4">

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


            {{-- Specialization --}}
            <div class="col-12">

                <label class="form-label">
                    Specialization
                </label>

                <input
                    type="text"
                    name="specialization"
                    class="form-control"
                    value="{{ old(
                        'specialization',
                        $consultant->specialization ?? ''
                    ) }}"
                    placeholder="e.g. Commercial Buildings, Mall Projects, RCC Structures"
                >

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Professional Information --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-patch-check me-2"></i>
            Professional Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Registration --}}
            <div class="col-md-4">

                <label class="form-label">
                    Registration / License No.
                </label>

                <input
                    type="text"
                    name="registration_no"
                    class="form-control"
                    value="{{ old(
                        'registration_no',
                        $consultant->registration_no ?? ''
                    ) }}"
                >

            </div>


            {{-- GST --}}
            <div class="col-md-4">

                <label class="form-label">
                    GST Number
                </label>

                <input
                    type="text"
                    name="gst_number"
                    class="form-control"
                    value="{{ old(
                        'gst_number',
                        $consultant->gst_number ?? ''
                    ) }}"
                >

            </div>


            {{-- PAN --}}
            <div class="col-md-4">

                <label class="form-label">
                    PAN Number
                </label>

                <input
                    type="text"
                    name="pan_number"
                    class="form-control"
                    value="{{ old(
                        'pan_number',
                        $consultant->pan_number ?? ''
                    ) }}"
                >

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Contact Information --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-person-lines-fill me-2"></i>
            Contact Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Contact Person --}}
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


            {{-- Designation --}}
            <div class="col-md-4">

                <label class="form-label">
                    Designation
                </label>

                <input
                    type="text"
                    name="contact_designation"
                    class="form-control"
                    value="{{ old(
                        'contact_designation',
                        $consultant->contact_designation ?? ''
                    ) }}"
                >

            </div>


            {{-- Email --}}
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


            {{-- Phone --}}
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


            {{-- Alternate Phone --}}
            <div class="col-md-4">

                <label class="form-label">
                    Alternate Phone
                </label>

                <input
                    type="text"
                    name="alternate_phone"
                    class="form-control"
                    value="{{ old(
                        'alternate_phone',
                        $consultant->alternate_phone ?? ''
                    ) }}"
                >

            </div>


            {{-- Website --}}
            <div class="col-md-4">

                <label class="form-label">
                    Website
                </label>

                <input
                    type="url"
                    name="website"
                    class="form-control"
                    value="{{ old(
                        'website',
                        $consultant->website ?? ''
                    ) }}"
                    placeholder="https://example.com"
                >

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Address --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-geo-alt me-2"></i>
            Address
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Address --}}
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


            {{-- Postal Code --}}
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


            {{-- City --}}
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


            {{-- State --}}
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


            {{-- Country --}}
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

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Project Appointment --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-calendar-event me-2"></i>
            Project Appointment
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Appointment Date --}}
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


            {{-- Start Date --}}
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


            {{-- End Date --}}
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

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Scope & Responsibilities --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-list-check me-2"></i>
            Scope & Responsibilities
        </h5>

    </div>

    <div class="card-body">

        <div class="mb-3">

            <label class="form-label">
                Scope of Services
            </label>

            <textarea
                name="scope_of_services"
                rows="5"
                class="form-control"
                placeholder="Describe the services included in the consultant appointment..."
            >{{ old(
                'scope_of_services',
                $consultant->scope_of_services ?? ''
            ) }}</textarea>

        </div>


        <div>

            <label class="form-label">
                Responsibilities
            </label>

            <textarea
                name="responsibilities"
                rows="5"
                class="form-control"
                placeholder="Describe the consultant's key responsibilities..."
            >{{ old(
                'responsibilities',
                $consultant->responsibilities ?? ''
            ) }}</textarea>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Contract Summary --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>
            Contract Summary
        </h5>

        <small class="text-muted">
            This is a summary only. Detailed contract management
            will remain in the shared Contract Management module.
        </small>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Contract Value --}}
            <div class="col-md-8">

                <label class="form-label">
                    Contract / Appointment Value
                </label>

                <input
                    type="number"
                    name="contract_value"
                    class="form-control"
                    min="0"
                    step="0.01"
                    value="{{ old(
                        'contract_value',
                        $consultant->contract_value ?? 0
                    ) }}"
                >

            </div>


            {{-- Currency --}}
            <div class="col-md-4">

                <label class="form-label">
                    Currency
                </label>

                <select
                    name="currency"
                    class="form-select"
                >

                    @foreach([
                        'INR',
                        'USD',
                        'EUR'
                    ] as $currency)

                        <option
                            value="{{ $currency }}"
                            @selected(
                                old(
                                    'currency',
                                    $consultant->currency ?? 'INR'
                                ) === $currency
                            )
                        >
                            {{ $currency }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Status & Remarks --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-clipboard-check me-2"></i>
            Status & Remarks
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">


            {{-- Status --}}
            <div class="col-md-4">

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
                        'Active',
                        'Inactive',
                        'Completed',
                        'Terminated'
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


            {{-- Remarks --}}
            <div class="col-md-8">

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

    </div>

</div>