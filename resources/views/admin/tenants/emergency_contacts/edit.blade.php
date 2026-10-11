@extends('layouts.app')

@section('title', 'Edit Emergency Contact')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Emergency Contact
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.emergency-contacts.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Emergency Contacts

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

                        <i class="fas fa-phone-alt
                                  text-danger
                                  me-2"></i>

                        Emergency Contact Information

                    </h5>

                    <small class="text-muted">

                        Update the emergency contact details.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.emergency-contacts.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'contact' =>
                                      $emergencyContact->id,
                              ]
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             PERSON NAME
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Person Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="person_name"
                                   value="{{ old(
                                       'person_name',
                                       $emergencyContact->person_name
                                   ) }}"
                                   class="form-control"
                                   maxlength="150"
                                   required>

                        </div>


                        {{-- =================================================
                             RELATION
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Relation

                            </label>

                            <input type="text"
                                   name="relation"
                                   value="{{ old(
                                       'relation',
                                       $emergencyContact->relation
                                   ) }}"
                                   class="form-control"
                                   maxlength="100"
                                   placeholder="e.g. Director, Brother">

                        </div>


                        {{-- =================================================
                             MOBILE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Mobile

                            </label>

                            <input type="text"
                                   name="mobile"
                                   value="{{ old(
                                       'mobile',
                                       $emergencyContact->mobile
                                   ) }}"
                                   class="form-control"
                                   maxlength="20">

                        </div>


                        {{-- =================================================
                             EMAIL
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old(
                                       'email',
                                       $emergencyContact->email
                                   ) }}"
                                   class="form-control"
                                   maxlength="150">

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Additional information">{{ old(
                                          'remarks',
                                          $emergencyContact->remarks
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.emergency-contacts.index',
                                $tenant->id
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="fas fa-save me-1"></i>

                                Update Emergency Contact

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection