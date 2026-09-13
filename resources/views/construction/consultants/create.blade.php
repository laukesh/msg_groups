@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Add Consultant
            </h4>

            <div class="text-muted">
                {{ $project->project_name ?? 'Project' }}
            </div>
        </div>

        <a href="{{ route('admin.projects.construction.consultants.index', $project) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.projects.construction.consultants.store', $project) }}">

        @csrf


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

                    {{-- Type --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Consultant Type
                        </label>

                        <select name="consultant_type"
                                class="form-select">

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

                                <option value="{{ $type }}"
                                    {{ old('consultant_type') === $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Role --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Consultant Role
                        </label>

                        <select name="consultant_role"
                                class="form-select">

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

                                <option value="{{ $role }}"
                                    {{ old('consultant_role') === $role ? 'selected' : '' }}>

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

                        <select name="discipline"
                                class="form-select">

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

                                <option value="{{ $discipline }}"
                                    {{ old('discipline') === $discipline ? 'selected' : '' }}>

                                    {{ $discipline }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Appointment Type --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Appointment Type
                        </label>

                        <select name="appointment_type"
                                class="form-select">

                            <option value="">
                                Select Appointment
                            </option>

                            @foreach([
                                'Design Only',
                                'Design + Supervision',
                                'Supervision Only',
                                'PMC',
                                'Technical Advisory',
                                'Other'
                            ] as $appointmentType)

                                <option value="{{ $appointmentType }}"
                                    {{ old('appointment_type') === $appointmentType ? 'selected' : '' }}>

                                    {{ $appointmentType }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Company --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Company Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="company_name"
                               value="{{ old('company_name') }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Lead --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Consultant / Lead Name
                        </label>

                        <input type="text"
                               name="consultant_name"
                               value="{{ old('consultant_name') }}"
                               class="form-control">

                    </div>


                    {{-- Specialization --}}
                    <div class="col-12">

                        <label class="form-label">
                            Specialization
                        </label>

                        <input type="text"
                               name="specialization"
                               value="{{ old('specialization') }}"
                               class="form-control"
                               placeholder="e.g. Commercial Buildings, Mall Projects, RCC Structures">

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

                    <div class="col-md-4">

                        <label class="form-label">
                            Registration / License No.
                        </label>

                        <input type="text"
                               name="registration_no"
                               value="{{ old('registration_no') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               value="{{ old('gst_number') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               value="{{ old('pan_number') }}"
                               class="form-control">

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

                    <div class="col-md-4">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input type="text"
                               name="contact_person"
                               value="{{ old('contact_person') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Designation
                        </label>

                        <input type="text"
                               name="contact_designation"
                               value="{{ old('contact_designation') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Alternate Phone
                        </label>

                        <input type="text"
                               name="alternate_phone"
                               value="{{ old('alternate_phone') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="url"
                               name="website"
                               value="{{ old('website') }}"
                               class="form-control"
                               placeholder="https://example.com">

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

                    <div class="col-12">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control">{{ old('address') }}</textarea>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            City
                        </label>

                        <input type="text"
                               name="city"
                               value="{{ old('city') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            State
                        </label>

                        <input type="text"
                               name="state"
                               value="{{ old('state') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Country
                        </label>

                        <input type="text"
                               name="country"
                               value="{{ old('country', 'India') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Postal Code
                        </label>

                        <input type="text"
                               name="postal_code"
                               value="{{ old('postal_code') }}"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Appointment --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Project Appointment
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Appointment Date
                        </label>

                        <input type="date"
                               name="appointment_date"
                               value="{{ old('appointment_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input type="date"
                               name="start_date"
                               value="{{ old('start_date') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            End Date
                        </label>

                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date') }}"
                               class="form-control">

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

                    <textarea name="scope_of_services"
                              rows="5"
                              class="form-control"
                              placeholder="Describe the services included in the consultant appointment...">{{ old('scope_of_services') }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Responsibilities
                    </label>

                    <textarea name="responsibilities"
                              rows="5"
                              class="form-control"
                              placeholder="Describe the consultant's key responsibilities...">{{ old('responsibilities') }}</textarea>

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

                    <div class="col-md-8">

                        <label class="form-label">
                            Contract / Appointment Value
                        </label>

                        <input type="number"
                               name="contract_value"
                               value="{{ old('contract_value', 0) }}"
                               class="form-control"
                               min="0"
                               step="0.01">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               value="{{ old('currency', 'USD') }}"
                               class="form-control"
                               maxlength="10">

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
                    <i class="bi bi-gear me-2"></i>
                    Status & Remarks
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            @foreach([
                                'Active',
                                'Pending',
                                'On Hold',
                                'Completed',
                                'Suspended',
                                'Terminated',
                                'Cancelled'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', 'Active') === $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="3"
                                  class="form-control">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('admin.projects.construction.consultants.index', $project) }}"
               class="btn btn-light border">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Save Consultant

            </button>

        </div>

    </form>

</div>

@endsection