@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Acquisition Cost
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.acquisition-costs.show',
                [
                    $land,
                    $acquisitionCost
                ]
            ) }}"
            class="btn btn-outline-primary">

            View

        </a>

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
            'admin.land.lands.acquisition-costs.update',
            [
                $land,
                $acquisitionCost
            ]
        ) }}">

        @csrf

        @method('PUT')


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Cost Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Cost Category *
                        </label>

                        <select
                            name="cost_category"
                            class="form-select"
                            required>

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
                                        old(
                                            'cost_category',
                                            $acquisitionCost
                                                ->cost_category
                                        ) === $category
                                    )>

                                    {{ $category }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <input
                            type="text"
                            name="cost_description"
                            class="form-control"
                            value="{{ old(
                                'cost_description',
                                $acquisitionCost
                                    ->cost_description
                            ) }}"
                        >

                    </div>


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
                            value="{{ old(
                                'amount',
                                $acquisitionCost->amount
                            ) }}"
                            required
                        >

                    </div>


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
                                $acquisitionCost->tax_amount
                            ) }}"
                        >

                    </div>


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
                                        $acquisitionCost->currency
                                    ) === 'INR'
                                )>

                                INR - Indian Rupee

                            </option>

                            <option
                                value="USD"
                                @selected(
                                    old(
                                        'currency',
                                        $acquisitionCost->currency
                                    ) === 'USD'
                                )>

                                USD - US Dollar

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


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
                                'cost_date',
                                $acquisitionCost->cost_date
                                    ? $acquisitionCost
                                        ->cost_date
                                        ->format('Y-m-d')
                                    : ''
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
                                            $acquisitionCost
                                                ->payment_status
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
                                'paid_date',
                                $acquisitionCost->paid_date
                                    ? $acquisitionCost
                                        ->paid_date
                                        ->format('Y-m-d')
                                    : ''
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
                                'reference_number',
                                $acquisitionCost
                                    ->reference_number
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


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
                >{{ old(
                    'remarks',
                    $acquisitionCost->remarks
                ) }}</textarea>

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

                Update Cost

            </button>

        </div>

    </form>

</div>

@endsection