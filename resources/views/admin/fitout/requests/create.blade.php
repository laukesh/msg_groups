@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Fit-Out Request
            </h4>

            <p class="text-muted mb-0">
                Create a new fit-out request for a leased unit.
            </p>

        </div>

        <a href="{{ route('admin.fitout.requests.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    <form method="POST"
          action="{{ route('admin.fitout.requests.store') }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- LEASE INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Lease & Unit Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Lease Agreement --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Lease Agreement
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="lease_agreement_id"
                            id="lease_agreement_id"
                            class="form-select @error('lease_agreement_id') is-invalid @enderror"
                            required>

                            <option value="">
                                Select Lease Agreement
                            </option>

                            @foreach($leaseAgreements as $agreement)

                                <option
                                    value="{{ $agreement->id }}"
                                    @selected(
                                        old('lease_agreement_id')
                                        == $agreement->id
                                    )>

                                    {{ $agreement->agreement_no }}

                                    -
                                    {{ $agreement->tenant->company_name ?? 'N/A' }}

                                </option>

                            @endforeach

                        </select>

                        @error('lease_agreement_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Tenant --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Tenant

                        </label>

                        <input
                            type="text"
                            id="tenant_name"
                            class="form-control"
                            value=""
                            readonly
                            placeholder="Tenant will be populated automatically">

                    </div>


                    {{-- Unit --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Unit
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="unit_id"
                            id="unit_id"
                            class="form-select @error('unit_id') is-invalid @enderror"
                            required
                            disabled>

                            <option value="">
                                Select Lease Agreement First
                            </option>

                        </select>

                        @error('unit_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Lease Dates --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Lease Period
                        </label>

                        <input
                            type="text"
                            id="lease_period"
                            class="form-control"
                            readonly
                            placeholder="Will be populated automatically">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CONTRACTOR --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Contractor
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">

                            Contractor
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="contractor_id"
                            id="contractor_id"
                            class="form-select @error('contractor_id') is-invalid @enderror"
                            required>

                            <option value="">
                                Select Approved Contractor
                            </option>

                            @foreach($contractors as $contractor)

                                <option
                                    value="{{ $contractor->id }}"
                                    @selected(
                                        old('contractor_id')
                                        == $contractor->id
                                    )>

                                    {{ $contractor->contractor_code }}
                                    -
                                    {{ $contractor->contractor_name }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">

                            Only approved contractors are available.

                        </small>

                        @error('contractor_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Contractor Contact
                        </label>

                        <input
                            type="text"
                            id="contractor_contact"
                            class="form-control"
                            readonly
                            placeholder="Auto populated">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Contractor Status
                        </label>

                        <input
                            type="text"
                            id="contractor_status"
                            class="form-control"
                            readonly
                            placeholder="Auto populated">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FIT-OUT DETAILS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Fit-Out Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Type --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Fit-Out Type
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="fitout_type"
                            class="form-select"
                            required>

                            <option value="">
                                Select Type
                            </option>

                            <option value="New"
                                @selected(old('fitout_type') === 'New')>
                                New
                            </option>

                            <option value="Renovation"
                                @selected(old('fitout_type') === 'Renovation')>
                                Renovation
                            </option>

                            <option value="Expansion"
                                @selected(old('fitout_type') === 'Expansion')>
                                Expansion
                            </option>

                            <option value="Modification"
                                @selected(old('fitout_type') === 'Modification')>
                                Modification
                            </option>

                        </select>

                    </div>


                    {{-- Estimated Cost --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Estimated Cost
                        </label>

                        <input
                            type="number"
                            name="estimated_cost"
                            class="form-control"
                            value="{{ old('estimated_cost', 0) }}"
                            min="0"
                            step="0.01">

                    </div>


                    {{-- Work Permit --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Work Permit No.
                        </label>

                        <input
                            type="text"
                            name="work_permit_no"
                            class="form-control"
                            value="{{ old('work_permit_no') }}"
                            maxlength="50">

                    </div>


                    {{-- Start Date --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Start Date
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="proposed_start_date"
                            class="form-control"
                            value="{{ old('proposed_start_date') }}"
                            required>

                    </div>


                    {{-- End Date --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed End Date
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="proposed_end_date"
                            class="form-control"
                            value="{{ old('proposed_end_date') }}"
                            required>

                    </div>


                    {{-- Safety --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Safety Induction
                        </label>

                        <select
                            name="safety_induction_completed"
                            class="form-select">

                            <option value="No"
                                @selected(
                                    old(
                                        'safety_induction_completed',
                                        'No'
                                    ) === 'No'
                                )>
                                No
                            </option>

                            <option value="Yes"
                                @selected(
                                    old(
                                        'safety_induction_completed'
                                    ) === 'Yes'
                                )>
                                Yes
                            </option>

                        </select>

                    </div>


                    {{-- Insurance --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Insurance Verified
                        </label>

                        <select
                            name="insurance_verified"
                            class="form-select">

                            <option value="No"
                                @selected(
                                    old(
                                        'insurance_verified',
                                        'No'
                                    ) === 'No'
                                )>
                                No
                            </option>

                            <option value="Yes"
                                @selected(
                                    old(
                                        'insurance_verified'
                                    ) === 'Yes'
                                )>
                                Yes
                            </option>

                        </select>

                    </div>


                    {{-- Description --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Work Description
                        </label>

                        <textarea
                            name="work_description"
                            rows="4"
                            class="form-control"
                            placeholder="Describe the proposed fit-out work...">{{ old('work_description') }}</textarea>

                    </div>


                    {{-- Remarks --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BUTTONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.fitout.requests.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Create Fit-Out Request

            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const leaseAgreementUnits =
        @json($leaseAgreementUnits);

    const contractors =
        @json($contractorData);


    const leaseSelect =
        document.getElementById(
            'lease_agreement_id'
        );

    const tenantInput =
        document.getElementById(
            'tenant_name'
        );

    const unitSelect =
        document.getElementById(
            'unit_id'
        );

    const leasePeriodInput =
        document.getElementById(
            'lease_period'
        );

    /*
    |--------------------------------------------------------------------------
    | Lease Agreement Change
    |--------------------------------------------------------------------------
    */

    leaseSelect.addEventListener(
        'change',
        function () {

            const agreementId =
                this.value;


            tenantInput.value = '';

            leasePeriodInput.value = '';

            unitSelect.innerHTML =
                '<option value="">Select Unit</option>';

            unitSelect.disabled = true;


            if (!agreementId) {
                return;
            }


            const agreement =
                leaseAgreementUnits[agreementId];


            if (!agreement) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            tenantInput.value =
                agreement.tenant_name || '-';


            /*
            |--------------------------------------------------------------------------
            | Lease Period
            |--------------------------------------------------------------------------
            */

            leasePeriodInput.value =
                (
                    agreement.lease_start_date || '-'
                )
                +
                ' to '
                +
                (
                    agreement.lease_end_date || '-'
                );


            /*
            |--------------------------------------------------------------------------
            | Units
            |--------------------------------------------------------------------------
            */

            if (
                agreement.units &&
                agreement.units.length > 0
            ) {

                agreement.units.forEach(
                    function (unit) {

                        const option =
                            document.createElement(
                                'option'
                            );

                        option.value =
                            unit.id;

                        option.textContent =
                            unit.unit_no;

                        unitSelect.appendChild(
                            option
                        );

                    }
                );


                unitSelect.disabled = false;

            } else {

                unitSelect.innerHTML =
                    '<option value="">No units found</option>';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Contractor Change
    |--------------------------------------------------------------------------
    */

    const contractorSelect =
        document.getElementById(
            'contractor_id'
        );

    const contractorContact =
        document.getElementById(
            'contractor_contact'
        );

    const contractorStatus =
        document.getElementById(
            'contractor_status'
        );


    contractorSelect.addEventListener(
        'change',
        function () {

            const contractor =
                contractors.find(
                    item =>
                        String(item.id)
                        ===
                        String(this.value)
                );


            contractorContact.value = '';

            contractorStatus.value = '';


            if (!contractor) {
                return;
            }


            contractorContact.value =
                contractor.mobile || '-';

            contractorStatus.value =
                contractor.status;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Restore Old Lease
    |--------------------------------------------------------------------------
    */

    const oldLease =
        @json(old('lease_agreement_id'));

    const oldUnit =
        @json(old('unit_id'));


    if (oldLease) {

        leaseSelect.value =
            oldLease;

        leaseSelect.dispatchEvent(
            new Event('change')
        );


        setTimeout(function () {

            if (oldUnit) {

                unitSelect.value =
                    oldUnit;
            }

        }, 100);

    }


    /*
    |--------------------------------------------------------------------------
    | Restore Old Contractor
    |--------------------------------------------------------------------------
    */

    const oldContractor =
        @json(old('contractor_id'));


    if (oldContractor) {

        contractorSelect.value =
            oldContractor;

        contractorSelect.dispatchEvent(
            new Event('change')
        );
    }

});

</script>

@endsection