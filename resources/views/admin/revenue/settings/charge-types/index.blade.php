@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Charge Types
            </h4>

            <p class="text-muted mb-0">
                Manage revenue charge types used for billing.
            </p>

        </div>

        <a
            href="{{ route(
                'admin.revenue.settings.charge-types.create'
            ) }}"
            class="btn btn-primary"
        >
            + Add Charge Type
        </a>

    </div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ERROR --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- SEARCH --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.revenue.settings.charge-types.index'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Charge name or charge code"
                        >

                    </div>

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>

                    <div class="col-md-2">

                        <a
                            href="{{ route(
                                'admin.revenue.settings.charge-types.index'
                            ) }}"
                            class="btn btn-light border w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <div>

                    <h5 class="mb-1">
                        Charge Type List
                    </h5>

                    <small class="text-muted">
                        {{ $chargeTypes->total() }} charge types
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Charge Name
                            </th>

                            <th>
                                Charge Code
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($chargeTypes as $index => $chargeType)

                            <tr>

                                <td>
                                    {{ $chargeTypes->firstItem() + $index }}
                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $chargeType->charge_name }}

                                    </div>

                                </td>

                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $chargeType->charge_code }}

                                    </span>

                                </td>

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.revenue.settings.charge-types.edit',
                                            $chargeType->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route(
                                            'admin.revenue.settings.charge-types.destroy',
                                            $chargeType->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this charge type?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">
                                        No charge types found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($chargeTypes->hasPages())

            <div class="card-footer bg-white">

                {{ $chargeTypes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection