@extends('layouts.app')

@section('title', 'Edit Tenant Contact')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Contact
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>


        <a href="{{ route(
            'admin.tenants.contacts.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Contacts

        </a>

    </div>


    {{-- =========================================================
         SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         ERRORS
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


    <div class="row justify-content-center">

        <div class="col-xl-7 col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-user-edit
                                  text-primary
                                  me-2"></i>

                        Contact Information

                    </h5>

                    <small class="text-muted">

                        Update contact details below.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.contacts.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'contact' =>
                                      $contact->id,
                              ]
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- CONTACT NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Contact Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="contact_name"
                                   value="{{ old(
                                       'contact_name',
                                       $contact->contact_name
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- DESIGNATION --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Designation

                            </label>

                            <input type="text"
                                   name="designation"
                                   value="{{ old(
                                       'designation',
                                       $contact->designation
                                   ) }}"
                                   class="form-control"
                                   placeholder="e.g. Director">

                        </div>


                        {{-- MOBILE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Mobile

                            </label>

                            <input type="text"
                                   name="mobile"
                                   value="{{ old(
                                       'mobile',
                                       $contact->mobile
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- EMAIL --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old(
                                       'email',
                                       $contact->email
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- PRIMARY --}}

                        <div class="mb-3">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_primary"
                                    value="1"
                                    class="form-check-input"
                                    id="is_primary"
                                    @checked(
                                        old(
                                            'is_primary',
                                            $contact->is_primary
                                        )
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_primary">

                                    Make Primary Contact

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one contact can be primary
                                for this tenant.

                            </small>

                        </div>


                        {{-- REMARKS --}}

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control">{{ old(
                                          'remarks',
                                          $contact->remarks
                                      ) }}</textarea>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.contacts.index',
                                $tenant->id
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Contact

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection