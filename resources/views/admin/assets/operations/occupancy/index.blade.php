@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Occupancy
            </h4>

            <p class="text-muted mb-0">
                Manage asset occupancy and tenant information.
            </p>
        </div>

        <a href="{{ route('admin.assets.operations.occupancy.create') }}"
           class="btn btn-primary">

            <i class="ri-add-line"></i>

            Add Occupancy

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.assets.operations.occupancy.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Asset name, asset code or tenant"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="occupancy_status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option value="occupied"
                                {{ request('occupancy_status') == 'occupied' ? 'selected' : '' }}>
                                Occupied
                            </option>

                            <option value="vacant"
                                {{ request('occupancy_status') == 'vacant' ? 'selected' : '' }}>
                                Vacant
                            </option>

                            <option value="partially_occupied"
                                {{ request('occupancy_status') == 'partially_occupied' ? 'selected' : '' }}>
                                Partially Occupied
                            </option>

                            <option value="under_maintenance"
                                {{ request('occupancy_status') == 'under_maintenance' ? 'selected' : '' }}>
                                Under Maintenance
                            </option>

                            <option value="reserved"
                                {{ request('occupancy_status') == 'reserved' ? 'selected' : '' }}>
                                Reserved
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="ri-search-line"></i>
                            Search
                        </button>


                        <a
                            href="{{ route('admin.assets.operations.occupancy.index') }}"
                            class="btn btn-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Asset
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Occupancy
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Monthly Rent
                            </th>

                            <th>
                                Lease End
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($occupancies as $occupancy)

                        <tr>

                            <td>
                                {{ $occupancies->firstItem() + $loop->index }}
                            </td>


                            <td>

                                @if($occupancy->asset)

                                    <strong>
                                        {{ $occupancy->asset->asset_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $occupancy->asset->asset_code }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                @php

                                    $statusClass = match(
                                        $occupancy->occupancy_status
                                    ) {

                                        'occupied' =>
                                            'bg-success',

                                        'vacant' =>
                                            'bg-secondary',

                                        'partially_occupied' =>
                                            'bg-warning text-dark',

                                        'under_maintenance' =>
                                            'bg-danger',

                                        'reserved' =>
                                            'bg-info',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $occupancy->occupancy_status
                                            )
                                        )
                                    }}

                                </span>

                            </td>


                            <td>

                                <strong>
                                    {{ number_format(
                                        $occupancy->occupancy_percentage,
                                        2
                                    ) }}%
                                </strong>

                                <div class="progress mt-1"
                                     style="height: 5px;">

                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: {{ $occupancy->occupancy_percentage }}%"
                                    ></div>

                                </div>

                            </td>


                            <td>
                                {{ $occupancy->tenant_name ?? '-' }}
                            </td>


                            <td>
                                ${{ number_format(
                                    $occupancy->monthly_rent ?? 0,
                                    2
                                ) }}
                            </td>


                            <td>

                                @if($occupancy->lease_end_date)

                                    {{ $occupancy->lease_end_date->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.occupancy.show',
                                            $occupancy
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                        title="View"
                                    >
                                        <i class="ri-eye-line"></i>
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.occupancy.edit',
                                            $occupancy
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit"
                                    >
                                        <i class="ri-edit-line"></i>
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.operations.occupancy.destroy',
                                            $occupancy
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this occupancy record?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                        >
                                            <i class="ri-delete-bin-line"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-4"
                            >

                                <i class="ri-inbox-line fs-2 text-muted"></i>

                                <p class="mb-0 text-muted">
                                    No occupancy records found.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="mt-3">

                {{ $occupancies->links() }}

            </div>

        </div>

    </div>

</div>

@endsection