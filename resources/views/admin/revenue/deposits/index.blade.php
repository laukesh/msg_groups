@extends('layouts.app')

@section('title', 'Deposits')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Deposits
            </h4>

            <p class="text-muted mb-0">
                Manage security, additional and utility deposits.
            </p>
        </div>

        <a href="{{ url()->previous() }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

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
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

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
             ADD DEPOSIT
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-money-check-alt
                                  text-primary
                                  me-2"></i>

                        Add Deposit

                    </h5>

                    <small class="text-muted">

                        Create a deposit against a lease agreement.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.deposits.store'
                          ) }}">

                        @csrf


                        {{-- =================================================
                             LEASE AGREEMENT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Lease Agreement
                                <span class="text-danger">*</span>

                            </label>

                            <select name="lease_agreement_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Lease Agreement
                                </option>

                                @foreach(
                                    $leaseAgreements
                                    as $agreement
                                )

                                    <option
                                        value="{{ $agreement->id }}"
                                        @selected(
                                            old(
                                                'lease_agreement_id'
                                            ) == $agreement->id
                                        )>

                                        {{ $agreement->agreement_no }}
                                        -
                                        {{ $agreement->agreement_status }}

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">

                                Only Active and Renewed agreements
                                are available.

                            </small>

                        </div>


                        {{-- =================================================
                             DEPOSIT TYPE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Deposit Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="deposit_type"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Deposit Type
                                </option>

                                <option
                                    value="Security Deposit"
                                    @selected(
                                        old('deposit_type')
                                        === 'Security Deposit'
                                    )>

                                    Security Deposit

                                </option>

                                <option
                                    value="Additional Deposit"
                                    @selected(
                                        old('deposit_type')
                                        === 'Additional Deposit'
                                    )>

                                    Additional Deposit

                                </option>

                                <option
                                    value="Utility Deposit"
                                    @selected(
                                        old('deposit_type')
                                        === 'Utility Deposit'
                                    )>

                                    Utility Deposit

                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             DEPOSIT AMOUNT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Deposit Amount
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input type="number"
                                       name="deposit_amount"
                                       value="{{ old(
                                           'deposit_amount'
                                       ) }}"
                                       class="form-control"
                                       min="0.01"
                                       step="0.01"
                                       placeholder="0.00"
                                       required>

                            </div>

                        </div>


                        {{-- =================================================
                             DUE DATE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Due Date

                            </label>

                            <input type="date"
                                   name="due_date"
                                   value="{{ old(
                                       'due_date'
                                   ) }}"
                                   class="form-control">

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Optional remarks">{{ old(
                                          'remarks'
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             INFO
                        ================================================== --}}

                        <div class="alert alert-light border mb-3">

                            <div class="small">

                                <div class="fw-semibold mb-1">

                                    <i class="fas fa-info-circle
                                              text-primary
                                              me-1"></i>

                                    Deposit calculation

                                </div>

                                <div class="text-muted">

                                    Received amount will initially be
                                    <strong>$0.00</strong>.

                                </div>

                                <div class="text-muted">

                                    Balance will equal the deposit amount.

                                </div>

                                <div class="text-muted">

                                    Payment status will be
                                    <strong>Pending</strong>.

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             SUBMIT
                        ================================================== --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Create Deposit

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             DEPOSIT LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">
                                Deposit Records
                            </h5>

                            <small class="text-muted">

                                {{ $deposits->count() }}
                                deposit(s)

                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if($deposits->count() > 0)

                        <div class="table-responsive">

                            <table class="table
                                          table-hover
                                          align-middle
                                          mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Agreement
                                        </th>

                                        <th>
                                            Type
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                        <th>
                                            Received
                                        </th>

                                        <th>
                                            Balance
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Refundable
                                        </th>

                                        <th class="text-end">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $deposits as $deposit
                                    )

                                        <tr>

                                            {{-- AGREEMENT --}}

                                            <td>

                                                @if(
                                                    $deposit
                                                        ->leaseAgreement
                                                )

                                                    <div class="fw-semibold">

                                                        {{
                                                            $deposit
                                                                ->leaseAgreement
                                                                ->agreement_no
                                                        }}

                                                    </div>

                                                    <small class="text-muted">

                                                        {{
                                                            $deposit
                                                                ->leaseAgreement
                                                                ->agreement_status
                                                        }}

                                                    </small>

                                                @else

                                                    <span class="text-danger">

                                                        Agreement not found

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- TYPE --}}

                                            <td>

                                                @php

                                                    $typeClass = match(
                                                        $deposit->deposit_type
                                                    ) {
                                                        'Security Deposit'
                                                            => 'primary',

                                                        'Additional Deposit'
                                                            => 'warning',

                                                        'Utility Deposit'
                                                            => 'info',

                                                        default
                                                            => 'secondary',
                                                    };

                                                @endphp

                                                <span class="badge
                                                             bg-{{ $typeClass }}">

                                                    {{
                                                        $deposit
                                                            ->deposit_type
                                                    }}

                                                </span>

                                            </td>


                                            {{-- AMOUNT --}}

                                            <td>

                                                <span class="fw-semibold">

                                                    ${{
                                                        number_format(
                                                            $deposit
                                                                ->deposit_amount,
                                                            2
                                                        )
                                                    }}

                                                </span>

                                            </td>


                                            {{-- RECEIVED --}}

                                            <td>

                                                ${{
                                                    number_format(
                                                        $deposit
                                                            ->received_amount,
                                                        2
                                                    )
                                                }}

                                            </td>


                                            {{-- BALANCE --}}

                                            <td>

                                                @if(
                                                    $deposit->balance_amount > 0
                                                )

                                                    <span class="text-danger
                                                                 fw-semibold">

                                                        ${{
                                                            number_format(
                                                                $deposit
                                                                    ->balance_amount,
                                                                2
                                                            )
                                                        }}

                                                    </span>

                                                @else

                                                    <span class="text-success
                                                                 fw-semibold">

                                                        $0.00

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                @php

                                                    $statusClass = match(
                                                        $deposit
                                                            ->payment_status
                                                    ) {

                                                        'Pending'
                                                            => 'secondary',

                                                        'Partial'
                                                            => 'warning',

                                                        'Paid'
                                                            => 'success',

                                                        'Refunded'
                                                            => 'info',

                                                        default
                                                            => 'dark',
                                                    };

                                                @endphp

                                                <span class="badge
                                                             bg-{{ $statusClass }}">

                                                    {{
                                                        $deposit
                                                            ->payment_status
                                                    }}

                                                </span>

                                            </td>


                                            {{-- REFUNDABLE --}}

                                            <td>

                                                ${{
                                                    number_format(
                                                        $deposit
                                                            ->refundable_amount,
                                                        2
                                                    )
                                                }}

                                            </td>


                                            {{-- ACTIONS --}}

                                            <td class="text-end">

                                                <div class="d-inline-flex
                                                            gap-1">

                                                    <a href="{{ route(
                                                        'admin.revenue.deposits.edit',
                                                        $deposit->id
                                                    ) }}"
                                                       class="btn btn-sm
                                                              btn-outline-warning"
                                                       title="Edit">

                                                        <i class="fas fa-edit"></i>

                                                    </a>


                                                    @if(
                                                        $deposit
                                                            ->received_amount <= 0
                                                    )

                                                        <form method="POST"
                                                              action="{{ route(
                                                                  'admin.revenue.deposits.destroy',
                                                                  $deposit->id
                                                              ) }}"
                                                              onsubmit="return confirm(
                                                                  'Are you sure you want to delete this deposit?'
                                                              );">

                                                            @csrf

                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="btn btn-sm
                                                                           btn-outline-danger"
                                                                    title="Delete">

                                                                <i class="fas fa-trash"></i>

                                                            </button>

                                                        </form>

                                                    @endif

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center
                                    text-muted
                                    py-5">

                            <i class="fas fa-hand-holding-usd
                                      fa-3x
                                      d-block
                                      mb-3">
                            </i>

                            <h6>
                                No deposits found
                            </h6>

                            <p class="mb-0">

                                Create your first deposit
                                using the form.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
<<<<<<< HEAD

=======
>>>>>>> 830839205abc2b72693740b12d977eb9c34f36aa
