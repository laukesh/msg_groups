@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Add Contractor</h4>

            <p class="text-muted mb-0">
                Register a new fit-out contractor
            </p>
        </div>

        <a href="{{ route('admin.fitout.contractors.index') }}"
           class="btn btn-secondary">

            Back to Contractors

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.fitout.contractors.store') }}">

        @csrf


        {{-- ========================================================= --}}
        {{-- LOGIN ACCOUNT --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Login Account
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            User Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Login Email <span class="text-danger">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Login Phone <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}"
                               required>

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Password --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Confirm Password --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Confirm Password <span class="text-danger">*</span>
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CONTRACTOR INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Contractor Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">


                    {{-- Contractor Name --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Contractor / Company Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="contractor_name"
                               class="form-control @error('contractor_name') is-invalid @enderror"
                               value="{{ old('contractor_name') }}"
                               required>

                        @error('contractor_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Contact Person --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input type="text"
                               name="contact_person"
                               class="form-control @error('contact_person') is-invalid @enderror"
                               value="{{ old('contact_person') }}">

                        @error('contact_person')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Contractor Mobile --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Contractor Mobile
                        </label>

                        <input type="text"
                               name="mobile"
                               class="form-control @error('mobile') is-invalid @enderror"
                               value="{{ old('mobile') }}">

                        @error('mobile')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Contractor Email --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Company Email
                        </label>

                        <input type="email"
                               name="contractor_email"
                               class="form-control @error('contractor_email') is-invalid @enderror"
                               value="{{ old('contractor_email') }}">

                        @error('contractor_email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Address --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LICENSE INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    License & Registration
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Trade License --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Trade License No.
                        </label>

                        <input type="text"
                               name="trade_license_no"
                               class="form-control"
                               value="{{ old('trade_license_no') }}">

                    </div>


                    {{-- Labour License --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Labour License No.
                        </label>

                        <input type="text"
                               name="labour_license_no"
                               class="form-control"
                               value="{{ old('labour_license_no') }}">

                    </div>


                    {{-- GST --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old('gst_number') }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INSURANCE & SAFETY --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Insurance & Safety
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Insurance Policy --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Policy No.
                        </label>

                        <input type="text"
                               name="insurance_policy_no"
                               class="form-control"
                               value="{{ old('insurance_policy_no') }}">

                    </div>


                    {{-- Insurance Expiry --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Expiry
                        </label>

                        <input type="date"
                               name="insurance_expiry"
                               class="form-control"
                               value="{{ old('insurance_expiry') }}">

                    </div>


                    {{-- Safety Induction --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Safety Induction Date
                        </label>

                        <input type="date"
                               name="safety_induction_date"
                               class="form-control"
                               value="{{ old('safety_induction_date') }}">

                    </div>


                    {{-- Worker Count --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Worker Count
                        </label>

                        <input type="number"
                               name="worker_count"
                               min="0"
                               class="form-control"
                               value="{{ old('worker_count', 0) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control @error('remarks') is-invalid @enderror"
                          placeholder="Enter any additional remarks...">{{ old('remarks') }}</textarea>

                @error('remarks')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('admin.fitout.contractors.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg"></i>

                Create Contractor

            </button>

        </div>

    </form>

</div>

@endsection