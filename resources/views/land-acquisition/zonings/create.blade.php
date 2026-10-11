@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Add Zoning
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
            'admin.land.lands.zonings.store',
            $land
        ) }}">

        @csrf


        {{-- Zoning Classification --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Zoning Classification
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Zoning Code --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Zoning Code
                        </label>

                        <input
                            type="text"
                            name="zoning_code"
                            class="form-control"
                            value="{{ old(
                                'zoning_code'
                            ) }}"
                            placeholder="Example: C-1"
                        >

                    </div>


                    {{-- Zoning Type --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Zoning Type *
                        </label>

                        <input
                            type="text"
                            name="zoning_type"
                            class="form-control"
                            value="{{ old(
                                'zoning_type'
                            ) }}"
                            placeholder="Commercial, Residential, Mixed Use..."
                            required
                        >

                    </div>


                    {{-- Authority --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Authority
                        </label>

                        <input
                            type="text"
                            name="authority"
                            class="form-control"
                            value="{{ old(
                                'authority'
                            ) }}"
                            placeholder="Planning authority / local authority"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Permitted Use --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Permitted Use
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="permitted_use"
                    rows="5"
                    class="form-control"
                    placeholder="Describe permitted land uses..."
                >{{ old('permitted_use') }}</textarea>

            </div>

        </div>


        {{-- Restrictions --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Restrictions
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="restrictions"
                    rows="5"
                    class="form-control"
                    placeholder="Enter zoning restrictions, limitations, conditions..."
                >{{ old('restrictions') }}</textarea>

            </div>

        </div>


        {{-- Validity --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Validity
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old(
                                'effective_date'
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            class="form-control"
                            value="{{ old(
                                'expiry_date'
                            ) }}"
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


        {{-- Actions --}}

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

                Save Zoning

            </button>

        </div>

    </form>

</div>

@endsection