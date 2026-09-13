@extends('layouts.app')

@section('title', 'Tenant Bank Accounts')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant Bank Accounts
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
             ADD BANK ACCOUNT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-university
                                  text-primary
                                  me-2"></i>

                        Add Bank Account

                    </h5>

                    <small class="text-muted">

                        Add tenant banking information.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.bank-accounts.store',
                              $tenant->id
                          ) }}">

                        @csrf


                        {{-- ACCOUNT HOLDER --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Account Holder
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="account_holder"
                                   value="{{ old(
                                       'account_holder'
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
                                       'bank_name'
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
                                       'branch_name'
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
                                       'account_number'
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- IFSC --}}

                        <div class="mb-3">

                            <label class="form-label">

                                IFSC Code

                            </label>

                            <input type="text"
                                   name="ifsc_code"
                                   value="{{ old(
                                       'ifsc_code'
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
                                       'swift_code'
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

                                <option value="">
                                    Select Account Type
                                </option>

                                <option value="Current"
                                    @selected(
                                        old('account_type')
                                        === 'Current'
                                    )>

                                    Current

                                </option>

                                <option value="Savings"
                                    @selected(
                                        old('account_type')
                                        === 'Savings'
                                    )>

                                    Savings

                                </option>

                            </select>

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

                                    Make Default Account

                                </label>

                            </div>

                            <small class="text-muted">

                                Only one bank account can be
                                default.

                            </small>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Add Bank Account

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             BANK ACCOUNT LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">
                                Bank Account List
                            </h5>

                            <small class="text-muted">

                                {{ $bankAccounts->count() }}
                                account(s)

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
                                        Bank
                                    </th>

                                    <th>
                                        Account Holder
                                    </th>

                                    <th>
                                        Account Number
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Default
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse(
                                $bankAccounts as $bankAccount
                            )

                                <tr>

                                    {{-- BANK --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $bankAccount->bank_name }}

                                        </div>

                                        @if(
                                            $bankAccount->branch_name
                                        )

                                            <small class="text-muted">

                                                {{ $bankAccount->branch_name }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- HOLDER --}}

                                    <td>

                                        {{ $bankAccount->account_holder }}

                                    </td>


                                    {{-- ACCOUNT NUMBER --}}

                                    <td>

                                        @php

                                            $accountNumber =
                                                $bankAccount->account_number;

                                            $maskedAccount =
                                                strlen($accountNumber) > 4
                                                    ? str_repeat(
                                                        'X',
                                                        strlen(
                                                            $accountNumber
                                                        ) - 4
                                                    ) .
                                                    substr(
                                                        $accountNumber,
                                                        -4
                                                    )
                                                    : $accountNumber;

                                        @endphp

                                        <span class="font-monospace">

                                            {{ $maskedAccount }}

                                        </span>

                                        @if(
                                            $bankAccount->ifsc_code
                                        )

                                            <small class="d-block
                                                         text-muted">

                                                IFSC:
                                                {{ $bankAccount->ifsc_code }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- TYPE --}}

                                    <td>

                                        <span class="badge bg-light
                                                     text-dark
                                                     border">

                                            {{ $bankAccount->account_type }}

                                        </span>

                                    </td>


                                    {{-- DEFAULT --}}

                                    <td>

                                        @if(
                                            $bankAccount->is_default
                                        )

                                            <span class="badge bg-success">

                                                <i class="fas fa-check
                                                          me-1"></i>

                                                Default

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
                                                'admin.tenants.bank-accounts.edit',
                                                [
                                                    'tenant' =>
                                                        $tenant->id,
                                                    'account' =>
                                                        $bankAccount->id,
                                                ]
                                            ) }}"
                                               class="btn btn-sm
                                                      btn-outline-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.tenants.bank-accounts.destroy',
                                                      [
                                                          'tenant' =>
                                                              $tenant->id,
                                                          'account' =>
                                                              $bankAccount->id,
                                                      ]
                                                  ) }}"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this bank account?'
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

                                        <i class="fas fa-university
                                                  fa-3x
                                                  d-block
                                                  mb-3">
                                        </i>

                                        <h6>
                                            No bank accounts found
                                        </h6>

                                        <p class="mb-0">

                                            Add the first bank account
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