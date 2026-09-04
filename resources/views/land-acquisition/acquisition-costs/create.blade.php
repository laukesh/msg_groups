@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="mb-4">

        <h3>
            Add Acquisition Cost
        </h3>

        <p class="text-muted">

            {{ $land->land_code }}
            -
            {{ $land->land_name }}

        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.acquisition-costs.store',
            $land
        ) }}">

        @csrf


        {{-- Cost Information --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Cost Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Category --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Cost Category *
                        </label>

                        <select
                            name="cost_category"
                            class="form-select"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach([
                                'Land Purchase Price',
                                'Stamp Duty',
                                'Registration Charges',
                                'Legal Fees',
                                'Brokerage',
                                'Due Diligence Cost',
                                'Survey Cost',
                                'Consultancy Cost',
                                'Other'
                            ] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        old('cost_category')
                                        === $category
                                    )>

                                    {{ $category }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Description --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <input
                            type="text"
                            name="cost_description"
                            class="form-control"
                            value="{{ old(
                                'cost_description'
                            ) }}"
                            placeholder="Describe this cost"
                        >

                    </div>


                    {{-- Amount --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Amount *
                        </label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old('amount', 0) }}"
                            required
                        >

                    </div>


                    {{-- Tax --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Tax Amount
                        </label>

                        <input
                            type="number"
                            name="tax_amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'tax_amount',
                                0
                            ) }}"
                        >

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Currency *
                        </label>

                        <select
                            name="currency"
                            class="form-select"
                            required>

                            <option
                                value="INR"
                                @selected(
                                    old(
                                        'currency',
                                        'INR'
                                    ) === 'INR'
                                )>

                                INR - Indian Rupee

                            </option>

                            <option
                                value="USD"
                                @selected(
                                    old('currency')
                                    === 'USD'
                                )>

                                USD - US Dollar

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Information --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Payment Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Cost Date
                        </label>

                        <input
                            type="date"
                            name="cost_date"
                            class="form-control"
                            value="{{ old(
                                'cost_date'
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Payment Status *
                        </label>

                        <select
                            name="payment_status"
                            class="form-select"
                            required>

                            @foreach([
                                'Pending',
                                'Partially Paid',
                                'Paid'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'payment_status',
                                            'Pending'
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Paid Date
                        </label>

                        <input
                            type="date"
                            name="paid_date"
                            class="form-control"
                            value="{{ old(
                                'paid_date'
                            ) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            class="form-control"
                            value="{{ old(
                                'reference_number'
                            ) }}"
                            placeholder="Payment / invoice / transaction reference"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-primary">

                Save Cost

            </button>

        </div>

    </form>

</div>

@endsection