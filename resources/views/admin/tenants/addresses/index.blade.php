@extends('layouts.app')

@section('title', 'Tenant Addresses')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant Addresses
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.show',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Tenant Details

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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


    <div class="row g-4">


        {{-- =====================================================
             ADD ADDRESS
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-map-marker-alt
                                  text-primary
                                  me-2"></i>

                        Add Address

                    </h5>

                    <small class="text-muted">

                        Add an address for this tenant.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.addresses.store',
                              $tenant->id
                          ) }}">

                        @csrf


                        {{-- ADDRESS TYPE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Address Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="address_type"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Address Type
                                </option>

                                <option value="Registered"
                                    @selected(
                                        old('address_type')
                                        === 'Registered'
                                    )>

                                    Registered

                                </option>

                                <option value="Corporate"
                                    @selected(
                                        old('address_type')
                                        === 'Corporate'
                                    )>

                                    Corporate

                                </option>

                                <option value="Billing"
                                    @selected(
                                        old('address_type')
                                        === 'Billing'
                                    )>

                                    Billing

                                </option>

                                <option value="Communication"
                                    @selected(
                                        old('address_type')
                                        === 'Communication'
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
                                       'address_line1'
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
                                       'address_line2'
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
                                   value="{{ old('city') }}"
                                   class="form-control">

                        </div>


                        <div class="row g-3">


                            {{-- STATE --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text"
                                       name="state"
                                       value="{{ old('state') }}"
                                       class="form-control">

                            </div>


                            {{-- PINCODE --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Pincode
                                </label>

                                <input type="text"
                                       name="pincode"
                                       value="{{ old(
                                           'pincode'
                                       ) }}"
                                       class="form-control"
                                       maxlength="10">

                            </div>

                        </div>


                        {{-- COUNTRY --}}

                        <div class="mb-3 mt-3">

                            <label class="form-label">
                                Country
                            </label>

                            <input type="text"
                                   name="country"
                                   value="{{ old(
                                       'country',
                                       'India'
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- DEFAULT --}}

                        <div class="mb-3">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_default"
                                    value="1"
                                    class="form-check-input"
                                    id="is_default"
                                    @checked(
                                        old('is_default')
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_default">

                                    Make Default Address

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one address can be default.

                            </small>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Add Address

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ADDRESS LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">
                                Address List
                            </h5>

                            <small class="text-muted">

                                {{ $addresses->count() }}
                                address(es)

                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        @forelse(
                            $addresses as $address
                        )

                            <div class="col-lg-6">

                                <div class="border
                                            rounded
                                            p-3
                                            h-100">

                                    {{-- HEADER --}}

                                    <div class="d-flex
                                                justify-content-between
                                                align-items-center
                                                mb-3">

                                        <div>

                                            <span class="badge
                                                         bg-primary">

                                                {{ $address->address_type }}

                                            </span>

                                        </div>


                                        @if(
                                            $address->is_default
                                        )

                                            <span class="badge
                                                         bg-success">

                                                <i class="fas fa-check
                                                          me-1"></i>

                                                Default

                                            </span>

                                        @endif

                                    </div>


                                    {{-- ADDRESS --}}

                                    <div class="mb-3">

                                        <div class="fw-semibold">

                                            {{ $address->address_line1 }}

                                        </div>


                                        @if(
                                            $address->address_line2
                                        )

                                            <div>

                                                {{ $address->address_line2 }}

                                            </div>

                                        @endif


                                        <div class="mt-2">

                                            @if($address->city)

                                                {{ $address->city }}

                                            @endif

                                            @if($address->state)

                                                @if($address->city)
                                                    ,
                                                @endif

                                                {{ $address->state }}

                                            @endif

                                        </div>


                                        <div>

                                            @if($address->pincode)

                                                {{ $address->pincode }}

                                            @endif

                                            @if($address->country)

                                                @if($address->pincode)
                                                    ,
                                                @endif

                                                {{ $address->country }}

                                            @endif

                                        </div>

                                    </div>


                                    {{-- ACTIONS --}}

                                    <div class="border-top
                                                pt-3
                                                d-flex
                                                justify-content-end
                                                gap-2">

                                        <a href="{{ route(
                                            'admin.tenants.addresses.edit',
                                            [
                                                'tenant' =>
                                                    $tenant->id,
                                                'address' =>
                                                    $address->id,
                                            ]
                                        ) }}"
                                           class="btn btn-sm
                                                  btn-outline-warning">

                                            <i class="fas fa-edit me-1"></i>

                                            Edit

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.tenants.addresses.destroy',
                                                  [
                                                      'tenant' =>
                                                          $tenant->id,
                                                      'address' =>
                                                          $address->id,
                                                  ]
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this address?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm
                                                       btn-outline-danger">

                                                <i class="fas fa-trash me-1"></i>

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="col-12">

                                <div class="text-center
                                            text-muted
                                            py-5">

                                    <i class="fas fa-map-marker-alt
                                              fa-3x
                                              d-block
                                              mb-3">
                                    </i>

                                    <h6>
                                        No addresses found
                                    </h6>

                                    <p class="mb-0">

                                        Add the first address
                                        using the form.

                                    </p>

                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection