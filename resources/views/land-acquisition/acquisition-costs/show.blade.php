@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Acquisition Cost Details
            </h3>

            <p class="text-muted mb-0">
                {{ $land->land_code }}
                -
                {{ $land->land_name }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.acquisition-costs.edit',
                    [
                        $land,
                        $acquisitionCost
                    ]
                ) }}"
                class="btn btn-primary">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Land

            </a>

        </div>

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


    {{-- Cost Information --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header">

            <strong>
                <i class="bi bi-cash-stack"></i>
                Cost Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Cost Category
                    </small>

                    <div class="fw-semibold">
                        {{ $acquisitionCost->cost_category ?? '-' }}
                    </div>

                </div>


                <div class="col-md-8 mb-4">

                    <small class="text-muted">
                        Description
                    </small>

                    <div>
                        {{ $acquisitionCost->cost_description ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Amount
                    </small>

                    <div class="fw-semibold text-primary">

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            (float) $acquisitionCost->amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Tax Amount
                    </small>

                    <div class="fw-semibold">

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            (float) $acquisitionCost->tax_amount,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Total Amount
                    </small>

                    <div class="fw-bold text-success fs-5">

                        {{ $acquisitionCost->currency }}

                        {{ number_format(
                            (float) $acquisitionCost->total_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Development Budget Information --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header">

            <strong>
                <i class="bi bi-wallet2"></i>
                Development Budget Allocation
            </strong>

        </div>

        <div class="card-body">

            @if($acquisitionCost->projectBudget)

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <small class="text-muted">
                            Development Budget
                        </small>

                        <div class="fw-semibold">

                            {{ $acquisitionCost->projectBudget->title }}

                        </div>

                        @if($acquisitionCost->projectBudget->budget_number)

                            <small class="text-muted">

                                Budget Number:
                                {{ $acquisitionCost->projectBudget->budget_number }}

                            </small>

                        @endif

                    </div>


                    <div class="col-md-3 mb-4">

                        <small class="text-muted">
                            Budget Status
                        </small>

                        <div>

                            @if($acquisitionCost->projectBudget->status === 'Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($acquisitionCost->projectBudget->status === 'Draft')

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    {{ $acquisitionCost->projectBudget->status }}
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-md-3 mb-4">

                        <small class="text-muted">
                            Budget Currency
                        </small>

                        <div class="fw-semibold">

                            {{ $acquisitionCost->projectBudget->currency ?? '-' }}

                        </div>

                    </div>

                </div>


                <hr>


                @if($acquisitionCost->budgetItem)

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <small class="text-muted">
                                Budget Item
                            </small>

                            <div class="fw-semibold">

                                {{ $acquisitionCost->budgetItem->item_name }}

                            </div>

                            @if($acquisitionCost->budgetItem->item_code)

                                <small class="text-muted">

                                    Item Code:
                                    {{ $acquisitionCost->budgetItem->item_code }}

                                </small>

                            @endif

                        </div>


                        <div class="col-md-3 mb-4">

                            <small class="text-muted">
                                Estimated Amount
                            </small>

                            <div class="fw-semibold">

                                {{ $acquisitionCost->projectBudget->currency }}

                                {{ number_format(
                                    (float) $acquisitionCost->budgetItem->estimated_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-3 mb-4">

                            <small class="text-muted">
                                Cost Type
                            </small>

                            <div class="fw-semibold">

                                {{ $acquisitionCost->budgetItem->cost_type ?? '-' }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="alert alert-info mb-0">

                        No specific budget item has been assigned.

                    </div>

                @endif

            @else

                <div class="alert alert-secondary mb-0">

                    This acquisition cost is not linked to any development budget.

                </div>

            @endif

        </div>

    </div>


    {{-- Payment Information --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header">

            <strong>
                <i class="bi bi-credit-card"></i>
                Payment Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Cost Date
                    </small>

                    <div>

                        {{ $acquisitionCost->cost_date
                            ? $acquisitionCost->cost_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Payment Status
                    </small>

                    <div>

                        @if($acquisitionCost->payment_status === 'Paid')

                            <span class="badge bg-success">
                                Paid
                            </span>

                        @elseif($acquisitionCost->payment_status === 'Partially Paid')

                            <span class="badge bg-warning text-dark">
                                Partially Paid
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Paid Date
                    </small>

                    <div>

                        {{ $acquisitionCost->paid_date
                            ? $acquisitionCost->paid_date->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div>

                        {{ $acquisitionCost->reference_number ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header">

            <strong>
                <i class="bi bi-chat-left-text"></i>
                Remarks
            </strong>

        </div>

        <div class="card-body">

            @if($acquisitionCost->remarks)

                {!! nl2br(e($acquisitionCost->remarks)) !!}

            @else

                <span class="text-muted">
                    No remarks available.
                </span>

            @endif

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card mb-4 shadow-sm">

        <div class="card-header">

            <strong>
                <i class="bi bi-clock-history"></i>
                Audit Information
            </strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Record ID
                    </small>

                    <div class="fw-semibold">
                        #{{ $acquisitionCost->id }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Created At
                    </small>

                    <div>

                        {{ $acquisitionCost->created_at
                            ? $acquisitionCost->created_at->format('d-m-Y H:i:s')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <div>

                        {{ $acquisitionCost->updated_at
                            ? $acquisitionCost->updated_at->format('d-m-Y H:i:s')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete Section --}}
    <div class="card border-danger mb-4 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Acquisition Cost
                    </strong>

                    <div class="text-muted">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.acquisition-costs.destroy',
                        [
                            $land,
                            $acquisitionCost
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this acquisition cost?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        <i class="bi bi-trash"></i>
                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- Bottom Navigation --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a
            href="{{ route(
                'admin.land.lands.acquisition-costs.index',
                $land
            ) }}"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Acquisition Costs

        </a>


        <a
            href="{{ route(
                'admin.land.lands.acquisition-costs.edit',
                [
                    $land,
                    $acquisitionCost
                ]
            ) }}"
            class="btn btn-primary">

            <i class="bi bi-pencil"></i>
            Edit Cost

        </a>

    </div>

</div>

@endsection