@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Asset Performance
            </h4>

            <p class="text-muted mb-0">
                Monitor asset performance and operational KPIs.
            </p>

        </div>

        <a
            href="{{ route(
                'admin.assets.operations.performance.create'
            ) }}"
            class="btn btn-primary"
        >

            <i class="ri-add-line"></i>

            Add Performance

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.assets.operations.performance.index'
                ) }}"
            >

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
                            placeholder="Asset, asset code or inspector"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="performance_status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'Excellent',
                                'Good',
                                'Average',
                                'Poor',
                                'Critical'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ request('performance_status') == $status
                                        ? 'selected'
                                        : '' }}
                                >

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Date
                        </label>

                        <input
                            type="date"
                            name="performance_date"
                            value="{{ request('performance_date') }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="ri-search-line"></i>

                            Search

                        </button>

                        <a
                            href="{{ route(
                                'admin.assets.operations.performance.index'
                            ) }}"
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
                                Date
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Availability
                            </th>

                            <th>
                                Utilization
                            </th>

                            <th>
                                Efficiency
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($performances as $performance)

                        <tr>

                            <td>
                                {{ $performances->firstItem() + $loop->index }}
                            </td>


                            <td>

                                @if($performance->asset)

                                    <strong>
                                        {{ $performance->asset->asset_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $performance->asset->asset_code }}
                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                {{ optional(
                                    $performance->performance_date
                                )->format('d-m-Y') }}

                            </td>


                            <td>

                                @if($performance->performance_score !== null)

                                    {{ number_format(
                                        $performance->performance_score,
                                        2
                                    ) }}%

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                {{ $performance->availability_percentage !== null
                                    ? number_format(
                                        $performance->availability_percentage,
                                        2
                                    ) . '%'
                                    : '-' }}

                            </td>


                            <td>

                                {{ $performance->utilization_percentage !== null
                                    ? number_format(
                                        $performance->utilization_percentage,
                                        2
                                    ) . '%'
                                    : '-' }}

                            </td>


                            <td>

                                {{ $performance->efficiency_percentage !== null
                                    ? number_format(
                                        $performance->efficiency_percentage,
                                        2
                                    ) . '%'
                                    : '-' }}

                            </td>


                            <td>

                                @php

                                    $statusClass = match(
                                        $performance->performance_status
                                    ) {

                                        'Excellent' =>
                                            'bg-success',

                                        'Good' =>
                                            'bg-primary',

                                        'Average' =>
                                            'bg-warning text-dark',

                                        'Poor' =>
                                            'bg-danger',

                                        'Critical' =>
                                            'bg-dark',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span
                                    class="badge {{ $statusClass }}"
                                >

                                    {{ $performance->performance_status }}

                                </span>

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.performance.show',
                                            $performance
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                        title="View"
                                    >

                                        <i class="ri-eye-line"></i>

                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.performance.edit',
                                            $performance
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit"
                                    >

                                        <i class="ri-edit-line"></i>

                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.operations.performance.destroy',
                                            $performance
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this performance record?'
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
                                colspan="9"
                                class="text-center py-5"
                            >

                                <i class="ri-inbox-line fs-2 text-muted"></i>

                                <p class="text-muted mb-0">
                                    No performance records found.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $performances->links() }}

            </div>

        </div>

    </div>

</div>

@endsection