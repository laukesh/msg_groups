@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Create Procurement Package</h4>

            <div class="text-muted">
                Project:
                <strong>{{ $project->project_name ?? $project->name }}</strong>
            </div>
        </div>

        <a href="{{ route('admin.projects.procurement.packages.index', [
            'project' => $project->id
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
          action="{{ route('admin.projects.procurement.packages.store', [
              'project' => $project->id
          ]) }}">

        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Package Information</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Procurement Plan --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Procurement Plan <span class="text-danger">*</span>
                        </label>

                        <select name="procurement_plan_id"
                                id="procurement_plan_id"
                                class="form-select @error('procurement_plan_id') is-invalid @enderror"
                                required>

                            <option value="">Select Procurement Plan</option>

                            @foreach ($procurementPlans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ old('procurement_plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->plan_number }}
                                    -
                                    {{ $plan->plan_title ?? $plan->plan_title ?? '' }}
                                </option>
                            @endforeach
                        </select>

                        @error('procurement_plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Package Title --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Package Title <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="package_title"
                               class="form-control @error('package_title') is-invalid @enderror"
                               value="{{ old('package_title') }}"
                               placeholder="Enter package title"
                               required>

                        @error('package_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Procurement Method --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Procurement Method</label>

                        <select name="procurement_method"
                                class="form-select">

                            <option value="">Select Method</option>
                            <option value="Open Tender"
                                {{ old('procurement_method') == 'Open Tender' ? 'selected' : '' }}>
                                Open Tender
                            </option>
                            <option value="Limited Tender"
                                {{ old('procurement_method') == 'Limited Tender' ? 'selected' : '' }}>
                                Limited Tender
                            </option>
                            <option value="Single Source"
                                {{ old('procurement_method') == 'Single Source' ? 'selected' : '' }}>
                                Single Source
                            </option>
                            <option value="RFQ"
                                {{ old('procurement_method') == 'RFQ' ? 'selected' : '' }}>
                                RFQ
                            </option>
                        </select>
                    </div>


                    {{-- Procurement Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Procurement Type</label>

                        <select name="procurement_type"
                                class="form-select">

                            <option value="">Select Type</option>
                            <option value="Works"
                                {{ old('procurement_type') == 'Works' ? 'selected' : '' }}>
                                Works
                            </option>
                            <option value="Goods"
                                {{ old('procurement_type') == 'Goods' ? 'selected' : '' }}>
                                Goods
                            </option>
                            <option value="Services"
                                {{ old('procurement_type') == 'Services' ? 'selected' : '' }}>
                                Services
                            </option>
                            <option value="Consultancy"
                                {{ old('procurement_type') == 'Consultancy' ? 'selected' : '' }}>
                                Consultancy
                            </option>
                        </select>
                    </div>


                    {{-- Tender Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tender Type</label>

                        <select name="tender_type"
                                class="form-select">

                            <option value="">Select Tender Type</option>
                            <option value="Item Rate"
                                {{ old('tender_type') == 'Item Rate' ? 'selected' : '' }}>
                                Item Rate
                            </option>
                            <option value="Lump Sum"
                                {{ old('tender_type') == 'Lump Sum' ? 'selected' : '' }}>
                                Lump Sum
                            </option>
                            <option value="Percentage Rate"
                                {{ old('tender_type') == 'Percentage Rate' ? 'selected' : '' }}>
                                Percentage Rate
                            </option>
                        </select>
                    </div>

                </div>

            </div>
        </div>


        {{-- Development Budget Linking --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Development Budget Link
                </h5>
            </div>

            <div class="card-body">

                <div class="alert alert-info">
                    Select an approved development budget and optionally link
                    this procurement package to a specific budget item.
                    Linking a budget item enables budget utilization tracking.
                </div>

                <div class="row">

                    {{-- Budget --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Development Budget
                        </label>

                        <select name="project_budget_id"
                                id="project_budget_id"
                                class="form-select @error('project_budget_id') is-invalid @enderror">

                            <option value="">Select Development Budget</option>

                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}"
                                    {{ old('project_budget_id') == $budget->id ? 'selected' : '' }}>

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

                        @error('project_budget_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Budget Item --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Development Budget Item
                        </label>

                        <select name="project_budget_item_id"
                                id="project_budget_item_id"
                                class="form-select @error('project_budget_item_id') is-invalid @enderror">

                            <option value="">Select Budget First</option>

                            @foreach ($budgetItems as $item)
                                <option value="{{ $item->id }}"
                                        data-budget-id="{{ $item->project_budget_id }}"
                                         data-approved-amount="{{ (float) $item->estimated_amount }}"
                                         data-committed-amount="{{ (float) ($item->total_committed_amount ?? 0) }}"
                                    {{ old('project_budget_item_id') == $item->id ? 'selected' : '' }}>

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

                        @error('project_budget_item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div id="budget-item-details"
                     class="alert alert-secondary d-none">

                    <strong>Selected Budget Item:</strong>

                    <div id="selected-budget-item-name"></div>

                    <div id="selected-budget-item-amount"></div>

                </div>

                <div id="budget-utilization-preview"
                     class="alert alert-light border d-none mt-3">
                    <h6 class="mb-3">
                        <i class="fa fa-chart-pie"></i>
                        Budget Utilization Preview
                    </h6>

                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Approved Budget</small>
                            <strong id="preview-approved-budget">—</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Already Committed</small>
                            <strong id="preview-committed-budget">—</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Remaining Budget</small>
                            <strong id="preview-remaining-budget">—</strong>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        This preview is informational. Final utilization is recalculated
                        from linked procurement, contract, construction and cost records.
                    </small>
                </div>

            </div>
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
                               id="estimated_cost"
                               class="form-control"
                               value="{{ old('estimated_cost') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Budget Amount</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="budget_amount"
                               id="budget_amount"
                               class="form-control"
                               value="{{ old('budget_amount') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tender Fee</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="tender_fee"
                               class="form-control"
                               value="{{ old('tender_fee') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">EMD Amount</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="emd_amount"
                               class="form-control"
                               value="{{ old('emd_amount') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Performance Security</label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="performance_security"
                               class="form-control"
                               value="{{ old('performance_security') }}">
                    </div>

                </div>

            </div>
        </div>


        {{-- Dates --}}
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
                               value="{{ old('planned_publish_date') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Planned Bid Submission Date
                        </label>

                        <input type="date"
                               name="planned_bid_submission_date"
                               class="form-control"
                               value="{{ old('planned_bid_submission_date') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Planned Opening Date</label>

                        <input type="date"
                               name="planned_opening_date"
                               class="form-control"
                               value="{{ old('planned_opening_date') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Planned Award Date</label>

                        <input type="date"
                               name="planned_award_date"
                               class="form-control"
                               value="{{ old('planned_award_date') }}">
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
                              class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-4 px-0">
                    <label class="form-label">Package Status</label>

                    <select name="package_status"
                            class="form-select">

                        <option value="Draft"
                            {{ old('package_status', 'Draft') == 'Draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="Planned"
                            {{ old('package_status') == 'Planned' ? 'selected' : '' }}>
                            Planned
                        </option>

                    </select>
                </div>

            </div>
        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.projects.procurement.packages.index', [
                'project' => $project->id
            ]) }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">
                <i class="fa fa-save"></i>
                Save Procurement Package
            </button>

        </div>

    </form>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const budgetSelect = document.getElementById('project_budget_id');
    const budgetItemSelect = document.getElementById('project_budget_item_id');

    const detailsBox = document.getElementById('budget-item-details');
    const selectedItemName = document.getElementById('selected-budget-item-name');
    const selectedItemAmount = document.getElementById('selected-budget-item-amount');
    const utilizationPreview = document.getElementById('budget-utilization-preview');
    const previewApproved = document.getElementById('preview-approved-budget');
    const previewCommitted = document.getElementById('preview-committed-budget');
    const previewRemaining = document.getElementById('preview-remaining-budget');

    const oldBudgetItemId = @json(old('project_budget_item_id'));

    function filterBudgetItems() {

        const selectedBudgetId = budgetSelect.value;

        let hasItems = false;

        Array.from(budgetItemSelect.options).forEach(function (option, index) {

            if (index === 0) {
                option.hidden = false;
                return;
            }

            const itemBudgetId = option.getAttribute('data-budget-id');

            if (
                selectedBudgetId &&
                itemBudgetId === selectedBudgetId
            ) {
                option.hidden = false;
                hasItems = true;
            } else {
                option.hidden = true;

                if (option.selected) {
                    option.selected = false;
                }
            }
        });

        if (!selectedBudgetId) {
            budgetItemSelect.value = '';
            budgetItemSelect.options[0].text =
                'Select Development Budget First';
        } else if (!hasItems) {
            budgetItemSelect.value = '';
            budgetItemSelect.options[0].text =
                'No Budget Items Available';
        } else {
            budgetItemSelect.options[0].text =
                'Select Development Budget Item';
        }

        showSelectedBudgetItem();
    }

    function showSelectedBudgetItem() {

        const selectedOption =
            budgetItemSelect.options[budgetItemSelect.selectedIndex];

        if (
            !selectedOption ||
            !selectedOption.value ||
            selectedOption.hidden
        ) {
            detailsBox.classList.add('d-none');
            selectedItemName.innerHTML = '';
            selectedItemAmount.innerHTML = '';
            utilizationPreview.classList.add('d-none');
            return;
        }

        const text = selectedOption.text.trim();

        selectedItemName.innerHTML =
            '<strong>Item:</strong> ' + text;

        detailsBox.classList.remove('d-none');

        const approved = parseFloat(selectedOption.dataset.approvedAmount || 0);
        const committed = parseFloat(selectedOption.dataset.committedAmount || 0);
        const remaining = approved - committed;

        previewApproved.textContent = approved.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        previewCommitted.textContent = committed.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        previewRemaining.textContent = remaining.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        utilizationPreview.classList.remove('d-none');
    }

    budgetSelect.addEventListener('change', function () {

        budgetItemSelect.value = '';

        filterBudgetItems();

    });

    budgetItemSelect.addEventListener('change', function () {
        showSelectedBudgetItem();
    });

    filterBudgetItems();

    if (oldBudgetItemId) {
        budgetItemSelect.value = oldBudgetItemId;
        showSelectedBudgetItem();
    }

});
</script>
@endsection





