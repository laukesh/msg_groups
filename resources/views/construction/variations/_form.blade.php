<div class="row g-3">

    {{-- Variation Date --}}
    <div class="col-md-4">
        <label class="form-label">
            Variation Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="variation_date"
            class="form-control"
            required
            value="{{ old(
                'variation_date',
                $variation?->variation_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

        @error('variation_date')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Variation Type --}}
    <div class="col-md-4">
        <label class="form-label">
            Variation Type <span class="text-danger">*</span>
        </label>

        <select
            name="variation_type"
            class="form-select"
            required
        >
            <option value="">Select Type</option>

            @foreach([
                'Addition',
                'Omission',
                'Substitution',
                'Design Change',
                'Scope Change',
                'Quantity Change',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'variation_type',
                            $variation?->variation_type
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach
        </select>

        @error('variation_type')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Status --}}
    <div class="col-md-4">
        <label class="form-label">
            Status
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $variation?->status ?? 'Draft' }}"
            readonly
        >

        <input
            type="hidden"
            name="status"
            value="{{ $variation?->status ?? 'Draft' }}"
        >

        <div class="form-text">
            Status changes through the approval workflow.
        </div>
    </div>


    {{-- Title --}}
    <div class="col-md-12">
        <label class="form-label">
            Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            maxlength="255"
            required
            placeholder="Variation title"
            value="{{ old(
                'title',
                $variation?->title
            ) }}"
        >

        @error('title')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Work Order --}}
    <div class="col-md-6">
        <label class="form-label">
            Work Order
        </label>

        <select
            name="construction_work_order_id"
            id="construction_work_order_id"
            class="form-select"
        >
            <option value="">Select Work Order</option>

            @foreach($workOrders as $workOrder)

                @php
                    $budgetName = '';

                    if ($workOrder->projectBudget) {
                        $budgetName = trim(
                            ($workOrder->projectBudget->budget_number ?? '') .
                            ' - ' .
                            ($workOrder->projectBudget->title ?? '')
                        );

                        $budgetName = trim($budgetName, ' -');
                    }

                    $budgetItemName =
                        $workOrder->budgetItem?->item_name
                        ?? $workOrder->budgetItem?->name
                        ?? '';
                @endphp

                <option
                    value="{{ $workOrder->id }}"
                    data-budget-id="{{ $workOrder->project_budget_id }}"
                    data-budget-item-id="{{ $workOrder->project_budget_item_id }}"
                    data-budget-name="{{ $budgetName }}"
                    data-budget-item-name="{{ $budgetItemName }}"
                    @selected(
                        old(
                            'construction_work_order_id',
                            $variation?->construction_work_order_id
                        ) == $workOrder->id
                    )
                >
                    {{ $workOrder->work_order_number }}
                    -
                    {{ $workOrder->work_order_title ?? $workOrder->title }}
                </option>

            @endforeach
        </select>

        @error('construction_work_order_id')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Development Budget --}}
    <div class="col-md-6">
        <label class="form-label">
            Development Budget
        </label>

        <input
            type="text"
            id="development_budget_display"
            class="form-control"
            value="Not Linked"
            readonly
        >

        <div class="form-text">
            Budget is automatically inherited from the selected Work Order.
        </div>
    </div>


    {{-- Development Budget Item --}}
    <div class="col-md-6">
        <label class="form-label">
            Development Budget Item
        </label>

        <input
            type="text"
            id="development_budget_item_display"
            class="form-control"
            value="Not Linked"
            readonly
        >

        <div class="form-text">
            Budget Item is automatically inherited from the selected Work Order.
        </div>
    </div>


    {{-- Procurement Contract --}}
    <div class="col-md-6">
        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
        >
            <option value="">Not Linked</option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id',
                            $variation?->procurement_contract_id
                        ) == $contract->id
                    )
                >
                    {{ $contract->contract_number }}
                    -
                    {{ $contract->contract_title }}
                </option>

            @endforeach
        </select>

        @error('procurement_contract_id')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Amount --}}
    <div class="col-md-6">
        <label class="form-label">
            Amount <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="amount"
            class="form-control"
            min="0.01"
            step="0.01"
            required
            value="{{ old(
                'amount',
                $variation?->amount
            ) }}"
        >

        @error('amount')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Currency --}}
    <div class="col-md-6">
        <label class="form-label">
            Currency <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="currency"
            class="form-control"
            maxlength="10"
            required
            value="{{ old(
                'currency',
                $variation?->currency ?? 'USD'
            ) }}"
        >

        @error('currency')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Description --}}
    <div class="col-md-12">
        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Describe the variation..."
        >{{ old(
            'description',
            $variation?->description
        ) }}</textarea>

        @error('description')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Reason --}}
    <div class="col-md-12">
        <label class="form-label">
            Reason
        </label>

        <textarea
            name="reason"
            class="form-control"
            rows="4"
            placeholder="Why is this variation required?"
        >{{ old(
            'reason',
            $variation?->reason
        ) }}</textarea>

        @error('reason')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Remarks --}}
    <div class="col-md-12">
        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3"
        >{{ old(
            'remarks',
            $variation?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const workOrderSelect = document.getElementById(
        'construction_work_order_id'
    );

    const budgetDisplay = document.getElementById(
        'development_budget_display'
    );

    const budgetItemDisplay = document.getElementById(
        'development_budget_item_display'
    );

    function updateBudgetDetails() {

        if (!workOrderSelect || !budgetDisplay || !budgetItemDisplay) {
            return;
        }

        const selectedOption =
            workOrderSelect.options[
                workOrderSelect.selectedIndex
            ];

        if (
            !selectedOption ||
            !selectedOption.value
        ) {
            budgetDisplay.value = 'Not Linked';
            budgetItemDisplay.value = 'Not Linked';
            return;
        }

        const budgetName =
            selectedOption.getAttribute('data-budget-name');

        const budgetItemName =
            selectedOption.getAttribute('data-budget-item-name');

        budgetDisplay.value =
            budgetName && budgetName.trim() !== ''
                ? budgetName
                : 'Not Linked';

        budgetItemDisplay.value =
            budgetItemName && budgetItemName.trim() !== ''
                ? budgetItemName
                : 'Not Linked';
    }

    if (workOrderSelect) {
        workOrderSelect.addEventListener(
            'change',
            updateBudgetDetails
        );
    }

    // Populate values automatically on page load,
    // especially when editing an existing variation.
    updateBudgetDetails();

});
</script>