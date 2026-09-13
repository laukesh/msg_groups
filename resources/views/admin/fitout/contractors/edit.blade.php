@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Contractor
            </h4>

            <p class="text-muted mb-0">
                Update contractor registration and compliance information
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.fitout.contractors.show', $contractor->id) }}"
               class="btn btn-secondary">

                Back to Details

            </a>

        </div>

    </div>


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
          action="{{ route('admin.fitout.contractors.update', $contractor->id) }}">

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- LOGIN ACCOUNT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Login Account
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            User Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $contractor->user->name ?? '') }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Login Email <span class="text-danger">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $contractor->user->email ?? '') }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Login Phone <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $contractor->user->phone ?? '') }}"
                               required>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CONTRACTOR INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Contractor Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Contractor Code
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $contractor->contractor_code }}"
                               readonly>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Contractor / Company Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="contractor_name"
                               class="form-control"
                               value="{{ old('contractor_name', $contractor->contractor_name) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input type="text"
                               name="contact_person"
                               class="form-control"
                               value="{{ old('contact_person', $contractor->contact_person) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Contractor Mobile
                        </label>

                        <input type="text"
                               name="mobile"
                               class="form-control"
                               value="{{ old('mobile', $contractor->mobile) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Company Email
                        </label>

                        <input type="email"
                               name="contractor_email"
                               class="form-control"
                               value="{{ old('contractor_email', $contractor->email) }}">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control">{{ old('address', $contractor->address) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- LICENSE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    License & Registration
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Trade License No.
                        </label>

                        <input type="text"
                               name="trade_license_no"
                               class="form-control"
                               value="{{ old('trade_license_no', $contractor->trade_license_no) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Labour License No.
                        </label>

                        <input type="text"
                               name="labour_license_no"
                               class="form-control"
                               value="{{ old('labour_license_no', $contractor->labour_license_no) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old('gst_number', $contractor->gst_number) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INSURANCE & SAFETY --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Insurance & Safety
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Policy No.
                        </label>

                        <input type="text"
                               name="insurance_policy_no"
                               class="form-control"
                               value="{{ old('insurance_policy_no', $contractor->insurance_policy_no) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Insurance Expiry
                        </label>

                        <input type="date"
                               name="insurance_expiry"
                               class="form-control"
                               value="{{ old('insurance_expiry', optional($contractor->insurance_expiry)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Safety Induction Date
                        </label>

                        <input type="date"
                               name="safety_induction_date"
                               class="form-control"
                               value="{{ old('safety_induction_date', optional($contractor->safety_induction_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Worker Count
                        </label>

                        <input type="number"
                               name="worker_count"
                               min="0"
                               class="form-control"
                               value="{{ old('worker_count', $contractor->worker_count ?? 0) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Remarks
                </h5>
            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control">{{ old('remarks', $contractor->remarks) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('admin.fitout.contractors.show', $contractor->id) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Update Contractor

            </button>

        </div>

    </form>

</div>

@endsection