@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Add Land Owner
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
            'admin.land.lands.owners.store',
            $land
        ) }}">

        @csrf


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Ownership Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Owner Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Owner Type *
                        </label>

                        <select
                            name="owner_type"
                            class="form-select"
                            required>

                            <option value="">
                                Select Owner Type
                            </option>

                            <option
                                value="Individual"
                                @selected(
                                    old('owner_type')
                                    === 'Individual'
                                )>
                                Individual
                            </option>

                            <option
                                value="Company"
                                @selected(
                                    old('owner_type')
                                    === 'Company'
                                )>
                                Company
                            </option>

                            <option
                                value="Government"
                                @selected(
                                    old('owner_type')
                                    === 'Government'
                                )>
                                Government
                            </option>

                            <option
                                value="Other"
                                @selected(
                                    old('owner_type')
                                    === 'Other'
                                )>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Owner Name --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Owner Name *
                        </label>

                        <input
                            type="text"
                            name="owner_name"
                            class="form-control"
                            value="{{ old('owner_name') }}"
                            required
                        >

                    </div>


                    {{-- Ownership Percentage --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership Percentage
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="ownership_percentage"
                                class="form-control"
                                step="0.0001"
                                min="0"
                                max="100"
                                value="{{ old(
                                    'ownership_percentage'
                                ) }}"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>


                    {{-- Title Reference --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Title Reference
                        </label>

                        <input
                            type="text"
                            name="title_reference"
                            class="form-control"
                            value="{{ old(
                                'title_reference'
                            ) }}"
                            placeholder="Title deed / registration reference"
                        >

                    </div>


                    {{-- Start Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership Start Date
                        </label>

                        <input
                            type="date"
                            name="ownership_start_date"
                            class="form-control"
                            value="{{ old(
                                'ownership_start_date'
                            ) }}"
                        >

                    </div>


                    {{-- End Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Ownership End Date
                        </label>

                        <input
                            type="date"
                            name="ownership_end_date"
                            class="form-control"
                            value="{{ old(
                                'ownership_end_date'
                            ) }}"
                        >

                    </div>


                    {{-- Remarks --}}

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

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

                Save Owner

            </button>

        </div>

    </form>

</div>

@endsection