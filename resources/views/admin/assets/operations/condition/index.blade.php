@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Asset Condition
            </h4>

            <p class="text-muted mb-0">
                Inspect, monitor and manage asset condition.
            </p>

        </div>

        <a
            href="{{ route(
                'admin.assets.operations.conditions.create'
            ) }}"
            class="btn btn-primary"
        >

            <i class="ri-add-line"></i>

            Add Condition

        </a>

    </div>


    {{-- Success --}}
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
                    'admin.assets.operations.conditions.index'
                ) }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Asset, code, condition or inspector"
                        >

                    </div>


                    {{-- Condition --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Condition
                        </label>

                        <select
                            name="condition"
                            class="form-select"
                        >

                            <option value="">
                                All Conditions
                            </option>

                            <option value="Excellent"
                                {{ request('condition') == 'Excellent' ? 'selected' : '' }}>
                                Excellent
                            </option>

                            <option value="Good"
                                {{ request('condition') == 'Good' ? 'selected' : '' }}>
                                Good
                            </option>

                            <option value="Fair"
                                {{ request('condition') == 'Fair' ? 'selected' : '' }}>
                                Fair
                            </option>

                            <option value="Poor"
                                {{ request('condition') == 'Poor' ? 'selected' : '' }}>
                                Poor
                            </option>

                            <option value="Critical"
                                {{ request('condition') == 'Critical' ? 'selected' : '' }}>
                                Critical
                            </option>

                        </select>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Inspection Date
                        </label>

                        <input
                            type="date"
                            name="inspection_date"
                            value="{{ request('inspection_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Buttons --}}
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
                                'admin.assets.operations.conditions.index'
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
                                Inspection Date
                            </th>

                            <th>
                                Condition
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Repair Cost
                            </th>

                            <th>
                                Inspector
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($conditions as $condition)

                        <tr>

                            <td>
                                {{ $conditions->firstItem() + $loop->index }}
                            </td>


                            {{-- Asset --}}
                            <td>

                                @if($condition->asset)

                                    <strong>
                                        {{ $condition->asset->asset_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $condition->asset->asset_code }}
                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Date --}}
                            <td>

                                {{ optional(
                                    $condition->inspection_date
                                )->format('d-m-Y') }}

                            </td>


                            {{-- Condition --}}
                            <td>

                                @php

                                    $conditionClass = match(
                                        $condition->condition
                                    ) {

                                        'Excellent' =>
                                            'bg-success',

                                        'Good' =>
                                            'bg-primary',

                                        'Fair' =>
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
                                    class="badge {{ $conditionClass }}"
                                >

                                    {{ $condition->condition }}

                                </span>

                            </td>


                            {{-- Score --}}
                            <td>

                                @if($condition->condition_score !== null)

                                    {{ number_format(
                                        $condition->condition_score,
                                        2
                                    ) }}%

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Repair Cost --}}
                            <td>

                                ${{ number_format(
                                    $condition->estimated_repair_cost ?? 0,
                                    2
                                ) }}

                            </td>


                            {{-- Inspector --}}
                            <td>

                                {{ $condition->inspector->name ?? '-' }}

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.conditions.show',
                                            $condition
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                        title="View"
                                    >

                                        <i class="ri-eye-line"></i>

                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.assets.operations.conditions.edit',
                                            $condition
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit"
                                    >

                                        <i class="ri-edit-line"></i>

                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.operations.conditions.destroy',
                                            $condition
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this condition record?'
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
                                class="text-center py-5"
                            >

                                <i class="ri-inbox-line fs-2 text-muted"></i>

                                <p class="text-muted mb-0">
                                    No asset condition records found.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $conditions->links() }}

            </div>

        </div>

    </div>

</div>

@endsection