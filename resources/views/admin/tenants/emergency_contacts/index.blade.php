@extends('layouts.app')

@section('title', 'Tenant Emergency Contacts')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Emergency Contacts
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
             ADD EMERGENCY CONTACT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-phone-alt
                                  text-danger
                                  me-2"></i>

                        Add Emergency Contact

                    </h5>

                    <small class="text-muted">

                        Add a person to contact during an emergency.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.emergency-contacts.store',
                              $tenant->id
                          ) }}">

                        @csrf


                        {{-- PERSON NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Person Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="person_name"
                                   value="{{ old(
                                       'person_name'
                                   ) }}"
                                   class="form-control"
                                   maxlength="150"
                                   required>

                        </div>


                        {{-- RELATION --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Relation

                            </label>

                            <input type="text"
                                   name="relation"
                                   value="{{ old(
                                       'relation'
                                   ) }}"
                                   class="form-control"
                                   maxlength="100"
                                   placeholder="e.g. Director, Brother">

                        </div>


                        {{-- MOBILE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Mobile

                            </label>

                            <input type="text"
                                   name="mobile"
                                   value="{{ old(
                                       'mobile'
                                   ) }}"
                                   class="form-control"
                                   maxlength="20">

                        </div>


                        {{-- EMAIL --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old(
                                       'email'
                                   ) }}"
                                   class="form-control"
                                   maxlength="150">

                        </div>


                        {{-- REMARKS --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Additional information">{{ old('remarks') }}</textarea>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-danger w-100">

                            <i class="fas fa-plus me-1"></i>

                            Add Emergency Contact

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CONTACT LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">

                                Emergency Contact List

                            </h5>

                            <small class="text-muted">

                                {{ $emergencyContacts->count() }}
                                contact(s)

                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table
                                      table-hover
                                      align-middle
                                      mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Person
                                    </th>

                                    <th>
                                        Relation
                                    </th>

                                    <th>
                                        Mobile
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse(
                                $emergencyContacts
                                as $contact
                            )

                                <tr>

                                    {{-- PERSON --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $contact->person_name }}

                                        </div>

                                    </td>


                                    {{-- RELATION --}}

                                    <td>

                                        {{ $contact->relation ?: '-' }}

                                    </td>


                                    {{-- MOBILE --}}

                                    <td>

                                        @if($contact->mobile)

                                            <a href="tel:{{
                                                $contact->mobile
                                            }}"
                                               class="text-decoration-none">

                                                <i class="fas fa-phone
                                                          text-success
                                                          me-1"></i>

                                                {{ $contact->mobile }}

                                            </a>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- EMAIL --}}

                                    <td>

                                        @if($contact->email)

                                            <a href="mailto:{{
                                                $contact->email
                                            }}"
                                               class="text-decoration-none">

                                                {{ $contact->email }}

                                            </a>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}

                                    <td class="text-end">

                                        <div class="btn-group">

                                            <a href="{{ route(
                                                'admin.tenants.emergency-contacts.edit',
                                                [
                                                    'tenant' =>
                                                        $tenant->id,
                                                    'contact' =>
                                                        $contact->id,
                                                ]
                                            ) }}"
                                               class="btn btn-sm
                                                      btn-outline-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.tenants.emergency-contacts.destroy',
                                                      [
                                                          'tenant' =>
                                                              $tenant->id,
                                                          'contact' =>
                                                              $contact->id,
                                                      ]
                                                  ) }}"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this emergency contact?'
                                                  );">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm
                                                           btn-outline-danger"
                                                    title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- REMARKS --}}

                                @if($contact->remarks)

                                    <tr class="table-light">

                                        <td colspan="5"
                                            class="small text-muted">

                                            <strong>
                                                Remarks:
                                            </strong>

                                            {{ $contact->remarks }}

                                        </td>

                                    </tr>

                                @endif


                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center
                                               text-muted
                                               py-5">

                                        <i class="fas fa-phone-slash
                                                  fa-3x
                                                  d-block
                                                  mb-3">
                                        </i>

                                        <h6>
                                            No emergency contacts found
                                        </h6>

                                        <p class="mb-0">

                                            Add the first emergency
                                            contact using the form.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection