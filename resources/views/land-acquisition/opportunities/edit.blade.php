@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Edit Land Opportunity
            </h3>

            <p class="text-muted mb-0">
                {{ $opportunity->opportunity_no }}
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.land.opportunities.index') }}"
                class="btn btn-outline-primary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Opportunities

            </a>

        </div>

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
            'admin.land.opportunities.update',
            $opportunity
        ) }}">

        @csrf

        @method('PUT')


        <div class="card mb-4">

            <div class="card-header">
                <strong>Opportunity Information</strong>
            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Opportunity No.
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $opportunity->opportunity_no }}"
                            disabled
                        >

                        <div class="form-text">
                            Opportunity number is system generated and cannot be changed.
                        </div>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Opportunity Name *
                        </label>

                        <input
                            type="text"
                            name="opportunity_name"
                            value="{{ old(
                                'opportunity_name',
                                $opportunity->opportunity_name
                            ) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Source
                        </label>

                        <input
                            type="text"
                            name="source"
                            value="{{ old(
                                'source',
                                $opportunity->source
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Identified Date
                        </label>

                        <input
                            type="date"
                            name="identified_date"
                            value="{{ old(
                                'identified_date',
                                optional(
                                    $opportunity->identified_date
                                )->format('Y-m-d')
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status *
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required>

                            @foreach([
                                'New',
                                'Under Evaluation',
                                'Approved',
                                'Rejected',
                                'On Hold'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $opportunity->status
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Location
                        </label>

                        <textarea
                            name="location_text"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'location_text',
                            $opportunity->location_text
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Estimated Area
                        </label>

                        <input
                            type="number"
                            step="0.0001"
                            name="estimated_area"
                            value="{{ old(
                                'estimated_area',
                                $opportunity->estimated_area
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Area Unit
                        </label>

                        <select
                            name="area_unit"
                            class="form-select">

                            @foreach([
                                'sqft' => 'Square Feet',
                                'sqm' => 'Square Meter',
                                'acre' => 'Acre',
                                'hectare' => 'Hectare'
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'area_unit',
                                            $opportunity->area_unit
                                        ) === $value
                                    )>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Estimated Acquisition Cost
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="estimated_acquisition_cost"
                            value="{{ old(
                                'estimated_acquisition_cost',
                                $opportunity->estimated_acquisition_cost
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Currency
                        </label>

                        <input
                            type="text"
                            name="currency"
                            value="{{ old(
                                'currency',
                                $opportunity->currency ?? 'INR'
                            ) }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                        >{{ old(
                            'description',
                            $opportunity->description
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        >{{ old(
                            'remarks',
                            $opportunity->remarks
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                    'admin.land.opportunities.show',
                    $opportunity
                ) }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                Update Opportunity

            </button>

        </div>

    </form>

</div>

@endsection