@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget / Item
            </div>

            <h3 class="mb-1">
                Edit Budget Item
            </h3>

            <div class="text-muted">
                {{ $item->item_code }}
                ·
                {{ $item->item_name }}
            </div>

            <div class="text-muted small mt-1">
                {{ $projectBudget->budget_number }}
                ·
                {{ $projectBudget->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.budget.show',
                [
                    'project' => $project->id,
                    'projectBudget' => $projectBudget->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Budget
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Approved Protection --}}
    {{-- ========================================================= --}}

    @if($projectBudget->status === 'Approved')

        <div class="alert alert-warning">

            This budget has been approved and its items cannot be
            modified.

        </div>

    @else


        <form
            method="POST"
            action="{{ route(
                'admin.projects.budget.items.update',
                [
                    'project' => $project->id,
                    'projectBudget' => $projectBudget->id,
                    'item' => $item->id,
                ]
            ) }}"
        >

            @csrf

            @method('PUT')


            {{-- ================================================= --}}
            {{-- Item Identification --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Item Identification</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Item Code --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="item_code"
                                class="form-label"
                            >
                                Item Code
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="item_code"
                                id="item_code"
                                class="form-control @error('item_code') is-invalid @enderror"
                                value="{{ old(
                                    'item_code',
                                    $item->item_code
                                ) }}"
                                required
                            >

                            @error('item_code')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Item Name --}}

                        <div class="col-md-5 mb-3">

                            <label
                                for="item_name"
                                class="form-label"
                            >
                                Item Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="item_name"
                                id="item_name"
                                class="form-control @error('item_name') is-invalid @enderror"
                                value="{{ old(
                                    'item_name',
                                    $item->item_name
                                ) }}"
                                required
                            >

                            @error('item_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Sequence --}}

                        <div class="col-md-4 mb-3">

                            <label
                                for="sequence"
                                class="form-label"
                            >
                                Sequence
                            </label>

                            <input
                                type="number"
                                name="sequence"
                                id="sequence"
                                class="form-control @error('sequence') is-invalid @enderror"
                                value="{{ old(
                                    'sequence',
                                    $item->sequence
                                ) }}"
                                min="0"
                            >

                            @error('sequence')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Classification --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Classification</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Category --}}

                        <div class="col-md-6 mb-3">

                            <label
                                for="project_budget_category_id"
                                class="form-label"
                            >
                                Budget Category
                            </label>

                            <select
                                name="project_budget_category_id"
                                id="project_budget_category_id"
                                class="form-select @error('project_budget_category_id') is-invalid @enderror"
                            >

                                <option value="">
                                    -- No Category --
                                </option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ old(
                                            'project_budget_category_id',
                                            $item->project_budget_category_id
                                        ) == $category->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $category->category_code }}
                                        -
                                        {{ $category->category_name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('project_budget_category_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Cost Type --}}

                        <div class="col-md-6 mb-3">

                            <label
                                for="cost_type"
                                class="form-label"
                            >
                                Cost Type
                            </label>

                            <select
                                name="cost_type"
                                id="cost_type"
                                class="form-select @error('cost_type') is-invalid @enderror"
                            >

                                <option value="">
                                    -- Select Cost Type --
                                </option>

                                @foreach([
                                    'Direct',
                                    'Indirect',
                                    'Contingency',
                                    'Other'
                                ] as $costType)

                                    <option
                                        value="{{ $costType }}"
                                        {{ old(
                                            'cost_type',
                                            $item->cost_type
                                        ) === $costType
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $costType }}
                                    </option>

                                @endforeach

                            </select>

                            @error('cost_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Item Hierarchy --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Item Hierarchy</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-8 mb-3">

                            <label
                                for="parent_item_id"
                                class="form-label"
                            >
                                Parent Item
                            </label>

                            <select
                                name="parent_item_id"
                                id="parent_item_id"
                                class="form-select @error('parent_item_id') is-invalid @enderror"
                            >

                                <option value="">
                                    -- Top Level Item --
                                </option>

                                @foreach($items as $parentItem)

                                    <option
                                        value="{{ $parentItem->id }}"
                                        {{ old(
                                            'parent_item_id',
                                            $item->parent_item_id
                                        ) == $parentItem->id
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $parentItem->item_code }}
                                        -
                                        {{ $parentItem->item_name }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Select a parent item if this is a
                                sub-item.
                            </div>

                            @error('parent_item_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Current Parent
                            </label>

                            <div class="form-control bg-light">

                                @if($item->parent)

                                    {{ $item->parent->item_code }}
                                    -
                                    {{ $item->parent->item_name }}

                                @else

                                    Top Level

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="alert alert-warning small mb-0">

                        An item cannot be its own parent.
                        Circular parent/child relationships should
                        also be avoided.

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Quantity & Rate --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Quantity & Cost</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Quantity --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="quantity"
                                class="form-label"
                            >
                                Quantity
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old(
                                    'quantity',
                                    $item->quantity
                                ) }}"
                                min="0"
                                step="0.0001"
                            >

                            @error('quantity')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Unit --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="unit"
                                class="form-label"
                            >
                                Unit
                            </label>

                            <input
                                type="text"
                                name="unit"
                                id="unit"
                                class="form-control @error('unit') is-invalid @enderror"
                                value="{{ old(
                                    'unit',
                                    $item->unit
                                ) }}"
                                placeholder="m² / m³ / Nos"
                            >

                            @error('unit')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Unit Rate --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="unit_rate"
                                class="form-label"
                            >
                                Unit Rate
                            </label>

                            <input
                                type="number"
                                name="unit_rate"
                                id="unit_rate"
                                class="form-control @error('unit_rate') is-invalid @enderror"
                                value="{{ old(
                                    'unit_rate',
                                    $item->unit_rate
                                ) }}"
                                min="0"
                                step="0.01"
                            >

                            @error('unit_rate')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Amount --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Estimated Amount
                            </label>

                            <input
                                type="text"
                                id="estimated_amount_preview"
                                class="form-control bg-light fw-semibold"
                                value="{{ number_format(
                                    $item->estimated_amount,
                                    2
                                ) }}"
                                readonly
                            >

                            <div class="form-text">
                                Quantity × Unit Rate
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Budget Utilization Summary --}}
            {{-- ================================================= --}}

            @php
                $approvedBudget = (float) ($item->estimated_amount ?? 0);

                $procurementAmount = (float) ($item->procurement_amount ?? 0);
                $contractAmount = (float) ($item->contract_amount ?? 0);
                $workOrderAmount = (float) ($item->work_order_amount ?? 0);
                $variationAmount = (float) ($item->variation_amount ?? 0);
                $otherCostAmount = (float) ($item->other_cost_amount ?? 0);
                $landCostAmount = (float) ($item->land_cost_amount ?? 0);
                $designCostAmount = (float) ($item->design_cost_amount ?? 0);

                $baseCommittedAmount = (float) ($item->base_committed_amount ?? 0);
                $totalCommittedAmount = (float) ($item->total_committed_amount ?? 0);
                $remainingAmount = (float) ($item->remaining_budget_amount ?? 0);
                $utilizationPercentage = (float) ($item->budget_utilization_percentage ?? 0);
                $utilizationStatus = $item->utilization_status ?? 'Healthy';

                $statusClass = match ($utilizationStatus) {
                    'Over Budget' => 'danger',
                    'Critical' => 'danger',
                    'Warning' => 'warning',
                    default => 'success',
                };

                $progressWidth = min(max($utilizationPercentage, 0), 100);
            @endphp

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Budget Utilization Summary</strong>

                    <span class="badge bg-{{ $statusClass }}">
                        {{ $utilizationStatus }}
                    </span>
                </div>

                <div class="card-body">

                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Approved Budget
                                </div>

                                <h5 class="mb-0 text-primary">
                                    {{ number_format($approvedBudget, 2) }}
                                </h5>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Total Committed
                                </div>

                                <h5 class="mb-0 text-dark">
                                    {{ number_format($totalCommittedAmount, 2) }}
                                </h5>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Remaining Budget
                                </div>

                                <h5 class="mb-0 text-{{ $remainingAmount < 0 ? 'danger' : 'success' }}">
                                    {{ number_format($remainingAmount, 2) }}
                                </h5>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Utilization
                                </div>

                                <h5 class="mb-0 text-{{ $statusClass }}">
                                    {{ number_format($utilizationPercentage, 2) }}%
                                </h5>
                            </div>
                        </div>

                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">
                                Budget Consumption
                            </span>

                            <span class="small fw-semibold">
                                {{ number_format($utilizationPercentage, 2) }}%
                            </span>
                        </div>

                        <div class="progress" style="height: 18px;">
                            <div
                                class="progress-bar bg-{{ $statusClass }}"
                                role="progressbar"
                                style="width: {{ $progressWidth }}%;"
                                aria-valuenow="{{ $utilizationPercentage }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >
                                {{ number_format($utilizationPercentage, 2) }}%
                            </div>
                        </div>

                        @if($utilizationPercentage > 100)
                            <div class="text-danger small mt-2">
                                This budget item is over-committed.
                            </div>
                        @endif
                    </div>

                    <h6 class="border-bottom pb-2">
                        Cost Breakdown
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cost Source</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        Procurement Packages
                                        <div class="text-muted small">
                                            Used as base commitment when contracts/work orders are unavailable
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($procurementAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Contracts
                                        <div class="text-muted small">
                                            Used as base commitment when work orders are unavailable
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($contractAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Work Orders
                                        <div class="text-muted small">
                                            Preferred base commitment
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($workOrderAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Approved Variations</td>
                                    <td class="text-end">
                                        {{ number_format($variationAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Approved Other Construction Costs</td>
                                    <td class="text-end">
                                        {{ number_format($otherCostAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Land Acquisition Costs</td>
                                    <td class="text-end">
                                        {{ number_format($landCostAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Design Change Costs</td>
                                    <td class="text-end">
                                        {{ number_format($designCostAmount, 2) }}
                                    </td>
                                </tr>

                                <tr class="table-secondary fw-semibold">
                                    <td>Base Commitment</td>
                                    <td class="text-end">
                                        {{ number_format($baseCommittedAmount, 2) }}
                                    </td>
                                </tr>

                                <tr class="table-dark fw-semibold">
                                    <td>Total Committed Amount</td>
                                    <td class="text-end">
                                        {{ number_format($totalCommittedAmount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-muted small mt-2">
                        Procurement packages, contracts, and work orders are treated as lifecycle commitment values.
                        The calculation uses the most advanced available lifecycle value to avoid double counting.
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- Remarks --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old(
                        'remarks',
                        $item->remarks
                    ) }}</textarea>

                    @error('remarks')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Actions --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-end gap-2 mb-5">

                <a
                    href="{{ route(
                        'admin.projects.budget.show',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Budget Item
                </button>

            </div>

        </form>

    @endif

</div>


{{-- ============================================================= --}}
{{-- Amount Preview --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const quantity =
            document.getElementById('quantity');

        const unitRate =
            document.getElementById('unit_rate');

        const preview =
            document.getElementById(
                'estimated_amount_preview'
            );


        function calculateAmount()
        {
            const qty =
                parseFloat(quantity.value) || 0;

            const rate =
                parseFloat(unitRate.value) || 0;

            const amount =
                qty * rate;


            preview.value =
                amount.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        if (
            quantity &&
            unitRate &&
            preview
        ) {

            quantity.addEventListener(
                'input',
                calculateAmount
            );

            unitRate.addEventListener(
                'input',
                calculateAmount
            );

            calculateAmount();

        }

    }
);

</script>

@endsection