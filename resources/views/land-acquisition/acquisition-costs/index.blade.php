@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Acquisition Costs
            </h3>

            <p class="text-muted mb-0">
                {{ $land->land_code }}
                -
                {{ $land->land_name }}
            </p>
        </div>

        <div>
            <a
                href="{{ route(
                    'admin.land.lands.acquisition-costs.create',
                    $land
                ) }}"
                class="btn btn-primary">

                + Add Cost

            </a>

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary">

                Back to Land

            </a>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- Summary --}}
    <div class="row mb-4">

        {{-- Total --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Acquisition Cost
                    </small>

                    <h3 class="mb-0">

                        {{ $acquisitionCosts->first()->currency ?? 'INR' }}

                        {{ number_format(
                            $totalAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Paid --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Paid
                    </small>

                    <h3 class="mb-0 text-success">

                        {{ $acquisitionCosts->first()->currency ?? 'INR' }}

                        {{ number_format(
                            $paidAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Pending
                    </small>

                    <h3 class="mb-0 text-danger">

                        {{ $acquisitionCosts->first()->currency ?? 'INR' }}

                        {{ number_format(
                            $pendingAmount,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Cost Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Acquisition Cost Details
            </strong>

            <span class="badge bg-light text-dark">
                {{ $acquisitionCosts->count() }} Records
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Category
                            </th>

                            <th>
                                Description
                            </th>

                            <th style="min-width: 240px;">
                                Development Budget
                            </th>

                            <th style="min-width: 260px;">
                                Budget Item
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Tax
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payment
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($acquisitionCosts as $cost)

                        <tr>

                            {{-- Category --}}
                            <td>

                                <strong>
                                    {{ $cost->cost_category }}
                                </strong>

                            </td>


                            {{-- Description --}}
                            <td>

                                {{ $cost->cost_description ?: '-' }}

                            </td>


                            {{-- Development Budget --}}
                            <td>

                                @if($cost->projectBudget)

                                    <div class="fw-semibold">
                                        {{ $cost->projectBudget->title }}
                                    </div>

                                    @if($cost->projectBudget->budget_number)

                                        <small class="text-muted d-block">
                                            {{ $cost->projectBudget->budget_number }}
                                        </small>

                                    @endif

                                    <small class="text-muted d-block">

                                        Status:

                                        <span class="badge bg-success">
                                            {{ $cost->projectBudget->status }}
                                        </span>

                                    </small>

                                @else

                                    <span class="text-muted">
                                        Not Allocated
                                    </span>

                                @endif

                            </td>


                            {{-- Budget Item --}}
                            <td>

                                @if($cost->budgetItem)

                                    <div class="fw-semibold">
                                        {{ $cost->budgetItem->item_name }}
                                    </div>

                                    @if($cost->budgetItem->item_code)

                                        <small class="text-muted d-block">
                                            Code:
                                            {{ $cost->budgetItem->item_code }}
                                        </small>

                                    @endif

                                    <small class="text-muted d-block">

                                        Allocated:

                                        {{ $cost->projectBudget->currency ?? '' }}

                                        {{ number_format(
                                            $cost->budgetItem->estimated_amount,
                                            2
                                        ) }}

                                    </small>

                                @else

                                    <span class="text-muted">
                                        Not Allocated
                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td>

                                {{ $cost->currency }}

                                {{ number_format(
                                    $cost->amount,
                                    2
                                ) }}

                            </td>


                            {{-- Tax --}}
                            <td>

                                {{ $cost->currency }}

                                {{ number_format(
                                    $cost->tax_amount,
                                    2
                                ) }}

                            </td>


                            {{-- Total --}}
                            <td>

                                <strong>

                                    {{ $cost->currency }}

                                    {{ number_format(
                                        $cost->total_amount,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- Payment Status --}}
                            <td>

                                @if($cost->payment_status === 'Paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($cost->payment_status === 'Partially Paid')

                                    <span class="badge bg-warning text-dark">
                                        Partially Paid
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.acquisition-costs.show',
                                            [
                                                $land,
                                                $cost
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.land.lands.acquisition-costs.edit',
                                            [
                                                $land,
                                                $cost
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary">

                                        Edit

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5">

                                <div class="text-muted mb-2">
                                    No acquisition costs found.
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.acquisition-costs.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary">

                                    Add First Cost

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if(method_exists($acquisitionCosts, 'links'))

            <div class="card-footer">
                {{ $acquisitionCosts->links() }}
            </div>

        @endif

    </div>

</div>

@endsection