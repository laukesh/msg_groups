@extends('layouts.app')

@section('title', 'Schedule Site Visit')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-map-pin-user-line me-1"></i>
                Schedule Site Visit
            </h4>

            <p class="text-muted mb-0">
                Schedule a property or unit visit for a leasing prospect.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.leasing.site-visits.index') }}"
               class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>
                Back to Site Visits
            </a>
        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.leasing.site-visits.store') }}">

        @csrf


        <div class="row g-4">


            {{-- =================================================
                VISIT DETAILS
            ================================================== --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-calendar-event-line me-1"></i>
                            Visit Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Prospect --}}
                            <div class="col-md-6">

                                <label for="prospect_id"
                                       class="form-label">
                                    Prospect
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="prospect_id"
                                        id="prospect_id"
                                        class="form-select @error('prospect_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Prospect --
                                    </option>

                                    @foreach ($prospects as $prospect)

                                        <option value="{{ $prospect->id }}"
                                            @selected(
                                                old(
                                                    'prospect_id',
                                                    $selectedProspect ?? ''
                                                ) == $prospect->id
                                            )>

                                            {{ $prospect->company_name }}

                                            @if ($prospect->contact_name)
                                                — {{ $prospect->contact_name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('prospect_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Unit --}}
                            <div class="col-md-6">

                                <label for="unit_id"
                                       class="form-label">

                                    Unit
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="unit_id"
                                        id="unit_id"
                                        class="form-select @error('unit_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Unit --
                                    </option>

                                    @foreach ($units as $unit)

                                        <option value="{{ $unit->id }}"
                                            @selected(
                                                old('unit_id') == $unit->id
                                            )>

                                            {{ $unit->unit_no }}

                                            @if (!empty($unit->shop_name))
                                                — {{ $unit->shop_name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('unit_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Visit Date --}}
                            <div class="col-md-6">

                                <label for="visit_date"
                                       class="form-label">

                                    Visit Date & Time
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="datetime-local"
                                       name="visit_date"
                                       id="visit_date"
                                       class="form-control @error('visit_date') is-invalid @enderror"
                                       value="{{ old('visit_date') }}"
                                       required>

                                @error('visit_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Assigned Officer --}}
                            <div class="col-md-6">

                                <label for="assigned_to"
                                       class="form-label">

                                    Leasing Officer

                                </label>

                                <select name="assigned_to"
                                        id="assigned_to"
                                        class="form-select @error('assigned_to') is-invalid @enderror">

                                    <option value="">
                                        -- Select Leasing Officer --
                                    </option>

                                    @foreach ($officers as $officer)

                                        <option value="{{ $officer->id }}"
                                            @selected(
                                                old('assigned_to') == $officer->id
                                            )>

                                            {{ $officer->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('assigned_to')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Attendees --}}
                            <div class="col-12">

                                <label for="attendees"
                                       class="form-label">

                                    Attendees

                                </label>

                                <textarea name="attendees"
                                          id="attendees"
                                          rows="3"
                                          class="form-control @error('attendees') is-invalid @enderror"
                                          placeholder="Enter names and roles of people attending the visit">{{ old('attendees') }}</textarea>

                                @error('attendees')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Example: Ahmed Ali - Director, John Doe - Leasing Manager
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                STATUS
            ================================================== --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-information-line me-1"></i>
                            Visit Status
                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- Status --}}
                        <div class="mb-3">

                            <label for="status"
                                   class="form-label">

                                Status
                                <span class="text-danger">*</span>

                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>

                                @php
                                    $statuses = [
                                        'SCHEDULED'   => 'Scheduled',
                                        'COMPLETED'   => 'Completed',
                                        'CANCELLED'   => 'Cancelled',
                                        'NO_SHOW'     => 'No Show',
                                        'RESCHEDULED' => 'Rescheduled',
                                    ];
                                @endphp

                                @foreach ($statuses as $value => $label)

                                    <option value="{{ $value }}"
                                        @selected(
                                            old('status', 'SCHEDULED') === $value
                                        )>

                                        {{ $label }}

                                    </option>

                                @endforeach

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Interest --}}
                        <div class="mb-3">

                            <label for="interest"
                                   class="form-label">

                                Interest Level

                            </label>

                            <select name="interest"
                                    id="interest"
                                    class="form-select @error('interest') is-invalid @enderror">

                                <option value="">
                                    -- Select Interest --
                                </option>

                                <option value="HIGH"
                                    @selected(old('interest') === 'HIGH')>
                                    High
                                </option>

                                <option value="MEDIUM"
                                    @selected(old('interest') === 'MEDIUM')>
                                    Medium
                                </option>

                                <option value="LOW"
                                    @selected(old('interest') === 'LOW')>
                                    Low
                                </option>

                                <option value="NOT_INTERESTED"
                                    @selected(old('interest') === 'NOT_INTERESTED')>
                                    Not Interested
                                </option>

                            </select>

                            @error('interest')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Follow Up --}}
                        <div class="mb-3">

                            <label for="follow_up_date"
                                   class="form-label">

                                Follow-up Date

                            </label>

                            <input type="datetime-local"
                                   name="follow_up_date"
                                   id="follow_up_date"
                                   class="form-control @error('follow_up_date') is-invalid @enderror"
                                   value="{{ old('follow_up_date') }}">

                            @error('follow_up_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Outcome --}}
                        <div class="mb-3">

                            <label for="outcome"
                                   class="form-label">

                                Outcome

                            </label>

                            <input type="text"
                                   name="outcome"
                                   id="outcome"
                                   class="form-control @error('outcome') is-invalid @enderror"
                                   value="{{ old('outcome') }}"
                                   placeholder="e.g. Interested in leasing">

                            @error('outcome')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                FEEDBACK / NOTES
            ================================================== --}}
            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-feedback-line me-1"></i>
                            Visit Feedback
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Feedback --}}
                            <div class="col-md-6">

                                <label for="feedback"
                                       class="form-label">

                                    Feedback

                                </label>

                                <textarea name="feedback"
                                          id="feedback"
                                          rows="5"
                                          class="form-control @error('feedback') is-invalid @enderror"
                                          placeholder="Record the prospect's feedback about the property/unit">{{ old('feedback') }}</textarea>

                                @error('feedback')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Notes --}}
                            <div class="col-md-6">

                                <label for="notes"
                                       class="form-label">

                                    Internal Notes

                                </label>

                                <textarea name="notes"
                                          id="notes"
                                          rows="5"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Enter internal leasing notes">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ACTIONS
            ================================================== --}}
            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <a href="{{ route('admin.leasing.site-visits.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="ri-close-line me-1"></i>
                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-calendar-check-line me-1"></i>
                            Schedule Site Visit

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection