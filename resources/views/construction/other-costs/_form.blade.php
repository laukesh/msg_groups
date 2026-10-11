@php
    $selectedWorkOrderId = old(
        'construction_work_order_id',
        $cost?->construction_work_order_id
    );

    $selectedBudgetId = old(
        'project_budget_id',
        $cost?->project_budget_id
    );

    $selectedBudgetItemId = old(
        'project_budget_item_id',
        $cost?->project_budget_item_id
    );
@endphp

<div class="row g-3">

    {{-- Cost Date --}}
    <div class="col-md-4">
        <label class="form-label">
            Cost Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="cost_date"
            class="form-control @error('cost_date') is-invalid @enderror"
            value="{{ old('cost_date', $cost?->cost_date?->format('Y-m-d')) }}"
            required
        >

        @error('cost_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Cost Type --}}
    <div class="col-md-4">
        <label class="form-label">
            Cost Type <span class="text-danger">*</span>
        </label>

        <select
            name="cost_type"
            class="form-select @error('cost_type') is-invalid @enderror"
            required
        >
            <option value="">Select Cost Type</option>

            @foreach([
                'Direct Labour',
                'Equipment Rental',
                'Temporary Works',
                'Direct Material Purchase',
                'Site Expense',
                'Consultant Expense',
                'Other',
            ] as $type)
                <option
                    value="{{ $type }}"
                    @selected(old('cost_type', $cost?->cost_type) === $type)
                >
                    {{ $type }}
                </option>
            @endforeach
        </select>

        @error('cost_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Work Order --}}
    <div class="col-md-4">
        <label class="form-label">
            Work Order
        </label>

        <select
            name="construction_work_order_id"
            id="construction_work_order_id"
            class="form-select @error('construction_work_order_id') is-invalid @enderror"
        >
            <option value="">No Work Order</option>

            @foreach($workOrders as $workOrder)
                <option
                    value="{{ $workOrder->id }}"
                    data-budget-id="{{ $workOrder->project_budget_id }}"
                    data-budget-item-id="{{ $workOrder->project_budget_item_id }}"
                    @selected((string) $selectedWorkOrderId === (string) $workOrder->id)
                >
                    {{ $workOrder->work_order_number }}
                    -
                    {{ $workOrder->work_order_title }}
                </option>
            @endforeach
        </select>

        <small class="text-muted">
            Selecting a Work Order will automatically inherit its budget linkage.
        </small>

        @error('construction_work_order_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Development Budget --}}
    <div class="col-md-6">
        <label class="form-label">
            Development Budget
            <span class="text-danger">*</span>
        </label>

        <select
            name="project_budget_id"
            id="project_budget_id"
            class="form-select @error('project_budget_id') is-invalid @enderror"
        >
            <option value="">Select Development Budget</option>

            @foreach($budgets as $budget)
                <option
                    value="{{ $budget->id }}"
                    @selected((string) $selectedBudgetId === (string) $budget->id)
                >
                    {{ $budget->budget_number }}
                    -
                    {{ $budget->title }}
                </option>
            @endforeach
        </select>

        @error('project_budget_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Development Budget Item --}}
    <div class="col-md-6">
        <label class="form-label">
            Development Budget Item
            <span class="text-danger">*</span>
        </label>

        <select
            name="project_budget_item_id"
            id="project_budget_item_id"
            class="form-select @error('project_budget_item_id') is-invalid @enderror"
        >
            <option value="">Select Budget Item</option>

            @foreach($budgetItems as $item)
                <option
                    value="{{ $item->id }}"
                    data-budget-id="{{ $item->project_budget_id }}"
                    @selected((string) $selectedBudgetItemId === (string) $item->id)
                >
                    {{ $item->item_code ? $item->item_code . ' - ' : '' }}
                    {{ $item->item_name }}
                </option>
            @endforeach
        </select>

        @error('project_budget_item_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Amount --}}
    <div class="col-md-4">
        <label class="form-label">
            Amount <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="amount"
            step="0.01"
            min="0.01"
            class="form-control @error('amount') is-invalid @enderror"
            value="{{ old('amount', $cost?->amount) }}"
            required
        >

        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Currency --}}
    <div class="col-md-4">
        <label class="form-label">
            Currency <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="currency"
            maxlength="10"
            class="form-control @error('currency') is-invalid @enderror"
            value="{{ old('currency', $cost?->currency ?? 'USD') }}"
            required
        >

        @error('currency')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >
            @foreach(['Draft', 'Submitted', 'Approved', 'Rejected'] as $status)
                <option
                    value="{{ $status }}"
                    @selected(old('status', $cost?->status ?? 'Draft') === $status)
                >
                    {{ $status }}
                </option>
            @endforeach
        </select>

        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Description --}}
    <div class="col-md-12">
        <label class="form-label">Description</label>

        <textarea
            name="description"
            rows="3"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $cost?->description) }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remarks --}}
    <div class="col-md-12">
        <label class="form-label">Remarks</label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control @error('remarks') is-invalid @enderror"
        >{{ old('remarks', $cost?->remarks) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const workOrderSelect = document.getElementById(
        'construction_work_order_id'
    );

    const budgetSelect = document.getElementById(
        'project_budget_id'
    );

    const budgetItemSelect = document.getElementById(
        'project_budget_item_id'
    );

    function filterBudgetItems() {
        const budgetId = budgetSelect.value;
        const currentItemId = budgetItemSelect.value;

        Array.from(budgetItemSelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const optionBudgetId = option.dataset.budgetId;

            option.hidden = optionBudgetId !== budgetId;

            if (option.hidden && option.value === currentItemId) {
                budgetItemSelect.value = '';
            }
        });
    }

    function inheritWorkOrderBudget() {
        const selectedOption =
            workOrderSelect.options[workOrderSelect.selectedIndex];

        if (!workOrderSelect.value) {
            budgetSelect.value = '';
            budgetItemSelect.value = '';

            filterBudgetItems();

            return;
        }

        const budgetId = selectedOption.dataset.budgetId || '';
        const budgetItemId = selectedOption.dataset.budgetItemId || '';

        budgetSelect.value = budgetId;

        filterBudgetItems();

        budgetItemSelect.value = budgetItemId;
    }

    budgetSelect.addEventListener('change', function () {
        filterBudgetItems();
    });

    workOrderSelect.addEventListener('change', function () {
        inheritWorkOrderBudget();
    });

    filterBudgetItems();

    if (workOrderSelect.value) {
        inheritWorkOrderBudget();
    }
});
</script>
@endpush