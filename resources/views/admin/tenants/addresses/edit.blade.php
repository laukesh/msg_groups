@extends('layouts.app')

@section('title', 'Edit Tenant Address')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Address
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.addresses.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Addresses

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

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


    {{-- =========================================================
         EDIT FORM
    ========================================================== --}}

    <div class="row justify-content-center">

        <div class="col-xl-7 col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-map-marker-alt
                                  text-primary
                                  me-2"></i>

                        Address Information

                    </h5>

                    <small class="text-muted">

                        Update the tenant address below.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.addresses.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'address' =>
                                      $address->id,
                              ]
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- ADDRESS TYPE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Address Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="address_type"
                                    class="form-select"
                                    required>

                                <option value="Registered"
                                    @selected(
                                        old(
                                            'address_type',
                                            $address->address_type
                                        ) === 'Registered'
                                    )>

                                    Registered

                                </option>

                                <option value="Corporate"
                                    @selected(
                                        old(
                                            'address_type',
                                            $address->address_type
                                        ) === 'Corporate'
                                    )>

                                    Corporate

                                </option>

                                <option value="Billing"
                                    @selected(
                                        old(
                                            'address_type',
                                            $address->address_type
                                        ) === 'Billing'
                                    )>

                                    Billing

                                </option>

                                <option value="Communication"
                                    @selected(
                                        old(
                                            'address_type',
                                            $address->address_type
                                        ) === 'Communication'
                                    )>

                                    Communication

                                </option>

                            </select>

                        </div>


                        {{-- ADDRESS LINE 1 --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Address Line 1
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="address_line1"
                                   value="{{ old(
                                       'address_line1',
                                       $address->address_line1
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- ADDRESS LINE 2 --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Address Line 2

                            </label>

                            <input type="text"
                                   name="address_line2"
                                   value="{{ old(
                                       'address_line2',
                                       $address->address_line2
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- CITY --}}

                        <div class="mb-3">

                            <label class="form-label">
                                City
                            </label>

                            <input type="text"
                                   name="city"
                                   value="{{ old(
                                       'city',
                                       $address->city
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- STATE + PINCODE --}}

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text"
                                       name="state"
                                       value="{{ old(
                                           'state',
                                           $address->state
                                       ) }}"
                                       class="form-control">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Pincode
                                </label>

                                <input type="text"
                                       name="pincode"
                                       value="{{ old(
                                           'pincode',
                                           $address->pincode
                                       ) }}"
                                       class="form-control"
                                       maxlength="10">

                            </div>

                        </div>


                        {{-- COUNTRY --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Country
                            </label>

                            <input type="text"
                                   name="country"
                                   value="{{ old(
                                       'country',
                                       $address->country
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- DEFAULT --}}

                        <div class="mb-4">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_default"
                                    value="1"
                                    class="form-check-input"
                                    id="is_default"
                                    @checked(
                                        old(
                                            'is_default',
                                            $address->is_default
                                        )
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_default">

                                    Make Default Address

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one address can be default
                                for this tenant.

                            </small>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.addresses.index',
                                $tenant->id
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Address

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection