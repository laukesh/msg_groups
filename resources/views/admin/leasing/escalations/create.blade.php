@extends('layouts.app')

@section('title', 'Create Lease Escalation')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Lease Escalation
            </h4>

            <div class="text-muted">
                Create a rent escalation for an active lease agreement.
            </div>
        </div>

        <a href="{{ route(
            'admin.leasing.escalations.index'
        ) }}"
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


    <form method="POST"
          action="{{ route(
              'admin.leasing.escalations.store'
          ) }}">

        @csrf


        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    Escalation Details
                </h5>
            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Agreement --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Lease Agreement
                            <span class="text-danger">*</span>
                        </label>

                        <select name="lease_agreement_id"
                                id="lease_agreement_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Agreement
                            </option>

                            @foreach($agreements as $agreement)

                                <option
                                    value="{{ $agreement->id }}"
                                    data-rent="{{ $agreement->monthly_rent }}"
                                    {{ old('lease_agreement_id') == $agreement->id ? 'selected' : '' }}
                                >

                                    {{ $agreement->agreement_no }}

                                    -

                                    {{ $agreement->tenant?->company_name ?? 'Tenant' }}

                                    -
                                    ${{ number_format(
                                        $agreement->monthly_rent ?? 0,
                                        2
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                        @error('lease_agreement_id')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Effective From
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="effective_from"
                               class="form-control"
                               value="{{ old('effective_from') }}"
                               required>

                        @error('effective_from')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Previous Rent --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Previous Monthly Rent
                        </label>

                        <input type="text"
                               id="previous_rent"
                               class="form-control"
                               readonly
                               value="0.00">

                    </div>


                    {{-- Escalation Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Escalation Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="escalation_type"
                                id="escalation_type"
                                class="form-select"
                                required>

                            <option value="Percentage">
                                Percentage
                            </option>

                            <option value="Fixed Amount">
                                Fixed Amount
                            </option>

                        </select>

                    </div>


                    {{-- Escalation Value --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Escalation Value
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="escalation_value"
                               id="escalation_value"
                               class="form-control"
                               value="{{ old('escalation_value') }}"
                               required>

                    </div>


                    {{-- Revised Rent Preview --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Revised Monthly Rent
                        </label>

                        <input type="text"
                               id="revised_rent"
                               class="form-control fw-bold"
                               readonly
                               value="0.00">

                        <small class="text-muted">
                            This amount will be stored in
                            <code>revised_rent</code>.
                        </small>

                    </div>


                    {{-- Remarks --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="4">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.leasing.escalations.index'
                ) }}"
                   class="btn btn-secondary">

                    Cancel

                </a>


                <button type="submit"
                        class="btn btn-primary">

                    <i class="fas fa-save me-1"></i>

                    Create Escalation

                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const agreement =
            document.getElementById(
                'lease_agreement_id'
            );

        const type =
            document.getElementById(
                'escalation_type'
            );

        const value =
            document.getElementById(
                'escalation_value'
            );

        const previous =
            document.getElementById(
                'previous_rent'
            );

        const revised =
            document.getElementById(
                'revised_rent'
            );


        function calculateRent()
        {
            const selected =
                agreement.options[
                    agreement.selectedIndex
                ];

            const rent =
                parseFloat(
                    selected?.dataset?.rent || 0
                );

            const escalation =
                parseFloat(
                    value.value || 0
                );


            previous.value =
                rent.toFixed(2);


            let revisedRent = rent;


            if (
                type.value === 'Percentage'
            ) {

                revisedRent =
                    rent +
                    (
                        rent
                        * escalation
                        / 100
                    );

            } else {

                revisedRent =
                    rent + escalation;
            }


            revised.value =
                revisedRent.toFixed(2);
        }


        agreement.addEventListener(
            'change',
            calculateRent
        );


        type.addEventListener(
            'change',
            calculateRent
        );


        value.addEventListener(
            'input',
            calculateRent
        );


        calculateRent();

    }
);

</script>

@endsection