@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h4 class="mb-1">
            Add Charge Type
        </h4>

        <p class="text-muted mb-0">
            Create a new revenue charge type.
        </p>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.revenue.settings.charge-types.store'
                ) }}"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Charge Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="charge_name"
                            value="{{ old('charge_name') }}"
                            class="form-control @error('charge_name') is-invalid @enderror"
                            placeholder="e.g. Maintenance"
                            required
                        >

                        @error('charge_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Charge Code
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="charge_code"
                            value="{{ old('charge_code') }}"
                            class="form-control @error('charge_code') is-invalid @enderror"
                            placeholder="e.g. MAINTENANCE"
                            required
                        >

                        @error('charge_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">
                            Only letters, numbers, hyphens and underscores.
                        </small>

                    </div>

                </div>


                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Charge Type
                    </button>

                    <a
                        href="{{ route(
                            'admin.revenue.settings.charge-types.index'
                        ) }}"
                        class="btn btn-light border"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection