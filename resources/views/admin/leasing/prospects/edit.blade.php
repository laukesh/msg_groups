@extends('layouts.app')

@section('title', 'Edit Lead / Prospect')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Lead / Prospect
            </h4>

            <p class="text-muted mb-0">
                {{ $prospect->company_name ?: ($prospect->contact_name ?: 'Lead / Prospect') }}
            </p>
        </div>

        <div class="d-flex gap-2">

            @can('prospects.view')

                <a href="{{ route(
                    'admin.leasing.prospects.show',
                    $prospect
                ) }}"
                   class="btn btn-success">

                    <i class="ri-eye-line me-1"></i>
                    View

                </a>

            @endcan

            <a href="{{ route(
                'admin.leasing.prospects.index'
            ) }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-semibold mb-2">
                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        UPDATE FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route(
              'admin.leasing.prospects.update',
              $prospect
          ) }}">

        @csrf
        @method('PUT')


        <div class="row g-4">

            {{-- =================================================
                LEFT COLUMN
            ================================================== --}}
            <div class="col-lg-8">


                {{-- =================================================
                    CONTACT INFORMATION
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="ri-user-line me-1"></i>
                            Contact Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Company --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Company
                                </label>

                                <input type="text"
                                       name="company_name"
                                       class="form-control @error('company_name') is-invalid @enderror"
                                       value="{{ old(
                                           'company_name',
                                           $prospect->company_name
                                       ) }}"
                                       placeholder="Company name">

                                @error('company_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Contact --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Contact Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="contact_name"
                                       class="form-control @error('contact_name') is-invalid @enderror"
                                       value="{{ old(
                                           'contact_name',
                                           $prospect->contact_name
                                       ) }}"
                                       placeholder="Contact person"
                                       required>

                                @error('contact_name')

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

                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old(
                                           'email',
                                           $prospect->email
                                       ) }}"
                                       placeholder="name@example.com">

                                @error('email')

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

                                <input type="text"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old(
                                           'phone',
                                           $prospect->phone
                                       ) }}"
                                       placeholder="Phone number">

                                @error('phone')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Brand --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Brand
                                </label>

                                <input type="text"
                                       name="brand_name"
                                       class="form-control @error('brand_name') is-invalid @enderror"
                                       value="{{ old(
                                           'brand_name',
                                           $prospect->brand_name
                                       ) }}"
                                       placeholder="Brand name">

                                @error('brand_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Category --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Business Category
                                </label>

                                <input type="text"
                                       name="category"
                                       class="form-control @error('category') is-invalid @enderror"
                                       value="{{ old(
                                           'category',
                                           $prospect->category
                                       ) }}"
                                       placeholder="Retail, Restaurant, Fashion...">

                                @error('category')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SPACE REQUIREMENTS
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="ri-building-line me-1"></i>
                            Space Requirements

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Area Need --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Area Required
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="area_need"
                                           class="form-control @error('area_need') is-invalid @enderror"
                                           value="{{ old(
                                               'area_need',
                                               $prospect->area_need
                                           ) }}"
                                           placeholder="0.00">

                                    <span class="input-group-text">
                                        Sq. Ft.
                                    </span>

                                </div>

                                @error('area_need')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Budget --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Budget
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="budget"
                                           class="form-control @error('budget') is-invalid @enderror"
                                           value="{{ old(
                                               'budget',
                                               $prospect->budget
                                           ) }}"
                                           placeholder="0.00">

                                </div>

                                @error('budget')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Preferred Location --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Preferred Location
                                </label>

                                <input type="text"
                                       name="preferred_location"
                                       class="form-control @error('preferred_location') is-invalid @enderror"
                                       value="{{ old(
                                           'preferred_location',
                                           $prospect->preferred_location
                                       ) }}"
                                       placeholder="Ground Floor, Fashion Zone, East Wing...">

                                @error('preferred_location')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                        </div>

                    </div>

                </div>


                {{-- =================================================
                    NOTES
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="ri-sticky-note-line me-1"></i>
                            Notes

                        </h5>

                    </div>

                    <div class="card-body">

                        <textarea name="notes"
                                  rows="6"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Add notes about this lead/prospect...">{{ old(
                                      'notes',
                                      $prospect->notes
                                  ) }}</textarea>

                        @error('notes')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


            </div>


            {{-- =================================================
                RIGHT COLUMN
            ================================================== --}}
            <div class="col-lg-4">


                {{-- =================================================
                    CRM DETAILS
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="ri-settings-3-line me-1"></i>
                            CRM Details

                        </h5>

                    </div>

                    <div class="card-body">


                        {{-- Source --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Lead Source
                            </label>

                            <input type="text"
                                   name="source"
                                   class="form-control @error('source') is-invalid @enderror"
                                   value="{{ old(
                                       'source',
                                       $prospect->source
                                   ) }}"
                                   placeholder="Website, Referral, Walk-in...">

                            @error('source')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">

                                @foreach([
                                    'lead',
                                    'prospect',
                                    'qualified',
                                    'site_visit',
                                    'negotiation',
                                    'converted',
                                    'lost',
                                    'inactive'
                                ] as $status)

                                    <option value="{{ $status }}"
                                        @selected(
                                            old(
                                                'status',
                                                strtolower($prospect->status)
                                            ) === $status
                                        )>

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        ) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Priority --}}
                        @if(\Illuminate\Support\Facades\Schema::hasColumn(
                            'leasing_prospects',
                            'priority'
                        ))

                            <div class="mb-3">

                                <label class="form-label">
                                    Priority
                                </label>

                                <select name="priority"
                                        class="form-select">

                                    @foreach([
                                        'low',
                                        'medium',
                                        'high',
                                        'urgent'
                                    ] as $priority)

                                        <option value="{{ $priority }}"
                                            @selected(
                                                old(
                                                    'priority',
                                                    $prospect->priority
                                                ) === $priority
                                            )>

                                            {{ ucfirst($priority) }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @endif


                        {{-- Owner --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Leasing Officer / Owner
                            </label>

                            <select name="assigned_to"
                                    class="form-select @error('assigned_to') is-invalid @enderror">

                                <option value="">
                                    Select Owner
                                </option>

                                @foreach($owners as $owner)

                                    <option value="{{ $owner->id }}"
                                        @selected(
                                            old(
                                                'assigned_to',
                                                $prospect->assigned_to
                                            ) == $owner->id
                                        )>

                                        {{ $owner->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('assigned_to')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Next Follow-up --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Next Follow-up
                            </label>

                            <input type="datetime-local"
                                   name="next_follow_up_at"
                                   class="form-control @error('next_follow_up_at') is-invalid @enderror"
                                   value="{{ old(
                                       'next_follow_up_at',
                                       $prospect->next_follow_up_at
                                           ? $prospect->next_follow_up_at->format('Y-m-d\TH:i')
                                           : ''
                                   ) }}">

                            @error('next_follow_up_at')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>


                {{-- =================================================
                    CURRENT RECORD INFORMATION
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0">
                            <i class="ri-information-line me-1"></i>
                            Record Information
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="small">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    ID
                                </span>

                                <span class="fw-semibold">
                                    #{{ $prospect->id }}
                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Created
                                </span>

                                <span>
                                    {{ $prospect->created_at
                                        ? $prospect->created_at->format('d M Y')
                                        : '-' }}
                                </span>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Updated
                                </span>

                                <span>
                                    {{ $prospect->updated_at
                                        ? $prospect->updated_at->format('d M Y')
                                        : '-' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">

                            <i class="ri-save-line me-1"></i>
                            Update Lead / Prospect

                        </button>

                        <a href="{{ route(
                            'admin.leasing.prospects.show',
                            $prospect
                        ) }}"
                           class="btn btn-light w-100">

                            Cancel

                        </a>

                    </div>

                </div>


            </div>

        </div>

    </form>

</div>

@endsection