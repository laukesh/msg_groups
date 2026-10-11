@extends('layouts.app')

@section('title', 'Create Tenant')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Create Tenant
            </h4>

            <p class="text-muted mb-0">
                Create tenant profile and login account
            </p>

        </div>


        <a href="{{ route('admin.tenants.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                Please fix the following errors:

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


    <form method="POST"
          action="{{ route('admin.tenants.store') }}">

        @csrf


        {{-- =====================================================
             LOGIN ACCOUNT
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-1">

                    <i class="fas fa-user-lock
                              text-primary
                              me-2"></i>

                    Login Account

                </h5>

                <small class="text-muted">

                    This account will be linked to the tenant.

                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- LOGIN NAME --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Login Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="user_name"
                               value="{{ old('user_name') }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- LOGIN EMAIL --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Login Email
                            <span class="text-danger">*</span>

                        </label>

                        <input type="email"
                               name="user_email"
                               value="{{ old('user_email') }}"
                               class="form-control"
                               required>

                        <small class="text-muted">

                            Must be unique.

                        </small>

                    </div>


                    {{-- PASSWORD --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Password
                            <span class="text-danger">*</span>

                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               required>

                    </div>


                    {{-- CONFIRM PASSWORD --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Confirm Password
                            <span class="text-danger">*</span>

                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TENANT INFORMATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-1">

                    <i class="fas fa-building
                              text-success
                              me-2"></i>

                    Tenant Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- TENANT CODE --}}

                    <div class="col-md-3">

                        <label class="form-label">

                            Tenant Code
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="tenant_code"
                               value="{{ old('tenant_code') }}"
                               class="form-control"
                               placeholder="TEN-0001"
                               required>

                    </div>


                    {{-- COMPANY NAME --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Company Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="company_name"
                               value="{{ old('company_name') }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="Active"
                                @selected(
                                    old(
                                        'status',
                                        'Active'
                                    ) === 'Active'
                                )>

                                Active

                            </option>

                            <option value="Inactive"
                                @selected(
                                    old('status') === 'Inactive'
                                )>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- BRAND --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Brand Name

                        </label>

                        <input type="text"
                               name="brand_name"
                               value="{{ old('brand_name') }}"
                               class="form-control">

                    </div>


                    {{-- BUSINESS CATEGORY --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Business Category ID

                        </label>

                        <input type="number"
                               name="business_category_id"
                               value="{{ old(
                                   'business_category_id'
                               ) }}"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TAX / REGISTRATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-1">

                    <i class="fas fa-id-card
                              text-warning
                              me-2"></i>

                    Tax & Registration

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- GST --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               value="{{ old('gst_number') }}"
                               class="form-control">

                    </div>


                    {{-- PAN --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               value="{{ old('pan_number') }}"
                               class="form-control">

                    </div>


                    {{-- REGISTRATION --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Company Registration No.

                        </label>

                        <input type="text"
                               name="company_registration_no"
                               value="{{ old(
                                   'company_registration_no'
                               ) }}"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CONTACT INFORMATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-1">

                    <i class="fas fa-address-card
                              text-info
                              me-2"></i>

                    Contact Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- EMAIL --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Tenant Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control">

                    </div>


                    {{-- PHONE --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control">

                    </div>


                    {{-- WEBSITE --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="text"
                               name="website"
                               value="{{ old('website') }}"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('admin.tenants.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save me-1"></i>

                Create Tenant

            </button>

        </div>

    </form>

</div>

@endsection