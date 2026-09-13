@extends('layouts.app')

@section('title', 'Edit Tenant Bank Account')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Bank Account
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.bank-accounts.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Bank Accounts

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

                        <i class="fas fa-university
                                  text-primary
                                  me-2"></i>

                        Bank Account Information

                    </h5>

                    <small class="text-muted">

                        Update the tenant bank account details.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.bank-accounts.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'account' =>
                                      $bankAccount->id,
                              ]
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- ACCOUNT HOLDER --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Account Holder
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="account_holder"
                                   value="{{ old(
                                       'account_holder',
                                       $bankAccount->account_holder
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- BANK NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Bank Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="bank_name"
                                   value="{{ old(
                                       'bank_name',
                                       $bankAccount->bank_name
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- BRANCH NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Branch Name

                            </label>

                            <input type="text"
                                   name="branch_name"
                                   value="{{ old(
                                       'branch_name',
                                       $bankAccount->branch_name
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- ACCOUNT NUMBER --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Account Number
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="account_number"
                                   value="{{ old(
                                       'account_number',
                                       $bankAccount->account_number
                                   ) }}"
                                   class="form-control"
                                   required>

                            <small class="text-muted">

                                Enter the complete account number.

                            </small>

                        </div>


                        {{-- IFSC --}}

                        <div class="mb-3">

                            <label class="form-label">

                                IFSC Code

                            </label>

                            <input type="text"
                                   name="ifsc_code"
                                   value="{{ old(
                                       'ifsc_code',
                                       $bankAccount->ifsc_code
                                   ) }}"
                                   class="form-control"
                                   maxlength="20">

                        </div>


                        {{-- SWIFT --}}

                        <div class="mb-3">

                            <label class="form-label">

                                SWIFT Code

                            </label>

                            <input type="text"
                                   name="swift_code"
                                   value="{{ old(
                                       'swift_code',
                                       $bankAccount->swift_code
                                   ) }}"
                                   class="form-control"
                                   maxlength="20">

                        </div>


                        {{-- ACCOUNT TYPE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Account Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="account_type"
                                    class="form-select"
                                    required>

                                <option value="Current"
                                    @selected(
                                        old(
                                            'account_type',
                                            $bankAccount->account_type
                                        ) === 'Current'
                                    )>

                                    Current

                                </option>

                                <option value="Savings"
                                    @selected(
                                        old(
                                            'account_type',
                                            $bankAccount->account_type
                                        ) === 'Savings'
                                    )>

                                    Savings

                                </option>

                            </select>

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
                                            $bankAccount->is_default
                                        )
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_default">

                                    Make Default Account

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one bank account can be
                                default for this tenant.

                            </small>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.bank-accounts.index',
                                $tenant->id
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Bank Account

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection