@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Procurement Package</h4>

            <div class="text-muted">
                Package:
                <strong>{{ $procurementPackage->package_number }}</strong>
            </div>
        </div>

        <a href="{{ route('admin.projects.procurement.packages.show', [
            'project' => $project->id,
            'procurementPackage' => $procurementPackage->id
        ]) }}"
           class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.projects.procurement.packages.update', [
              'project' => $project->id,
              'procurementPackage' => $procurementPackage->id
          ]) }}">

        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Package Information</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Package Number --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Package Number</label>

                        <input type="text"
                               class="form-control"
                               value="{{ $procurementPackage->package_number }}"
                               readonly>
                    </div>

                    {{-- Procurement Plan --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Procurement Plan <span class="text-danger">*</span>
                        </label>

                        <select name="procurement_plan_id"
                                class="form-select"
                                required>

                            <option value="">Select Procurement Plan</option>

                            @foreach ($procurementPlans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ old(
                                        'procurement_plan_id',
                                        $procurementPackage->procurement_plan_id
                                    ) == $plan->id ? 'selected' : '' }}>

                                    {{ $plan->plan_number }}
                                    -
                                    {{ $plan->title ?? $plan->plan_name ?? '' }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Package Title --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Package Title <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="package_title"
                               class="form-control"
                               value="{{ old(
                                   'package_title',
                                   $procurementPackage->package_title
                               ) }}"
                               required>
                    </div>

                    {{-- Procurement Method --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Procurement Method</label>

                        <select name="procurement_method"
                                class="form-select">

                            <option value="">Select Method</option>

                            @foreach ([
                                'Open Tender',
                                'Limited Tender',
                                'Single Source',
                                'RFQ'
                            ] as $method)

                                <option value="{{ $method }}"
                                    {{ old(
                                        'procurement_method',
                                        $procurementPackage->procurement_method
                                    ) == $method ? 'selected' : '' }}>

                                    {{ $method }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Procurement Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Procurement Type</label>

                        <select name="procurement_type"
                                class="form-select">

                            <option value="">Select Type</option>

                            @foreach ([
                                'Works',
                                'Goods',
                                'Services',
                                'Consultancy'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old(
                                        'procurement_type',
                                        $procurementPackage->procurement_type
                                    ) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Tender Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tender Type</label>

                        <select name="tender_type"
                                class="form-select">

                            <option value="">Select Tender Type</option>

                            @foreach ([
                                'Item Rate',
                                'Lump Sum',
                                'Percentage Rate'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old(
                                        'tender_type',
                                        $procurementPackage->tender_type
                                    ) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                </div>

            </div>
        </div>


        {{-- Development Budget --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Development Budget Link</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Budget --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Development Budget
                        </label>

                        <select name="project_budget_id"
                                id="project_budget_id"
                                class="form-select">

                            <option value="">Select Development Budget</option>

                            @foreach ($budgets as $budget)

                                <option value="{{ $budget->id }}"
                                    {{ old(
                                        'project_budget_id',
                                        $procurementPackage->project_budget_id
                                    ) == $budget->id ? 'selected' : '' }}>

                                    {{ $budget->budget_number }}
                                    -
                                    {{ $budget->title }}

                                    @if ($budget->version_number)
                                        | Version {{ $budget->version_number }}
                                    @endif

                                    | {{ $budget->currency }}
                                    {{ number_format((float) $budget->total_budget, 2) }}

                                </option>

                            @endforeach

                        </select>
                    </div>


                    {{-- Budget Item --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Development Budget Item
                        </label>

                        <select name="project_budget_item_id"
                                id="project_budget_item_id"
                                class="form-select">

                            <option value="">Select Budget Item</option>

                            @foreach ($budgetItems as $item)

                                <option value="{{ $item->id }}"
                                        data-budget-id="{{ $item->project_budget_id }}"
                                    {{ old(
                                        'project_budget_item_id',
                                        $procurementPackage->project_budget_item_id
                                    ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->item_code }}
                                    -
                                    {{ $item->item_name }}

                                    @if ($item->category)
                                        | {{ $item->category->category_name }}
                                    @endif

                                    @if ($item->estimated_amount !== null)
                                        | Amount:
                                        {{ number_format((float) $item->estimated_amount, 2) }}
                                    @endif

                                </option>

                            @endforeach

                        </select>
                    </div>

                </div>

            </div>
        </div>



                <div id="budget-utilization-preview"
                     class="alert alert-light border mt-3">
                    <h6 class="mb-3">
                        <i class="fa fa-chart-pie"></i>
                        Linked Budget Item Summary
                    </h6>

                    @php
                        $selectedBudgetItem = $procurementPackage->budgetItem;
                        $approvedAmount = (float) ($selectedBudgetItem->estimated_amount ?? 0);
                        $committedAmount = (float) ($selectedBudgetItem->total_committed_amount ?? 0);
                        $remainingAmount = $approvedAmount - $committedAmount;
                    @endphp

                    @if ($selectedBudgetItem)
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Approved Budget</small>
                                <strong>{{ number_format($approvedAmount, 2) }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Already Committed</small>
                                <strong>{{ number_format($committedAmount, 2) }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Remaining Budget</small>
                                <strong class="{{ $remainingAmount < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($remainingAmount, 2) }}
                                </strong>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">
                            No budget item is currently linked to this package.
                        </span>
                    @endif
                </div>

        {{-- Cost Information --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Cost Information</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estimated Cost</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="estimated_cost"
                               class="form-control"
                               value="{{ old(
                                   'estimated_cost',
                                   $procurementPackage->estimated_cost
                               ) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Budget Amount</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="budget_amount"
                               class="form-control"
                               value="{{ old(
                                   'budget_amount',
                                   $procurementPackage->budget_amount
                               ) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tender Fee</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="tender_fee"
                               class="form-control"
                               value="{{ old(
                                   'tender_fee',
                                   $procurementPackage->tender_fee
                               ) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">EMD Amount</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="emd_amount"
                               class="form-control"
                               value="{{ old(
                                   'emd_amount',
                                   $procurementPackage->emd_amount
                               ) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Performance Security</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="performance_security"
                               class="form-control"
                               value="{{ old(
                                   'performance_security',
                                   $procurementPackage->performance_security
                               ) }}">
                    </div>

                </div>

            </div>
        </div>


        {{-- Schedule --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Procurement Schedule</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Planned Publish Date</label>

                        <input type="date"
                               name="planned_publish_date"
                               class="form-control"
                               value="{{ old(
                                   'planned_publish_date',
                                   optional($procurementPackage->planned_publish_date)->format('Y-m-d')
                               ) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Planned Bid Submission Date
                        </label>

                        <input type="date"
                               name="planned_bid_submission_date"
                               class="form-control"
                               value="{{ old(
                                   'planned_bid_submission_date',
                                   optional($procurementPackage->planned_bid_submission_date)->format('Y-m-d')
                               ) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Planned Opening Date</label>

                        <input type="date"
                               name="planned_opening_date"
                               class="form-control"
                               value="{{ old(
                                   'planned_opening_date',
                                   optional($procurementPackage->planned_opening_date)->format('Y-m-d')
                               ) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Planned Award Date</label>

                        <input type="date"
                               name="planned_award_date"
                               class="form-control"
                               value="{{ old(
                                   'planned_award_date',
                                   optional($procurementPackage->planned_award_date)->format('Y-m-d')
                               ) }}">
                    </div>

                </div>

            </div>
        </div>


        {{-- Description and Status --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Additional Information</h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old(
                                  'description',
                                  $procurementPackage->description
                              ) }}</textarea>
                </div>

                <div class="col-md-4 px-0">
                    <label class="form-label">Package Status</label>

                    <select name="package_status"
                            class="form-select">

                        @foreach ([
                            'Draft',
                            'Planned'
                        ] as $status)

                            <option value="{{ $status }}"
                                {{ old(
                                    'package_status',
                                    $procurementPackage->package_status
                                ) == $status ? 'selected' : '' }}>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>
                </div>

            </div>
        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.projects.procurement.packages.show', [
                'project' => $project->id,
                'procurementPackage' => $procurementPackage->id
            ]) }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">
                <i class="fa fa-save"></i>
                Update Procurement Package
            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const budgetSelect = document.getElementById('project_budget_id');
    const budgetItemSelect = document.getElementById('project_budget_item_id');

    function filterBudgetItems() {

        const selectedBudgetId = budgetSelect.value;

        Array.from(budgetItemSelect.options).forEach(function (option, index) {

            if (index === 0) {
                return;
            }

            const itemBudgetId = option.getAttribute('data-budget-id');

            if (
                selectedBudgetId &&
                itemBudgetId === selectedBudgetId
            ) {
                option.hidden = false;
            } else {
                option.hidden = true;

                if (option.selected) {
                    option.selected = false;
                }
            }
        });

        if (!selectedBudgetId) {
            budgetItemSelect.value = '';
        }
    }

    budgetSelect.addEventListener('change', function () {
        budgetItemSelect.value = '';
        filterBudgetItems();
    });

    filterBudgetItems();

});
</script>

@endpush