@extends('layouts.app')

@section('title', 'Tenant Contacts')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant Contacts
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.tenants.show',
                $tenant->id
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Tenant Details

            </a>

        </div>

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
             ADD CONTACT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-user-plus
                                  text-primary
                                  me-2"></i>

                        Add Contact

                    </h5>

                    <small class="text-muted">

                        Add a person associated with this tenant.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.contacts.store',
                              $tenant->id
                          ) }}">

                        @csrf


                        {{-- CONTACT NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Contact Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="contact_name"
                                   value="{{ old(
                                       'contact_name'
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
                                       'designation'
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
                                       'mobile'
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
                                       'email'
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
                                        old('is_primary')
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_primary">

                                    Make Primary Contact

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one contact can be primary.

                            </small>

                        </div>


                        {{-- REMARKS --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control">{{ old('remarks') }}</textarea>

                        </div>


                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Add Contact

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
                                Contact List
                            </h5>

                            <small class="text-muted">

                                {{ $contacts->count() }}
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
                                        Contact
                                    </th>

                                    <th>
                                        Designation
                                    </th>

                                    <th>
                                        Mobile
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Primary
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse(
                                $contacts as $contact
                            )

                                <tr>

                                    {{-- CONTACT --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $contact->contact_name }}

                                        </div>

                                        @if($contact->remarks)

                                            <small class="text-muted">

                                                {{ $contact->remarks }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- DESIGNATION --}}

                                    <td>

                                        {{ $contact->designation ?: '-' }}

                                    </td>


                                    {{-- MOBILE --}}

                                    <td>

                                        @if($contact->mobile)

                                            <i class="fas fa-phone
                                                      text-muted
                                                      me-1"></i>

                                            {{ $contact->mobile }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- EMAIL --}}

                                    <td>

                                        {{ $contact->email ?: '-' }}

                                    </td>


                                    {{-- PRIMARY --}}

                                    <td>

                                        @if($contact->is_primary)

                                            <span class="badge bg-success">

                                                <i class="fas fa-star me-1"></i>

                                                Primary

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="text-end">

                                        <div class="btn-group">

                                            <a href="{{ route(
                                                    'admin.tenants.contacts.edit',
                                                    [
                                                        'tenant' => $tenant->id,
                                                        'contact' => $contact->id,
                                                    ]
                                                ) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">

                                                    <i class="fas fa-edit"></i>

                                                </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.tenants.contacts.destroy',
                                                      [
                                                          'tenant' =>
                                                              $tenant->id,
                                                          'contact' =>
                                                              $contact->id,
                                                      ]
                                                  ) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this contact?'
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

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center
                                               text-muted
                                               py-5">

                                        <i class="fas fa-address-book
                                                  fa-3x
                                                  d-block
                                                  mb-3">
                                        </i>

                                        <h6>
                                            No contacts found
                                        </h6>

                                        <p class="mb-0">

                                            Add the first contact
                                            using the form.

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