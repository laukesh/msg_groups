@extends('layouts.app')

@section('title', 'Create Lease Termination')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Create Lease Termination</h4>
            <div class="text-muted">
                Create a termination request for an active lease agreement.
            </div>
        </div>

        <a href="{{ route('admin.leasing.terminations.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


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
          action="{{ route('admin.leasing.terminations.store') }}">

        @csrf


        {{-- Agreement Information --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Lease Agreement
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Lease Agreement
                            <span class="text-danger">*</span>
                        </label>

                        <select name="lease_agreement_id"
                                id="lease_agreement_id"
                                class="form-select @error('lease_agreement_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Lease Agreement
                            </option>

                            @foreach($agreements as $agreement)

                                <option
                                    value="{{ $agreement->id }}"
                                    data-tenant="{{ $agreement->tenant?->company_name ?? $agreement->tenant?->name ?? '' }}"
                                    data-start="{{ $agreement->lease_start_date?->format('Y-m-d') }}"
                                    data-end="{{ $agreement->lease_end_date?->format('Y-m-d') }}"
                                    data-rent="{{ $agreement->monthly_rent ?? 0 }}"
                                    data-deposit="{{ $agreement->security_deposit ?? 0 }}"
                                    {{ old('lease_agreement_id') == $agreement->id ? 'selected' : '' }}
                                >

                                    {{ $agreement->agreement_no }}

                                </option>

                            @endforeach

                        </select>

                        @error('lease_agreement_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Tenant
                        </label>

                        <input type="text"
                               id="tenant_name"
                               class="form-control"
                               readonly
                               placeholder="Tenant will appear here">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Lease Start Date
                        </label>

                        <input type="text"
                               id="lease_start_date"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Lease End Date
                        </label>

                        <input type="text"
                               id="lease_end_date"
                               class="form-control"
                               readonly>

                    </div>

                </div>

            </div>

        </div>


        {{-- Termination Details --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Termination Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Termination Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="termination_type"
                                class="form-select @error('termination_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Lease Expiry',
                                'Tenant Request',
                                'Mall Request',
                                'Mutual Agreement',
                                'Legal',
                                'Default'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old('termination_type') == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('termination_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Request Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="request_date"
                               value="{{ old('request_date', date('Y-m-d')) }}"
                               class="form-control @error('request_date') is-invalid @enderror"
                               required>

                        @error('request_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Notice Date
                        </label>

                        <input type="date"
                               name="notice_date"
                               value="{{ old('notice_date') }}"
                               class="form-control @error('notice_date') is-invalid @enderror">

                        @error('notice_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Effective Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="effective_date"
                               value="{{ old('effective_date') }}"
                               class="form-control @error('effective_date') is-invalid @enderror"
                               required>

                        @error('effective_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Reason
                        </label>

                        <textarea name="reason"
                                  rows="2"
                                  class="form-control @error('reason') is-invalid @enderror"
                                  placeholder="Enter reason for termination">{{ old('reason') }}</textarea>

                        @error('reason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial Settlement --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Financial Settlement
                </h5>

                <small class="text-muted">
                    Enter outstanding dues, penalties, damages and refundable deposit.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Outstanding Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="outstanding_amount"
                                   id="outstanding_amount"
                                   value="{{ old('outstanding_amount', 0) }}"
                                   class="form-control amount-field">

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Penalty Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="penalty_amount"
                                   id="penalty_amount"
                                   value="{{ old('penalty_amount', 0) }}"
                                   class="form-control amount-field">

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Damage Charges
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="damage_charges"
                                   id="damage_charges"
                                   value="{{ old('damage_charges', 0) }}"
                                   class="form-control amount-field">

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Refundable Deposit
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="refundable_deposit"
                                   id="refundable_deposit"
                                   value="{{ old('refundable_deposit', 0) }}"
                                   class="form-control amount-field">

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Final Settlement Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   step="0.01"
                                   name="final_settlement_amount"
                                   id="final_settlement_amount"
                                   value="{{ old('final_settlement_amount', 0) }}"
                                   class="form-control">

                        </div>

                        <small class="text-muted">
                            Positive = amount payable by tenant.
                            Negative = amount refundable to tenant.
                        </small>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Monthly Rent
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="text"
                                   id="monthly_rent"
                                   class="form-control"
                                   readonly
                                   value="0.00">

                        </div>

                    </div>

                </div>


                <div class="alert alert-info mt-4 mb-0">

                    <strong>Settlement calculation:</strong>

                    <span id="settlement_summary">
                        Enter the financial amounts above.
                    </span>

                </div>

            </div>

        </div>


        {{-- Status --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Termination Status
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Inspection Status
                        </label>

                        <input type="text"
                               class="form-control"
                               value="Pending"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Handover Status
                        </label>

                        <input type="text"
                               class="form-control"
                               value="Pending"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Termination Status
                        </label>

                        <input type="text"
                               class="form-control"
                               value="Draft"
                               readonly>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>


            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          class="form-control"
                          placeholder="Additional remarks">{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('admin.leasing.terminations.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save me-1"></i>

                Save Termination

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const agreementSelect =
            document.getElementById(
                'lease_agreement_id'
            );

        const tenantName =
            document.getElementById(
                'tenant_name'
            );

        const leaseStart =
            document.getElementById(
                'lease_start_date'
            );

        const leaseEnd =
            document.getElementById(
                'lease_end_date'
            );

        const monthlyRent =
            document.getElementById(
                'monthly_rent'
            );


        function loadAgreementDetails() {

            const option =
                agreementSelect.options[
                    agreementSelect.selectedIndex
                ];


            if (!option || !option.value) {

                tenantName.value = '';
                leaseStart.value = '';
                leaseEnd.value = '';
                monthlyRent.value = '0.00';

                return;
            }


            tenantName.value =
                option.dataset.tenant || '-';


            leaseStart.value =
                option.dataset.start || '-';


            leaseEnd.value =
                option.dataset.end || '-';


            monthlyRent.value =
                parseFloat(
                    option.dataset.rent || 0
                ).toFixed(2);


            updateSettlement();

        }


        agreementSelect.addEventListener(
            'change',
            loadAgreementDetails
        );


        const amountFields =
            document.querySelectorAll(
                '.amount-field'
            );


        amountFields.forEach(
            function (field) {

                field.addEventListener(
                    'input',
                    updateSettlement
                );

            }
        );


        function updateSettlement() {

            const outstanding =
                parseFloat(
                    document.getElementById(
                        'outstanding_amount'
                    ).value
                ) || 0;


            const penalty =
                parseFloat(
                    document.getElementById(
                        'penalty_amount'
                    ).value
                ) || 0;


            const damages =
                parseFloat(
                    document.getElementById(
                        'damage_charges'
                    ).value
                ) || 0;


            const deposit =
                parseFloat(
                    document.getElementById(
                        'refundable_deposit'
                    ).value
                ) || 0;


            /*
             * Tenant payable:
             *
             * outstanding
             * + penalty
             * + damage
             * - refundable deposit
             */

            const settlement =
                outstanding
                + penalty
                + damages
                - deposit;


            document.getElementById(
                'final_settlement_amount'
            ).value =
                settlement.toFixed(2);


            const summary =
                document.getElementById(
                    'settlement_summary'
                );


            if (settlement > 0) {

                summary.innerHTML =
                    '<strong class="text-danger">' +
                    '$' +
                    settlement.toFixed(2) +
                    '</strong> payable by tenant.';

            } else if (settlement < 0) {

                summary.innerHTML =
                    '<strong class="text-success">' +
                    '$' +
                    Math.abs(settlement).toFixed(2) +
                    '</strong> refundable to tenant.';

            } else {

                summary.innerHTML =
                    '<strong>$0.00</strong> — settlement is balanced.';

            }

        }


        loadAgreementDetails();

    }
);

</script>

@endsection