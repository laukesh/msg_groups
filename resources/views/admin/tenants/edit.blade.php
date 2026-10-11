@extends('layouts.app')

@section('title', 'Edit Tenant')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Tenant
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->tenant_code }}
                -
                {{ $tenant->company_name }}

            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.tenants.show',
                $tenant->id
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- ERRORS --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.tenants.update',
              $tenant->id
          ) }}">

        @csrf

        @method('PUT')


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

                    This account is linked to this tenant.

                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">

                            Login Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="user_name"
                               class="form-control"
                               value="{{ old(
                                   'user_name',
                                   $tenant->user?->name
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Login Email
                            <span class="text-danger">*</span>

                        </label>

                        <input type="email"
                               name="user_email"
                               class="form-control"
                               value="{{ old(
                                   'user_email',
                                   $tenant->user?->email
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            New Password

                        </label>

                        <input type="password"
                               name="password"
                               class="form-control">

                        <small class="text-muted">

                            Leave blank to keep current password.

                        </small>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Confirm New Password

                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TENANT INFORMATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">

                    <i class="fas fa-building
                              text-success
                              me-2"></i>

                    Tenant Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">

                            Tenant Code
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="tenant_code"
                               class="form-control"
                               value="{{ old(
                                   'tenant_code',
                                   $tenant->tenant_code
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">

                            Company Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="company_name"
                               class="form-control"
                               value="{{ old(
                                   'company_name',
                                   $tenant->company_name
                               ) }}"
                               required>

                    </div>


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
                                        $tenant->status
                                    ) === 'Active'
                                )>

                                Active

                            </option>

                            <option value="Inactive"
                                @selected(
                                    old(
                                        'status',
                                        $tenant->status
                                    ) === 'Inactive'
                                )>

                                Inactive

                            </option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Brand Name
                        </label>

                        <input type="text"
                               name="brand_name"
                               class="form-control"
                               value="{{ old(
                                   'brand_name',
                                   $tenant->brand_name
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">

                            Business Category ID

                        </label>

                        <input type="number"
                               name="business_category_id"
                               class="form-control"
                               value="{{ old(
                                   'business_category_id',
                                   $tenant->business_category_id
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TAX & REGISTRATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">

                    <i class="fas fa-id-card
                              text-warning
                              me-2"></i>

                    Tax & Registration

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old(
                                   'gst_number',
                                   $tenant->gst_number
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               class="form-control"
                               value="{{ old(
                                   'pan_number',
                                   $tenant->pan_number
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Company Registration No.

                        </label>

                        <input type="text"
                               name="company_registration_no"
                               class="form-control"
                               value="{{ old(
                                   'company_registration_no',
                                   $tenant->company_registration_no
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CONTACT
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">

                    <i class="fas fa-address-card
                              text-info
                              me-2"></i>

                    Contact Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Tenant Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old(
                                   'email',
                                   $tenant->email
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old(
                                   'phone',
                                   $tenant->phone
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="text"
                               name="website"
                               class="form-control"
                               value="{{ old(
                                   'website',
                                   $tenant->website
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.tenants.show',
                $tenant->id
            ) }}"
               class="btn btn-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save me-1"></i>

                Update Tenant

            </button>

        </div>

    </form>

</div>

@endsection